<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opd extends Model
{
    protected $table = 'opd';
    public $timestamps = false;

    public function tabelOpd()
    {
        return $this->hasMany("App\Models\JenisTabel", "id_opd");
    }

    // public function getUser()
    // {
    //     return $this->hasOne(User::class, 'id_opd');
    // }
}
