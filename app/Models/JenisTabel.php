<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisTabel extends Model
{
    protected $casts = [
        'nama_kolom' => 'array'
    ];


    protected $table = 'jenis_tabel';
    public $timestamps = false;


    public function isiTabel()
    {
        return $this->hasMany("App\Model\IsiTabel", "id_jenis_tabel");
    }

    public function depan()
    {
        return $this->belongsTo(IsiTabel::class, 'id_jenis_tabel');
    }


    public function opd()
    {
        return $this->belongsTo(Opd::class, 'id_opd');
    }
}
