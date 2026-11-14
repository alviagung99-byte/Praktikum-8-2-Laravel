<?php

namespace App\Modules\SmartEdu\Siswa;

use App\Http\Controllers\Controller;
use App\Modules\SmartEdu\Siswa\Services\SiswaService;

class SiswaController extends Controller
{
    public function __construct(protected SiswaService $service) {}

    public function siswaDashboard()
    {
        return $this->service->siswaDashboard();
    }

    public function materi()
    {
        return $this->service->materi();
    }

    public function detailMateri($kodeMateri)
    {
        return $this->service->detailMateri($kodeMateri);
    }

    public function latihan()
    {
        return $this->service->latihan();
    }

    public function detailLatihan($kodeMateri)
    {
        return $this->service->detailLatihan($kodeMateri);
    }
}
