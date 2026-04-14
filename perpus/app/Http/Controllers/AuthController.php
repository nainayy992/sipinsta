<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showRoleSelection()
    {
        return view('auth.role_selection');
    }

    public function showAdminLogin()
    {
        return view('auth.login_admin');
    }

    public function showSiswaLogin()
    {
        return view('auth.login_siswa');
    }

    public function showRegisterAdmin()
    {
        return view('auth.register_admin');
    }

    public function login(Request $request)
    {
        if ($request->has('username')) {

            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string'
            ]);

            $loginType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $loginValue = $request->username;

        } elseif ($request->has('nis')) {

            $request->validate([
                'nis' => 'required|string',
                'password' => 'required|string'
            ]);

            $loginType = 'nis';
            $loginValue = $request->nis;

        } else {
            return back()->withErrors(['login_failed' => 'Login tidak valid']);
        }

        \Illuminate\Support\Facades\Log::info('Login attempt', [
            'loginType' => $loginType,
            'loginValue' => $loginValue,
            'session_id' => $request->session()->getId()
        ]);

        // Attempt login with basic credentials first (email/username/nis + password)
        if (Auth::attempt([$loginType => $loginValue, 'password' => $request->password])) {
            $user = Auth::user();
            \Illuminate\Support\Facades\Log::info('Login success', [
                'user_id' => $user->id,
                'role' => $user->role,
                'new_session_id' => $request->session()->getId()
            ]);

            // Check if user has the correct role for the login they are using
            if ($request->has('username') && $user->role !== 'admin') {
                Auth::logout();
                return back()->withErrors(['login_failed' => 'Akun ini bukan akun Admin.']);
            }

            if ($request->has('nis') && $user->role !== 'siswa') {
                Auth::logout();
                return back()->withErrors(['login_failed' => 'Akun ini bukan akun Siswa.']);
            }

            $request->session()->regenerate();

            if ($user->role === 'admin') {
                \Illuminate\Support\Facades\Log::info('Redirecting to admin dashboard');
                return redirect()->route('admin.dashboard');
            }

            \Illuminate\Support\Facades\Log::info('Redirecting to siswa dashboard');
            return redirect()->route('siswa.dashboard');
        }

        \Illuminate\Support\Facades\Log::warning('Login failed for user', ['loginValue' => $loginValue]);
        throw ValidationException::withMessages([
            'login_failed' => __('auth.failed'),
        ]);
    }

    public function registerAdmin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|alpha_num',
        ]);

        User::create([
            'name' => $request->username,
            'email' => $request->email,
            'username' => $request->username,
            'role' => 'admin',
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login.admin')
            ->with('success', 'Registrasi berhasil! Silahkan login.');
    }

    public function createSiswa(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'nis' => 'required|unique:users',
            'password' => 'required|min:6|alpha_num'
        ]);

        User::create([
            'name' => $request->name,
            'nis' => $request->nis,
            'role' => 'siswa',
            'password' => Hash::make($request->password),
        ]);

        \App\Models\Siswa::create([
            'nis' => $request->nis,
            'nama_siswa' => $request->name,
            'kelas' => '-',
            'jurusan' => '-',
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Akun siswa berhasil dibuat');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
