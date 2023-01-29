<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIsiTabelTable extends Migration
{
    
    public function up()
    {
        Schema::create('isi_tabel', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->json("data")->nullable(false);
            
            $table->unsignedBigInteger("id_jenis_tabel");
            $table
                ->foreign("id_jenis_tabel")
                ->references("id")
                ->on("jenis_tabel")
                ->onDelete("cascade");
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('isi_tabel');
    }
}
