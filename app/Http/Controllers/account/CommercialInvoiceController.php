<?php

namespace App\Http\Controllers\account;

use App\Http\Controllers\Controller;
use App\Models\CalculationSheet;
use App\Models\CommercialInvoice;
use App\Models\Shipment;
use App\Models\Templates;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class CommercialInvoiceController extends Controller
{
    public function index()
    {
        $invoices = CommercialInvoice::with([
            'shipment',
            'calculation'
        ])
            ->latest()
            ->paginate(20);

        return view(
            'account.commercial.index',
            compact('invoices')
        );
    }

    public function create()
    {
        $shipments = Shipment::latest()->get();

        return view(
            'account.commercial.create',
            compact('shipments')
        );
    }

    public function loadCalculation($shipmentId)
    {
        try {

            $calculation = CalculationSheet::with('items')
                ->where('shipment_id', $shipmentId)
                ->first();

            if(!$calculation)
            {
                return 'No Calculation Found';
            }

            return view(
                'account.commercial.partials.product',
                compact('calculation')
            );

        } catch (\Throwable $e) {

            return response()->json([
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipment_id'   => 'required',
            'export_number' => 'required',
        ]);

        $calculation = CalculationSheet::where(
            'shipment_id',
            $request->shipment_id
        )->firstOrFail();

        CommercialInvoice::create([

            'shipment_id' =>
                $request->shipment_id,

            'calculation_sheet_id' =>
                $calculation->id,

            'export_number' =>
                $request->export_number,

            'shipping_cost' =>
                $request->shipping_cost ?? 0,

        ]);

        return redirect()
            ->route(
                'account.commercial.index'
            )
            ->with(
                'success',
                'Commercial Invoice Created Successfully'
            );
    }

    public function show($id)
    {
        $invoice = CommercialInvoice::with([
            'shipment',
            'calculation.items'
        ])->findOrFail($id);

        $grandTotal = 0;

        foreach(
            $invoice->calculation->items
            as $item
        )
        {
            $pricePiA =
                $item->direct_usd
                    ? $item->item_price
                    : $item->tl_usd;

            $grandTotal +=
                $pricePiA *
                $item->invoice_qty;
        }

        $finalTotal =
            $grandTotal +
            $invoice->shipping_cost;

        return view(
            'account.commercial.view',
            compact(
                'invoice',
                'grandTotal',
                'finalTotal'
            )
        );
    }

    public function edit($id)
    {
        $invoice = CommercialInvoice::with([
            'shipment',
            'calculation.items'
        ])->findOrFail($id);

        return view(
            'account.commercial.edit',
            compact('invoice')
        );
    }
    public function update(Request $request,$id)
    {
        $invoice = CommercialInvoice::findOrFail($id);

        $invoice->update([

            'export_number' =>
                $request->export_number,

            'shipping_cost' =>
                $request->shipping_cost,

        ]);

        return redirect()
            ->route(
                'account.commercial.index'
            )
            ->with(
                'success',
                'Commercial Invoice Updated Successfully'
            );
    }

    public function destroy($id)
    {
        $invoice = CommercialInvoice::findOrFail(
            $id
        );

        $invoice->delete();

        return redirect()
            ->back()
            ->with(
                'success',
                'Commercial Invoice Deleted Successfully'
            );
    }

    public function exportExcel($id)
    {
        $invoice = CommercialInvoice::with([
            'shipment',
            'calculation.items'
        ])->findOrFail($id);

        $template = Templates::where(
            'type',
            'CI'
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

        $sheet =
            $spreadsheet->getActiveSheet();

        /*
        |--------------------------------------------------------------------------
        | HEADER INFORMATION
        |--------------------------------------------------------------------------
        */

        $sheet->setCellValue(
            'G4',
            $invoice->export_number
        );



        $sheet->setCellValue(
            'G3',
            $invoice->created_at
                ->format('d.m.Y')
        );

        /*
        |--------------------------------------------------------------------------
        | PRODUCT COUNT
        |--------------------------------------------------------------------------
        */

        $items =
            $invoice->calculation->items;

        $productCount =
            $items->count();

        $templateRows = 7;

        /*
        |--------------------------------------------------------------------------
        | INSERT EXTRA ROWS IF NEEDED
        |--------------------------------------------------------------------------
        */

        if($productCount > $templateRows)
        {
            $extraRows =
                $productCount -
                $templateRows;

            $sheet->insertNewRowBefore(
                28,
                $extraRows
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PRODUCT DATA
        |--------------------------------------------------------------------------
        */

        $row = 21;

        $grandTotal = 0;

        foreach($items as $item)
        {
            $pricePiA =
                $item->direct_usd
                    ? $item->item_price
                    : $item->tl_usd;

            $totalValue =
                $pricePiA *
                $item->invoice_qty;

            $grandTotal +=
                $totalValue;

            $sheet->setCellValue(
                'A'.$row,
                $item->english_name
            );

            $sheet->setCellValue(
                'F'.$row,
                $item->invoice_qty
            );

            $sheet->setCellValue(
                'G'.$row,
                round(
                    $pricePiA,
                    4
                )
            );

            $sheet->setCellValue(
                'H'.$row,
                round(
                    $totalValue,
                    2
                )
            );

            $row++;
        }

        /*
        |--------------------------------------------------------------------------
        | DYNAMIC SHIPPING / TOTAL ROWS
        |--------------------------------------------------------------------------
        */

        $extraRows =
            max(
                0,
                $productCount - $templateRows
            );

        $shippingRow =
            28 + $extraRows;

        $totalRow =
            30 + $extraRows;

        /*
        |--------------------------------------------------------------------------
        | SHIPPING COST
        |--------------------------------------------------------------------------
        */

        $sheet->setCellValue(
            'H'.$shippingRow,
            $invoice->shipping_cost
        );

        /*
        |--------------------------------------------------------------------------
        | FINAL TOTAL
        |--------------------------------------------------------------------------
        */

        $sheet->setCellValue(
            'H'.$totalRow,
            round(
                $grandTotal +
                $invoice->shipping_cost,
                2
            )
        );

        /*
        |--------------------------------------------------------------------------
        | NUMBER FORMAT
        |--------------------------------------------------------------------------
        */

        for($i = 21; $i < $row; $i++)
        {
            $sheet->getStyle('G'.$i)
                ->getNumberFormat()
                ->setFormatCode(
                    '#,##0.0000'
                );

            $sheet->getStyle('H'.$i)
                ->getNumberFormat()
                ->setFormatCode(
                    '#,##0.00'
                );
        }

        $sheet->getStyle(
            'H'.$shippingRow
        )
            ->getNumberFormat()
            ->setFormatCode(
                '#,##0.00'
            );

        $sheet->getStyle(
            'H'.$totalRow
        )
            ->getNumberFormat()
            ->setFormatCode(
                '#,##0.00'
            );

        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD
        |--------------------------------------------------------------------------
        */

        $fileName =
            'Commercial_Invoice_'.
            $invoice->shipment->booking_number.
            '.xlsx';

        $tempPath =
            storage_path(
                'app/temp'
            );

        if(!file_exists($tempPath))
        {
            mkdir(
                $tempPath,
                0777,
                true
            );
        }

        $fullPath =
            $tempPath.'/'.$fileName;

        $writer = new Xlsx(
            $spreadsheet
        );

        $writer->save(
            $fullPath
        );

        return response()
            ->download(
                $fullPath
            )
            ->deleteFileAfterSend(true);
    }

    public function exportPdf($id)
    {
        $invoice = CommercialInvoice::with([
            'shipment',
            'calculation.items'
        ])->findOrFail($id);

        $template = Templates::where(
            'type',
            'CI'
        )->firstOrFail();

        $templatePath = storage_path(
            'app/private/' . $template->file
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

        $sheet =
            $spreadsheet->getActiveSheet();

        /*
        |--------------------------------------------------------------------------
        | HEADER INFORMATION
        |--------------------------------------------------------------------------
        */

        $sheet->setCellValue(
            'N4',
            $invoice->export_number
        );

        $sheet->setCellValue(
            'N5',
            $invoice->shipment->booking_number
        );

        $sheet->setCellValue(
            'N6',
            $invoice->created_at
                ->format('d.m.Y')
        );

        /*
        |--------------------------------------------------------------------------
        | PRODUCT COUNT
        |--------------------------------------------------------------------------
        */

        $items =
            $invoice->calculation->items;

        $productCount =
            $items->count();

        $templateRows = 7;

        if($productCount > $templateRows)
        {
            $extraRows =
                $productCount -
                $templateRows;

            $sheet->insertNewRowBefore(
                28,
                $extraRows
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PRODUCT DATA
        |--------------------------------------------------------------------------
        */

        $row = 21;

        $grandTotal = 0;

        foreach($items as $item)
        {
            $pricePiA =
                $item->direct_usd
                    ? $item->item_price
                    : $item->tl_usd;

            $totalValue =
                $pricePiA *
                $item->invoice_qty;

            $grandTotal +=
                $totalValue;

            $sheet->setCellValue(
                'A'.$row,
                $item->english_name
            );

            $sheet->setCellValue(
                'F'.$row,
                $item->invoice_qty
            );

            $sheet->setCellValue(
                'G'.$row,
                round(
                    $pricePiA,
                    4
                )
            );

            $sheet->setCellValue(
                'H'.$row,
                round(
                    $totalValue,
                    2
                )
            );

            $row++;
        }

        /*
        |--------------------------------------------------------------------------
        | SHIPPING & TOTAL
        |--------------------------------------------------------------------------
        */

        $extraRows =
            max(
                0,
                $productCount - $templateRows
            );

        $shippingRow =
            28 + $extraRows;

        $totalRow =
            30 + $extraRows;

        $sheet->setCellValue(
            'H'.$shippingRow,
            $invoice->shipping_cost
        );

        $sheet->setCellValue(
            'H'.$totalRow,
            round(
                $grandTotal +
                $invoice->shipping_cost,
                2
            )
        );

        /*
        |--------------------------------------------------------------------------
        | NUMBER FORMAT
        |--------------------------------------------------------------------------
        */

        for($i = 21; $i < $row; $i++)
        {
            $sheet->getStyle('G'.$i)
                ->getNumberFormat()
                ->setFormatCode(
                    '#,##0.0000'
                );

            $sheet->getStyle('H'.$i)
                ->getNumberFormat()
                ->setFormatCode(
                    '#,##0.00'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | PDF PRINT AREA
        |--------------------------------------------------------------------------
        */

        $sheet->getPageSetup()
            ->setPrintArea(
                'A1:I39'
            );

        /*
        |--------------------------------------------------------------------------
        | TEMP DIRECTORY
        |--------------------------------------------------------------------------
        */

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

        $xlsxFile =
            $tempDir .
            '/commercial_invoice_'.$invoice->id.'.xlsx';

        $pdfFile =
            $tempDir .
            '/commercial_invoice_'.$invoice->id.'.pdf';

        /*
        |--------------------------------------------------------------------------
        | SAVE EXCEL
        |--------------------------------------------------------------------------
        */

        $writer = new Xlsx(
            $spreadsheet
        );

        $writer->save(
            $xlsxFile
        );

        /*
        |--------------------------------------------------------------------------
        | CONVERT TO PDF USING LIBREOFFICE
        |--------------------------------------------------------------------------
        */

        $libreOfficePath =
            '"C:\Program Files\LibreOffice\program\soffice.exe"';

        $command =
            $libreOfficePath .
            ' --headless --convert-to pdf' .
            ' --outdir "' . $tempDir . '"' .
            ' "' . $xlsxFile . '"';

        exec($command);

        $pdfFile =
            $tempDir .
            '/commercial_invoice_' .
            $invoice->id .
            '.pdf';

        /*
        |--------------------------------------------------------------------------
        | CHECK PDF EXISTS
        |--------------------------------------------------------------------------
        */

        if(!file_exists($pdfFile))
        {
            return back()->with(
                'error',
                'PDF generation failed'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD PDF
        |--------------------------------------------------------------------------
        */

        return response()->download(
            $pdfFile,
            'Commercial_Invoice_' .
            $invoice->shipment->booking_number .
            '.pdf'
        )->deleteFileAfterSend(true);
    }


}
