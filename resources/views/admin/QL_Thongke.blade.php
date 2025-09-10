<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Thống kê đơn đã thanh toán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nhanvien/timkiem.css') }}">
    <style>
        .app {
            display: flex;
            min-height: 100vh
        }

        main {
            flex: 1;
            background: #fafafa
        }

        .top {
            padding: 12px 16px;
            border-bottom: 1px solid #eee;
            background: #fff
        }

        .chart-card {
            border: 1px solid #eee;
            border-radius: 12px;
            background: #fff
        }

        .toggle-wrap {
            gap: 14px
        }

        .chart-wrap[hidden] {
            display: none !important
        }
    </style>
</head>

<body>
    <div class="app">
        {{-- Sidebar --}}
        @include('admin.sidebar-admin')

        <main>
            {{-- Bộ lọc & công tắc bật/tắt biểu đồ --}}
            <div class="top">
                <form class="row g-3 align-items-end" action="{{ route('admin.thongke.index') }}" method="GET">
                    <div class="col-auto">
                        <h1 class="h5 mb-0">Thống kê đơn <b>đã thanh toán</b></h1>
                    </div>
                    <div class="col-auto ms-auto">
                        <label class="form-label mb-0 small">Từ ngày</label>
                        <input type="date" name="date_from" value="{{ $from }}" class="form-control">
                    </div>
                    <div class="col-auto">
                        <label class="form-label mb-0 small">Đến ngày</label>
                        <input type="date" name="date_to" value="{{ $to }}" class="form-control">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-funnel me-1"></i>Lọc</button>
                    </div>
                </form>

                <div class="d-flex flex-wrap toggle-wrap mt-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="tg-line" checked>
                        <label class="form-check-label" for="tg-line">Biểu đồ đường</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="tg-bar" checked>
                        <label class="form-check-label" for="tg-bar">Biểu đồ cột</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="tg-pie" checked>
                        <label class="form-check-label" for="tg-pie">Biểu đồ tròn</label>
                    </div>
                </div>
            </div>

            {{-- Vùng biểu đồ --}}
            <div class="container-fluid py-4">
                <div class="row g-4">
                    <div class="col-lg-6 chart-wrap" id="wrap-line">
                        <div class="chart-card p-3">
                            <h6 class="mb-3">Doanh thu theo ngày</h6>
                            <canvas id="lineChart" height="120"></canvas>
                        </div>
                    </div>

                    <div class="col-lg-6 chart-wrap" id="wrap-bar">
                        <div class="chart-card p-3">
                            <h6 class="mb-3">Top sản phẩm bán chạy (số lượng)</h6>
                            <canvas id="barChart" height="120"></canvas>
                        </div>
                    </div>

                    <div class="col-lg-6 chart-wrap" id="wrap-pie">
                        <div class="chart-card p-3">
                            <h6 class="mb-3">Top sản phẩm theo doanh thu</h6>
                            <canvas id="pieChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    {{-- JS libs --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    @php
    $tkData = [
    'lineLabels' => $lineLabels ?? [],
    'lineData' => $lineData ?? [],
    'barLabels' => $barLabels ?? [],
    'barData' => $barData ?? [],
    'pieLabels' => $pieLabels ?? [],
    'pieData' => $pieData ?? [],
    ];
    @endphp

    {{-- JSON THUẦN cho JS đọc (KHÔNG bọc {} ngoài) --}}
    <script type="application/json" id="tk-data">
        @json($tkData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
    </script>

    {{-- JS điều khiển 3 biểu đồ + bật/tắt --}}
    <script src="{{ asset('admin/thongke.js') }}"></script>
</body>

</html>