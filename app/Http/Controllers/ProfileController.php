<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil.
     */
    public function index()
    {
        return view('profile');
    }

    /**
     * Perbarui informasi profil pengguna.
     *
     * Mengubah data di tabel users (kolom firebase_token diabaikan).
     * Email bersifat opsional, sehingga pengguna dapat mengganti nama saja.
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|max:255'
        ]);

        $user = Auth::user();
        $user->name = $request->input('name');

        if ($request->filled('email')) {
            $user->email = $request->input('email');
        }

        $user->save();

        return redirect()->back()->with('status', 'Informasi profil berhasil diperbarui.');
    }

    /**
     * Perbarui password pengguna.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|string|min:8|confirmed'
        ]);

        $user = Auth::user();

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        return redirect()->back()->with('status', 'Password berhasil diperbarui.');
    }

    /**
     * Hapus akun pengguna.
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->input('password'), $user->password)) {
            return redirect()->back()->withErrors(['password' => 'Password salah.']);
        }

        // Logout dan hapus akun
        Auth::logout();
        $user->delete();

        return redirect('/')->with('status', 'Akun Anda telah dihapus.');
    }
}
