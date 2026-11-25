@extends('admin.layouts.master')
@section('title', 'Dasbor')

@section('css')
@endsection

@section('content')
    <div class="page-heading">
        @php($roleLabel = auth()->user()->role?->role_name ?? 'pengguna')
        <h3>Selamat datang, {{ $roleLabel }}!</h3>
    </div>
    <div class="page-content">
        <section class="row">
            <div class="col-12 col-lg-12">
                <div class="row">
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon purple mb-2">
                                            <i class="iconly-boldWallet"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Pesanan Hari Ini</h6>
                                        <h6 class="font-extrabold mb-0">{{ $todayOrders }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon blue">
                                            <i class="iconly-boldBuy"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Pendapatan Hari Ini</h6>
                                        <h6 class="font-extrabold mb-0">{{ 'Rp'. number_format($todayRevenue, 0, ',', '.') }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon green mb-2">
                                            <i class="iconly-boldFolder"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Total Pesanan</h6>
                                        <h6 class="font-extrabold mb-0">{{ $totalOrders }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon blue mb-2">
                                            <i class="iconly-boldProfile"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Total Pendapatan</h6>
                                        <h6 class="font-extrabold mb-0">{{ 'Rp'. number_format($totalRevenue, 0, ',', '.') }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <div>
                                    <h4 class="mb-1">Grafik Penjualan</h4>
                                    <p class="text-muted mb-0">{{ $salesChartSubtitle }}</p>
                                </div>
                                <form action="{{ route('dashboard') }}" method="GET" class="d-flex align-items-center gap-2">
                                    <label for="sales-period" class="text-muted mb-0">Periode</label>
                                    <select id="sales-period" name="period" class="form-select form-select-sm" aria-label="Pilih periode grafik">
                                        <option value="day" @selected($salesPeriod === 'day')>Per Hari</option>
                                        <option value="month" @selected($salesPeriod === 'month')>Per Bulan</option>
                                        <option value="year" @selected($salesPeriod === 'year')>Per Tahun</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-sm">Terapkan</button>
                                </form>
                            </div>
                            <div class="card-body">
                                <div
                                    id="chart-profile-visit"
                                    data-labels='@json($salesChartLabels)'
                                    data-values='@json($salesChartValues)'
                                    aria-label="Grafik penjualan"
                                    role="img"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
