<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login()
    {
        if (!empty(Auth::check())) {

            if (Auth::user()->user_types == 1) {
                return redirect('admin/dashboard');
            } else if (Auth::user()->user_types == 2) {
                return redirect('teacher/dashboard');
            } else if (Auth::user()->user_types == 3) {
                return redirect('student/dashboard');
            } else if (Auth::user()->user_types == 4) {
                return redirect('parent/dashboard');
            }
        }
        return view('auth.login');
    }

    function AuthLogin(Request $requst)
    {
        $remember = !empty($requst->remember) ? true : false;
        if (Auth::attempt(['email' => $requst->email, 'password' => $requst->password], $remember)) {

            if (Auth::user()->user_types == 1) {
                return redirect('admin/dashboard');
            } else if (Auth::user()->user_types == 2) {
                return redirect('teacher/dashboard');
            } else if (Auth::user()->user_types == 3) {
                return redirect('student/dashboard');
            } else if (Auth::user()->user_types == 4) {
                return redirect('parent/dashboard');
            }
        } else {
            return redirect()->back()->with('error', 'Please enter a valid email and password');
        }
    }

    public function forgotpassword()
    {
        return view('auth.forgot');
    }

    public function PostForgotpassword(Request $req)
    {
        $user = User::getEmailSingle($req->email);
        if (!empty($user)) {
            $user->remember_token = Str::random(30);
            Mail::to($user->email)->send(new ForgotPasswordMail($user));

            return redirect()->back()->with('success', 'Please check your email and reset your password');
        } else {
            return redirect()->back()->with('error', 'Email not found');
        }
    }

    public function reset($remember_token)
    {
        $user = User::getTokenSingle($remember_token);
        if (!empty($user)) {
            $data['user'] = $user;
            return view('auth.reset', $data);
        } else {
            abort(404);
        }
    }

    public function PasswordReset($token, Request $req){
        if($req->password == $req->confirm){
            $user = User::getTokenSingle($token);
            $user->password = Hash::make($req->password);
            $user->remember_token = Str::random(30);
            $user->save();

            return redirect(url('/'))->with('success', 'Password successfull reseted');
        }
        else{
            return redirect()->back()->with('error', 'Password and confirm password does not match');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect(url('/'));
    }
}
