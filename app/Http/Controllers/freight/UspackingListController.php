<?php

namespace App\Http\Controllers\freight;

use App\Http\Controllers\Controller;
use App\Models\ContainerUpload;
use App\Models\Shipment;
use App\Models\Templates;
use App\Models\TrPackingList;
use App\Models\TrPackingListItem;
use App\Models\UsPackingList;
use App\Models\UsPackingListProduct;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UspackingListController extends Controller
{
    public function index()
    {
        $shipments = Shipment::where('status', 'Submitted')
            ->orderBy('id', 'desc')
            ->get();

        return view('feright.pl.uspl.uspl', compact('shipments'));
    }
    public function getContainers($bookingNumber)
    {
        $containers = ContainerUpload::with([
            'vgmInfo',
            'usPackingLists'
        ])
            ->where('booking_number', $bookingNumber)
            ->get();

        return response()->json($containers);
    }

    public function create($id)
    {
        $container = ContainerUpload::with('vgmInfo')
            ->findOrFail($id);

        $trPackingList = TrPackingList::where(
            'container_upload_id',
            $container->id
        )->first();

        $products = collect();

        if ($trPackingList) {
            $products = $trPackingList->items;
        }

        return view(
            'feright.pl.uspl.createuspl',
            compact(
                'container',
                'trPackingList',
                'products'
            )
        );
    }
    public function store(Request $request)
    {
        $existing = UsPackingList::where(
            'container_upload_id',
            $request->container_upload_id
        )->first();

        if ($existing) {
            return redirect()
                ->route('uspl.edit', $existing->id)
                ->with('error', 'Packing List already exists.');
        }
        $request->validate([
            'container_upload_id' => 'required|exists:container_uploads,id',
            'items'               => 'required|array|min:1',
            'items.*.product_name'=> 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {

            $packingList = UsPackingList::create([
                'container_upload_id' => $request->container_upload_id,
                'status'              => $request->action == 'draft'
                    ? 'draft'
                    : 'submitted',
                'created_by'          => Auth::id(),
            ]);

            foreach ($request->items as $item)
            {
                UsPackingListProduct::create([
                    'us_packing_list_id' => $packingList->id,

                    'product_name'       => $item['product_name'] ?? null,

                    'warehouse_code'     => $item['warehouse_code'] ?? null,

                    'total_pallets'      => $item['total_pallets'] ?? 0,

                    'packages'           => $item['total_packages'] ?? 0,
                    'qty_per_pallet' => $item['qty_per_pallet'] ?? null,

                    'total_kg'           => $item['net_weight'] ?? 0,

                    'gross_weight'       => $item['gross_weight'] ?? 0,

                    'total_item_qty'     => $item['item_quantity'] ?? 0,

                    'pallet_pack_kg'     => (
                        ($item['total_pallets'] ?? 0) > 0
                    )
                        ? ($item['net_weight'] / $item['total_pallets'])
                        : 0,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('us.pl')
                ->with('success','US Packing List Saved Successfully');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error',$e->getMessage());
        }
    }

    public function edit($id)
    {
        $packingList = UsPackingList::with([
            'products',
            'container.vgmInfo'
        ])->findOrFail($id);

        return view(
            'feright.pl.uspl.edituspl',
            compact('packingList')
        );
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_name' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {

            $packingList = UsPackingList::findOrFail($id);

            $packingList->update([
                'status' => $request->action == 'draft'
                    ? 'draft'
                    : 'submitted',
            ]);

            // Remove old products
            UsPackingListProduct::where(
                'us_packing_list_id',
                $packingList->id
            )->delete();

            // Insert new products
            foreach ($request->items as $item)
            {
                UsPackingListProduct::create([

                    'us_packing_list_id' => $packingList->id,

                    'product_name'       => $item['product_name'] ?? null,

                    'warehouse_code'     => $item['warehouse_code'] ?? null,
                    'qty_per_pallet'    =>$item['qty_per_pallet'] ?? null,

                    'total_pallets'      => $item['total_pallets'] ?? 0,

                    'packages'           => $item['total_packages'] ?? 0,

                    'total_kg'           => $item['net_weight'] ?? 0,

                    'gross_weight'       => $item['gross_weight'] ?? 0,

                    'total_item_qty'     => $item['item_quantity'] ?? 0,

                    'pallet_pack_kg' =>
                        ($item['total_pallets'] ?? 0) > 0
                            ? round($item['net_weight'] / $item['total_pallets'], 2)
                            : (
                        ($item['total_packages'] ?? 0) > 0
                            ? round($item['net_weight'] / $item['total_packages'], 2)
                            : 0
                        ),
                ]);
            }

            DB::commit();

            return redirect()
                ->route('us.pl')
                ->with(
                    'success',
                    'US Packing List Updated Successfully'
                );

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function preview($id)
    {
        $packingList = UsPackingList::with([
            'container',
            'container.vgmInfo',
            'products'
        ])->findOrFail($id);

        return view(
            'feright.pl.uspl.preview',
            compact('packingList')
        );
    }
    public function exportExcel($id)
    {
        $packingList = UsPackingList::with([
            'container',
            'products'
        ])->findOrFail($id);

        $template = Templates::where(
            'type',
            'US_PL'
        )->first();

        if(!$template)
        {
            return back()->with(
                'error',
                'US PL Template Not Found'
            );
        }

        $templatePath = storage_path(
            'app/private/' . $template->file
        );

        if(!file_exists($templatePath))
        {
            return back()->with(
                'error',
                'Template File Not Found'
            );
        }

        $spreadsheet = IOFactory::load($templatePath);

        $sheet = $spreadsheet->getActiveSheet();

        /*
        ==========================
        HEADER DATA
        ==========================
        */


        $sheet->setCellValue(
            'C6',
            "CONTAINER NUMBER"." ".$packingList->container->container_number
        );

        /*
        ==========================
        PRODUCT DATA
        ==========================
        */

        $row = 8;

        foreach($packingList->products as $item)
        {
            $sheet->setCellValue(
                'C'.$row,
                $item->total_pallets
            );

            $sheet->setCellValue(
                'D'.$row,
                $item->packages
            );
            $sheet->setCellValue(
                'E'.$row,
                $item->qty_per_pallet
            );

            $sheet->setCellValue(
                'F'.$row,
                $item->product_name
            );

            $sheet->setCellValue(
                'G'.$row,
                $item->total_item_qty
            );

            $palletPackKg = 0;

            if($item->total_pallets > 0)
            {
                $palletPackKg =
                    $item->total_kg /
                    $item->total_pallets;
            }
            elseif($item->packages > 0)
            {
                $palletPackKg =
                    $item->total_kg /
                    $item->packages;
            }

            $sheet->setCellValue(
                'H'.$row,
                round($palletPackKg,2)
            );


            $sheet->setCellValue(
                'I'.$row,
                $item->total_kg
            );

            $sheet->setCellValue(
                'J'.$row,
                $item->gross_weight
            );
            $sheet->setCellValue(
                'K'.$row,
                $item->warehouse_code
            );

            $row++;
        }

        /*
        ==========================
        TOTALS
        ==========================
        */

        $totalNetWeight = $packingList->products->sum('total_kg');

        $totalGrossWeight = $packingList->products->sum('gross_weight');

        $totalPallets = $packingList->products->sum('total_pallets');

        $totalPackages = $packingList->products->sum('packages');

        $totalPieces = $packingList->products->sum('total_item_qty');

        $sheet->setCellValue(
            'I18',
            $totalNetWeight
        );

        $sheet->setCellValue(
            'I19',
            $totalGrossWeight
        );

        $sheet->setCellValue(
            'I20',
            $totalPallets
        );

        $sheet->setCellValue(
            'I22',
            $totalPackages
        );

        $sheet->setCellValue(
            'I23',
            $totalPieces
        );

        $fileName =
            'US_Packing_List_'.
            $packingList->container->container_number.
            '.xlsx';

        return response()->streamDownload(
            function () use ($spreadsheet)
            {
                $writer = IOFactory::createWriter(
                    $spreadsheet,
                    'Xlsx'
                );

                $writer->save('php://output');
            },
            $fileName
        );
    }

    public function delete($id)
    {
        $packingList = UsPackingList::findOrFail($id);

        // Delete all items first
        UsPackingListProduct::where(
            'us_packing_list_id',
            $packingList->id
        )->delete();

        // Delete packing list
        $packingList->delete();

        return redirect()
            ->route('us.pl')
            ->with(
                'success',
                'Packing List deleted successfully.'
            );
    }

    public function exportPdf($id)
    {
        $packingList = UsPackingList::with([
            'container',
            'products'
        ])->findOrFail($id);

        $template = Templates::where(
            'type',
            'US_PL'
        )->first();

        if (!$template)
        {
            return back()->with(
                'error',
                'US PL Template Not Found'
            );
        }

        $templatePath = storage_path(
            'app/private/' . $template->file
        );

        if (!file_exists($templatePath))
        {
            return back()->with(
                'error',
                'Template File Not Found'
            );
        }

        $spreadsheet = IOFactory::load($templatePath);

        $sheet = $spreadsheet->getActiveSheet();

        /*
        ==========================
        PAGE SETUP
        ==========================
        */
        $sheet->getPageMargins()->setTop(0.25);
        $sheet->getPageMargins()->setBottom(0.25);
        $sheet->getPageMargins()->setLeft(0.25);
        $sheet->getPageMargins()->setRight(0.25);

        $sheet->getPageSetup()->setHorizontalCentered(true);
        $sheet->getPageSetup()->setVerticalCentered(true);
        $sheet->getPageSetup()->setPrintArea('B2:L24');

        $sheet->getPageSetup()->setOrientation(
            \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE
        );

        $sheet->getPageSetup()->setPaperSize(
            \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4
        );

        /*
        ==========================
        HEADER
        ==========================
        */

        $sheet->setCellValue(
            'C6',
            "CONTAINER NUMBER"." ".$packingList->container->container_number
        );

        /*
        ==========================
        PRODUCT DATA
        ==========================
        */

        $row = 8;

        foreach($packingList->products as $item)
        {
            $sheet->setCellValue(
                'C'.$row,
                $item->total_pallets
            );

            $sheet->setCellValue(
                'D'.$row,
                $item->packages
            );
            $sheet->setCellValue(
                'E'.$row,
                $item->qty_per_pallet
            );

            $sheet->setCellValue(
                'F'.$row,
                $item->product_name
            );

            $sheet->setCellValue(
                'G'.$row,
                $item->total_item_qty
            );

            $palletPackKg = 0;

            if($item->total_pallets > 0)
            {
                $palletPackKg =
                    $item->total_kg /
                    $item->total_pallets;
            }
            elseif($item->packages > 0)
            {
                $palletPackKg =
                    $item->total_kg /
                    $item->packages;
            }

            $sheet->setCellValue(
                'H'.$row,
                round($palletPackKg,2)
            );


            $sheet->setCellValue(
                'I'.$row,
                $item->total_kg
            );

            $sheet->setCellValue(
                'J'.$row,
                $item->gross_weight
            );
            $sheet->setCellValue(
                'K'.$row,
                $item->warehouse_code
            );

            $row++;
        }

        /*
        ==========================
        TOTALS
        ==========================
        */

        $totalNetWeight = $packingList->products->sum('total_kg');

        $totalGrossWeight = $packingList->products->sum('gross_weight');

        $totalPallets = $packingList->products->sum('total_pallets');

        $totalPackages = $packingList->products->sum('packages');

        $totalPieces = $packingList->products->sum('total_item_qty');

        $sheet->setCellValue(
            'I18',
            $totalNetWeight
        );

        $sheet->setCellValue(
            'I19',
            $totalGrossWeight
        );

        $sheet->setCellValue(
            'I20',
            $totalPallets
        );

        $sheet->setCellValue(
            'I22',
            $totalPackages
        );

        $sheet->setCellValue(
            'I23',
            $totalPieces
        );
        /*
        ==========================
        SAVE XLSX
        ==========================
        */

        $xlsxFile = storage_path(
            'app/temp/US_PL_'.$packingList->id.'.xlsx'
        );

        $writer = IOFactory::createWriter(
            $spreadsheet,
            'Xlsx'
        );

        $writer->save($xlsxFile);

        /*
        ==========================
        CONVERT TO PDF
        ==========================
        */

        $command =
            '"C:\Program Files\LibreOffice\program\soffice.exe" ' .
            '--headless --convert-to pdf ' .
            '--outdir "' .
            storage_path('app/temp') .
            '" "' .
            $xlsxFile .
            '"';

        exec($command, $output, $result);

        $pdfFile = storage_path(
            'app/temp/US_PL_'.$packingList->id.'.pdf'
        );

        if (!file_exists($pdfFile))
        {
            return back()->with(
                'error',
                'PDF conversion failed'
            );
        }

        return response()->download(
            $pdfFile,
            'US_Packing_List_' .
            $packingList->container->container_number .
            '.pdf'
        )->deleteFileAfterSend(false);
    }
}
