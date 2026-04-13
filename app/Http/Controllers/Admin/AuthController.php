<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Debug: Log input
        \Log::info('Admin Login Attempt', [
            'email' => $credentials['email'],
            'password_length' => strlen($credentials['password'])
        ]);

        $admin = Admin::where('email', $credentials['email'])->first();

        if (!$admin) {
            \Log::warning('Admin not found', ['email' => $credentials['email']]);
            return back()->withErrors([
                'email' => 'Email tidak ditemukan di database.',
            ])->onlyInput('email');
        }

        \Log::info('Admin found', [
            'id' => $admin->id,
            'email' => $admin->email,
            'hash_preview' => substr($admin->password, 0, 20)
        ]);

        $passwordCheck = Hash::check($credentials['password'], $admin->password);
        \Log::info('Password check result', ['result' => $passwordCheck ? 'TRUE' : 'FALSE']);

        if (!$passwordCheck) {
            return back()->withErrors([
                'email' => 'Password yang Anda masukkan salah.',
            ])->onlyInput('email');
        }

        // Set session
        $request->session()->put('admin_id', $admin->id);
        $request->session()->put('admin_name', $admin->name);
        $request->session()->save();

        \Log::info('Admin logged in successfully', ['admin_id' => $admin->id]);

        return redirect()->route('admin.dashboard')->with('success', 'Login berhasil!');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['admin_id', 'admin_name']);
        return redirect()->route('admin.login');
    }
}
