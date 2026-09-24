@extends('layouts.app')

@section('content')
	<div class="dashboard-page">
		<div class="dashboard-frame">
			<aside class="dashboard-sidebar" aria-label="Navigasi anggota">
				<p class="sidebar-label">PUSTAKA DIGITAL</p>
				<nav>
					<a class="active" href="{{ route('dashboard') }}"><span>▦</span>Dashboard</a>
					<a href="{{ route('books.index') }}"><span>▤</span>Katalog Buku</a>
					<a href="{{ route('borrowings.index') }}"><span>◫</span>Peminjaman</a>
					<a href="{{ route('borrowings.index') }}"><span>◷</span>Riwayat</a>
					<a href="{{ route('about') }}"><span>◎</span>Tentang</a>
				</nav>
				<div class="sidebar-note"><span>14</span><p>hari masa<br>peminjaman</p></div>
			</aside>
			<div class="dashboard-main">
		<section class="dashboard-welcome">
			<div>
				<p class="eyebrow">RUANG ANGGOTA</p>
				<h1>Selamat datang, {{ auth()->user()->name }} <span aria-hidden="true">👋</span></h1>
				<p>Temukan buku yang menarik dan lanjutkan perjalanan membacamu.</p>
			</div>
			<div class="welcome-mark" aria-hidden="true"><span>PD</span><b>Ruang baca<br>untuk ide baru.</b></div>
		</section>

		<section class="dashboard-search" aria-label="Cari buku">
			<form method="GET" action="{{ route('books.index') }}">
				<span aria-hidden="true">⌕</span>
				<input type="search" name="q" placeholder="Cari buku, penulis, atau kategori...">
				<button class="button small" type="submit">Cari</button>
			</form>
		</section>

		<section class="dashboard-section" aria-label="Ringkasan peminjaman">
			<div class="dashboard-stats">
				<article class="dashboard-stat"><span class="stat-icon">▣</span><div><strong>{{ $stats['total'] }}</strong><span>Total dipinjam</span></div></article>
				<article class="dashboard-stat"><span class="stat-icon teal">◫</span><div><strong>{{ $stats['active'] }}</strong><span>Sedang dipinjam</span></div></article>
				<article class="dashboard-stat"><span class="stat-icon green">✓</span><div><strong>{{ $stats['completed'] }}</strong><span>Buku selesai</span></div></article>
				<article class="dashboard-stat"><span class="stat-icon muted">☆</span><div><strong>—</strong><span>Favorit belum tersedia</span></div></article>
			</div>
		</section>

		<section class="dashboard-section" aria-labelledby="active-title">
			<div class="dashboard-section-head"><div><p class="eyebrow">LANJUTKAN MEMBACA</p><h2 id="active-title">Sedang dipinjam</h2></div><a href="{{ route('borrowings.index') }}">Lihat riwayat <span aria-hidden="true">-></span></a></div>
			<div class="active-loans">
				@forelse($current as $loan)
					<article class="active-loan">
						<img src="{{ $loan->book->cover_url }}" alt="Sampul {{ $loan->book->judul }}">
						<div class="active-loan-body"><span class="loan-label">{{ $loan->status === 'terlambat' ? 'Perlu dikembalikan' : 'Sedang dibaca' }}</span><h3>{{ $loan->book->judul }}</h3><p>{{ $loan->book->penulis }}</p><div class="loan-dates"><span>Dipinjam <b>{{ $loan->tanggal_pinjam->format('d M Y') }}</b></span><span>Batas kembali <b>{{ $loan->tanggal_jatuh_tempo->format('d M Y') }}</b></span></div><div class="loan-progress"><span style="width: {{ $loan->status === 'terlambat' ? '100' : '65' }}%"></span></div></div>
						<a class="button small" href="{{ route('books.show', $loan->book) }}">Lihat detail</a>
					</article>
				@empty
					<div class="dashboard-empty"><strong>Belum ada buku yang sedang dipinjam.</strong><span>Yuk, temukan buku pertamamu.</span><a class="button small" href="{{ route('books.index') }}">Jelajahi katalog</a></div>
				@endforelse
			</div>
		</section>

		<section class="dashboard-section" aria-labelledby="recommendation-title">
			<div class="dashboard-section-head"><div><p class="eyebrow">PILIHAN UNTUKMU</p><h2 id="recommendation-title">Rekomendasi buku</h2><p>Buku yang mungkin menarik untuk kamu.</p></div><a href="{{ route('books.index') }}">Buka katalog <span aria-hidden="true">-></span></a></div>
			<div class="dashboard-books">
				@foreach($recommendations as $book)
					<a class="dashboard-book" href="{{ route('books.show', $book) }}"><img src="{{ $book->cover_url }}" alt="Sampul {{ $book->judul }}"><span>{{ $book->category->nama_kategori }}</span><h3>{{ $book->judul }}</h3><p>{{ $book->penulis }}</p></a>
				@endforeach
			</div>
		</section>

		<section class="dashboard-section dashboard-categories" aria-labelledby="category-title">
			<div class="dashboard-section-head"><div><p class="eyebrow">JELAJAHI KOLEKSI</p><h2 id="category-title">Mulai dari minatmu</h2></div></div>
			<div class="dashboard-category-grid">@foreach($categories as $category)<a href="{{ route('books.index', ['kategori' => $category->id]) }}"><b>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</b><span>{{ $category->nama_kategori }}</span><small>{{ $category->books_count }} buku</small></a>@endforeach</div>
		</section>

		<section class="dashboard-section history-section" aria-labelledby="history-title">
			<div class="dashboard-section-head"><div><p class="eyebrow">AKTIVITAS</p><h2 id="history-title">Riwayat peminjaman terbaru</h2></div><a href="{{ route('borrowings.index') }}">Lihat semua <span aria-hidden="true">-></span></a></div>
			<div class="history-table"><div class="history-row history-heading"><span>Buku</span><span>Tanggal pinjam</span><span>Tanggal kembali</span><span>Status</span></div>@forelse($history as $loan)<div class="history-row"><span class="history-book"><img src="{{ $loan->book->cover_url }}" alt=""><b>{{ $loan->book->judul }}</b></span><span>{{ $loan->tanggal_pinjam->format('d M Y') }}</span><span>{{ $loan->tanggal_kembali?->format('d M Y') ?? '—' }}</span><span class="status-pill {{ $loan->status }}">{{ ucfirst($loan->status) }}</span></div>@empty<div class="dashboard-empty compact"><strong>Belum ada riwayat peminjaman.</strong><span>Aktivitas peminjamanmu akan muncul di sini.</span></div>@endforelse</div>
		</section>

		<section class="library-note"><div><p class="eyebrow">INFORMASI PUSTAKA</p><h2>Ruang untuk membaca lebih jauh.</h2><p>Temukan koleksi digital yang mendukung kegiatan belajar dan membaca setiap hari.</p></div><a class="button small" href="{{ route('about') }}">Tentang pustaka</a></section>
			</div>
		</div>
	</div>
@endsection
