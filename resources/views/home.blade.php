@extends('layouts.app')

@section('content')
	<section class="hero" aria-labelledby="home-title">
		<div>
			<p class="eyebrow">PERPUSTAKAAN DIGITAL</p>
			<h1 id="home-title">Temukan cerita yang<br><em>menetap</em> di pikiran.</h1>
			<p class="lead">Katalog terkurasi, peminjaman sederhana, dan ruang belajar yang selalu terbuka.</p>
			<div class="inline">
				<a class="button" href="{{ route('books.index') }}">Jelajahi katalog <span aria-hidden="true">-></span></a>
				<a class="text-link" href="#categories">Lihat kategori</a>
			</div>
		</div>
		<div class="hero-art" aria-hidden="true">
			<div class="book-stack">
				<div class="book b1">BACA<br>LEBIH</div>
				<div class="book b2">IDE<br>BARU</div>
				<div class="book b3">CERITA<br>HARI INI</div>
			</div>
		</div>
	</section>

	<section class="stats" aria-label="Ringkasan perpustakaan">
		<div><b>{{ $bookCount }}</b><span>koleksi buku</span></div>
		<div><b>{{ $memberCount }}</b><span>anggota aktif</span></div>
		<div><b>14</b><span>hari masa pinjam</span></div>
	</section>

	<section class="section" aria-labelledby="featured-title">
		<div class="section-head">
			<div><p class="eyebrow">PILIHAN HARI INI</p><h2 id="featured-title">Buku yang layak dibaca</h2></div>
			<a href="{{ route('books.index') }}">Lihat semua <span aria-hidden="true">-></span></a>
		</div>
		<div class="book-grid">
			@foreach($featured as $book)
				<a class="book-card" href="{{ route('books.show',$book) }}">
					<img src="{{ $book->cover_url }}" alt="Sampul {{ $book->judul }}">
					<div><small>{{ $book->category->nama_kategori }}</small><h3>{{ $book->judul }}</h3><p>{{ $book->penulis }}</p><span class="rating">★ {{ number_format($book->rating,1) }}</span></div>
				</a>
			@endforeach
		</div>
	</section>

	<section class="section categories" id="categories" aria-labelledby="categories-title">
		<p class="eyebrow">JELAJAHI BERDASARKAN MINAT</p>
		<h2 id="categories-title">Mulai dari sini</h2>
		<div class="category-grid">
			@foreach($categories as $category)
				<a href="{{ route('books.index',['kategori'=>$category->id]) }}"><b>{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</b><span>{{ $category->nama_kategori }}</span><small>{{ $category->books_count }} buku</small></a>
			@endforeach
		</div>
	</section>
@endsection
