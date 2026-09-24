@extends('layouts.app')

@section('content')
	<div class="admin-dashboard">
		<div class="admin-frame">
			<aside class="dashboard-sidebar admin-sidebar" aria-label="Navigasi admin">
				<p class="sidebar-label">PUSTAKA DIGITAL</p>
				<nav>
					<a class="active" href="{{ route('admin.dashboard') }}"><span>▦</span>Dashboard</a>
					<a href="{{ route('admin.books.index') }}"><span>▤</span>Manajemen buku</a>
					<a href="{{ route('admin.categories.index') }}"><span>◇</span>Kategori</a>
					<a href="{{ route('admin.borrowings.index') }}"><span>◫</span>Peminjaman</a>
					<a href="{{ route('admin.borrowings.index') }}"><span>◷</span>Pengembalian</a>
				</nav>
				<div class="sidebar-note"><span>ADMIN</span><p>kelola ruang<br>baca bersama</p></div>
			</aside>

			<main class="admin-main">
				<section class="admin-welcome">
					<div><p class="eyebrow">PANEL ADMIN</p><h1>Selamat datang, Admin.</h1><p>Kelola aktivitas perpustakaan dengan mudah dari satu tempat.</p></div>
					<time datetime="{{ now()->toDateString() }}">{{ now()->format('l, d F Y') }}</time>
				</section>

				<section class="admin-stats" aria-label="Statistik perpustakaan">
					<article class="admin-stat-card"><span class="admin-stat-icon">▤</span><small>Total buku</small><strong>{{ $stats['books'] }}</strong><em>Koleksi tersedia</em></article>
					<article class="admin-stat-card"><span class="admin-stat-icon teal">♙</span><small>Total anggota</small><strong>{{ $stats['members'] }}</strong><em>Anggota aktif</em></article>
					<article class="admin-stat-card"><span class="admin-stat-icon gold">◫</span><small>Peminjaman aktif</small><strong>{{ $stats['borrowed'] }}</strong><em>Sedang berjalan</em></article>
					<article class="admin-stat-card"><span class="admin-stat-icon green">✓</span><small>Dikembalikan</small><strong>{{ $stats['returned'] }}</strong><em>Selesai dipinjam</em></article>
					<article class="admin-stat-card"><span class="admin-stat-icon coral">!</span><small>Terlambat</small><strong>{{ $stats['late'] }}</strong><em>Perlu perhatian</em></article>
					<article class="admin-stat-card"><span class="admin-stat-icon muted">◇</span><small>Total kategori</small><strong>{{ $stats['categories'] }}</strong><em>Kelompok koleksi</em></article>
				</section>

				<section class="admin-actions" aria-label="Akses cepat">
					<div><p class="eyebrow">AKSES CEPAT</p><h2>Kelola perpustakaan</h2></div>
					<div class="admin-action-links"><a href="{{ route('admin.books.create') }}"><span>＋</span>Tambah buku</a><a href="{{ route('admin.categories.create') }}"><span>＋</span>Tambah kategori</a><a href="{{ route('admin.borrowings.index') }}"><span>◫</span>Kelola peminjaman</a></div>
				</section>

				<div class="admin-content-grid">
					<section class="admin-panel recent-panel" aria-labelledby="recent-title">
						<div class="admin-panel-head"><div><p class="eyebrow">SIRKULASI</p><h2 id="recent-title">Peminjaman terbaru</h2></div><a href="{{ route('admin.borrowings.index') }}">Lihat semua <span aria-hidden="true">-></span></a></div>
						<div class="admin-table-wrap"><table class="admin-table"><thead><tr><th>Anggota</th><th>Buku</th><th>Status</th><th></th></tr></thead><tbody>@forelse($recentBorrowings as $borrowing)<tr><td><strong>{{ $borrowing->user->name }}</strong><small>{{ $borrowing->tanggal_pinjam->format('d M Y') }}</small></td><td>{{ $borrowing->book->judul }}</td><td><span class="status-pill {{ $borrowing->status }}">{{ ucfirst($borrowing->status) }}</span></td><td><a class="table-action" href="{{ route('admin.borrowings.index') }}">Lihat</a></td></tr>@empty<tr><td colspan="4"><div class="admin-empty">Belum ada peminjaman terbaru.</div></td></tr>@endforelse</tbody></table></div>
					</section>

					<section class="admin-panel chart-panel" aria-labelledby="chart-title">
						<div class="admin-panel-head"><div><p class="eyebrow">AKTIVITAS 7 HARI</p><h2 id="chart-title">Statistik peminjaman</h2></div></div>
						@php($chartMax = max(1, $dailyStats->max('borrowings'), $dailyStats->max('returns')))
						<div class="mini-chart" aria-label="Grafik peminjaman dan pengembalian tujuh hari terakhir">@foreach($dailyStats as $day)<div class="chart-column"><div class="chart-bars"><i style="height: {{ ($day['borrowings'] / $chartMax) * 100 }}%"></i><b style="height: {{ ($day['returns'] / $chartMax) * 100 }}%"></b></div><small>{{ $day['label'] }}</small></div>@endforeach</div>
						<div class="chart-legend"><span><i></i>Peminjaman</span><span><i></i>Pengembalian</span></div>
					</section>
				</div>

				<div class="admin-content-grid lower-grid">
					<section class="admin-panel popular-panel" aria-labelledby="popular-title"><div class="admin-panel-head"><div><p class="eyebrow">KOLEKSI PILIHAN</p><h2 id="popular-title">Buku terpopuler</h2></div><a href="{{ route('admin.books.index') }}">Kelola buku <span aria-hidden="true">-></span></a></div><div class="popular-list">@forelse($popularBooks as $book)<a href="{{ route('admin.books.edit', $book) }}"><img src="{{ $book->cover_url }}" alt="Sampul {{ $book->judul }}"><span><strong>{{ $book->judul }}</strong><small>{{ $book->penulis }}</small></span><em>{{ $book->borrowings_count }} pinjam</em></a>@empty<div class="admin-empty">Belum ada koleksi buku.</div>@endforelse</div></section>
					<section class="admin-panel alert-panel" aria-labelledby="alert-title"><p class="eyebrow">PERLU PERHATIAN</p><h2 id="alert-title">Peminjaman terlambat</h2>@if($stats['late'] > 0)<div class="alert-number">{{ $stats['late'] }}</div><p>peminjaman perlu ditindaklanjuti dan dikonfirmasi pengembaliannya.</p><a class="button small" href="{{ route('admin.borrowings.index') }}">Periksa sekarang</a>@else<div class="alert-clear">✓</div><p>Semua peminjaman dalam kondisi baik.</p><a href="{{ route('admin.borrowings.index') }}">Buka sirkulasi <span aria-hidden="true">-></span></a>@endif</section>
				</div>
			</main>
		</div>
	</div>
@endsection
