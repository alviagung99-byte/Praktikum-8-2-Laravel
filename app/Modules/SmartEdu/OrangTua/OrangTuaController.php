<?php

namespace App\Modules\SmartEdu\OrangTua;

use App\Http\Controllers\Controller;
use App\Modules\SmartEdu\OrangTua\Services\OrangTuaService;

class OrangTuaController extends Controller
{
    public function __construct(protected OrangTuaService $service) {}

    public function orangTuaDashboard()
    {
        return $this->service->orangTuaDashboard();
    }
}
