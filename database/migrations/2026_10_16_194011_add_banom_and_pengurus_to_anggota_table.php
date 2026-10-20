<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->foreignId('banom_id')->nullable()->after('position')->constrained('banoms')->nullOnDelete();
            $table->integer('urutan')->default(0)->after('status');
            $table->boolean('is_pengurus')->default(false)->after('urutan');
        });
    }

    public function down(): void
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->dropForeign(['banom_id']);
            $table->dropColumn(['banom_id', 'urutan', 'is_pengurus']);
        });
    }
};
