<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('staycation_id');
            $table->decimal('total_amount', $precision = 8, $scale = 2)->nullable();
            $table->dateTime('start');
            $table->dateTime('end');
            $table->unsignedInteger('pax');
            $table->text('remarks')->nullable();
            $table->string('occasion')->nullable();
            $table->boolean('isAllDay');
            $table->string('backgroundColor')->nullable();
            $table->string('status');
            $table->uuid('booked_by');
            $table->uuid('cancelled_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('booked_by')->references('id')->on('users');
            $table->foreign('cancelled_by')->references('id')->on('users');
            $table->foreign('staycation_id')->references('id')->on('staycations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
