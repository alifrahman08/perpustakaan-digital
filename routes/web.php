<?php

use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BookController::class, 'home'])->name('home');
Route::get('/katalog', [BookController::class, 'index'])->name('books.index');
Route::get('/katalog/{book}', [BookController::class, 'show'])->name('books.show');
Route::view('/tentang-kami', 'pages.about')->name('about');
Route::view('/kontak', 'pages.contact')->name('contact');

Route::middleware(['auth', 'member'])->group(function () {
    Route::get('/dashboard', [BorrowingController::class, 'dashboard'])->name('dashboard');
    Route::post('/katalog/{book}/pinjam', [BorrowingController::class, 'store'])->name('borrowings.store');
    Route::post('/katalog/{book}/ulasan', [BorrowingController::class, 'review'])->name('reviews.store');
    Route::get('/peminjaman', [BorrowingController::class, 'index'])->name('borrowings.index');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::resource('books', AdminBookController::class)->except('show');
    Route::resource('categories', AdminCategoryController::class)->except('show');
    Route::get('/peminjaman', [BorrowingController::class, 'adminIndex'])->name('borrowings.index');
    Route::patch('/peminjaman/{borrowing}/kembalikan', [BorrowingController::class, 'returnBook'])->name('borrowings.return');
});

require __DIR__.'/auth.php';
