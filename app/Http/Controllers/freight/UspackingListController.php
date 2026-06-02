<?php

namespace App\Http\Controllers\freight;

use App\Http\Controllers\Controller;
use App\Models\ContainerUpload;
use App\Models\Shipment;
use App\Models\Templates;
use App\Models\UsPackingList;
use App\Models\UsPackingListProduct;
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

        return view(
            'feright.pl.uspl.createuspl',
            compact('container')
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

                    'total_pallets'      => $item['total_pallets'] ?? 0,

                    'packages'           => $item['total_packages'] ?? 0,

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
            'product'
        ])->findOrFail($id);

        $template = Templates::where(
            'type',
            'US_PL'
        )->first();

        if(!$template)
        {
            return back()->with(
                'error',
                'TR PL Template Not Found'
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

        $row = 6;

        foreach($packingList->product as $item)
        {
            $sheet->setCellValue(
                'C'.$row,
                $item->total_pallets
            );

            $sheet->setCellValue(
                'D'.$row,
                $item->total_packages
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
                $item->item_quantity
            );

            $palletPackKg = 0;

            if($item->total_pallets > 0)
            {
                $palletPackKg =
                    $item->net_weight /
                    $item->total_pallets;
            }
            elseif($item->total_packages > 0)
            {
                $palletPackKg =
                    $item->net_weight /
                    $item->total_packages;
            }

            $sheet->setCellValue(
                'H'.$row,
                round($palletPackKg,2)
            );

            $sheet->setCellValue(
                'I'.$row,
                $item->net_weight
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

        $sheet->setCellValue(
            'I18',
            $packingList->total_net_weight
        );

        $sheet->setCellValue(
            'I19',
            $packingList->total_gross_weight
        );

        $sheet->setCellValue(
            'I20',
            $packingList->total_pallets
        );

        $sheet->setCellValue(
            'I22',
            $packingList->total_packages
        );

        $sheet->setCellValue(
            'I23',
            $packingList->total_item_quantity
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
}
