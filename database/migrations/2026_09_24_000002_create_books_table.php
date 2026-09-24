<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('books', function (Blueprint $table) {
            $table->id(); $table->foreignId('kategori_id')->constrained('categories')->cascadeOnDelete();
            $table->string('judul'); $table->string('penulis'); $table->string('penerbit')->nullable(); $table->year('tahun_terbit')->nullable(); $table->string('isbn')->nullable()->unique();
            $table->text('deskripsi')->nullable(); $table->string('cover_image')->nullable(); $table->unsignedInteger('jumlah_halaman')->nullable(); $table->unsignedInteger('stok')->default(1); $table->decimal('rating', 3, 2)->default(0); $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('books'); }
};
