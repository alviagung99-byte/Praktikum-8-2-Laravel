<?php

namespace App\Modules\SmartEdu\Guru;

use App\Http\Controllers\Controller;
use App\Modules\SmartEdu\Guru\Services\GuruService;

class GuruController extends Controller
{
    public function __construct(protected GuruService $service) {}

    public function guruDashboard()
    {
        return $this->service->guruDashboard();
    }
}
