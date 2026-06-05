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
            'items.product'
        ])->findOrFail($id);

        return view(
            'account.view',
            compact('calculation')
        );
    }

}
