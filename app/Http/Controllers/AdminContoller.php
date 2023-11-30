<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminContoller extends Controller
{
    public function list(){
        $data['getRecord'] = User::getAdmin();
        $data['header_title'] = "Admin List";
       return view('admin.admin.list', $data);
    }

    public function add(){
        $data['header_title'] = "Add New Admin";
        return view('admin.admin.add',$data);
    }

    public function insert(Request $request){
       $user = new User();
       $user->name = trim($request->name);
       $user->email = trim($request->email);
       $user->password = Hash::Make($request->password);
       $user->user_types = 1;
       $user->save();

       return redirect('admin/admin/list')->with('success',"Admin successfully created");
    }
}
