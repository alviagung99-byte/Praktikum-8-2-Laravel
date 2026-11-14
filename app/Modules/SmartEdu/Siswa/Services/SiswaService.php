<?php

namespace App\Modules\SmartEdu\Siswa\Services;

class SiswaService
{
    public function siswaDashboard()
    {
        $data = [
            'nama_siswa' => 'Andi Prasetyo',
            'kelas' => 'VII-A',
            'progress' => 65,
            'materi_selesai' => 5,
            'total_materi' => 8,
        ];
        $materi_hari_ini = [
            ['kode' => 'MTK-01', 'judul' => 'Bilangan Bulat', 'mapel' => 'Matematika', 'status' => 'Selesai'],
            ['kode' => 'IPA-02', 'judul' => 'Ekosistem Hutan', 'mapel' => 'IPA', 'status' => 'Belum'],
            ['kode' => 'BIN-03', 'judul' => 'Menulis Puisi', 'mapel' => 'Bhs. Indonesia', 'status' => 'Belum'],
        ];

        return view('smartedu-siswa::dashboard', compact('data', 'materi_hari_ini'));
    }

    public function materi()
    {
        $materi = [
            ['kode' => 'MTK-01', 'judul' => 'Bilangan Bulat', 'mapel' => 'Matematika', 'status' => 'Selesai', 'halaman' => 5],
            ['kode' => 'IPA-02', 'judul' => 'Ekosistem Hutan', 'mapel' => 'IPA', 'status' => 'Belum', 'halaman' => 8],
            ['kode' => 'BIN-03', 'judul' => 'Menulis Puisi', 'mapel' => 'Bhs. Indonesia', 'status' => 'Belum', 'halaman' => 4],
            ['kode' => 'IPS-04', 'judul' => 'Keragaman Budaya', 'mapel' => 'IPS', 'status' => 'Selesai', 'halaman' => 6],
            ['kode' => 'BIG-05', 'judul' => 'Simple Present Tense', 'mapel' => 'Bhs. Inggris', 'status' => 'Belum', 'halaman' => 7],
        ];

        return view('smartedu-siswa::materi', compact('materi'));
    }

    public function detailMateri(string $kodeMateri)
    {
        $contoh = [
            'MTK-01' => ['judul' => 'Bilangan Bulat', 'mapel' => 'Matematika', 'isi' => 'Bilangan bulat adalah bilangan yang terdiri dari bilangan negatif, nol, dan bilangan positif. Contoh: ..., -3, -2, -1, 0, 1, 2, 3, ...'],
            'IPA-02' => ['judul' => 'Ekosistem Hutan', 'mapel' => 'IPA', 'isi' => 'Ekosistem hutan adalah suatu sistem ekologi yang terbentuk oleh hubungan timbal balik antara makhluk hidup dengan lingkungannya di kawasan hutan.'],
            'BIN-03' => ['judul' => 'Menulis Puisi', 'mapel' => 'Bhs. Indonesia', 'isi' => 'Puisi adalah karya sastra yang mengungkapkan pikiran dan perasaan penyair secara imajinatif dengan mengutamakan keindahan bahasa.'],
        ];
        $materi = $contoh[$kodeMateri] ?? [
            'judul' => 'Materi '.$kodeMateri,
            'mapel' => 'Umum',
            'isi' => 'Konten materi ini akan segera tersedia.',
        ];

        return view('smartedu-siswa::detail-materi', compact('materi', 'kodeMateri'));
    }

    public function latihan()
    {
        $latihan = [
            ['kode' => 'MTK-01', 'judul' => 'Soal Bilangan Bulat', 'mapel' => 'Matematika', 'total_soal' => 10, 'status' => 'Selesai'],
            ['kode' => 'IPA-02', 'judul' => 'Soal Ekosistem', 'mapel' => 'IPA', 'total_soal' => 8, 'status' => 'Belum'],
            ['kode' => 'BIN-03', 'judul' => 'Soal Puisi dan Sastra', 'mapel' => 'Bhs. Indonesia', 'total_soal' => 5, 'status' => 'Belum'],
        ];

        return view('smartedu-siswa::latihan', compact('latihan'));
    }

    public function detailLatihan(string $kodeMateri)
    {
        $contoh = [
            'MTK-01' => [
                'judul' => 'Soal Latihan Bilangan Bulat',
                'mapel' => 'Matematika',
                'soal' => [
                    ['no' => 1, 'pertanyaan' => 'Berapakah hasil dari -5 + 3?', 'pilihan' => ['-8', '-2', '2', '8'], 'jawaban' => 'B'],
                    ['no' => 2, 'pertanyaan' => 'Manakah yang merupakan bilangan bulat negatif?', 'pilihan' => ['5', '0', '-3', '2.5'], 'jawaban' => 'C'],
                    ['no' => 3, 'pertanyaan' => 'Hasil dari (-4) × (-3) adalah?', 'pilihan' => ['-12', '-7', '7', '12'], 'jawaban' => 'D'],
                ],
            ],
        ];
        $latihan = $contoh[$kodeMateri] ?? [
            'judul' => 'Soal Latihan '.$kodeMateri,
            'mapel' => 'Umum',
            'soal' => [],
        ];

        return view('smartedu-siswa::detail-latihan', compact('latihan', 'kodeMateri'));
    }
}
