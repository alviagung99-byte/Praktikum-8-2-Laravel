<?php

namespace App\Modules\SmartEdu;

use Illuminate\Support\ServiceProvider;

class SmartEduModuleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $modules = [
            'auth' => 'Auth',
            'staff-tu' => 'StaffTu',
            'guru' => 'Guru',
            'siswa' => 'Siswa',
            'orang-tua' => 'OrangTua',
        ];

        foreach ($modules as $namespace => $directory) {
            $this->loadViewsFrom(__DIR__.'/'.$directory.'/Views', 'smartedu-'.$namespace);
        }
    }
}
