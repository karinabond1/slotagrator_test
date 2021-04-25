<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObjectOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('object_operations', function (Blueprint $table) {
            $table->id();
            $table->integer('status');
            $table->unsignedBigInteger('operation_id')->nullable(false);
            $table->foreign('operation_id')->references('id')->on('operations');
            $table->unsignedBigInteger('object_id')->nullable(false);
            $table->foreign('object_id')->references('id')->on('objects_things');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('object_operations');
    }
}
