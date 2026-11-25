@php
    $cartCount = collect(session('cart', []))->sum('qty');
@endphp

<header class="customer-navbar-shell sticky-top">
    <nav class="navbar navbar-expand-lg customer-navbar">
        <div class="container">
            <a href="{{ route('menu') }}" class="customer-brand" aria-label="Restoranku, lihat menu">
                <span class="customer-brand-mark" aria-hidden="true">R</span>
                <span class="customer-brand-copy">
                    <strong>Restoranku</strong>
                    <small>MENU &amp; PEMESANAN</small>
                </span>
            </a>

            <button class="navbar-toggler customer-nav-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#customerNavigation" aria-controls="customerNavigation" aria-expanded="false" aria-label="Buka navigasi">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="customerNavigation">
                <div class="navbar-nav customer-nav-links ms-auto me-lg-4">
                    <a href="{{ route('menu') }}" class="nav-link {{ request()->routeIs('menu') ? 'active' : '' }}">Menu</a>
                </div>

                <div class="customer-nav-actions">
                    @if (session('tableNumber'))
                        <span class="customer-table-pill"><i class="fas fa-chair" aria-hidden="true"></i> Meja {{ session('tableNumber') }}</span>
                    @endif
                    <a href="{{ route('cart') }}" class="customer-cart-link" aria-label="Buka keranjang, {{ $cartCount }} item">
                        <i class="fas fa-shopping-bag" aria-hidden="true"></i>
                        <span>Keranjang</span>
                        <span class="customer-cart-count" data-cart-count>{{ $cartCount }}</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>
</header>
