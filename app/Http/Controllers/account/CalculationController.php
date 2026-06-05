<?php

namespace App\Http\Controllers\account;

use App\Http\Controllers\Controller;
use App\Models\CalculationItem;
use App\Models\CalculationSheet;
use App\Models\ContainerUpload;
use App\Models\Shipment;
use App\Models\ShipmentItem;
use Illuminate\Http\Request;

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

        $shipment = Shipment::findOrFail($shipmentId);

        $products = ShipmentItem::with('product')
            ->where('shipment_id', $shipmentId)
            ->get();

        $containers = $shipment->container_qty;

        return view(
            'account.partials.product',
            compact(
                'products',
                'containers'
            )
        );

    } catch (\Exception $e) {

        return response()->json([
            'error' => $e->getMessage()
        ]);

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

        if($request->filled('product_id'))
        {
            foreach($request->product_id as $index => $productId)
            {
                $containerData = [];

                foreach($request->containers[$index] as $key => $qty)
                {
                    $containerData[$key] = (int)$qty;
                }

                $invoiceQty = array_sum($containerData);

                $originalPrice =
                    $request->original_price[$index] ?? 0;

                $percentage =
                    $request->percentage ?? 0;

                $itemPrice = $originalPrice;

                if($percentage > 0)
                {
                    $itemPrice =
                        $originalPrice +
                        ($originalPrice * $percentage / 100);
                }

                CalculationItem::create([
                    'calculation_sheet_id' => $sheet->id,
                    'product_id' => $productId,
                    'container_quantities' => $containerData,
                    'invoice_qty' => $invoiceQty,
                    'original_price' => $originalPrice,
                    'item_price' => $itemPrice,

                    'price_pi_a' => $request->price_pi_a[$index],
                    'tl_usd' => $request->tl_usd[$index],
                    'shipping_additional' => $request->shipping_additional[$index],
                    'cif_price' => $request->cif_price[$index],
                    'tl_total' => $request->tl_total[$index],
                ]);
            }
        }

        return redirect()
            ->route('account.calculation.index')
            ->with(
                'success',
                'Calculation created successfully'
            );
    }

}
