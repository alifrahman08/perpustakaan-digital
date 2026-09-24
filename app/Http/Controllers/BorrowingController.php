<?php
namespace App\Http\Controllers;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class BorrowingController extends Controller {
    public function dashboard(Request $request) {
        $user = $request->user();
        $current = $user->borrowings()->with('book')->whereIn('status', ['dipinjam', 'terlambat'])->latest()->get();
        $history = $user->borrowings()->with('book')->latest()->take(5)->get();
        $recommendations = Book::with('category')->latest()->take(6)->get();
        $categories = \App\Models\Category::withCount('books')->orderBy('nama_kategori')->get();
        $stats = [
            'total' => $user->borrowings()->count(),
            'active' => $current->count(),
            'completed' => $user->borrowings()->where('status', 'dikembalikan')->count(),
        ];

        return view('dashboard', compact('current', 'history', 'recommendations', 'categories', 'stats'));
    }
    public function index(Request $request) { $borrowings=$request->user()->borrowings()->with('book')->latest()->paginate(10); return view('borrowings.index', compact('borrowings')); }
    public function store(Request $request, Book $book) { DB::transaction(function() use ($request,$book) { $locked=Book::whereKey($book->id)->lockForUpdate()->first(); abort_if(!$locked || $locked->stok < 1, 422, 'Stok buku sedang habis.'); abort_if($request->user()->borrowings()->where('book_id',$book->id)->whereIn('status',['dipinjam','terlambat'])->exists(), 422, 'Anda masih meminjam buku ini.'); $locked->decrement('stok'); $request->user()->borrowings()->create(['book_id'=>$locked->id,'tanggal_pinjam'=>now(),'tanggal_jatuh_tempo'=>now()->addDays(14),'status'=>'dipinjam']); }); return back()->with('success','Buku berhasil dipinjam selama 14 hari.'); }
    public function review(Request $request, Book $book) { $data=$request->validate(['rating'=>'required|integer|min:1|max:5','komentar'=>'nullable|string|max:1000']); abort_unless($request->user()->borrowings()->where('book_id',$book->id)->whereIn('status',['dikembalikan','dipinjam','terlambat'])->exists(), 403); Review::updateOrCreate(['user_id'=>$request->user()->id,'book_id'=>$book->id],$data); $book->update(['rating'=>round($book->reviews()->avg('rating'),2)]); return back()->with('success','Ulasan berhasil disimpan.'); }
    public function adminIndex() {
        $borrowings = Borrowing::query()
            ->with(['user:id,name', 'book:id,judul'])
            ->latest()
            ->paginate(15);

        return view('admin.borrowings', compact('borrowings'));
    }
    public function returnBook(Borrowing $borrowing) { abort_if($borrowing->status === 'dikembalikan', 422); DB::transaction(function () use ($borrowing) { $borrowing->update(['status'=>'dikembalikan','tanggal_kembali'=>now()]); $borrowing->book()->increment('stok'); }); return back()->with('success','Pengembalian dikonfirmasi.'); }
}
