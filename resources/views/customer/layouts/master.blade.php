@include('customer.layouts.__header')

    <body id="top" class="customer-app">

        <!-- Spinner Start -->
        <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50 d-flex align-items-center justify-content-center customer-spinner" aria-label="Memuat halaman">
            <div class="spinner-grow text-primary" role="status"></div>
        </div>
        <!-- Spinner End -->

        <!-- Navbar start -->
        @include('customer.layouts.__navbar')
        <!-- Navbar End -->

        <!-- Content start -->
        @yield('content')
        <!-- Content End -->

        <div id="customer-toast" class="customer-toast" role="status" aria-live="polite"></div>

        <!-- Footer Start -->
        @include('customer.layouts.__footer')
        <!-- Footer End -->

        <!-- Back to Top -->
        <a href="#top" class="btn btn-primary border-0 rounded-circle back-to-top" aria-label="Kembali ke atas"><i class="fa fa-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/customer/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('assets/customer/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/customer/lib/lightbox/js/lightbox.min.js') }}"></script>
    <script src="{{ asset('assets/customer/lib/owlcarousel/owl.carousel.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('assets/customer/js/main.js') }}"></script>

    <script>
        window.showCustomerToast = function (message, type = 'success') {
            const toast = document.getElementById('customer-toast');
            if (!toast) return;

            toast.textContent = message;
            toast.className = `customer-toast is-visible ${type === 'error' ? 'is-error' : 'is-success'}`;
            clearTimeout(window.customerToastTimer);
            window.customerToastTimer = setTimeout(() => toast.classList.remove('is-visible'), 3200);
        };

        document.addEventListener('click', async function (event) {
            const button = event.target.closest('[data-add-to-cart]');
            if (!button) return;

            button.disabled = true;
            const originalText = button.innerHTML;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Menambahkan';

            try {
                const response = await fetch(button.dataset.addUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ id: button.dataset.addToCart }),
                });
                const data = await response.json();

                if (!response.ok || data.status !== 'success') {
                    throw new Error(data.message || 'Menu belum berhasil ditambahkan.');
                }

                const count = Object.values(data.cart || {}).reduce((total, item) => total + Number(item.qty || 0), 0);
                document.querySelectorAll('[data-cart-count]').forEach((badge) => badge.textContent = count);
                window.showCustomerToast('Menu berhasil ditambahkan ke keranjang.');
            } catch (error) {
                window.showCustomerToast(error.message || 'Terjadi kendala. Silakan coba lagi.', 'error');
            } finally {
                button.disabled = false;
                button.innerHTML = originalText;
            }
        });
    </script>

    @yield('script')
    </body>
</html>
