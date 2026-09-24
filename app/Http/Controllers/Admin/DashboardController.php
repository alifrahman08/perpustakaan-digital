<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\User;
class DashboardController extends Controller {
	public function __invoke() {
		$stats = [
			'books' => Book::count(),
			'members' => User::where('role', 'anggota')->count(),
			'borrowed' => Borrowing::whereIn('status', ['dipinjam', 'terlambat'])->count(),
			'returned' => Borrowing::where('status', 'dikembalikan')->count(),
			'late' => Borrowing::where('status', 'terlambat')->count(),
			'categories' => Category::count(),
		];
		$recentBorrowings = Borrowing::with(['user', 'book'])->latest()->take(6)->get();
		$popularBooks = Book::with('category')->withCount('borrowings')->orderByDesc('borrowings_count')->latest()->take(5)->get();
		$dailyStats = collect(range(6, 0))->map(function (int $daysAgo) {
			$date = now()->subDays($daysAgo);

			return [
				'label' => $date->format('D'),
				'borrowings' => Borrowing::whereDate('created_at', $date)->count(),
				'returns' => Borrowing::whereDate('tanggal_kembali', $date)->count(),
			];
		});

		return view('admin.dashboard', compact('stats', 'recentBorrowings', 'popularBooks', 'dailyStats'));
	}
}
