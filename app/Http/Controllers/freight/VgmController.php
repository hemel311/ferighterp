<?php

namespace App\Http\Controllers\freight;

use App\Http\Controllers\Controller;
use App\Models\ContainerUpload;
use App\Models\Shipment;
use App\Models\Vgminfo;
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

        $grossWeight = $request->vgm_weight - $request->container_weight;

        Vgminfo::create([
            'container_id'      => $request->container_id,
            'vgm_weight'        => $request->vgm_weight,
            'container_weight'  => $request->container_weight,
            'gross_weight'      => $grossWeight,
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
}
