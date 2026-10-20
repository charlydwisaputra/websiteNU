<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->string('kartu_token', 64)->nullable()->unique()->after('status');
        });

        // Backfill token unik untuk seluruh anggota lama
        $members = DB::table('anggota')->whereNull('kartu_token')->get();
        foreach ($members as $member) {
            DB::table('anggota')
                ->where('id', $member->id)
                ->update(['kartu_token' => Str::random(32)]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->dropColumn('kartu_token');
        });
    }
};
