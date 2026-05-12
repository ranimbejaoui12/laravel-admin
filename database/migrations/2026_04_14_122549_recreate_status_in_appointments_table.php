<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {

            // إضافة status فقط إذا موش موجودة
            if (!Schema::hasColumn('appointments', 'status')) {

                $table->enum('status', [
                    'pending',
                    'confirmed',
                    'cancelled',
                    'completed'
                ])->default('pending');

            }

        });
    }

    public function down(): void
    {
        // لا شيء
    }
};