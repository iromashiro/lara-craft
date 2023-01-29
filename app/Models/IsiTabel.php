<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IsiTabel extends Model
{
    protected $casts = [
        'data' => 'array'
    ];
    protected $table = 'isi_tabel';
    public $timestamps = false;
}
