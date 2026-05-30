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
}
