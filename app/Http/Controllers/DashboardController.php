<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function dashboard(){
        $data['header_title'] = 'Dashboard';
        if (Auth::user()->user_types == 1) {
            return view('admin.dashboard', $data);
        } else if (Auth::user()->user_types == 2) {
            return view('teacher.dashboard', $data);
        } else if (Auth::user()->user_types == 3) {
            return view('student.dashboard', $data);
        } else if (Auth::user()->user_types == 4) {
            return view('parent.dashboard', $data);
        }
    }
}