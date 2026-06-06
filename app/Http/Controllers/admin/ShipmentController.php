<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Shipment;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('product_name')->get();
        return view('admin.feright.shipment.add',[
            'products' => $products
        ]);
    }
    public function create()
    {
        return view('admin.shipment.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_number' => 'required|unique:shipments',
        ]);

        $shipment = Shipment::create([
            'booking_number' => $request->booking_number,
            'shipment_type' => $request->shipment_type,
            'carrier' => $request->carrier,
            'vessel_name' => $request->vessel_name,
            'voyage' => $request->voyage,
            'port_of_loading' => $request->port_of_loading,
            'port_of_discharge' => $request->port_of_discharge,
            'etd' => $request->etd,
            'eta' => $request->eta,
            'si_cut_off' => $request->si_cut_off,
            'cy_cut_off' => $request->cy_cut_off,
            'container_qty' => $request->container_qty,
            'remarks' => $request->remarks,
            'status' => $request->status,
        ]);

        if($request->product_id)
        {
            foreach($request->product_id as $key => $productId)
            {
                if(!empty($productId))
                {
                    $product = Product::find($productId);

                    $shipment->items()->create([
                        'product_id' => $productId,
                        'hs_code'    => $request->hs_code[$key],
                        'item_name'  => $product->product_name,
                    ]);
                }
            }
        }

        return redirect()
            ->route('admin.manage.shipment')
            ->with('success','Shipment Saved Successfully');
    }
    public function manage()
    {
        $shipment=Shipment::all();
        return view('admin.feright.shipment.manage',['shipments'=>$shipment]);
    }

    public function  seeDetails($id)
    {
        $shipment=Shipment::findorFail($id);
        return view('admin.feright.shipment.seedetails',['shipment'=>$shipment]);
    }
    public function delete($id)
    {
        $shipment=Shipment::findorFail($id);
        $shipment->delete();
        return redirect()->back()->with('message','Shipment Deleted Successfully');
    }

    public function edit($id)
    {
        $products=Product::orderBy('product_name')->get();
        $shipment=Shipment::findorFail($id);
        return view('admin.feright.shipment.edit',['shipment'=>$shipment,'products' => $products,]);

    }

    public function update(Request $request, $id)
    {
        $shipment = Shipment::findOrFail($id);

        $request->validate([
            'booking_number' => 'required|unique:shipments,booking_number,'.$shipment->id,
        ]);

        $shipment->update([
            'booking_number' => $request->booking_number,
            'shipment_type' => $request->shipment_type,
            'carrier' => $request->carrier,
            'vessel_name' => $request->vessel_name,
            'voyage' => $request->voyage,
            'port_of_loading' => $request->port_of_loading,
            'port_of_discharge' => $request->port_of_discharge,
            'etd' => $request->etd,
            'eta' => $request->eta,
            'si_cut_off' => $request->si_cut_off,
            'cy_cut_off' => $request->cy_cut_off,
            'container_qty' => $request->container_qty,
            'remarks' => $request->remarks,
            'status' => $request->status,
        ]);

        $shipment->items()->delete();

        if($request->product_id)
        {
            foreach($request->product_id as $key => $productId)
            {
                if(!empty($productId))
                {
                    $product = Product::find($productId);

                    $shipment->items()->create([
                        'product_id' => $productId,
                        'hs_code'    => $request->hs_code[$key],
                        'item_name'  => $product->product_name,
                    ]);
                }
            }
        }

        return redirect()
            ->route('admin.manage.shipment')
            ->with('success','Shipment Updated Successfully');
    }
}
