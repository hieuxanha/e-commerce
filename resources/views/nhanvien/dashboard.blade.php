{{-- resources/views/nhanvien/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard Nhân Viên</title>

    {{-- CSS khu vực nhân viên --}}
    <link rel="stylesheet" href="{{ asset('css/nhanvien/nhanvien.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nhanvien/timkiem.css') }}">

    {{-- Bootstrap + Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        .app { display: flex; min-height: 100vh; }
        main { flex: 1; background: #fafafa; }
        .top { padding: 12px 16px; border-bottom: 1px solid #eee; background: #fff; }

        .stat-card { border: 1px solid #eee; border-radius: 14px; }
        .stat-card .icon {
            width: 44px; height: 44px; border-radius: 10px;
            display: grid; place-items: center;
        }
        .table td, .table th { vertical-align: middle; }
        .product-thumb { width: 48px; height: 48px; object-fit: cover; border-radius: 8px; border: 1px solid #eee; }
    </style>
</head>

<body>
<div class="app">
    @include('nhanvien.sidebar-nhanvien')

    <main>
        {{-- Topbar tìm kiếm --}}
        <div class="top">
            <form class="top-search" action="#" method="GET" role="search">
                <input type="text" name="q" class="top-search-input" placeholder="Tìm sản phẩm, danh mục, thương hiệu..." autocomplete="off" />
                <button class="top-search-btn" aria-label="Tìm kiếm">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="7"></circle>
                        <line x1="16.65" y1="16.65" x2="21" y2="21"></line>
                    </svg>
                </button>
            </form>
        </div>

        <div class="container-fluid py-4">
            {{-- Hàng thẻ chỉ số nhanh --}}
            <div class="row g-3">
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="p-3 bg-white stat-card">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon bg-primary-subtle">
                                <i class="bi bi-box-seam text-primary"></i>
                            </div>
                            <div>
                                <div class="text-secondary small">Tổng sản phẩm</div>
                                <div class="h4 mb-0">{{ number_format($totalProducts) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="p-3 bg-white stat-card">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon bg-warning-subtle">
                                <i class="bi bi-exclamation-triangle text-warning"></i>
                            </div>
                            <div>
                                <div class="text-secondary small">Sắp hết hàng (&lt;5)</div>
                                <div class="h4 mb-0">{{ number_format($lowStockCount) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="p-3 bg-white stat-card">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon bg-success-subtle">
                                <i class="bi bi-tags text-success"></i>
                            </div>
                            <div>
                                <div class="text-secondary small">Thương hiệu</div>
                                <div class="h4 mb-0">{{ number_format($totalBrands) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="p-3 bg-white stat-card">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon bg-info-subtle">
                                <i class="bi bi-grid text-info"></i>
                            </div>
                            <div>
                                <div class="text-secondary small">Danh mục</div>
                                <div class="h4 mb-0">{{ number_format($totalCategories) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Biểu đồ + thao tác nhanh --}}
            <div class="row g-3 mt-1">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-white">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="mb-0">Sản phẩm thêm mới (7 ngày)</h6>
                                <small class="text-muted">Nguồn dữ liệu: bảng products</small>
                            </div>
                        </div>
                        <div class="card-body">
                            {{-- NHÚNG JSON THUẦN vào data-attributes để tránh lỗi JS --}}
                            <canvas
                                id="spChart"
                                height="120"
                                data-labels='{{ json_encode($chartLabels ?? [], JSON_UNESCAPED_UNICODE) }}'
                                data-data='{{ json_encode($chartData ?? [], JSON_UNESCAPED_UNICODE) }}'>
                            </canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Thao tác nhanh</h6>
                        </div>
                        <div class="card-body d-grid gap-2">
                            <a href="{{ route('nhanvien.sanpham.them') }}" class="btn btn-primary">
                                <i class="bi bi-plus-lg me-1"></i> Thêm sản phẩm
                            </a>
                            <a href="{{ route('nhanvien.danhsachsanpham') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-box-seam me-1"></i> Quản lý sản phẩm
                            </a>
                            <a href="{{ route('nhanvien.brands.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-tags me-1"></i> Quản lý thương hiệu
                            </a>
                            <a href="{{ route('nhanvien.categories.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-grid me-1"></i> Quản lý danh mục
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sản phẩm mới cập nhật --}}
            <div class="card mt-3">
                <div class="card-header bg-white d-flex align-items-center justify-content-between">
                    <h6 class="mb-0">Sản phẩm mới cập nhật</h6>
                    <a class="small" href="{{ route('nhanvien.danhsachsanpham') }}">Xem tất cả</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                        <tr>
                            <th style="width:56px">Ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>SKU</th>
                            <th class="text-end">Giá</th>
                            <th class="text-end">KM</th>
                            <th class="text-center">Tồn</th>
                            <th style="width:120px"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($latestProducts as $p)
                            <tr>
                                <td>
                                    @if($p->hinh_anh_chinh)
                                        <img class="product-thumb" src="{{ asset('storage/'.$p->hinh_anh_chinh) }}" alt="">
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ $p->ten_san_pham }}</td>
                                <td class="text-muted">{{ $p->sku }}</td>
                                <td class="text-end">{{ number_format($p->gia, 0, ',', '.') }}đ</td>
                                <td class="text-end">
                                    {{ $p->gia_khuyen_mai ? number_format($p->gia_khuyen_mai,0,',','.') . 'đ' : '—' }}
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ ($p->so_luong_ton_kho ?? 0) < 5 ? 'text-bg-warning text-dark' : 'text-bg-success' }}">
                                        {{ $p->so_luong_ton_kho ?? 0 }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('nhanvien.sanpham.edit', $p->id) }}">Sửa</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Chưa có sản phẩm.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function () {
        var canvas = document.getElementById('spChart');
        if (!canvas) return;

        try {
            // Lấy JSON thuần từ data-attributes (đã json_encode ở Blade)
            var labels = JSON.parse(canvas.getAttribute('data-labels') || '[]');
            var data   = JSON.parse(canvas.getAttribute('data-data')   || '[]');

            var ctx = canvas.getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'SP thêm mới',
                        data: data,
                        borderWidth: 2,
                        tension: .35
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { grid: { color: '#eef2f7' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        } catch (e) {
            console.error('Lỗi parse dữ liệu biểu đồ:', e);
        }
    })();
</script>
</body>
</html>
