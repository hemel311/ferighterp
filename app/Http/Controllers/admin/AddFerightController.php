<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\FerightForwarder;
use Illuminate\Http\Request;

class AddFerightController extends Controller
{
    public $feright,$ferightImage,$imageName,$directory,$imageurl;
    public function index()
    {
        return view('admin.feright.user.adduser');
    }
    public function sendImage($request)
    {
        if($request->file('image'))
        {
            $this->ferightImage=$request->file('image');
            $this->imageName='feright'.time().'.'.$this->ferightImage->getClientOriginalExtension();
            $this->directory='assets/admin/images/feright/';
            $this->ferightImage->move($this->directory,$this->imageName);
            $this->imageurl=$this->directory.$this->imageName;
            return $this->imageurl;
        }
        else
        {
            return "";
        }

    }
    public function store(Request $request)
    {
        $request->validate([
                'name'=>'string|required',
                'email'=>'string|required',
                'password'=>'required|string|min:6',

            ]
        );
        FerightForwarder::create(
            [
              'name'=>$request->name,
              'email'=>$request->email,
              'password'=>bcrypt($request->password),
              'image'=>$this->sendImage($request)
            ]
        );
        return redirect()->back()->with('success',"Freight Forwarder Add Successfully");
    }
    public function manageferight()
    {
        $this->feright=FerightForwarder::all();

        return view('admin.feright.user.manageuser',['forwarders'=>$this->feright]);
    }

    public function delete($id)
    {
        $this->feright = FerightForwarder::findOrFail($id);

        if ($this->feright->image && file_exists(public_path($this->feright->image))) {
            unlink(public_path($this->feright->image));
        }

        $this->feright->delete();

        return redirect()->back()->with('success', 'Freight Forwarder deleted successfully');
    }
    public function edit($id)
    {
        $this->feright=FerightForwarder::findorfail($id);
        return view('admin.feright.user.edit',['forwarder'=>$this->feright]);
    }
    public function update(Request $request,$id)
    {
        $this->feright=FerightForwarder::findorfail($id);

        $request->validate([
            'name'=>'required|string',
            'email'=>'required|string',
        ]);
        if($request->file('image'))
        {
            if(file_exists($this->feright->image))
            {
                unlink($this->feright->image);
            }
            $this->imageurl=$this->sendImage($request);
        }
        else{
            $this->imageurl=$this->feright->image;
        }
        $this->feright->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
            'image'=>$this->imageurl,
        ]);

        return redirect()->back()->with('success',"Freight Forwarder update successfully");
    }
}
