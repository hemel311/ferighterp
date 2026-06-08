<?php

namespace App\Http\Controllers\account;

use App\Http\Controllers\Controller;
use App\Models\CalculationItem;
use App\Models\CalculationSheet;
use App\Models\ContainerUpload;
use App\Models\Shipment;
use App\Models\ShipmentItem;
use App\Models\Templates;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CalculationController extends Controller
{
    public function index()
    {
        $calculations = CalculationSheet::latest()
            ->paginate(20);

        return view(
            'account.index',
            compact('calculations')
        );
    }

    public function create()
    {
        $shipments = Shipment::latest()
            ->get();

        return view(
            'account.create',
            compact('shipments')
        );
    }
    public function loadProducts($shipmentId)
    {
        try {

            $shipment = Shipment::findOrFail(
                $shipmentId
            );

            $containers =
                $shipment->container_qty;

            return view(
                'account.partials.product',
                compact('containers')
            );

        } catch (\Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }
    }
    public function store(Request $request)
    {
        $sheet = CalculationSheet::create([
            'shipment_id'   => $request->shipment_id,
            'tcmb'          => $request->tcmb,
            'shipping_cost' => $request->shipping_cost,
            'percentage'    => $request->percentage,
        ]);

        if($request->filled('english_name'))
        {
            foreach(
                $request->english_name
                as $index => $englishName
            )
            {
                $containerData = [];

                if(
                isset(
                    $request->containers[$index]
                )
                )
                {
                    foreach(
                        $request->containers[$index]
                        as $container => $qty
                    )
                    {
                        $containerData[$container]
                            = (int)$qty;
                    }
                }

                CalculationItem::create([

                    'calculation_sheet_id'
                    => $sheet->id,

                    'turkish_name'
                    => $request
                        ->turkish_name[$index],

                    'english_name'
                    => $englishName,

                    'container_quantities'
                    => $containerData,

                    'invoice_qty'
                    => $request
                        ->invoice_qty[$index],

                    'original_price'
                    => $request
                        ->original_price[$index],

                    'item_price'
                    => $request
                        ->item_price[$index],

                    'price_pi_a'
                    => $request
                            ->price_pi_a[$index] ?? null,

                    'tl_usd'
                    => $request
                        ->tl_usd[$index],

                    'shipping_additional'
                    => $request
                        ->shipping_additional[$index],

                    'cif_price'
                    => $request
                        ->cif_price[$index],

                    'tl_total'
                    => $request
                        ->tl_total[$index],
                    'direct_usd' =>
                        $request->direct_usd[$index] ?? 0,

                ]);
            }
        }

        return redirect()
            ->route(
                'account.calculation.index'
            )
            ->with(
                'success',
                'Calculation Created Successfully'
            );
    }

    public function show($id)
    {
        $calculation = CalculationSheet::with([
            'shipment',
            'items'
        ])->findOrFail($id);

        return view(
            'account.view',
            compact('calculation')
        );
    }

    public function edit($id)
    {
        $calculation = CalculationSheet::with([
            'shipment',
            'items'
        ])->findOrFail($id);

        $containers =
            $calculation->shipment->container_qty;

        $shipments = Shipment::latest()->get();

        return view(
            'account.edit',
            compact(
                'calculation',
                'containers',
                'shipments'
            )
        );
    }

    public function update(Request $request, $id)
    {
        $sheet = CalculationSheet::findOrFail($id);

        $sheet->update([
            'tcmb'          => $request->tcmb,
            'shipping_cost' => $request->shipping_cost,
            'percentage'    => $request->percentage,
        ]);

        $sheet->items()->delete();

        foreach($request->english_name as $index => $englishName)
        {
            $containerData = [];

            if(isset($request->containers[$index]))
            {
                foreach(
                    $request->containers[$index]
                    as $container => $qty
                )
                {
                    $containerData[$container] =
                        (int)$qty;
                }
            }

            CalculationItem::create([

                'calculation_sheet_id' => $sheet->id,

                'turkish_name' =>
                    $request->turkish_name[$index],

                'english_name' =>
                    $englishName,

                'container_quantities' =>
                    $containerData,

                'invoice_qty' =>
                    $request->invoice_qty[$index],

                'original_price' =>
                    $request->original_price[$index],

                'item_price' =>
                    $request->item_price[$index],

                'tl_usd' =>
                    $request->tl_usd[$index],

                'shipping_additional' =>
                    $request->shipping_additional[$index],

                'cif_price' =>
                    $request->cif_price[$index],

                'tl_total' =>
                    $request->tl_total[$index],

            ]);
        }

     return redirect()->back()->with('success','Calculation Updated Successfully');

}

    public function destroy($id)
    {
        $calculation = CalculationSheet::findOrFail($id);

        $calculation->delete();

        return redirect()
            ->route('account.calculation.index')
            ->with(
                'success',
                'Calculation Deleted Successfully'
            );
    }

    public function exportExcel($id)
    {
        $calculation = CalculationSheet::with([
            'shipment',
            'items'
        ])->findOrFail($id);

        $template = Templates::where(
            'type',
            'calculation'
        )->firstOrFail();

        $templatePath = storage_path(
            'app/private/'.$template->file
        );

        if(!file_exists($templatePath))
        {
            return back()->with(
                'error',
                'Template not found'
            );
        }

        $spreadsheet = IOFactory::load(
            $templatePath
        );

        $sheet = $spreadsheet->getActiveSheet();

        /*
        |--------------------------------------------------------------------------
        | MAX CONTAINER COUNT
        |--------------------------------------------------------------------------
        */

        $containerCount = 0;

        foreach($calculation->items as $item)
        {
            $count = count(
                $item->container_quantities ?? []
            );

            if($count > $containerCount)
            {
                $containerCount = $count;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | INSERT EXTRA CONTAINERS
        |--------------------------------------------------------------------------
        */

        if($containerCount > 4)
        {
            $extraColumns =
                $containerCount - 4;

            $sheet->insertNewColumnBefore(
                'H',
                $extraColumns
            );

            $pricePiACol =
                Coordinate::stringFromColumnIndex(
                    8 + $extraColumns
                );

            $shippingCol =
                Coordinate::stringFromColumnIndex(
                    9 + $extraColumns
                );

            $cifCol =
                Coordinate::stringFromColumnIndex(
                    10 + $extraColumns
                );

            $tlUsdCol =
                Coordinate::stringFromColumnIndex(
                    11 + $extraColumns
                );

            $tlTotalCol =
                Coordinate::stringFromColumnIndex(
                    12 + $extraColumns
                );

            $itemPriceCol =
                Coordinate::stringFromColumnIndex(
                    13 + $extraColumns
                );

            $tcmbCol =
                Coordinate::stringFromColumnIndex(
                    14 + $extraColumns
                );

            $originalPriceCol =
                Coordinate::stringFromColumnIndex(
                    16 + $extraColumns
                );
        }
        else
        {
            $pricePiACol = 'H';
            $shippingCol = 'I';
            $cifCol = 'J';
            $tlUsdCol = 'K';
            $tlTotalCol = 'L';
            $itemPriceCol = 'M';
            $tcmbCol = 'N';
            $originalPriceCol = 'P';
        }

        /*
        |--------------------------------------------------------------------------
        | CONTAINER HEADERS
        |--------------------------------------------------------------------------
        */

        for($i = 1; $i <= max(4,$containerCount); $i++)
        {
            $column =
                Coordinate::stringFromColumnIndex(
                    3 + $i
                );

            $sheet->setCellValue(
                $column.'1',
                'CONT '.$i
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PRODUCT DATA
        |--------------------------------------------------------------------------
        */

        $row = 2;

        foreach($calculation->items as $item)
        {
            $sheet->setCellValue(
                'A'.$row,
                $item->turkish_name
            );

            $sheet->setCellValue(
                'B'.$row,
                $item->english_name
            );

            $sheet->setCellValue(
                'C'.$row,
                $item->invoice_qty
            );

            /*
            |--------------------------------------------------------------------------
            | CONTAINER DATA
            |--------------------------------------------------------------------------
            */

            $containers =
                $item->container_quantities ?? [];

            for($i = 1; $i <= max(4,$containerCount); $i++)
            {
                $sheet->setCellValue(
                    Coordinate::stringFromColumnIndex(
                        3 + $i
                    ).$row,
                    $containers['CONT '.$i] ?? 0
                );
            }

            /*
            |--------------------------------------------------------------------------
            | PRICE PI A
            |--------------------------------------------------------------------------
            */

            $pricePiA =
                $item->direct_usd
                    ? $item->item_price
                    : $item->tl_usd;

            $sheet->setCellValue(
                $pricePiACol.$row,
                $pricePiA
            );

            /*
            |--------------------------------------------------------------------------
            | SHIPPING ADDITIONAL
            |--------------------------------------------------------------------------
            */

            $sheet->setCellValue(
                $shippingCol.$row,
                $item->shipping_additional
            );

            /*
            |--------------------------------------------------------------------------
            | CIF PRICE
            |--------------------------------------------------------------------------
            */

            $sheet->setCellValue(
                $cifCol.$row,
                $item->cif_price
            );

            /*
            |--------------------------------------------------------------------------
            | TL/USD
            |--------------------------------------------------------------------------
            */

            $sheet->setCellValue(
                $tlUsdCol.$row,
                $item->tl_usd
            );

            /*
            |--------------------------------------------------------------------------
            | TL TOTAL
            |--------------------------------------------------------------------------
            */

            $sheet->setCellValue(
                $tlTotalCol.$row,
                $item->tl_total
            );

            /*
            |--------------------------------------------------------------------------
            | ITEM PRICE
            |--------------------------------------------------------------------------
            */

            $sheet->setCellValue(
                $itemPriceCol.$row,
                $item->item_price
            );

            /*
            |--------------------------------------------------------------------------
            | TCMB
            |--------------------------------------------------------------------------
            */

            $sheet->setCellValue(
                $tcmbCol.$row,
                $calculation->tcmb
            );

            /*
            |--------------------------------------------------------------------------
            | ORIGINAL PRICE
            |--------------------------------------------------------------------------
            */

            $sheet->setCellValue(
                $originalPriceCol.$row,
                $item->original_price
            );

            $row++;
        }

        /*
        |--------------------------------------------------------------------------
        | TOTAL QTY
        |--------------------------------------------------------------------------
        */

        $sheet->setCellValue(
            'C12',
            $calculation->items->sum(
                'invoice_qty'
            )
        );

        /*
        |--------------------------------------------------------------------------
        | SHIPPING COST
        |--------------------------------------------------------------------------
        */

        $sheet->setCellValue(
            $shippingCol.'12',
            $calculation->shipping_cost
        );

        /*
        |--------------------------------------------------------------------------
        | SAVE FILE
        |--------------------------------------------------------------------------
        */

        $fileName =
            'Calculation_'.
            $calculation->shipment->booking_number.
            '.xlsx';

        $tempDir =
            storage_path('app/temp');

        if(!file_exists($tempDir))
        {
            mkdir(
                $tempDir,
                0777,
                true
            );
        }

        $tempFile =
            $tempDir.'/'.$fileName;

        $writer = new Xlsx(
            $spreadsheet
        );

        $writer->save(
            $tempFile
        );

        return response()->download(
            $tempFile
        )->deleteFileAfterSend(true);
    }
}

