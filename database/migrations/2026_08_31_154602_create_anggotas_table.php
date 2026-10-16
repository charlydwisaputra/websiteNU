<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Menggunakan nama tabel 'anggota' sesuai definisi di Model
        Schema::create('anggota', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('photo')->nullable();
            $table->string('position')->nullable(); // Jabatan
            $table->string('region')->nullable(); // Wilayah
            $table->string('status')->default('Aktif'); // Aktif, Nonaktif, Alumni, Pending
            $table->date('joined_at')->nullable();
            $table->text('bio')->nullable();
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggota');
    }
};
