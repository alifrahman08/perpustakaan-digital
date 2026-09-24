<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $names = ['Fiksi', 'Non-Fiksi', 'Sains', 'Sejarah', 'Teknologi', 'Anak-anak'];
        foreach ($names as $name) Category::create(['nama_kategori' => $name, 'slug' => Str::slug($name), 'deskripsi' => 'Koleksi '.$name.' pilihan perpustakaan.']);
        $categories = Category::pluck('id')->all();
        foreach (range(1, 15) as $i) Book::create(['kategori_id' => $categories[($i - 1) % count($categories)], 'judul' => ['Laut Bercerita', 'Filosofi Teras', 'Atlas Dunia Modern', 'Jejak Nusantara', 'Belajar Laravel 11', 'Ensiklopedia Sains'][($i - 1) % 6].' '.$i, 'penulis' => ['Leila S. Chudori', 'Henry Manampiring', 'Tim Literasi'][($i - 1) % 3], 'penerbit' => 'Pustaka Digital', 'tahun_terbit' => 2020 + ($i % 5), 'isbn' => '978602'.str_pad($i, 7, '0', STR_PAD_LEFT), 'deskripsi' => 'Buku pilihan dengan pembahasan yang relevan, ditulis secara ringan dan mendalam untuk memperluas wawasan pembaca.', 'cover_image' => 'https://picsum.photos/seed/perpustakaan'.$i.'/500/700', 'jumlah_halaman' => 180 + $i * 12, 'stok' => 2 + ($i % 4), 'rating' => 4]);
        User::create(['name' => 'Administrator', 'email' => 'admin@perpustakaan.com', 'password' => Hash::make('admin123'), 'role' => 'admin']);
        foreach (range(1, 3) as $i) { $member = User::create(['name' => 'Anggota '.$i, 'email' => 'anggota'.$i.'@example.com', 'password' => Hash::make('anggota123'), 'role' => 'anggota']); $book = Book::find($i); Borrowing::create(['user_id' => $member->id, 'book_id' => $book->id, 'tanggal_pinjam' => now()->subDays(3), 'tanggal_jatuh_tempo' => now()->addDays(11), 'status' => 'dipinjam']); $book->decrement('stok'); }
    }
}
