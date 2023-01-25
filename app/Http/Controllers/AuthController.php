<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function index()
    {
        return view('auth.login');
    }

    public function prosesLogin(Request $request)
    {
        if (Auth::guard('karyawan')->attempt(['nik' => $request->nik, 'password' => $request->password])) {
            return redirect(route('dashboard.index'));
        } else {
            return redirect(route('auth.index'))->with(['error' => 'NIK / Password yang anda masukan salah!']);
        }
    }

    public function prosesLogout()
    {
        if (Auth::guard('karyawan')->check()) {
            Auth::guard('karyawan')->logout();
            return redirect(route('auth.index'));
        }
    }
}
