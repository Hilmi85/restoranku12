<footer class="customer-footer">
    <div class="container">
        <div class="customer-footer-main">
            <div class="customer-footer-brand">
                <a href="{{ route('menu') }}" class="customer-brand" aria-label="Restoranku">
                    <span class="customer-brand-mark" aria-hidden="true">R</span>
                    <span class="customer-brand-copy">
                        <strong>Restoranku</strong>
                        <small>MENU &amp; PEMESANAN</small>
                    </span>
                </a>
                <p>Hidangan pilihan, dipesan dengan mudah.</p>
            </div>

            <div class="customer-footer-links">
                <strong>Jelajahi</strong>
                <a href="{{ route('menu') }}">Lihat menu</a>
                <a href="{{ route('cart') }}">Keranjang</a>
            </div>

            <div class="customer-footer-links">
                <strong>Pembayaran</strong>
                <span>QRIS</span>
                <span>Tunai di kasir</span>
            </div>
        </div>

        <div class="customer-footer-bottom">
            <span>&copy; {{ date('Y') }} Restoranku. Dibuat oleh <strong>Ilham Hilmi Hilmawan</strong>.</span>
        </div>
    </div>
</footer>
