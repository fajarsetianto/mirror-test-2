<?php

namespace  App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected $viewNamespace = "pages.admin.setting.";

    public function index(){
        $item = auth()->user();
        return view($this->viewNamespace.'index', compact('item'));
    }

    public function update(Request $request){
        $request->validate([
            'name' => 'required|string',
            // 'old_password' => 'password',
            // 'new_password' => 'required|password|confirmed',
        ]);
        $validatedData = $request->validated();
        dd($validatedData);
    }
}
