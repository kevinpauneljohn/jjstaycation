<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staycation_id');
            $table->string('name');
            $table->text('remarks')->nullable();
            $table->smallInteger('pax');
            $table->float('amount',8,2);
            $table->uuid('created_by');
            $table->integer('days');
            $table->string('time_in');
            $table->string('time_out');
            $table->string('color')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('staycation_id')->references('id')->on('staycations');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packages');
    }
}
