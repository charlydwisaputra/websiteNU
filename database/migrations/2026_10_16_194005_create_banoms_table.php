<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banoms', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->string('singkatan');
            $table->text('deskripsi')->nullable();
            $table->string('logo')->nullable();
            $table->foreignId('koordinator_id')->nullable()->constrained('anggota')->nullOnDelete();
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banoms');
    }
};
