<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. حاول حذف FK بطريقة آمنة
        Schema::table('appointments', function (Blueprint $table) {
            try {
                DB::statement('ALTER TABLE appointments DROP FOREIGN KEY appointments_hospital_id_foreign');
            } catch (\Throwable $e) {
                // FK doesn't exist → ignore
            }
        });

        // 2. حذف العمود إذا موجود
        Schema::table('appointments', function (Blueprint $table) {
            if (Schema::hasColumn('appointments', 'hospital_id')) {
                $table->dropColumn('hospital_id');
            }
        });

        // 3. إعادة إضافة العمود nullable
        Schema::table('appointments', function (Blueprint $table) {
            if (!Schema::hasColumn('appointments', 'hospital_id')) {
                $table->unsignedBigInteger('hospital_id')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (Schema::hasColumn('appointments', 'hospital_id')) {
                $table->dropColumn('hospital_id');
            }
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->unsignedBigInteger('hospital_id')->nullable(false);
        });
    }
};