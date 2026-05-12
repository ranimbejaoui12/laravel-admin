<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();

            // relation with users
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('name');
            $table->string('lastname');
            $table->string('username')->unique();
            $table->integer('noSSocial');
            $table->date('dob');
            $table->string('phone');
            $table->string('email');

            $table->string('diseases')->nullable();
            $table->string('allergies')->nullable();
            $table->string('antecedents')->nullable();
            $table->text('comments')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('patients');
    }
};