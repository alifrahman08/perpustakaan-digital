<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Borrowing extends Model { use HasFactory; protected $fillable = ['user_id','book_id','tanggal_pinjam','tanggal_jatuh_tempo','tanggal_kembali','status']; protected $casts = ['tanggal_pinjam'=>'date','tanggal_jatuh_tempo'=>'date','tanggal_kembali'=>'date']; public function user() { return $this->belongsTo(User::class); } public function book() { return $this->belongsTo(Book::class); } }
