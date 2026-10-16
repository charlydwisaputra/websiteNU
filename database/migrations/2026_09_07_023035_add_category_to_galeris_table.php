<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('galeris', function (Blueprint $table) {
            // Menambahkan kolom kategori baru
            if (! Schema::hasColumn('galeris', 'category')) {
                $table->string('category')->nullable()->after('title');
            }

            // Menyesuaikan nama kolom foto dari 'image' menjadi 'photo' jika belum ada
            if (Schema::hasColumn('galeris', 'image') && ! Schema::hasColumn('galeris', 'photo')) {
                $table->renameColumn('image', 'photo');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galeris', function (Blueprint $table) {
            $table->dropColumn('category');
            if (Schema::hasColumn('galeris', 'photo') && ! Schema::hasColumn('galeris', 'image')) {
                $table->renameColumn('photo', 'image');
            }
        });
    }
};
