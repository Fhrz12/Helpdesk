<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeAssigneddateNullableInTicketsTable extends Migration
{
    public function up()
    {
        // Mengubah kolom 'assigneddate' agar bisa menerima nilai NULL
        Schema::table('tickets', function (Blueprint $table) {
            $table->dateTime('assigneddate')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dateTime('assigneddate')->nullable(false)->change();
        });
    }
}
