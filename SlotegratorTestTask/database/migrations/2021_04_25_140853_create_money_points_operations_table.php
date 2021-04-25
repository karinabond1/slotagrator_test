<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoneyPointsOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('money_points_operations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('money_operation_id')->nullable(false);
            $table->foreign('money_operation_id')->references('id')->on('money_operations');
            $table->unsignedBigInteger('point_operation_id')->nullable(false);
            $table->foreign('point_operation_id')->references('id')->on('point_operations');
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
        Schema::dropIfExists('money_points_operations');
    }
}
