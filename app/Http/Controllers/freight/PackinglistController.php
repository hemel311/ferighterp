<?php

namespace App\Http\Controllers\freight;

use App\Http\Controllers\Controller;
use App\Models\ContainerUpload;
use App\Models\Shipment;
use App\Models\TrPackingList;
use Illuminate\Http\Request;

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

        // Check VGM exists
        if (!$container->vgmInfo) {
            return redirect()->back()
                ->with('error', 'VGM information not found for this container.');
        }

        return view(
            'feright.pl.create',
            compact('container')
        );
    }

}
