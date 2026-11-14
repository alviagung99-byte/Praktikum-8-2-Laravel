<?php

namespace App\Modules\SmartEdu\Shared\Models;

use Illuminate\Database\Eloquent\Model;

class MapelUpload extends Model
{
    protected $table = 'mapel_uploads';

    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
        'nama_file',
        'nama_asli',
        'path_file',
        'mime_type',
        'ukuran',
    ];
}
