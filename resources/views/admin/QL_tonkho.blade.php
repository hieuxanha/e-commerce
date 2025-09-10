{{-- resources/views/admin/tonkho.blade.php --}}
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Quản lý tồn kho</title>
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

        .top .top-search {
            display: flex;
            gap: 8px;
            max-width: 720px
        }

        .top .top-search-input {
            flex: 1
        }

        .thumb {
            width: 56px;
            height: 56px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #eee
        }

        .qty-input {
            width: 96px;
            text-align: center
        }

        .stat-pill {
            font-weight: 600
        }
    </style>
</head>

<body>
    <div class="app">
        {{-- Sidebar --}}
        @include('admin.sidebar-admin')

        <main>
            {{-- Top search/filter --}}
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

            <div class="container-fluid py-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h1 class="h4 mb-0">Quản lý tồn kho</h1>
                    <div class="d-flex gap-2">
                        <form action="{{ route('admin.tonkho.export') }}" method="GET">
                            <button class="btn btn-outline-secondary"><i class="bi bi-download me-1"></i>Xuất CSV</button>
                        </form>
                        <form action="{{ route('admin.tonkho.bulkAdjust') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label class="btn btn-outline-primary mb-0">
                                <i class="bi bi-upload me-1"></i>Nhập điều chỉnh CSV
                                <input type="file" name="file" accept=".csv" hidden onchange="this.form.submit()">
                            </label>
                        </form>
                    </div>
                </div>

                {{-- Flash --}}
                @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
                @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:64px">Ảnh</th>
                                        <th>Sản phẩm</th>
                                        <th>SKU</th>
                                        <th class="text-end">Giá</th>
                                        <th class="text-end">KM</th>
                                        <th class="text-center">Tồn kho</th>
                                        <th>Trạng thái</th>
                                        <th style="min-width:260px">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($products as $p)
                                    @php
                                    $price = number_format($p->gia ?? 0, 0, ',', '.').'đ';
                                    $priceKm = $p->gia_khuyen_mai ? number_format($p->gia_khuyen_mai, 0, ',', '.').'đ' : '—';
                                    $badge = 'secondary';
                                    if ($p->so_luong_ton_kho == 0) $badge = 'danger';
                                    elseif ($p->so_luong_ton_kho < 5) $badge='warning text-dark' ;
                                        else $badge='success' ;
                                        $stateMap=['con_hang'=>'success','het_hang'=>'secondary','sap_ve'=>'info','an'=>'dark'];
                                        @endphp
                                        <tr>
                                            <td>
                                                <img class="thumb"
                                                    src="{{ $p->hinh_anh_chinh ? (Str::startsWith($p->hinh_anh_chinh, ['http','https']) ? $p->hinh_anh_chinh : asset('storage/'.$p->hinh_anh_chinh)) : 'https://via.placeholder.com/56' }}"
                                                    alt="img">
                                            </td>
                                            <td class="fw-semibold">
                                                {{ $p->ten_san_pham }}
                                                <div class="text-muted small">ID: {{ $p->id }}</div>
                                            </td>
                                            <td><span class="text-muted">{{ $p->sku ?? '—' }}</span></td>
                                            <td class="text-end">{{ $price }}</td>
                                            <td class="text-end">{{ $priceKm }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-{{ $badge }} stat-pill">{{ $p->so_luong_ton_kho }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $stateMap[$p->trang_thai] ?? 'secondary' }}">
                                                    {{ ['con_hang'=>'Còn hàng','het_hang'=>'Hết hàng','sap_ve'=>'Sắp về','an'=>'Ẩn'][$p->trang_thai] ?? $p->trang_thai }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-2">
                                                    {{-- Giảm nhanh --}}
                                                    <form method="POST" action="{{ route('admin.tonkho.adjust', $p) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="delta" value="-1">
                                                        <button class="btn btn-sm btn-outline-danger" title="-1">
                                                            <i class="bi bi-dash"></i>
                                                        </button>
                                                    </form>

                                                    {{-- Tăng nhanh --}}
                                                    <form method="POST" action="{{ route('admin.tonkho.adjust', $p) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="delta" value="1">
                                                        <button class="btn btn-sm btn-outline-success" title="+1">
                                                            <i class="bi bi-plus"></i>
                                                        </button>
                                                    </form>

                                                    {{-- Cập nhật số lượng cụ thể (modal) --}}
                                                    <button
                                                        class="btn btn-sm btn-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#setQtyModal"
                                                        data-id="{{ $p->id }}"
                                                        data-name="{{ $p->ten_san_pham }}"
                                                        data-qty="{{ $p->so_luong_ton_kho }}">
                                                        <i class="bi bi-pencil-square me-1"></i> Cập nhật
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted p-4">Chưa có sản phẩm phù hợp.</td>
                                        </tr>
                                        @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if($products instanceof \Illuminate\Contracts\Pagination\Paginator)
                    <div class="card-footer">
                        {{ $products->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    {{-- Modal: Set Qty --}}
    <div class="modal fade" id="setQtyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ route('admin.tonkho.setQty') }}">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Cập nhật số lượng tồn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="product_id" id="setQtyId">
                    <div class="mb-2">
                        <div class="text-muted small">Sản phẩm</div>
                        <div id="setQtyName" class="fw-semibold"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số lượng</label>
                        <input type="number" class="form-control qty-input" name="so_luong_ton_kho" id="setQtyValue" min="0" required>
                        <div class="form-text">Đặt số lượng tuyệt đối (không cộng/trừ).</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary"><i class="bi bi-save me-1"></i>Lưu</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const modal = document.getElementById('setQtyModal');
        modal?.addEventListener('show.bs.modal', event => {
            const btn = event.relatedTarget;
            document.getElementById('setQtyId').value = btn.getAttribute('data-id');
            document.getElementById('setQtyName').textContent = btn.getAttribute('data-name');
            document.getElementById('setQtyValue').value = btn.getAttribute('data-qty');
        });
    </script>
</body>

</html>