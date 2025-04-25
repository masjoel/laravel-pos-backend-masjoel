@extends('layouts.app')

@section('title', 'Dashboard')

@push('style')
    <style>
        .card .card-stats .card-stats-title {
            padding: 10px 25px !important;
        }
    </style>
@endpush

@section('main')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="card card-statistic-2">
                        <div class="card-stats">
                            <form method="GET">
                                @csrf
                                <div class="d-flex justify-content-between">
                                    <span class="card-stats-title text-nowrap">Statistik </span>
                                    <div class="d-flex w-100">
                                        @php
                                            $months = [
                                                '01' => 'Januari',
                                                '02' => 'Februari',
                                                '03' => 'Maret',
                                                '04' => 'April',
                                                '05' => 'Mei',
                                                '06' => 'Juni',
                                                '07' => 'Juli',
                                                '08' => 'Agustus',
                                                '09' => 'September',
                                                '10' => 'Oktober',
                                                '11' => 'November',
                                                '12' => 'Desember',
                                            ];
                                        @endphp
                                        <select name="search" class="form-control select2">
                                            @foreach ($months as $m => $value)
                                                <option value="{{ $m }}" {{ $m == $search ? 'selected' : '' }}>
                                                    {{ $value }}</option>
                                            @endforeach
                                        </select>
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <button class="btn btn-sm btn-primary" style="padding: 0.1rem 0.8rem"><i
                                                        class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="card-stats-items mt-2">
                                <div class="card-stats-item">
                                    <div class="card-stats-item-count">{{ number_format($tot_proses) }}</div>
                                    <div class="card-stats-item-label">QRIS</div>
                                </div>
                                <div class="card-stats-item">
                                    <div class="card-stats-item-count">{{ number_format($tot_finish) }}</div>
                                    <div class="card-stats-item-label">TUNAI</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-6 d-flex">
                                <a href="{{ route('order.index') }}">
                                    <div class="card-icon shadow-primary bg-primary mb-0 mr-0">
                                        <i class="fas fa-archive"></i>
                                    </div>
                                </a>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4 class="text-nowrap">Total Orders</h4>
                                    </div>
                                    <div class="card-body" style="font-size: 20px">
                                        {{ number_format($tot_order) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6 d-flex">
                                <a href="{{ route('order.index') }}">
                                    <div class="card-icon shadow-primary bg-primary mr-0">
                                        <i class="fas fa-shopping-bag"></i>
                                    </div>
                                </a>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Sales</h4>
                                    </div>
                                    <div class="card-body" style="font-size: 20px">
                                        {{ number_format($tot_sales) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="card card-statistic-2">
                        <div class="card-chart">
                            <canvas id="balance-chart" height="80"></canvas>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-6 d-flex">
                                <a href="{{ route('order.index') }}">
                                    <div class="card-icon shadow-primary bg-primary mb-0 mr-0">
                                        <i class="fas fa-rupiah-sign"></i>
                                    </div>
                                </a>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Omzet</h4>
                                    </div>
                                    <div class="card-body" style="font-size: 20px">
                                        {{ number_format($tot_balance) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6 d-flex">
                                <a href="{{ route('order.index') }}">
                                    <div class="card-icon shadow-primary bg-primary mr-0">
                                        <i class="fas fa-rupiah-sign"></i>
                                    </div>
                                </a>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Margin</h4>
                                    </div>
                                    <div class="card-body" style="font-size: 20px">
                                        {{ number_format($tot_balance - $tot_budget) }}
                                        <span class="text-success"
                                            style="font-size: 12px">{{ $tot_balance > 0 ? number_format((($tot_balance - $tot_budget) / $tot_balance) * 100, 2) : 0 }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Biaya vs Penjualan</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="myChart2" style="height: 100px"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card ">
                        <div class="card-header">
                            <h4>5 Produk terlaris</h4>
                            <form method="GET">
                                @csrf

                                <div class="d-flex justify-content-between">
                                    @php
                                        $toporder = [
                                            date('Y-m-d') => 'Hari ini',
                                            date('W') => 'Minggu ini',
                                            date('m') => 'Bulan ini',
                                            date('Y') => 'Tahun ini',
                                        ];
                                    @endphp
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex clearfix">
                                            <select name="terlaris" class="form-control form-control-sm select2"
                                                style="border-radius:3px">
                                                @foreach ($toporder as $y => $value)
                                                    <option value="{{ $y }}"
                                                        {{ $y == $terlaris ? 'selected' : '' }}>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-sm btn-primary" style="border-radius:3px"><i
                                                        class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled list-unstyled-border">
                                @foreach ($bestproducts as $item)
                                    @php
                                        $item_sales = $item->total_sales * $item->price;
                                        $item_budget = $item->total_sales * $item->hpp;
                                        $tot_pros = $item_sales + $item_budget;
                                        $pros_sales = ($item_sales / $tot_pros) * 100;
                                        $pros_budget = ($item_budget / $tot_pros) * 100;
                                    @endphp
                                    <li class="media">
                                        <a href="{{ route('product.edit', $item->id) }}">
                                            @if ($item->image_url !== null)
                                                <img class="mr-3 rounded" width="55"
                                                    src="{{ Storage::url($item->image_url) }}" alt="product">
                                            @endif
                                        </a>
                                        <div class="media-body">
                                            <div class="float-right">
                                                <div class="font-weight-600 text-muted text-small">
                                                    {{ $item->total_sales }}
                                                    terjual</div>
                                            </div>
                                            <div class="media-title">{{ $item->name }}</div>
                                            <div class="mt-1">
                                                <div class="budget-price">
                                                    <div class="budget-price-square bg-primary"
                                                        data-width="{{ $pros_sales }}%"></div>
                                                    <div class="budget-price-label">{{ number_format($item_sales) }}</div>
                                                </div>
                                                <div class="budget-price">
                                                    <div class="budget-price-square bg-danger"
                                                        data-width="{{ $pros_budget }}%"></div>
                                                    <div class="budget-price-label">{{ number_format($item_budget) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>

    <script>
        let asset = '{{ Storage::url('') }}';
        $(document).on("click", "a#delete-data", function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            showDeletePopup('{{ url('') }}/order/' + id, '{{ csrf_token() }}',
                '{{ url('') }}/order');
        });

        function showDeletePopup(url, token, reload) {
            swal({
                    title: 'Hapus data',
                    text: 'Yakin data akan dihapus?',
                    icon: 'error',
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                                url: url,
                                "headers": {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                type: "DELETE"
                            })
                            .done(function(data) {
                                if (data.status == 'success') {
                                    swal('Data telah dihapus', {
                                        icon: 'success',
                                    });
                                    setTimeout(function() {
                                        swal.close()
                                        window.location.replace(reload);
                                    }, 1000);
                                } else {
                                    swal("Error!", data.message, "error");
                                }
                            })
                            .fail(function(data) {
                                swal("Oops...!", "Terjadi kesalahan pada server!", "error");
                            });
                    }
                });
        }

        var tglBalance = @json($tglBalance);
        var totalBudget = @json($totalBudget);
        var totalBalance = @json($totalBalance);

        var balance_chart = document.getElementById("balance-chart").getContext("2d");
        var balance_chart_bg_color = balance_chart.createLinearGradient(0, 0, 0, 70);
        balance_chart_bg_color.addColorStop(0, "rgba(63,82,227,.2)");
        balance_chart_bg_color.addColorStop(1, "rgba(63,82,227,0)");

        var myChart = new Chart(balance_chart, {
            type: "line",
            data: {
                labels: tglBalance,
                datasets: [{
                    label: "Balance",
                    data: totalBalance,
                    backgroundColor: balance_chart_bg_color,
                    borderWidth: 3,
                    borderColor: "rgba(63,82,227,1)",
                    pointBorderWidth: 0,
                    pointBorderColor: "transparent",
                    pointRadius: 3,
                    pointBackgroundColor: "transparent",
                    pointHoverBackgroundColor: "rgba(63,82,227,1)",
                }, ],
            },
            options: {
                layout: {
                    padding: {
                        bottom: -1,
                        left: -1,
                    },
                },
                legend: {
                    display: false,
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            beginAtZero: true,
                            display: false,
                        },
                    }, ],
                    xAxes: [{
                        gridLines: {
                            drawBorder: false,
                            display: false,
                        },
                        ticks: {
                            display: false,
                        },
                    }, ],
                },
            },
        });

        var ctx = document.getElementById("myChart2").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: tglBalance,
                datasets: [{
                    label: 'Biaya',
                    data: totalBudget,
                    borderWidth: 2,
                    backgroundColor: '#fc544b',
                    borderColor: '#fc544b',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#ffffff',
                    pointRadius: 4
                }, {
                    label: 'Penjualan',
                    data: totalBalance,
                    borderWidth: 2,
                    backgroundColor: '#6777ef',
                    borderColor: '#6777ef',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#ffffff',
                    pointRadius: 4
                }, ]
            },
            options: {
                legend: {
                    display: true,
                    position: 'bottom',
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            drawBorder: false,
                            color: '#f2f2f2',
                        },
                    }],
                    xAxes: [{
                        ticks: {
                            display: false
                        },
                        gridLines: {
                            display: false
                        }
                    }]
                },
            }
        });

    </script>
@endpush
