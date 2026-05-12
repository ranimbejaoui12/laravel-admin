<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('attestations', function (Blueprint $table) {

        // نحيو القديم
        $table->dropForeign(['doctor_id']);

        // نربط بـ users
        $table->foreign('doctor_id')
              ->references('id')
              ->on('users')
              ->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
