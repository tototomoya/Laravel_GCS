<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('password');
            $table->string('updated_date');
            $table->integer('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('files');
    }
}
