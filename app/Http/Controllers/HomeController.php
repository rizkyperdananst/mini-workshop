<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function memberRegister(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'npm' => 'required|unique:members,npm',
            'class' => 'required',
            'phone' => 'required|min:10|max:13',
        ], [
            'name.required' => 'Nama tidak boleh kosong',
            'npm.required' => 'NPM tidak boleh kosong',
            'npm.unique' => 'NPM sudah terdaftar',
            'class.required' => 'Kelas tidak boleh kosong',
            'phone.required' => 'Nomor HP tidak boleh kosong',
            'phone.min' => 'Nomor HP minimal 10 angka',
            'phone.max' => 'Nomor HP maksimal 13 angka',
        ]);

        $member = Member::create($validated);

        return redirect()->route('home')->with('success', 'Anda berhasil mendaftar');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
