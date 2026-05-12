<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attestations', function (Blueprint $table) {

            // نحذف description فقط إذا موجودة
            if (Schema::hasColumn('attestations', 'description')) {
                $table->dropColumn('description');
            }

        });
    }

    public function down(): void
    {
        Schema::table('attestations', function (Blueprint $table) {

            // نرجعها إذا موش موجودة
            if (!Schema::hasColumn('attestations', 'description')) {
                $table->text('description')->nullable();
            }

        });
    }
};