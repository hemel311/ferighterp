<?php

namespace App\Http\Controllers\freight;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('feright.product.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required',
            'hs_code' => 'required',
        ]);

        Product::create([
            'product_name' => $request->product_name,
            'hs_code' => $request->hs_code,
            'description' => $request->description,
        ]);

        return redirect()
            ->back()
            ->with('success','Product Added Successfully');
    }

    public function manage()
    {
        $products = Product::latest()->get();

        return view('feright.product.manage',[
            'products' => $products
        ]);
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);

        return view('feright.product.edit',[
            'product' => $product
        ]);
    }

    public function update(Request $request,$id)
    {
        $product = Product::findOrFail($id);

        $product->update([
            'product_name' => $request->product_name,
            'hs_code' => $request->hs_code,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('manage.product')
            ->with('success','Product Updated Successfully');
    }

    public function delete($id)
    {
        Product::findOrFail($id)->delete();

        return redirect()
            ->back()
            ->with('success','Product Deleted Successfully');
    }
}
