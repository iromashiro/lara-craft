<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $table = "user";

    public function getopd()
    {
        return $this->belongsTo(Opd::class, 'id_opd');
    }
}
