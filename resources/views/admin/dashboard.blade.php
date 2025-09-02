<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Tổng quan quản trị</title>
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/nhanvien/timkiem.css') }}" />
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet" />
    <style>
        .kpi {
            box-shadow: var(--shadow, 0 1px 4px rgba(0, 0, 0, 0.08));
            border-radius: 14px;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .card {
            border-radius: 14px;
        }
    </style>
</head>

<body>
    <div class="app">
        @include('admin.sidebar-admin')

        <main>
            {{-- Thanh search giữ nguyên UI --}}
            <div class="top">
                <form class="top-search" action="#" role="search">
                    <input
                        type="text"
                        class="top-search-input"
                        placeholder="Tìm nhanh... (demo)" />
                    <button class="top-search-btn" aria-label="Tìm kiếm">
                        <svg
                            viewBox="0 0 24 24"
                            width="18"
                            height="18"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2">
                            <circle cx="11" cy="11" r="7"></circle>
                            <line
                                x1="16.65"
                                y1="16.65"
                                x2="21"
                                y2="21"></line>
                        </svg>
                    </button>
                </form>
            </div>

            <div class="content p-3">
                {{-- KPIs --}}
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="kpi p-3 bg-white">
                            <div class="text-muted small">Sản phẩm</div>
                            <div class="fs-3 fw-bold">
                                {{ number_format($metrics["products"]) }}
                            </div>
                            <div class="small text-secondary">
                                Danh mục: {{ $metrics["categories"] }} •
                                Thương hiệu: {{ $metrics["brands"] }}
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="kpi p-3 bg-white">
                            <div class="text-muted small">Người dùng</div>
                            <div class="fs-3 fw-bold">
                                {{ number_format($metrics["users"]) }}
                            </div>
                            <div class="small text-secondary">
                                Admin {{ $metrics["admins"] }} • NV
                                {{ $metrics["staff"] }} • KH
                                {{ $metrics["customers"] }}
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="kpi p-3 bg-white">
                            <div class="text-muted small">Đánh giá</div>
                            <div class="fs-3 fw-bold">
                                {{ number_format($metrics["reviews"]) }}
                            </div>
                            <div class="small text-secondary">
                                Chờ duyệt: {{ $metrics["pending_reviews"] }}
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="kpi p-3 bg-white">
                            <div class="text-muted small">Giỏ hàng</div>
                            <div class="fs-3 fw-bold">
                                {{ number_format($metrics["cart_items"]) }}
                            </div>
                            <div class="small text-secondary">
                                Mục trong giỏ của người dùng
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Charts --}}
                <div class="row g-3 mt-1">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header fw-semibold">
                                Sản phẩm theo danh mục
                            </div>
                            <div class="card-body">
                                <canvas
                                    id="chartByCategory"
                                    height="120"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card h-100">
                            <div class="card-header fw-semibold">
                                Người dùng theo vai trò
                            </div>
                            <div class="card-body">
                                <canvas
                                    id="chartRoles"
                                    height="120"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tables --}}
                <div class="row g-3 mt-1">
                    <div class="col-lg-7">
                        <div class="card">
                            <div class="card-header fw-semibold">
                                Sắp hết hàng
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 70px">
                                                    STT
                                                </th>
                                                <th>Sản phẩm</th>
                                                <th>SKU</th>
                                                <th class="text-end">
                                                    Tồn
                                                </th>
                                                <th class="text-end">
                                                    Giá
                                                </th>
                                                <th class="text-end">KM</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($lowStocks as $p)
                                            <tr>
                                                <td>
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td class="fw-semibold">
                                                    {{ $p->ten_san_pham }}
                                                </td>
                                                <td>{{ $p->sku }}</td>
                                                <td class="text-end">
                                                    {{ $p->so_luong_ton_kho }}
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format($p->gia,0,',','.') }}
                                                    đ
                                                </td>
                                                <td class="text-end">
                                                    {{ $p->gia_khuyen_mai ? number_format($p->gia_khuyen_mai,0,',','.') . ' đ' : '—' }}
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td
                                                    colspan="6"
                                                    class="text-center text-muted py-4">
                                                    Không có sản phẩm sắp
                                                    hết hàng.
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="card">
                            <div class="card-header fw-semibold">
                                Giảm giá nổi bật
                            </div>
                            <div class="card-body p-0">
                                <table class="table mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 70px">STT</th>
                                            <th>Sản phẩm</th>
                                            <th class="text-end">
                                                Giảm (%)
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topDiscounts as $p)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="fw-semibold">
                                                {{ $p->ten_san_pham }}
                                            </td>
                                            <td class="text-end">
                                                {{ $p->discount_percent }}%
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td
                                                colspan="3"
                                                class="text-center text-muted py-4">
                                                Chưa có sản phẩm khuyến mãi.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header fw-semibold">
                                Đánh giá mới
                            </div>
                            <div class="card-body p-0">
                                <table class="table mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 70px">STT</th>
                                            <th>SP / Người dùng</th>
                                            <th class="text-end">Sao</th>
                                            <th>TT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($latestReviews as $r)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="fw-semibold">
                                                    {{ $r->product?->ten_san_pham ?? '—' }}
                                                </div>
                                                <div
                                                    class="small text-muted">
                                                    {{ $r->user?->name ?? '—' }}
                                                    •
                                                    {{ $r->user?->email ?? '' }}
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                {{ $r->rating }}/5
                                            </td>
                                            <td>
                                                @if($r->approved)
                                                <span
                                                    class="badge bg-success">Đã duyệt</span>
                                                @else
                                                <span
                                                    class="badge bg-warning text-dark">Chờ duyệt</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td
                                                colspan="4"
                                                class="text-center text-muted py-4">
                                                Chưa có đánh giá.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    {{-- 1) Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- 2) JSON thuần --}}
    @php
    $dashboardPayload = [
    'catLabels' => $catLabels,
    'catCounts' => $catCounts,s
    'roleLabels' => $roleLabels,
    'roleCounts' => $roleCounts,
    ];
    @endphp
    <script type="application/json" id="dashboard-data">
        {
            !!json_encode($dashboardPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!
        }
    </script>

    {{-- 3) File JS --}}
    <script src="{{ asset('admin/dashboard.js') }}"></script>


</html>