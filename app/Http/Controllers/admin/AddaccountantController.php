<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Accountant;
use Illuminate\Http\Request;

class AddaccountantController extends Controller
{
    public $account,$accountImage,$imageName,$directory,$imageurl;
    public function index()
    {
        return view('admin.account.user.adduser');
    }
    public function sendImage($request)
    {
        if($request->file('image'))
        {
            $this->accountImage=$request->file('image');
            $this->imageName='feright'.time().'.'.$this->accountImage->getClientOriginalExtension();
            $this->directory='assets/admin/images/account/';
            $this->accountImage->move($this->directory,$this->imageName);
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
        Accountant::create(
            [
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>bcrypt($request->password),
                'image'=>$this->sendImage($request)
            ]
        );
        return redirect()->back()->with('success',"Accountant Add Successfully");
    }
    public function manageaccountant()
    {
        $this->account=Accountant::all();

        return view('admin.account.user.manageuser',['accountants'=>$this->account]);
    }

    public function delete($id)
    {
        $this->account = Accountant::findOrFail($id);

        if ($this->account->image && file_exists(public_path($this->account->image))) {
            unlink(public_path($this->account->image));
        }

        $this->account->delete();

        return redirect()->back()->with('success', 'Account deleted successfully');
    }
    public function edit($id)
    {
        $this->account=Accountant::findorfail($id);
        return view('admin.account.user.edituser',['accountant'=>$this->account]);
    }
    public function update(Request $request,$id)
    {
        $this->account=Accountant::findorfail($id);

        $request->validate([
            'name'=>'required|string',
            'email'=>'required|string',
        ]);
        if($request->file('image'))
        {
            if(file_exists($this->account->image))
            {
                unlink($this->account->image);
            }
            $this->imageurl=$this->sendImage($request);
        }
        else{
            $this->imageurl=$this->account->image;
        }
        $this->account->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
            'image'=>$this->imageurl,
        ]);

        return redirect()->back()->with('success',"Accountant update successfully");
    }
}
