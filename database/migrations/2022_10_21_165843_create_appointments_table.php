<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            $table->text('motivation');
            $table->date('date');

            $table->time('start_time');
            $table->time('end_time');

            // doctor (user role)
            $table->foreignId('doctor_id')
                ->constrained('users')
                ->onDelete('cascade');

            // patient (user role)
            $table->foreignId('patient_id')
                ->constrained('users')
                ->onDelete('cascade');

            // created by (admin  / system)
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->string('status')->default('pending');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
};