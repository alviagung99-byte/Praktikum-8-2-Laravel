<?php

namespace App\Modules\SmartEdu\StaffTu;

use App\Http\Controllers\Controller;
use App\Modules\SmartEdu\StaffTu\Services\StaffTuService;
use Illuminate\Http\Request;

class StaffTuController extends Controller
{
    public function __construct(protected StaffTuService $service) {}

    public function staffDashboard()
    {
        return $this->service->staffDashboard();
    }

    public function pengguna()
    {
        return $this->service->pengguna();
    }

    public function createPengguna()
    {
        return $this->service->createPengguna();
    }

    public function storePengguna(Request $request)
    {
        return $this->service->storePengguna($request);
    }

    public function editPengguna(string $id)
    {
        return $this->service->editPengguna($id);
    }

    public function updatePengguna(Request $request, string $id)
    {
        return $this->service->updatePengguna($request, $id);
    }

    public function destroyPengguna(string $id)
    {
        return $this->service->destroyPengguna($id);
    }

    public function kelas()
    {
        return $this->service->kelas();
    }

    public function mapel()
    {
        return $this->service->mapel();
    }

    public function mapelData()
    {
        return response()->json($this->service->mapelData());
    }

    public function uploadMapelFile(Request $request)
    {
        return $this->service->uploadMapelFile($request);
    }

    public function ajaxStoreMapel(Request $request)
    {
        return $this->service->ajaxStoreMapel($request);
    }

    public function ajaxUpdateMapel(Request $request, int $id)
    {
        return $this->service->ajaxUpdateMapel($request, $id);
    }

    public function ajaxDeleteMapel(int $id)
    {
        return $this->service->ajaxDeleteMapel($id);
    }
}
