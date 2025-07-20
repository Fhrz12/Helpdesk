<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeSlaIdNullableInTicketsTable extends Migration
{
    public function up()
    {
        // Perintah untuk mengubah kolom sla_id agar bisa menerima nilai NULL
        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('sla_id')->nullable()->change();
        });
    }

    public function down()
    {
        // Perintah untuk mengembalikan jika diperlukan
        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('sla_id')->nullable(false)->change();
        });
    }
}
