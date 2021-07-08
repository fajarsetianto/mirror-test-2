<?php

namespace  App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    protected $viewNamespace = "pages.officer.setting.";
    

    public function index(){
        $item = auth('officer')->user();
        return view($this->viewNamespace.'index', compact('item'));
    }

    public function update(Request $request){
        $validator = $request->validate([
            'name' => 'required_without:old_password,password|string',
            'old_password' => 'required_with:password|string|password',
            'password' => 'required_with:old_password|string|min:6|confirmed',
        ]);
        $data = $request->only(['name']);

        if($request->has('password')){
            $data['password'] = Hash::make($request->password);
        }

        auth('officer')->user()->update($data);

        return back()->with('success','Data berhasil diperbaharui');
    }
}