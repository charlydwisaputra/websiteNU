<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galeris', function (Blueprint $table) {
            // Kolom JSON untuk menyimpan array path foto (album)
            $table->json('photos')->nullable()->after('photo');
            $table->text('description')->nullable()->after('photos');
        });

        // Migrasi data lama: pindahkan kolom 'photo' ke dalam array 'photos'
        DB::table('galeris')->whereNotNull('photo')->chunkById(100, function ($rows) {
            foreach ($rows as $row) {
                DB::table('galeris')->where('id', $row->id)->update([
                    'photos' => json_encode([$row->photo]),
                ]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('galeris', function (Blueprint $table) {
            $table->dropColumn(['photos', 'description']);
        });
    }
};
