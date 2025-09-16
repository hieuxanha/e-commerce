{{-- resources/views/admin/QL_donhang.blade.php --}}
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Quản lý đơn hàng</title>

    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nhanvien/timkiem.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

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
            flex-wrap: wrap
        }

        .top .top-search-input {
            flex: 1;
            min-width: 260px
        }

        .table td,
        .table th {
            vertical-align: middle
        }

        .subtle {
            font-size: .9rem;
            color: #6c757d
        }
    </style>
</head>

<body>
    <div class="app">
        @include('admin.sidebar-admin')

        <main>
            {{-- Top search --}}
            <div class="top">
                <form class="top-search" action="{{ route('admin.orders.index') }}" method="GET" role="search">
                    <input type="text" name="q" class="top-search-input form-control"
                        placeholder="Tìm theo tài khoản / người nhận / email / SĐT / địa chỉ..."
                        value="{{ request('q') }}" autocomplete="off" />
                    <input type="text" name="code" class="form-control" style="max-width:220px"
                        placeholder="Mã đơn..." value="{{ request('code') }}">
                    <button class="btn btn-primary" aria-label="Tìm kiếm">
                        <i class="bi bi-search me-1"></i> Tìm
                    </button>
                    @if(request()->hasAny(['q','code','status','payment_status','payment_method']))
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">Xóa lọc</a>
                    @endif
                </form>
            </div>

            <!-- nội dung ở dưới -->
            <div class="container-fluid py-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h1 class="h4 mb-0">Quản lý đơn hàng</h1>
                    <div class="subtle">Tách rõ Khách đặt (tài khoản) và Người nhận.</div>
                </div>

                @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
                @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:50px" class="text-center">STT</th>
                                        <th>Mã đơn</th>
                                        <th>Khách đặt (tài khoản)</th>
                                        <th>Người nhận</th>
                                        <th class="text-end">Tổng tiền</th>
                                        <th>Thanh toán</th>
                                        <th>Trạng thái VC</th>
                                        <th>Phương thức TT</th>
                                        <th style="min-width:160px">Thời gian</th>
                                        <th style="width:90px">Thao tác</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($orders as $o)
                                    @php
                                    // STT liên tục qua trang
                                    $stt = method_exists($orders,'currentPage')
                                    ? ($orders->currentPage()-1)*$orders->perPage()+$loop->iteration
                                    : $loop->iteration;

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

                                    $STATES = [
                                    'da_dat'=>'Đã đặt',
                                    'cho_chuyen_phat'=>'Chờ chuyển phát',
                                    'dang_trung_chuyen'=>'Đang trung chuyển',
                                    'da_giao'=>'Đã giao'
                                    ];
                                    $PAYS = [
                                    'chua_thanh_toan'=>'Chưa thanh toán',
                                    'da_thanh_toan'=>'Đã thanh toán',
                                    'that_bai'=>'Thất bại',
                                    'hoan_tien'=>'Hoàn tiền'
                                    ];
                                    @endphp
                                    <tr>
                                        {{-- STT --}}
                                        <td class="text-center">{{ $stt }}</td>

                                        {{-- Mã đơn (kèm email người nhận) --}}
                                        <td class="fw-semibold">
                                            {{ $o->code }}
                                            <div class="subtle">{{ $o->email }}</div>
                                        </td>

                                        {{-- Khách đặt (tài khoản) --}}
                                        <td>
                                            @if($o->relationLoaded('user') ? $o->user : $o->user()->first())
                                            <div class="fw-semibold">{{ $o->user->name ?? '' }}</div>
                                            <div class="subtle">{{ $o->user->email ?? '' }}</div>
                                            @else
                                            <div class="subtle">— Không có tài khoản —</div>
                                            @endif
                                        </td>

                                        {{-- Người nhận (từ form checkout) --}}
                                        <td>
                                            <div class="fw-semibold">{{ $o->fullname }}</div>
                                            <div class="subtle">{{ $o->phone }}</div>
                                            <div class="subtle">
                                                {{ $o->address }}
                                                @if($o->ward_name), {{ $o->ward_name }} @endif
                                                @if($o->district_name), {{ $o->district_name }} @endif
                                                @if($o->province_name), {{ $o->province_name }} @endif
                                            </div>
                                        </td>

                                        {{-- Tổng tiền --}}
                                        <td class="text-end fw-bold">{{ number_format($o->total,0,',','.') }}đ</td>

                                        {{-- Thanh toán --}}
                                        <td>
                                            <span class="badge bg-{{ $payBadge }}">
                                                {{ $PAYS[$o->payment_status] ?? $o->payment_status }}
                                            </span>
                                            @if(!empty($o->paid_at))
                                            <div class="subtle mt-1">
                                                TT lúc: {{ \Illuminate\Support\Carbon::parse($o->paid_at)->format('d/m/Y H:i') }}
                                            </div>
                                            @endif
                                        </td>

                                        {{-- Trạng thái vận chuyển --}}
                                        <td><span class="badge bg-{{ $statusBadge }}">{{ $STATES[$o->status] ?? $o->status }}</span></td>

                                        {{-- Phương thức thanh toán --}}
                                        <td>{{ $o->payment_method ?? '—' }}</td>

                                        {{-- Thời gian --}}
                                        <td class="subtle">
                                            Tạo: {{ $o->created_at?->format('d/m/Y H:i') }}<br>
                                        </td>

                                        {{-- Thao tác --}}
                                        <td>
                                            <button type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#orderModal-{{ $o->id }}">
                                                Xem
                                            </button>
                                        </td>

                                    </tr>

                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted p-4">Chưa có đơn phù hợp.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{-- ========== MODALS ========== --}}
                            @foreach($orders as $o)
                            @php
                            $STATES = ['da_dat'=>'Đã đặt','cho_chuyen_phat'=>'Chờ chuyển phát','dang_trung_chuyen'=>'Đang trung chuyển','da_giao'=>'Đã giao'];
                            $PAYS = ['chua_thanh_toan'=>'Chưa thanh toán','da_thanh_toan'=>'Đã thanh toán','that_bai'=>'Thất bại','hoan_tien'=>'Hoàn tiền'];
                            $statusBadge = ['da_dat'=>'secondary','cho_chuyen_phat'=>'warning','dang_trung_chuyen'=>'info','da_giao'=>'success'][$o->status] ?? 'secondary';
                            $payBadge = ['chua_thanh_toan'=>'warning text-dark','da_thanh_toan'=>'success','that_bai'=>'danger','hoan_tien'=>'secondary'][$o->payment_status] ?? 'secondary';

                            // Nếu đã eager-load quan hệ items thì dùng $o->items, còn không sẽ là [] (tránh lỗi)
                            $items = isset($o->items) ? $o->items : collect();
                            @endphp

                            <div class="modal fade" id="orderModal-{{ $o->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Đơn {{ $o->code }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                        </div>
                                        <div class="modal-body">

                                            {{-- Thông tin tổng quan --}}
                                            <div class="mb-3 d-flex justify-content-between align-items-start gap-3">
                                                <div>
                                                    <div class="fw-semibold">Mã đơn: {{ $o->code }}</div>
                                                    <div class="text-muted small">Tạo lúc: {{ $o->created_at?->format('d/m/Y H:i') }}</div>
                                                    @if($o->paid_at)
                                                    <div class="text-muted small">Thanh toán lúc:
                                                        {{ \Illuminate\Support\Carbon::parse($o->paid_at)->format('d/m/Y H:i') }}
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="text-end">
                                                    <span class="badge bg-{{ $statusBadge }} me-2">{{ $STATES[$o->status] ?? $o->status }}</span>
                                                    <span class="badge bg-{{ $payBadge }}">{{ $PAYS[$o->payment_status] ?? $o->payment_status }}</span>
                                                    <div class="small mt-1">PTTT: <strong>{{ strtoupper($o->payment_method ?? '—') }}</strong></div>
                                                </div>
                                            </div>

                                            {{-- Người nhận --}}
                                            <div class="mb-3">
                                                <div class="fw-semibold mb-1">Người nhận</div>
                                                <div class="small">
                                                    {{ $o->fullname }} — {{ $o->phone }} — {{ $o->email }}<br>
                                                    {{ $o->address }}
                                                    @if($o->ward_name), {{ $o->ward_name }} @endif
                                                    @if($o->district_name), {{ $o->district_name }} @endif
                                                    @if($o->province_name), {{ $o->province_name }} @endif
                                                </div>
                                            </div>

                                            {{-- Khách đặt (tài khoản) nếu có --}}
                                            <div class="mb-3">
                                                <div class="fw-semibold mb-1">Khách đặt</div>
                                                @if($o->relationLoaded('user') ? $o->user : $o->user()->first())
                                                <div class="small">{{ $o->user->name ?? '' }} — {{ $o->user->email ?? '' }}</div>
                                                @else
                                                <div class="small text-muted">— Không có tài khoản —</div>
                                                @endif
                                            </div>

                                            {{-- Bảng sản phẩm --}}
                                            <div class="table-responsive">
                                                <table class="table align-middle">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th style="width:64px">Ảnh</th>
                                                            <th>Sản phẩm</th>
                                                            <th class="text-end">Giá</th>
                                                            <th class="text-center">SL</th>
                                                            <th class="text-end">Thành tiền</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($items as $it)
                                                        @php
                                                        $img = $it->image ?? null;
                                                        $imgSrc = $img
                                                        ? (\Illuminate\Support\Str::startsWith($img, ['http://','https://']) ? $img : asset('storage/'.$img))
                                                        : null;
                                                        @endphp
                                                        <tr>
                                                            <td>
                                                                @if($imgSrc)
                                                                <img src="{{ $imgSrc }}" alt="img" class="rounded" style="width:56px;height:56px;object-fit:cover">
                                                                @else
                                                                <div class="bg-light border rounded d-inline-flex align-items-center justify-content-center"
                                                                    style="width:56px;height:56px">—</div>
                                                                @endif
                                                            </td>
                                                            <td class="fw-semibold">{{ $it->product_name ?? $it->name ?? 'Sản phẩm' }}</td>
                                                            <td class="text-end">
                                                                {{ number_format((int)($it->price ?? 0), 0, ',', '.') }}đ
                                                            </td>
                                                            <td class="text-center">{{ $it->quantity ?? $it->qty ?? 0 }}</td>
                                                            <td class="text-end">
                                                                {{ number_format((int)($it->total ?? (($it->price ?? 0) * ($it->quantity ?? 0))), 0, ',', '.') }}đ
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center text-muted">Không có dòng hàng.</td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="4" class="text-end">Tạm tính</th>
                                                            <th class="text-end">{{ number_format((int)($o->subtotal ?? 0),0,',','.') }}đ</th>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="4" class="text-end">Phí vận chuyển</th>
                                                            <th class="text-end">{{ number_format((int)($o->shipping_fee ?? 0),0,',','.') }}đ</th>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="4" class="text-end">Tổng cộng</th>
                                                            <th class="text-end">{{ number_format((int)($o->total ?? 0),0,',','.') }}đ</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </div>
                    </div>

                    {{-- Pagination --}}
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