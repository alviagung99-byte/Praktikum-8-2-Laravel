<?php

namespace Tests\Feature;

use App\Modules\SmartEdu\Shared\Models\MapelUpload;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SmartEduModuleViewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_role_module_views_render_for_authenticated_users(): void
    {
        $routes = [
            ['staff-tu', '/staff-tu/dashboard'],
            ['staff-tu', '/staff-tu/pengguna'],
            ['staff-tu', '/staff-tu/kelas'],
            ['staff-tu', '/staff-tu/mapel'],
            ['guru', '/guru/dashboard'],
            ['siswa', '/siswa/dashboard'],
            ['siswa', '/siswa/materi'],
            ['siswa', '/siswa/materi/MTK-01'],
            ['siswa', '/siswa/latihan'],
            ['siswa', '/siswa/latihan/MTK-01'],
            ['orang-tua', '/orang-tua/dashboard'],
        ];

        foreach ($routes as [$role, $uri]) {
            $this->withSession(['smartedu_user' => ['role' => $role]])
                ->get($uri)
                ->assertOk();
        }

        $this->withSession(['smartedu_user' => ['role' => 'staff-tu']])
            ->getJson('/staff-tu/mapel/data')
            ->assertOk()
            ->assertJsonStructure([['id', 'kode', 'nama', 'nama_file', 'path_file']]);
    }

    public function test_staff_tu_can_manage_mapel_through_ajax_with_uploaded_file(): void
    {
        Storage::fake('public');
        $this->withSession(['smartedu_user' => ['role' => 'staff-tu']]);

        $this->postJson('/staff-tu/mapel/ajax', [
            'kode_mapel' => 'mtk',
            'nama_mapel' => 'Matematika',
            'file' => UploadedFile::fake()->create('materi.pdf', 10, 'application/pdf'),
        ])
            ->assertOk()
            ->assertJsonPath('mapel.kode', 'MTK');

        $mapel = MapelUpload::firstOrFail();
        Storage::disk('public')->assertExists('uploads/mapel/'.$mapel->nama_file);

        $this->putJson('/staff-tu/mapel/'.$mapel->id.'/ajax', [
            'kode_mapel' => 'MTK2',
            'nama_mapel' => 'Matematika Lanjutan',
        ])
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->deleteJson('/staff-tu/mapel/'.$mapel->id.'/ajax')
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('mapel_uploads', ['id' => $mapel->id]);
    }
}
