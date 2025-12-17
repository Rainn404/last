<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // =========================
        // VALIDASI INPUT
        // =========================
        $request->validate(
            [
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|max:255|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
            ],
            [
                'email.unique'      => 'Email sudah terdaftar.',
                'password.confirmed'=> 'Konfirmasi kata sandi tidak cocok.',
                'password.min'      => 'Kata sandi minimal 8 karakter.',
            ]
        );

        // =========================
        // BLOK EMAIL ADMIN / SYSTEM
        // =========================
        $blockedEmails = [
            'superadmn.himati@gmail.com',
            'tipolitalaa@gmail.com',
        ];

        if (in_array($request->email, $blockedEmails)) {
            return back()
                ->withErrors([
                    'email' => 'Email ini tidak bisa digunakan untuk pendaftaran umum.',
                ])
                ->withInput();
        }

        // =========================
        // BUAT USER BARU (MAHASISWA)
        // =========================
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'mahasiswa', // ⬅️ KONSISTEN
        ]);

        // =========================
        // LOGIN OTOMATIS
        // =========================
        Auth::login($user);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Akun berhasil dibuat! Selamat datang di HIMA-TI 🎉');
    }
}
