<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarSurveiModel extends Model
{
    use HasFactory;

    protected $table = 'list_kegiatan_survei';

    protected $fillable = [
        'daftar_kegiatan_survei'
    ];
}
