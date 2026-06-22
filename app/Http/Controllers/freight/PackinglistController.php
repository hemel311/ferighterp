<?php

namespace App\Http\Controllers\freight;

use App\Http\Controllers\Controller;
use App\Models\ContainerUpload;
use App\Models\Shipment;
use App\Models\TrPackingList;
use App\Models\TrPackingListItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Templates;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class PackinglistController extends Controller
{
    public function index()
    {
        $shipments = ContainerUpload::select('booking_number')
            ->distinct()
            ->orderBy('booking_number')
            ->get();

        return view('feright.pl.pl', compact('shipments'));
    }
    public function getContainers($bookingNumber)
    {
        $containers = ContainerUpload::with([
            'vgmInfo',
            'trPackingLists'
        ])
            ->where('booking_number', $bookingNumber)
            ->get();

        return response()->json($containers);
    }
    public function create($id)
    {

        $container = ContainerUpload::with([
            'vgmInfo'
        ])->findOrFail($id);

        // Prevent duplicate packing list creation
        $existingPackingList = TrPackingList::where(
            'container_upload_id',
            $container->id
        )->first();

        if ($existingPackingList) {

            if ($existingPackingList->status == 'draft') {
                return redirect()
                    ->route('trpl.edit', $existingPackingList->id)
                    ->with('warning', 'Draft packing list already exists.');
            }

            return redirect()
                ->route('trpl.preview', $existingPackingList->id)
                ->with('warning', 'Packing list already submitted.');
        }

        return view(
            'feright.pl.create',
            compact('container')
        );
    }

    public function store(Request $request)
    {
        $totalNetWeight = 0;
        $totalGrossWeight = 0;
        $totalPallets = 0;
        $totalPackages = 0;
        $totalPieces = 0;

        foreach ($request->items as $item)
        {
            $totalNetWeight += $item['net_weight'] ?? 0;
            $totalGrossWeight += $item['gross_weight'] ?? 0;
            $totalPallets += $item['total_pallets'] ?? 0;
            $totalPackages += $item['total_packages'] ?? 0;
            $totalPieces += $item['item_quantity'] ?? 0;
        }

        $container = ContainerUpload::findOrFail(
            $request->container_upload_id
        );

        $shipment = Shipment::where(
            'booking_number',
            $container->booking_number
        )->first();

        if(!$shipment)
        {
            return redirect()->back()
                ->with('error','Shipment not found');
        }

        $status = $request->action == 'draft'
            ? 'draft'
            : 'submitted';

        $packingList = TrPackingList::create([

            'shipment_id' => $shipment->id,

            'container_upload_id' => $request->container_upload_id,

            'vgm_info_id' => $request->vgm_info_id,

            'pl_date' => $request->pl_date,

            'from_location' => $request->from_location,

            'to_location' => $request->to_location,

            'total_net_weight' => $totalNetWeight,

            'total_gross_weight' => $totalGrossWeight,

            'total_pallets' => $totalPallets,

            'total_packages' => $totalPackages,

            'total_item_quantity' => $totalPieces,


            'status' => $status,
        ]);

        $container = ContainerUpload::with('vgmInfo')
            ->findOrFail($request->container_upload_id);

        if (
            $status == 'submitted' &&
            !$container->vgmInfo
        ) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error',
                    'VGM must be submitted before Packing List can be submitted.'
                );
        }

        foreach ($request->items as $item)
        {
            TrPackingListItem::create([

                'tr_packing_list_id' => $packingList->id,

                'product_name' => $item['product_name'],
                'description' => $item['description'] ?? null,

                'total_pallets' => $item['total_pallets'] ?? 0,

                'total_packages' => $item['total_packages'] ?? 0,

                'item_quantity' => $item['item_quantity'] ?? 0,
                'quantity_per_unit'=>$item['quantity_per_unit'] ?? 0,

                'gross_weight' => $item['gross_weight'] ?? 0,

                'net_weight' => $item['net_weight'] ?? 0,
                'pallet_pack_kg' => $item['pallet_pack_kg'] ?? null,
                'is_special_product' =>
                    isset($item['is_special_product']) ? 1 : 0,

                'is_m2' =>
                    isset($item['is_m2']) ? 1 : 0,

                'total_m2' =>
                    $item['total_m2'] ?? null,
            ]);
        }

        if($status == 'draft')
        {
            return redirect()
                ->route('trpl.edit',$packingList->id)
                ->with('success','Draft saved successfully');
        }

        return redirect()
            ->route('trpl.preview',$packingList->id)
            ->with('success','Packing List submitted successfully');
    }
    public function edit($id)
    {
        $packingList = TrPackingList::with([
            'items',
            'container.vgmInfo'
        ])->findOrFail($id);

        return view(
            'feright.pl.edit',
            compact('packingList')
        );
    }


    public function update(Request $request, $id)
    {
        $packingList = TrPackingList::findOrFail($id);

        $totalNetWeight = 0;
        $totalGrossWeight = 0;
        $totalPallets = 0;
        $totalPackages = 0;
        $totalPieces = 0;
        $totalM2 = 0;

        foreach ($request->items as $item)
        {
            $totalNetWeight += $item['net_weight'] ?? 0;

            $totalGrossWeight += $item['gross_weight'] ?? 0;

            $totalPallets += $item['total_pallets'] ?? 0;

            $totalPackages += $item['total_packages'] ?? 0;

            $totalPieces += $item['item_quantity'] ?? 0;

            if(isset($item['is_m2']))
            {
                $totalM2 += $item['total_m2'] ?? 0;
            }
        }

        $status = $request->action == 'draft'
            ? 'draft'
            : 'submitted';

        $packingList->update([

            'pl_date' => $request->pl_date,

            'from_location' => $request->from_location,

            'to_location' => $request->to_location,

            'total_net_weight' => $totalNetWeight,

            'total_gross_weight' => $totalGrossWeight,

            'total_pallets' => $totalPallets,

            'total_packages' => $totalPackages,

            'total_item_quantity' => $totalPieces,
            'total_m2' => $totalM2,

            'status' => $status,
        ]);

        // Delete old items
        TrPackingListItem::where(
            'tr_packing_list_id',
            $packingList->id
        )->delete();

        // Insert new items
        foreach ($request->items as $item)
        {
            TrPackingListItem::create([

                'tr_packing_list_id' => $packingList->id,

                'product_name' => $item['product_name'],

                'quantity_per_unit' =>
                    $item['quantity_per_unit'] ?? null,

                'description' =>
                    $item['description'] ?? null,

                'total_pallets' =>
                    $item['total_pallets'] ?? 0,

                'total_packages' =>
                    $item['total_packages'] ?? 0,

                'item_quantity' =>
                    $item['item_quantity'] ?? 0,

                'gross_weight' =>
                    $item['gross_weight'] ?? 0,

                'net_weight' =>
                    $item['net_weight'] ?? 0,

                'pallet_pack_kg' =>
                    $item['pallet_pack_kg'] ?? null,

                'is_special_product' =>
                    isset($item['is_special_product']) ? 1 : 0,

                'is_m2' =>
                    isset($item['is_m2']) ? 1 : 0,

                'total_m2' =>
                    $item['total_m2'] ?? null,

            ]);
        }

        if($status == 'draft')
        {
            return redirect()
                ->route('trpl.edit',$packingList->id)
                ->with('success','Draft updated successfully');
        }

        return redirect()
            ->route('trpl.preview',$packingList->id)
            ->with('success','Packing List submitted successfully');
    }

    public function preview($id)
    {
        $packingList = TrPackingList::with([
            'shipment',
            'container.vgmInfo',
            'items'
        ])->findOrFail($id);

        return view(
            'feright.pl.preview',
            compact('packingList')
        );
    }


