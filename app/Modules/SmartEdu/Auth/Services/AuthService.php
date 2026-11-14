<?php

namespace App\Modules\SmartEdu\Auth\Services;

use App\Modules\SmartEdu\Shared\Models\SmartEduUser;
use Illuminate\Http\Request;

class AuthService
{
    public function landing()
    {
        $fitur = [
            ['ikon' => '📚', 'judul' => 'Materi Inklusif', 'deskripsi' => 'Konten pembelajaran yang disesuaikan dengan kebutuhan setiap siswa.'],
            ['ikon' => '✏️', 'judul' => 'Latihan Interaktif', 'deskripsi' => 'Soal latihan terstruktur untuk mengukur pemahaman siswa.'],
            ['ikon' => '📊', 'judul' => 'Pantau Perkembangan', 'deskripsi' => 'Orang tua dapat memantau progres belajar anak secara real-time.'],
            ['ikon' => '🏫', 'judul' => 'Manajemen Sekolah', 'deskripsi' => 'Staff TU mengelola kelas, guru, dan siswa dengan mudah.'],
        ];
        $roles = ['Staff TU', 'Guru', 'Siswa', 'Orang Tua'];

        return view('smartedu-auth::landing', compact('fitur', 'roles'));
    }

    public function loginForm()
    {
        $redirectMap = [
            'staff-tu' => 'staff-tu.dashboard',
            'guru' => 'guru.dashboard',
            'siswa' => 'siswa.dashboard',
            'orang-tua' => 'orang-tua.dashboard',
        ];
        $role = session('smartedu_user.role');

        if (isset($redirectMap[$role])) {
            return redirect()->route($redirectMap[$role]);
        }

        return view('smartedu-auth::login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);
        $user = SmartEduUser::findByEmail(strtolower(trim($validated['email'])));

        if (! $user || ($user['password'] ?? '') !== $validated['password']) {
            return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
        }

        $role = strtolower(str_replace(' ', '-', $user['role']));
        $redirectMap = [
            'staff-tu' => 'staff-tu.dashboard',
            'guru' => 'guru.dashboard',
            'siswa' => 'siswa.dashboard',
            'orang-tua' => 'orang-tua.dashboard',
        ];

        $request->session()->put('smartedu_user', [
            'email' => strtolower(trim($validated['email'])),
            'name' => $user['nama'],
            'role' => $role,
        ]);

        return redirect()->route($redirectMap[$role] ?? 'home');
    }

    public function logout()
    {
        session()->forget('smartedu_user');

        return redirect()->route('login');
    }
}
