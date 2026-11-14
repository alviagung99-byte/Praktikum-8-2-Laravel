<?php

namespace App\Modules\SmartEdu\StaffTu\Services;

use App\Modules\SmartEdu\Shared\Models\MapelUpload;
use App\Modules\SmartEdu\Shared\Models\SmartEduUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StaffTuService
{
    public function staffDashboard()
    {
        $statistik = ['total_siswa' => 128, 'guru_aktif' => 14, 'kelas' => 8];
        $aktivitas = [
            ['nama' => 'Budi Santoso', 'role' => 'Siswa', 'aksi' => 'Menyelesaikan materi Matematika Bab 3', 'waktu' => '2 menit yang lalu'],
            ['nama' => 'Ibu Dewi', 'role' => 'Guru', 'aksi' => 'Menambahkan soal latihan IPA', 'waktu' => '15 menit yang lalu'],
            ['nama' => 'Ahmad Fauzi', 'role' => 'Siswa', 'aksi' => 'Mendaftar ke kelas VII-A', 'waktu' => '1 jam yang lalu'],
            ['nama' => 'Pak Rahmat', 'role' => 'Staff TU', 'aksi' => 'Memperbarui data kelas VIII-B', 'waktu' => '2 jam yang lalu'],
        ];

        return view('smartedu-staff-tu::dashboard', compact('statistik', 'aktivitas'));
    }

    public function pengguna()
    {
        return view('smartedu-staff-tu::pengguna', ['pengguna' => SmartEduUser::all()]);
    }

    public function createPengguna()
    {
        return view('smartedu-staff-tu::pengguna-create');
    }

    public function storePengguna(Request $request)
    {
        $validated = $request->validate($this->userRules());
        $users = SmartEduUser::all();
        $duplicate = collect($users)->contains(
            fn ($user) => strtolower($user['email']) === strtolower($validated['email'])
                || $user['id'] === $validated['id']
        );

        if ($duplicate) {
            return back()->withErrors(['email' => 'ID atau email sudah terdaftar.'])->withInput();
        }

        $validated['email'] = strtolower($validated['email']);
        SmartEduUser::create($validated);

        return redirect()->route('staff-tu.pengguna')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function editPengguna(string $id)
    {
        $user = collect(SmartEduUser::all())->firstWhere('id', $id);

        abort_if(! $user, 404);

        return view('smartedu-staff-tu::pengguna-edit', compact('user'));
    }

    public function updatePengguna(Request $request, string $id)
    {
        $validated = $request->validate($this->userRules());
        $users = SmartEduUser::all();
        $targetIndex = collect($users)->search(fn ($user) => $user['id'] === $id);

        abort_if($targetIndex === false, 404);

        $duplicate = collect($users)->contains(
            fn ($user, $index) => $index !== $targetIndex
                && (strtolower($user['email']) === strtolower($validated['email'])
                    || $user['id'] === $validated['id'])
        );

        if ($duplicate) {
            return back()->withErrors(['email' => 'ID atau email sudah digunakan oleh pengguna lain.'])->withInput();
        }

        $validated['email'] = strtolower($validated['email']);
        SmartEduUser::update($id, $validated);

        return redirect()->route('staff-tu.pengguna')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroyPengguna(string $id)
    {
        SmartEduUser::delete($id);

        return redirect()->route('staff-tu.pengguna')->with('success', 'Pengguna berhasil dihapus.');
    }

    private function userRules(): array
    {
        return [
            'id' => ['required', 'string', 'max:20'],
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'password' => ['required', 'string', 'min:4'],
            'role' => ['required', 'in:Staff TU,Guru,Siswa,Orang Tua'],
        ];
    }

    public function kelas()
    {
        $kelas = [
            ['kode' => 'VII-A', 'nama' => 'Kelas VII A'],
            ['kode' => 'VII-B', 'nama' => 'Kelas VII B'],
            ['kode' => 'VIII-A', 'nama' => 'Kelas VIII A'],
            ['kode' => 'VIII-B', 'nama' => 'Kelas VIII B'],
            ['kode' => 'IX-A', 'nama' => 'Kelas IX A'],
            ['kode' => 'IX-B', 'nama' => 'Kelas IX B'],
        ];

        return view('smartedu-staff-tu::kelas', compact('kelas'));
    }

    public function mapelData(): array
    {
        $mapel = MapelUpload::query()
            ->select('id', 'kode_mapel as kode', 'nama_mapel as nama', 'nama_asli', 'path_file')
            ->whereNotNull('kode_mapel')
            ->whereNotNull('nama_mapel')
            ->orderBy('kode_mapel')
            ->get()
            ->map(fn ($item) => [
                'id' => $item->id,
                'kode' => $item->kode,
                'nama' => $item->nama,
                'nama_file' => $item->nama_asli,
                'path_file' => $item->path_file,
            ])
            ->all();

        if (empty($mapel)) {
            return [
                ['id' => 1, 'kode' => 'MTK', 'nama' => 'Matematika', 'nama_file' => 'contoh-matematika.pdf', 'path_file' => '#'],
                ['id' => 2, 'kode' => 'IPA', 'nama' => 'Ilmu Pengetahuan Alam', 'nama_file' => 'contoh-ipa.pdf', 'path_file' => '#'],
                ['id' => 3, 'kode' => 'IPS', 'nama' => 'Ilmu Pengetahuan Sosial', 'nama_file' => 'contoh-ips.pdf', 'path_file' => '#'],
                ['id' => 4, 'kode' => 'BIN', 'nama' => 'Bahasa Indonesia', 'nama_file' => 'contoh-bahasa-indonesia.pdf', 'path_file' => '#'],
                ['id' => 5, 'kode' => 'BIG', 'nama' => 'Bahasa Inggris', 'nama_file' => 'contoh-bahasa-inggris.pdf', 'path_file' => '#'],
                ['id' => 6, 'kode' => 'PKN', 'nama' => 'Pendidikan Kewarganegaraan', 'nama_file' => 'contoh-pkn.pdf', 'path_file' => '#'],
            ];
        }

        return $mapel;
    }

    public function mapel()
    {
        return view('smartedu-staff-tu::mapel', ['mapel' => $this->mapelData()]);
    }

    public function uploadMapelFile(Request $request)
    {
        $validated = $request->validate($this->mapelRules(true));
        $this->storeMapelFile($validated, $request);

        return redirect()->route('staff-tu.mapel')->with('success', 'Mapel berhasil ditambahkan: '.trim($validated['nama_mapel']));
    }

    public function ajaxStoreMapel(Request $request)
    {
        $validated = $request->validate($this->mapelRules(true));
        $item = $this->storeMapelFile($validated, $request);

        return response()->json([
            'success' => true,
            'message' => 'Mapel berhasil ditambahkan: '.trim($validated['nama_mapel']),
            'mapel' => $item,
        ]);
    }

    public function ajaxUpdateMapel(Request $request, int $id)
    {
        $validated = $request->validate($this->mapelRules(false));
        $mapel = MapelUpload::find($id);

        if (! $mapel) {
            return response()->json(['success' => false, 'message' => 'Data mapel tidak ditemukan.'], 404);
        }

        $mapel->kode_mapel = strtoupper(trim($validated['kode_mapel']));
        $mapel->nama_mapel = trim($validated['nama_mapel']);

        if ($request->hasFile('file')) {
            $this->replaceMapelFile($mapel, $request);
        }

        $mapel->save();

        return response()->json([
            'success' => true,
            'message' => 'Mapel berhasil diperbarui: '.trim($validated['nama_mapel']),
        ]);
    }

    public function ajaxDeleteMapel(int $id)
    {
        $mapel = MapelUpload::find($id);

        if (! $mapel) {
            return response()->json(['success' => false, 'message' => 'Data mapel tidak ditemukan.'], 404);
        }

        Storage::disk('public')->delete('uploads/mapel/'.$mapel->nama_file);
        $mapel->delete();

        return response()->json(['success' => true, 'message' => 'Mapel berhasil dihapus.']);
    }

    private function mapelRules(bool $fileRequired): array
    {
        return [
            'kode_mapel' => ['required', 'string', 'max:20'],
            'nama_mapel' => ['required', 'string', 'max:100'],
            'file' => [$fileRequired ? 'required' : 'nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png', 'max:2048'],
        ];
    }

    private function storeMapelFile(array $validated, Request $request): array
    {
        $file = $request->file('file');
        $filename = time().'_'.$file->getClientOriginalName();
        $path = $file->storeAs('uploads/mapel', $filename, 'public');
        $mapel = MapelUpload::create([
            'kode_mapel' => strtoupper(trim($validated['kode_mapel'])),
            'nama_mapel' => trim($validated['nama_mapel']),
            'nama_file' => $filename,
            'nama_asli' => $file->getClientOriginalName(),
            'path_file' => Storage::url($path),
            'mime_type' => $file->getMimeType(),
            'ukuran' => $file->getSize(),
        ]);

        return [
            'id' => $mapel->id,
            'kode' => $mapel->kode_mapel,
            'nama' => $mapel->nama_mapel,
            'nama_file' => $mapel->nama_asli,
            'path_file' => $mapel->path_file,
        ];
    }

    private function replaceMapelFile(MapelUpload $mapel, Request $request): void
    {
        $file = $request->file('file');
        $filename = time().'_'.$file->getClientOriginalName();
        $path = $file->storeAs('uploads/mapel', $filename, 'public');

        Storage::disk('public')->delete('uploads/mapel/'.$mapel->nama_file);
        $mapel->nama_file = $filename;
        $mapel->nama_asli = $file->getClientOriginalName();
        $mapel->path_file = Storage::url($path);
        $mapel->mime_type = $file->getMimeType();
        $mapel->ukuran = $file->getSize();
    }
}
