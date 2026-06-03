<?php

namespace App\Http\Controllers\freight;

use App\Http\Controllers\Controller;
use App\Models\ContainerUpload;
use App\Models\Shipment;
use App\Models\Vgminfo;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;
use Illuminate\Http\Request;

class VgmController extends Controller
{
    public function index()
    {
        $shipments = Shipment::select('booking_number')
            ->distinct()
            ->get();

        return view(
            'feright.vgm.vgm',
            compact('shipments')
        );
    }
    public function search(Request $request)
    {
        $containers = ContainerUpload::with('vgmInfo')
            ->where('booking_number', $request->booking_number)
            ->get();

        return response()->json($containers);
    }
    public function create($id)
    {
        $container = ContainerUpload::findOrFail($id);

        return view('feright.vgm.create', compact('container'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'container_id'      => 'required',
            'vgm_weight'        => 'required|numeric',
            'container_weight'  => 'required|numeric',
        ]);
        $pdfPath = null;

        if ($request->hasFile('pdf_file'))
        {
            $pdfPath = $request->file('pdf_file')
                ->store('vgm_pdfs', 'public');
        }
        $grossWeight = $request->vgm_weight - $request->container_weight;

        Vgminfo::create([
            'container_id'      => $request->container_id,
            'vgm_weight'        => $request->vgm_weight,
            'container_weight'  => $request->container_weight,
            'gross_weight'      => $grossWeight,
            'pdf_file'          =>$pdfPath
        ]);

        return redirect()->route('vgm')
            ->with('success','VGM Information Saved Successfully');
    }
    public function delete($id)
    {
        $vgm = Vgminfo::findOrFail($id);

        $vgm->delete();

        return redirect()->back()
            ->with('success','VGM deleted successfully');
    }

    public function extractPdf(Request $request)
    {
        $parser = new Parser();

        $pdf = $parser->parseFile(
            $request->file('pdf')->getPathname()
        );

        $text = $pdf->getText();

        preg_match('/([A-Z]{4}\d{7})/', $text, $container);

// Get all KG values from PDF
        preg_match_all('/(\d+\.\d+)\s*KG/i', $text, $weights);

// Debug once
// dd($weights);

        $vgmWeight = 0;
        $containerWeight = 0;

        if(isset($weights[1][1])) {
            $vgmWeight = (float) str_replace('.', '', $weights[1][1]);
        }

        if(isset($weights[1][3])) {
            $containerWeight = (float) str_replace('.', '', $weights[1][3]);
        }

        return response()->json([
            'container_number' => $container[1] ?? '',
            'vgm_weight' => $vgmWeight,
            'container_weight' => $containerWeight,
            'gross_weight' => $vgmWeight - $containerWeight,
        ]);
    }

    public function download($id)
    {
        $vgm = Vgminfo::findOrFail($id);

        return Storage::disk('public')
            ->download($vgm->pdf_file);
    }
}
