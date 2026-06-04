<?php

namespace App\Http\Controllers\freight;

use App\Http\Controllers\Controller;
use App\Models\Isf;
use App\Models\MblPrefix;
use App\Models\Shipment;
use App\Models\Templates;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class IsfController extends Controller
{
    public function create()
    {
        $shipments = Shipment::where('status', 'Submitted')
            ->orderBy('booking_number')
            ->get();

        $prefixes = MblPrefix::orderBy('shipping_company')
            ->get();

        return view('feright.isf.create', compact(
            'shipments',
            'prefixes'
        ));
    }

    public function getShipmentData($id)
    {
        $shipment = Shipment::with(['items', 'containers'])
            ->findOrFail($id);

        return response()->json([
            'shipment' => $shipment
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipment_id'      => 'required|exists:shipments,id',
            'mbl_prefix_id'    => 'required',
            'booking_number'   => 'nullable',
        ]);

        $shipment = Shipment::findOrFail($request->shipment_id);

        $isf=Isf::create([
            'shipment_id'        => $shipment->id,
            'mbl_prefix_id'      => $request->mbl_prefix_id,

            'booking_number'     => $shipment->booking_number,
            'from_address' => $request->from_address,
            'to_address'   => $request->to_address,
            'manufacturer' => $request->manufacturer,

            'product_name'       => $request->product_name,
            'hs_code'            => $request->hs_code,

            'hbl'                => $request->hbl,
            'mbl'                => $request->mbl,

            'etd'                => $request->etd,

            'port_of_loading'    => $request->port_of_loading,
            'port_of_discharge'  => $request->port_of_discharge,

            'container_numbers'  => $request->container_numbers,
            'vessel_name' => $request->vessel_name,
            'voyage'      => $request->voyage,

            'status'             => $request->status,
        ]);

        if($request->status == 'Draft')
        {
            return redirect()
                ->route('isf.manage')
                ->with('success', 'ISF Draft Saved Successfully');
        }

        return redirect()
            ->route('isf.preview', $isf->id)
            ->with('success', 'ISF Submitted Successfully');
    }

    public function preview($id)
    {
        $isf = Isf::findOrFail($id);

        return view('feright.isf.preview', compact('isf'));
    }

    public function manage()
    {
        $isf=Isf::all();
        return view('feright.isf.manage',['isfs'=>$isf]);
    }

    public function exportExcel($id)
    {

        $isf = Isf::findOrFail($id);

        $template = Templates::where('type', 'ISF')
            ->firstOrFail();

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
        $hsCodes = explode("\n", trim($isf->hs_code));
        $products = explode("\n", trim($isf->product_name));
        $containers = collect(
            preg_split('/\r\n|\r|\n/', trim($isf->container_numbers))
        )
            ->filter()
            ->implode(',');

        $combined = [];

        foreach ($hsCodes as $index => $hsCode)
        {
            $combined[] = trim($hsCode).' '.trim($products[$index] ?? '');
        }

        $spreadsheet = IOFactory::load($templatePath);

        $sheet = $spreadsheet->getActiveSheet();

        /*
        |--------------------------------------------------------------------------
        | Fill Template Cells
        |--------------------------------------------------------------------------
        */

        // Product & HS Code
        $sheet->setCellValue(
            'A20',
            implode(',', $combined)
        );

        // BL Information
        $sheet->setCellValue('A29', $isf->hbl);
        $sheet->setCellValue('D29', $isf->mbl);
        $sheet->setCellValue('F29', $isf->vessel_name);
        $sheet->setCellValue('J29', $isf->voyage);
        $sheet->setCellValue('A7',$isf->from_address);
        $sheet->setCellValue('F7',$isf->to_address);
        $sheet->setCellValue('I20',$isf->manufacturer);

        // Shipment Information
        $sheet->setCellValue('A31', Carbon::parse($isf->etd)->format('d M Y'));
        $sheet->setCellValue('D31', $isf->port_of_loading);
        $sheet->setCellValue('F31', $containers);
        $sheet->setCellValue('J31', $isf->port_of_discharge);

        $fileName = 'ISF-'.$isf->booking_number.'.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');

        }, $fileName);
    }


    public function edit($id)
    {
        $isf = Isf::findOrFail($id);

        $shipments = Shipment::where('status', 'Submitted')
            ->orderBy('booking_number')
            ->get();

        $prefixes = MblPrefix::orderBy('shipping_company')
            ->get();

        return view(
            'feright.isf.edit',
            compact(
                'isf',
                'shipments',
                'prefixes'
            )
        );
    }

    public function update(Request $request, $id)
    {
        $isf = Isf::findOrFail($id);

        $isf->update([

            'mbl_prefix_id'     => $request->mbl_prefix_id,

            'from_address' => $request->from_address,
            'to_address'   => $request->to_address,
            'manufacturer' => $request->manufacturer,


            'product_name'      => $request->product_name,
            'hs_code'           => $request->hs_code,

            'hbl'               => $request->hbl,
            'mbl'               => $request->mbl,

            'vessel_name'       => $request->vessel_name,
            'voyage'            => $request->voyage,

            'etd'               => $request->etd,

            'port_of_loading'   => $request->port_of_loading,
            'port_of_discharge' => $request->port_of_discharge,

            'container_numbers' => $request->container_numbers,

            'status'            => $request->status,
        ]);

        if($request->status == 'Draft')
        {
            return redirect()
                ->route('isf.edit',$id)
                ->with('success','ISF Updated Successfully');
        }

        return redirect()
            ->route('isf.preview',$isf->id)
            ->with('success','ISF Submitted Successfully');
    }

    public function delete($id)
    {
        $isf=Isf::findorFail($id);
        $isf->delete();
        return redirect()->back()->with('success',"ISF Deleted Successfully");
    }

    public function exportPdf($id)
    {
        $isf = Isf::findOrFail($id);

        $pdf = Pdf::loadView(
            'feright.isf.pdf',
            compact('isf')
        );

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream(
            'ISF-' . $isf->booking_number . '.pdf'
        );
    }

}
