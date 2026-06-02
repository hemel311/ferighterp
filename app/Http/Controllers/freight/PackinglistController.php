<?php

namespace App\Http\Controllers\freight;

use App\Http\Controllers\Controller;
use App\Models\ContainerUpload;
use App\Models\Shipment;
use App\Models\TrPackingList;
use App\Models\TrPackingListItem;
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

        foreach ($request->items as $item)
        {
            TrPackingListItem::create([

                'tr_packing_list_id' => $packingList->id,

                'product_name' => $item['product_name'],

                'description' => $item['description'] ?? null,

                'total_pallets' => $item['total_pallets'] ?? 0,

                'total_packages' => $item['total_packages'] ?? 0,

                'item_quantity' => $item['item_quantity'] ?? 0,

                'gross_weight' => $item['gross_weight'] ?? 0,

                'net_weight' => $item['net_weight'] ?? 0,
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

        foreach ($request->items as $item)
        {
            $totalNetWeight += $item['net_weight'] ?? 0;

            $totalGrossWeight += $item['gross_weight'] ?? 0;

            $totalPallets += $item['total_pallets'] ?? 0;

            $totalPackages += $item['total_packages'] ?? 0;

            $totalPieces += $item['item_quantity'] ?? 0;
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

                'description' => $item['description'] ?? null,

                'total_pallets' => $item['total_pallets'] ?? 0,

                'total_packages' => $item['total_packages'] ?? 0,

                'item_quantity' => $item['item_quantity'] ?? 0,

                'gross_weight' => $item['gross_weight'] ?? 0,

                'net_weight' => $item['net_weight'] ?? 0,
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

    }

}
