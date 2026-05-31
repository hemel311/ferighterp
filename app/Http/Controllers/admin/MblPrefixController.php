<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\MblPrefix;
use Illuminate\Http\Request;

class MblPrefixController extends Controller
{
    protected $prefix;
    public function index()
    {
        return view('admin.feright.prefix.addprefix');

    }
    public function store(Request $request)
    {
        $request->validate([
            'shipping_company'=>'string|required',
            'prefix'=>'string|required',


        ]
    );
        MblPrefix::create(
            [
                'shipping_company'=>$request->shipping_company,
                'prefix'=>$request->prefix,
            ]
        );
        return redirect()->back()->with('success',"MBL Prefix add successfully");
    }
    public function manageprefix()
    {
        $this->prefix=MblPrefix::all();

        return view('admin.feright.prefix.manageprefix',['prefixes'=>$this->prefix]);
    }

    public function delete($id)
    {
        $this->prefix = MblPrefix::findOrFail($id);

        $this->prefix->delete();

        return redirect()->back()->with('success', 'MBL Prefix deleted successfully');
    }
    public function edit($id)
    {
        $this->prefix=MblPrefix::findorfail($id);
        return view('admin.feright.prefix.edit',['prefix'=>$this->prefix]);
    }
    public function update(Request $request,$id)
    {
        $this->prefix=MblPrefix::findorfail($id);

        $request->validate([
            'shipping_company'=>'required|string',
            'prefix'=>'required|string',
        ]);
        $this->prefix->update([
            'shipping_company'=>$request->shipping_company,
            'prefix'=>$request->prefix,
        ]);

        return redirect()->back()->with('success',"MBL Prefix update successfully");
    }
}
