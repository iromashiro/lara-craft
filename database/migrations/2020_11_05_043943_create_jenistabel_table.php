<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJenistabelTable extends Migration
{

    public function up()
    {
        Schema::create("jenis_tabel", function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("nama_tabel")
                ->nullable(false);
            $table->json("nama_kolom")
                ->nullable(false);

            $table->unsignedBigInteger("id_opd");

            $table->foreign("id_opd")
                ->references("id_opd")
                ->on("opd")
                ->onDelete("cascade");
        });
    }


    public function down()
    {
        Schema::dropIfExists("jenis_tabel");
    }
}