public function delete($id)
{
    $packingList = TrPackingList::findOrFail($id);

    // Delete all items first
    TrPackingListItem::where(
        'tr_packing_list_id',
        $packingList->id
    )->delete();

    // Delete packing list
    $packingList->delete();

    return redirect()
        ->route('trpl.index')
        ->with(
            'success',
            'Packing List deleted successfully.'
        );
}



    public function exportExcel($id)
    {
        $packingList = TrPackingList::with([
            'container',
            'items'
        ])->findOrFail($id);
        $hasM2 = $packingList->items->contains(function ($item) {
            return $item->is_m2 == 1;
        });

        $totalM2 = $packingList->items->sum('total_m2');

        $template = Templates::where(
            'type',
            'TR_PL'
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
            "To:"." ".$packingList->to_location
        );

        $sheet->setCellValue(
            'C13',
            "From:"." ".$packingList->from_location
        );

        $sheet->setCellValue(
            'F6',
            "CONTAINER NUMBER"." ".$packingList->container->container_number
        );

        /*
        ==========================
        PRODUCT DATA
        ==========================
        */

        $row = 8;
        $hasM2 = $packingList->items->contains(function ($item) {
            return $item->is_m2 == 1;
        });

        $totalM2 = $packingList->items->sum('total_m2');
        if($hasM2)
        {
            $sheet->insertNewColumnBefore('K', 1);

            $sheet->setCellValue(
                'K7',
                'TOTAL M²'
            );
        }

        foreach($packingList->items as $item)
        {
            $sheet->setCellValue(
                'F'.$row,
                $item->total_pallets
            );

            $sheet->setCellValue(
                'G'.$row,
                $item->total_packages
            );
            $sheet->setCellValue(
                'H'.$row,
                $item->quantity_per_unit
            );

            $sheet->setCellValue(
                'I'.$row,
                $item->product_name
            );

            $sheet->setCellValue(
                'J'.$row,
                $item->item_quantity
            );

            if($hasM2)
            {
                $sheet->setCellValue('J'.$row,$item->item_quantity);

                $sheet->setCellValue(
                    'K'.$row,
                    $item->is_m2
                        ? $item->total_m2
                        : ''
                );

                $sheet->setCellValue(
                    'L'.$row,
                    $item->pallet_pack_kg
                );

                $sheet->setCellValue(
                    'M'.$row,
                    $item->net_weight
                );

                $sheet->setCellValue(
                    'N'.$row,
                    $item->gross_weight
                );
            }
            else
            {
                $sheet->setCellValue('J'.$row,$item->item_quantity);

                $sheet->setCellValue(
                    'K'.$row,
                    $item->pallet_pack_kg
                );

                $sheet->setCellValue(
                    'L'.$row,
                    $item->net_weight
                );

                $sheet->setCellValue(
                    'M'.$row,
                    $item->gross_weight
                );
            }
            $row++;
        }

        /*
       ==========================
TOTALS
==========================
*/



        if($hasM2)
        {

            $sheet->insertNewRowBefore(26,1);
            $sheet->setCellValue(
                'L22',
                "Net Weight"
            );
            $sheet->setCellValue(
                'M22',
                $packingList->total_net_weight
            );

            $sheet->setCellValue(
                'L23',
                "Gross weight"
            );
            $sheet->setCellValue(
                'M23',
                $packingList->total_gross_weight
            );
            $sheet->setCellValue(
                'L24',
                "Total Palates"
            );

            $sheet->setCellValue(
                'M24',
                $packingList->total_pallets
            );

            $sheet->setCellValue(
                'L26',
                'Total M²'
            );

            $sheet->setCellValue(
                'M26',
                $totalM2
            );

            $sheet->setCellValue(
                'N26',
                'm²'
            );

            // shifted rows
            $sheet->setCellValue(
                'L27',
               "Total Package"
            );
            $sheet->setCellValue(
                'M27',
                $packingList->total_packages
            );

            $sheet->setCellValue(
                'L28',
                "Total Pieces"
            );
            $sheet->setCellValue(
                'M28',
                $packingList->total_item_quantity
            );
        }
        else
        {
            $sheet->setCellValue(
                'K22',
                "Net Weight"
            );
            $sheet->setCellValue(
                'L22',
                $packingList->total_net_weight
            );

            $sheet->setCellValue(
                'K23',
                "Gross weight"
            );
            $sheet->setCellValue(
                'L23',
                $packingList->total_gross_weight
            );
            $sheet->setCellValue(
                'K24',
                "Total Palates"
            );

            $sheet->setCellValue(
                'L24',
                $packingList->total_pallets
            );
            $sheet->setCellValue(
                'K26',
                "Total Package"
            );
            $sheet->setCellValue(
                'L26',
                $packingList->total_packages
            );


            $sheet->setCellValue(
                'L27',
                $packingList->total_item_quantity
            );
        }
        $sheet->setCellValue(
            'C21',"INVOICE DATE:".Carbon::parse($packingList->pl_date
            )->format('d.m.Y')
        );

        $fileName =
            'TR_Packing_List_'.
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

public function exportPdf($id)
{
    $packingList = TrPackingList::with([
        'container',
        'items'
    ])->findOrFail($id);
    $hasM2 = $packingList->items->contains(function ($item) {
        return $item->is_m2 == 1;
    });
    $totalM2 = $packingList->items->sum('total_m2');

    $template = Templates::where(
        'type',
        'TR_PL'
    )->first();

    if (!$template)
    {
        return back()->with(
            'error',
            'TR PL Template Not Found'
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

    $sheet->getPageSetup()->setPrintArea('B2:N28');

    $sheet->getPageSetup()->setOrientation(
        \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE
    );

    $sheet->getPageSetup()->setPaperSize(
        \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4
    );

    /*
    |--------------------------------------------------------------------------
    | Center on Page
    |--------------------------------------------------------------------------
    */
    $sheet->getPageMargins()->setTop(0.25);
    $sheet->getPageMargins()->setBottom(0.25);
    $sheet->getPageMargins()->setLeft(0.25);
    $sheet->getPageMargins()->setRight(0.25);

    $sheet->getPageSetup()->setHorizontalCentered(true);
    $sheet->getPageSetup()->setVerticalCentered(true);
    /*
    ==========================
    HEADER
    ==========================
    */

    $sheet->setCellValue(
        'C6',
        "To:"." ".$packingList->to_location
    );

    $sheet->setCellValue(
        'C13',
        "From:"." ".$packingList->from_location
    );

    $sheet->setCellValue(
        'F6',
        "CONTAINER NUMBER"." ".$packingList->container->container_number
    );

    /*
    ==========================
    PRODUCT DATA
    ==========================
    */

    $row = 8;
    $hasM2 = $packingList->items->contains(function ($item) {
        return $item->is_m2 == 1;
    });

    $totalM2 = $packingList->items->sum('total_m2');
    if($hasM2)
    {
        $sheet->insertNewColumnBefore('K', 1);

        $sheet->setCellValue(
            'K7',
            'TOTAL M²'
        );
    }

    foreach($packingList->items as $item)
    {
        $sheet->setCellValue(
            'F'.$row,
            $item->total_pallets
        );

        $sheet->setCellValue(
            'G'.$row,
            $item->total_packages
        );
        $sheet->setCellValue(
            'H'.$row,
            $item->quantity_per_unit
        );

        $sheet->setCellValue(
            'I'.$row,
            $item->product_name
        );

        $sheet->setCellValue(
            'J'.$row,
            $item->item_quantity
        );

        if($hasM2)
        {
            $sheet->setCellValue('J'.$row,$item->item_quantity);

            $sheet->setCellValue(
                'K'.$row,
                $item->is_m2
                    ? $item->total_m2
                    : ''
            );

            $sheet->setCellValue(
                'L'.$row,
                $item->pallet_pack_kg
            );

            $sheet->setCellValue(
                'M'.$row,
                $item->net_weight
            );

            $sheet->setCellValue(
                'N'.$row,
                $item->gross_weight
            );
        }
        else
        {
            $sheet->setCellValue('J'.$row,$item->item_quantity);

            $sheet->setCellValue(
                'K'.$row,
                $item->pallet_pack_kg
            );

            $sheet->setCellValue(
                'L'.$row,
                $item->net_weight
            );

            $sheet->setCellValue(
                'M'.$row,
                $item->gross_weight
            );
        }
        $row++;
    }

    /*
   ==========================
TOTALS
==========================
*/



    if($hasM2)
    {

        $sheet->insertNewRowBefore(26,1);
        $sheet->setCellValue(
            'L22',
            "Net Weight"
        );
        $sheet->setCellValue(
            'M22',
            $packingList->total_net_weight
        );

        $sheet->setCellValue(
            'L23',
            "Gross weight"
        );
        $sheet->setCellValue(
            'M23',
            $packingList->total_gross_weight
        );
        $sheet->setCellValue(
            'L24',
            "Total Palates"
        );

        $sheet->setCellValue(
            'M24',
            $packingList->total_pallets
        );

        $sheet->setCellValue(
            'L26',
            'Total M²'
        );

        $sheet->setCellValue(
            'M26',
            $totalM2
        );

        $sheet->setCellValue(
            'N26',
            'm²'
        );

        // shifted rows
        $sheet->setCellValue(
            'L27',
            "Total Package"
        );
        $sheet->setCellValue(
            'M27',
            $packingList->total_packages
        );

        $sheet->setCellValue(
            'L28',
            "Total Pieces"
        );
        $sheet->setCellValue(
            'M28',
            $packingList->total_item_quantity
        );
    }
    else
    {
        $sheet->setCellValue(
            'K22',
            "Net Weight"
        );
        $sheet->setCellValue(
            'L22',
            $packingList->total_net_weight
        );

        $sheet->setCellValue(
            'K23',
            "Gross weight"
        );
        $sheet->setCellValue(
            'L23',
            $packingList->total_gross_weight
        );
        $sheet->setCellValue(
            'K24',
            "Total Palates"
        );

        $sheet->setCellValue(
            'L24',
            $packingList->total_pallets
        );
        $sheet->setCellValue(
            'K26',
            "Total Package"
        );
        $sheet->setCellValue(
            'L26',
            $packingList->total_packages
        );


        $sheet->setCellValue(
            'L27',
            $packingList->total_item_quantity
        );
    }
    $sheet->setCellValue(
        'C21',"INVOICE DATE:".Carbon::parse($packingList->pl_date
        )->format('d.m.Y')
    );
    /*
    ==========================
    SAVE XLSX
    ==========================
    */

    $xlsxFile = storage_path(
        'app/temp/TR_PL_'.$packingList->id.'.xlsx'
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
        'app/temp/TR_PL_'.$packingList->id.'.pdf'
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
        'TR_Packing_List_' .
        $packingList->container->container_number .
        '.pdf'
    )->deleteFileAfterSend(false);
}



//    public function testLibreOffice()
//    {
//        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
//
//        $sheet = $spreadsheet->getActiveSheet();
//
//        $sheet->setCellValue('A1', 'TR Packing List Test');
//
//        $xlsxFile = storage_path('app/temp/test.xlsx');
//
//        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter(
//            $spreadsheet,
//            'Xlsx'
//        );
//
//        $writer->save($xlsxFile);
//
//        $command =
//            '"C:\Program Files\LibreOffice\program\soffice.exe" ' .
//            '--headless --convert-to pdf ' .
//            '--outdir "' . storage_path('app/temp') . '" "' .
//            $xlsxFile . '"';
//
//        exec($command, $output, $result);
//
//        return [
//            'result' => $result,
//            'output' => $output,
//            'pdf_exists' => file_exists(
//                storage_path('app/temp/test.pdf')
//            )
//        ];
//    }

}
