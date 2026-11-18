@extends('customer.layouts.master')

@section('content')

        <!-- Single Page Header start -->
        <div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6">Checkout</h1>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item active text-primary">Silakan lengkapi form pesanan anda</li>
            </ol>
        </div>
        <!-- Single Page Header End -->

    <div class="container-fluid py-5">
        <div class="container py-5">
            <h1 class="mb-4">Detail Pembayaran</h1>
            <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST">
                @csrf
                <div class="row g-5">
                    <div class="col-md-12 col-lg-6 col-xl-6">
                        <div class="row">
                            <div class="col-md-12 col-lg-4">
                                <div class="form-item w-100">
                                    <label class="form-label my-3">Nama Lengkap<sup>*</sup></label>
                                    <input type="text" name="fullname" class="form-control" placeholder="Masukkan nama anda" required>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-4">
                                <div class="form-item w-100">
                                    <label class="form-label my-3">Nomor WhatsApp<sup>*</sup></label>
                                    <input type="text" name="phone" class="form-control" placeholder="Masukkan nomor whatsApp anda" required>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-4">
                                <div class="form-item w-100">
                                    <label class="form-label my-3">Nomor Meja<sup>*</sup></label>
                                    <input type="text" class="form-control" value="{{ session('tableNumber') ?? 'Tidak ada nomor meja' }}" disabled required>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12 col-lg-12">
                                <div class="form-item">
                                    <textarea name="text" class="form-control" spellcheck="false" cols="30" rows="5" placeholder="Catatan pesanan (Opsional)"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="table-responsive">
                                <br><br>
                                <h4 class="mb-4">Detail Pesanan</h4>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Gambar</th>
                                            <th scope="col">Menu</th>
                                            <th scope="col">Harga</th>
                                            <th scope="col">Jumlah</th>
                                            <th scope="col">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $subTotal = 0;
                                        @endphp
                                        @foreach (Session('cart') as $item)
                                            @php
                                                $itemTotal = $item['price'] * $item['qty'];
                                                $subTotal += $itemTotal;
                                            @endphp
                                            <tr>
                                                <th scope="row">
                                                    <div class="d-flex align-items-center mt-2">
                                                        <img src="{{ asset('img_item_upload/'. $item['image']) }}"
                                                        class="img-fluid me-5 rounded-circle"
                                                        style="width: 80px; height: 80px;"
                                                        alt=""
                                                        onerror="this.onerror=null;this.src='{{ $item['image'] }}';">
                                                    </div>
                                                </th>
                                                <td class="py-5">{{ $item['name'] }}</td>
                                                <td class="py-5">{{ 'Rp'. number_format($item['price'], 0, ',', '.') }}</td>
                                                <td class="py-5">{{ $item['qty'] }}</td>
                                                <td class="py-5">{{ 'Rp'. number_format($item['price'] * $item['qty'], 0, ',', '.') }}</td>
                                            </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    @php
                        $tax = $subTotal * 0.1;
                        $total = $subTotal + $tax;
                    @endphp

                    <div class="col-md-12 col-lg-6 col-xl-6">
                        <div class="row g-4 align-items-center py-3">
                            <div class="col-lg-12">
                                <div class="bg-light rounded">
                                    <div class="p-4">
                                        <h3 class="display-6 mb-4">Total <span class="fw-normal">Pesanan</span></h3>
                                        <div class="d-flex justify-content-between mb-4">
                                            <h5 class="mb-0 me-4">Subtotal</h5>
                                            <p class="mb-0">{{ 'Rp'. number_format($subTotal, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <p class="mb-0 me-4">Pajak (10%)</p>
                                            <div class="">
                                                <p class="mb-0">{{ 'Rp'. number_format($tax, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="py-4 mb-4 border-top border-bottom d-flex justify-content-between">
                                        <h4 class="mb-0 ps-4 me-4">Total</h4>
                                        <h5 class="mb-0 pe-4">{{ 'Rp'. number_format($total, 0, ',', '.') }}</h5>
                                    </div>

                                    <div class="py-4 mb-4 d-flex justify-content-between">
                                        <h5 class="mb-0 ps-4 me-4">Metode Pembayaran</h5>
                                        <div class="mb-0 pe-4 mb-3 pe-5">
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input bg-primary border-0" id="qris" name="payment_method" value="qris">
                                                <label class="form-check-label" for="qris">QRIS</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input bg-primary border-0" id="cash" name="payment_method" value="tunai">
                                                <label class="form-check-label" for="cash">Tunai</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="button" id="pay-button" class="btn border-secondary py-3 text-uppercase text-primary">Konfirmasi Pesanan</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const payButton = document.getElementById('pay-button');
        const form = document.querySelector('form');

        payButton.addEventListener('click', function (e) {
            e.preventDefault();

            let paymentMethod = document.querySelector('input[name="payment_method"]:checked');

            if (!paymentMethod) {
                alert('Choose a payment method to proceed.');
                return;
            }

            paymentMethod = paymentMethod.value;
            let formData = new FormData(form);

            if (paymentMethod === 'tunai') {
                form.submit();
            } else {
                fetch("{{ route('checkout.store') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);

                    if (data.snap_token) {
                        snap.pay(data.snap_token, {
                            gopayMode: 'qr',
                            onSuccess: function(result){
                                window.location.href = "/checkout/success/" + data.order_code;
                            },
                            onPending: function(result){
                                alert("Payment pending. Please complete your payment to confirm the order.");
                            },
                            onError: function(result){
                                console.log(result);
                                alert("Payment failed. Please try again.");
                            },
                            onClose: function(){
                                alert("Payment popup was closed.");
                            }
                        });
                    } else {
                        alert(data.message ? data.message : "Unexpected response from server. Please try again.");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("An error occurred while processing your payment. Please try again.");
                });
            }

        });
    });
</script>

@endsection
