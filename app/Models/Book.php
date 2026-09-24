<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Book extends Model { use HasFactory; protected $fillable = ['kategori_id','judul','penulis','penerbit','tahun_terbit','isbn','deskripsi','cover_image','jumlah_halaman','stok','rating']; protected $casts = ['tahun_terbit'=>'integer','rating'=>'decimal:2']; public function category() { return $this->belongsTo(Category::class, 'kategori_id'); } public function borrowings() { return $this->hasMany(Borrowing::class); } public function reviews() { return $this->hasMany(Review::class); } public function getCoverUrlAttribute() { return $this->cover_image ?: 'https://picsum.photos/seed/book-'.$this->id.'/500/700'; } }
