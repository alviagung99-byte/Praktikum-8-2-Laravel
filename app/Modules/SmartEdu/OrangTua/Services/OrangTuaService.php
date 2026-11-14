<?php

namespace App\Modules\SmartEdu\OrangTua\Services;

class OrangTuaService
{
    public function orangTuaDashboard()
    {
        $data = [
            'nama_ortu' => 'Bapak Prasetyo',
            'nama_anak' => 'Andi Prasetyo',
            'kelas' => 'VII-A',
            'progress' => 65,
            'materi_selesai' => 5,
            'total_materi' => 8,
        ];
        $progres_mingguan = [
            ['hari' => 'Sen', 'aktivitas' => 3],
            ['hari' => 'Sel', 'aktivitas' => 5],
            ['hari' => 'Rab', 'aktivitas' => 2],
            ['hari' => 'Kam', 'aktivitas' => 4],
            ['hari' => 'Jum', 'aktivitas' => 1],
        ];
        $tugas_pending = [
            ['judul' => 'Soal Latihan Ekosistem', 'mapel' => 'IPA'],
            ['judul' => 'Menulis Puisi Bebas', 'mapel' => 'Bhs. Indonesia'],
        ];

        return view('smartedu-orang-tua::dashboard', compact('data', 'progres_mingguan', 'tugas_pending'));
    }
}
