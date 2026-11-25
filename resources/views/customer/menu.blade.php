@extends('customer.layouts.master')

@section('title', 'Menu Restoranku')

@section('content')
    <section class="customer-page-intro">
        <p class="customer-eyebrow">Dibuat dengan sepenuh hati</p>
        <h1>Temukan menu favorit Anda</h1>
        <p>Pilihan hidangan lezat untuk menemani momen makan Anda. Pilih menu, lalu tambahkan ke keranjang.</p>
        <div class="customer-intro-count" aria-label="{{ $items->count() }} menu tersedia">
            <div><strong>{{ $items->count() }}</strong><span>menu tersedia</span></div>
        </div>
    </section>

    <main class="container customer-main-section">
        <div class="customer-section-heading">
            <div>
                <h2>Menu pilihan</h2>
                <p>Pesanan Anda disiapkan setelah dikonfirmasi.</p>
            </div>
            <a href="{{ route('cart') }}" class="customer-secondary-button">
                <i class="fas fa-shopping-bag" aria-hidden="true"></i> Lihat keranjang
            </a>
        </div>

        <div class="row g-4">
            @forelse ($items as $item)
            @csrf
                @php
                    $categoryName = $item->category?->cat_name ?? 'Pilihan restoran';
                @endphp
                <div class="col-12 col-sm-6 col-xl-4">
                    <article class="customer-menu-card">
                        <div class="customer-menu-image">
                            <img
                                src="{{ asset('img_item_upload/' . $item->img) }}"
                                alt="{{ $item->name }}"
                                loading="lazy"
                                onerror="this.onerror=null;this.src='{{ $item->img }}';"
                            >
                            <span class="customer-menu-category">{{ $categoryName }}</span>
                        </div>
                        <div class="customer-menu-body">
                            <h3>{{ $item->name }}</h3>
                            <p class="customer-menu-description">{{ $item->description ?: 'Disiapkan segar untuk menemani waktu makan Anda.' }}</p>
                            <div class="customer-menu-bottom">
                                <span class="customer-menu-price">Rp{{ number_format($item->price, 0, ',', '.') }}</span>
                                <button
                                    type="button"
                                    class="customer-add-button"
                                    data-add-to-cart="{{ $item->id }}"
                                    data-add-url="{{ route('cart.add') }}"
                                >
                                    <i class="fas fa-plus" aria-hidden="true"></i> Tambah ke keranjang
                                </button>
                            </div>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <div class="customer-empty-state">
                        <span class="customer-empty-icon"><i class="fas fa-utensils" aria-hidden="true"></i></span>
                        <h2>Menu belum tersedia</h2>
                        <p>Silakan kembali lagi nanti untuk melihat pilihan hidangan dari Restoranku.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </main>
@endsection
