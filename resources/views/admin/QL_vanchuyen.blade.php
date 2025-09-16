{{-- resources/views/admin/ql_vanchuyen.blade.php --}}
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Quản lý vận chuyển</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    {{-- (tuỳ) CSS khác --}}
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}?v={{ filemtime(public_path('css/admin/admin.css')) }}" />
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
            max-width: 560px
        }

        .top .top-search-input {
            flex: 1
        }

        .text-truncate {
            max-width: 180px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap
        }
    </style>
</head>

<body>
    <div class="app">

        {{-- Sidebar --}}
        @include('admin.sidebar-admin')

        {{-- Main --}}
        <main>
            <div class="top">
                <form class="top-search" action="#" method="GET" role="search">
                    <input type="text" name="q" class="top-search-input" placeholder="Tìm kiếm..." autocomplete="off" />
                    <button class="top-search-btn" aria-label="Tìm kiếm">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7"></circle>
                            <line x1="16.65" y1="16.65" x2="21" y2="21"></line>
                        </svg>
                    </button>
                </form>
            </div>

            <div class="container-fluid py-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h1 class="h4 mb-0">Quản lý vận chuyển</h1>
                    <div class="small text-muted">Chọn “Đã giao” → bấm Cập nhật để gửi email cho khách.</div>
                </div>

                {{-- Flash messages --}}
                @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
                @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

                {{-- Bộ lọc --}}
                <form method="GET" action="{{ route('admin.vanchuyen.index') }}" class="row g-2 align-items-end mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Tìm theo mã đơn</label>
                        <input type="text" name="code" value="{{ request('code') }}" class="form-control" placeholder="VD: COD2509...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Trạng thái vận chuyển</label>
                        <select name="status" class="form-select">
                            <option value="">— Tất cả —</option>
                            @php
                            $STATES = [
                            'da_dat' => 'Đã đặt',
                            'cho_chuyen_phat' => 'Chờ chuyển phát',
                            'dang_trung_chuyen' => 'Đang trung chuyển',
                            'da_giao' => 'Đã giao',
                            ];
                            @endphp
                            @foreach($STATES as $k => $v)
                            <option value="{{ $k }}" @selected(request('status')===$k)>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Trạng thái thanh toán</label>
                        <select name="payment_status" class="form-select">
                            <option value="">— Tất cả —</option>
                            @php
                            $PAYS = [
                            'chua_thanh_toan' => 'Chưa thanh toán',
                            'da_thanh_toan' => 'Đã thanh toán',
                            'that_bai' => 'Thất bại',
                            'hoan_tien' => 'Hoàn tiền',
                            ];
                            @endphp
                            @foreach($PAYS as $k => $v)
                            <option value="{{ $k }}" @selected(request('payment_status')===$k)>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i> Lọc
                        </button>
                    </div>
                </form>

                {{-- Bảng đơn hàng --}}
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Mã đơn</th>
                                        <th>Khách hàng</th>
                                        <th>Thông tin đơn</th> {{-- Cột MỚI --}}
                                        <th>Thanh toán</th>
                                        <th>Trạng thái VC</th>
                                        <th>Cập nhật trạng thái</th>
                                        <th>Thời gian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($orders as $o)
                                    @php
                                    $statusBadge = [
                                    'da_dat' => 'secondary',
                                    'cho_chuyen_phat' => 'warning',
                                    'dang_trung_chuyen' => 'info',
                                    'da_giao' => 'success',
                                    ][$o->status] ?? 'secondary';

                                    $payBadge = [
                                    'chua_thanh_toan' => 'warning text-dark',
                                    'da_thanh_toan' => 'success',
                                    'that_bai' => 'danger',
                                    'hoan_tien' => 'secondary',
                                    ][$o->payment_status] ?? 'secondary';

                                    $statusText = $STATES[$o->status] ?? $o->status;
                                    $payText = $PAYS[$o->payment_status] ?? $o->payment_status;

                                    $maxPreview = 2;
                                    $collapseId = 'items-'.$o->id;
                                    @endphp
                                    <tr>
                                        <td class="fw-semibold">
                                            {{ $o->code }}<br>
                                            <span class="text-muted small">{{ $o->email }}</span>
                                        </td>

                                        <td>
                                            <div class="fw-semibold">{{ $o->fullname }}</div>
                                            <div class="text-muted small">{{ $o->phone }}</div>
                                            <div class="text-muted small">
                                                {{ $o->address }}
                                                @if($o->ward_name), {{ $o->ward_name }} @endif
                                                @if($o->district_name), {{ $o->district_name }} @endif
                                                @if($o->province_name), {{ $o->province_name }} @endif
                                            </div>
                                        </td>


                                        {{-- Cột MỚI: Thông tin đơn --}}
                                        <td style="min-width:260px">
                                            <div class="small">

                                                <div>
                                                    <span class="text-muted">Phí ship:</span>
                                                    <strong>{{ number_format($o->shipping_fee,0,',','.') }}đ</strong>
                                                </div>

                                            </div>

                                            {{-- Tóm tắt sản phẩm --}}
                                            @php
                                            $totalItems = $o->items->count();
                                            $preview = $o->items->take($maxPreview);
                                            $rest = $totalItems - $maxPreview;
                                            @endphp
                                            <div class="mt-2">
                                                @foreach($preview as $it)
                                                <div class="d-flex justify-content-between small">
                                                    <span class="text-truncate me-2" title="{{ $it->product_name }}">{{ $it->product_name }}</span>
                                                    <span class="text-nowrap">x{{ $it->quantity }} · {{ number_format($it->total,0,',','.') }}đ</span>
                                                </div>
                                                @endforeach

                                                @if($rest > 0)
                                                <a class="small" data-bs-toggle="collapse" href="#{{ $collapseId }}" role="button" aria-expanded="false" aria-controls="{{ $collapseId }}">
                                                    +{{ $rest }} sản phẩm nữa
                                                </a>
                                                <div class="collapse mt-1" id="{{ $collapseId }}">
                                                    @foreach($o->items->skip($maxPreview) as $it)
                                                    <div class="d-flex justify-content-between small">
                                                        <span class="text-truncate me-2" title="{{ $it->product_name }}">{{ $it->product_name }}</span>
                                                        <span class="text-nowrap">x{{ $it->quantity }} · {{ number_format($it->total,0,',','.') }}đ</span>
                                                    </div>
                                                    @endforeach
                                                </div>
                                                @endif
                                            </div>
                                        </td>

                                        <td><span class="badge bg-{{ $payBadge }}">{{ $payText }}</span></td>
                                        <td><span class="badge bg-{{ $statusBadge }}">{{ $statusText }}</span></td>

                                        <td style="min-width:260px">
                                            <form method="POST" action="{{ route('admin.vanchuyen.updateStatus', $o) }}" class="d-flex gap-2">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" class="form-select form-select-sm">
                                                    @foreach($STATES as $k => $v)
                                                    <option value="{{ $k }}" @selected($o->status===$k)>{{ $v }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-primary">Cập nhật</button>
                                            </form>
                                            <div class="form-text">Chọn <b>Đã giao</b> rồi cập nhật để gửi email.</div>
                                        </td>

                                        <td class="text-muted small">
                                            Tạo: {{ $o->created_at?->format('d/m/Y H:i') }}<br>
                                            Sửa: {{ $o->updated_at?->format('d/m/Y H:i') }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted p-4">Chưa có đơn phù hợp.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if($orders instanceof \Illuminate\Contracts\Pagination\Paginator)
                    <div class="card-footer">
                        {{ $orders->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>