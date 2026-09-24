<?php
namespace App\Http\Controllers;
use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
class BookController extends Controller {
    public function home() { return view('home', ['featured'=>Book::with('category')->latest()->take(6)->get(), 'categories'=>Category::withCount('books')->get(), 'bookCount'=>Book::count(), 'memberCount'=>User::where('role','anggota')->count()]); }
    public function index(Request $request) { $books = Book::with('category')->when($request->q, fn($q,$v)=>$q->where(fn($q)=>$q->where('judul','like',"%$v%")->orWhere('penulis','like',"%$v%")))->when($request->kategori, fn($q,$v)=>$q->where('kategori_id',$v))->when($request->tersedia === '1', fn($q)=>$q->where('stok','>',0))->orderBy($request->sort === 'oldest' ? 'created_at' : 'judul', $request->sort === 'newest' ? 'desc' : 'asc')->paginate(12)->withQueryString(); return view('books.index', ['books'=>$books,'categories'=>Category::orderBy('nama_kategori')->get()]); }
    public function show(Book $book) { $book->load(['category','reviews.user']); return view('books.show', compact('book')); }
}
