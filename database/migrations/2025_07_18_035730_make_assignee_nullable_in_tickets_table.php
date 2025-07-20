<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeAssigneeNullableInTicketsTable extends Migration
{
    public function up()
    {
        // Mengubah kolom 'assignee' agar bisa menerima nilai NULL
        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('assignee')->nullable()->change();
        });
    }

    public function down()
    {
        // Mengembalikan kolom 'assignee' menjadi tidak nullable
        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('assignee')->nullable(false)->change();
        });
    }
}
