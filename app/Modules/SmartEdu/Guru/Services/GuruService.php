<?php

namespace App\Modules\SmartEdu\Guru\Services;

class GuruService
{
    public function guruDashboard()
    {
        $data = [
            'nama_guru' => 'Ibu Dewi Lestari',
            'mata_pelajaran' => 'Ilmu Pengetahuan Alam (IPA)',
            'total_siswa' => 32,
            'total_materi' => 10,
            'total_soal' => 25,
        ];

        return view('smartedu-guru::dashboard', compact('data'));
    }
}
