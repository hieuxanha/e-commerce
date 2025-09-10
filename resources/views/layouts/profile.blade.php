{{-- resources/views/layouts/profile.blade.php --}}
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Hồ sơ của tôi</title>

    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* ---- Sticky footer fallback (nếu không dùng class Bootstrap) ---- */
        html,
        body {
            height: 100%;
        }

        /* body đã dùng class Bootstrap d-flex flex-column min-vh-100,
           nhưng vẫn thêm fallback để an toàn */
        body {
            background: #f6f7fb;
        }

        .card-soft {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
        }

        .sidebar .list-group-item {
            border: none;
            border-radius: .6rem;
            margin-bottom: .25rem;
        }

        .sidebar .list-group-item.active {
            background: #e8f7ef;
            color: #0f5132;
            font-weight: 600;
        }

        .muted {
            color: #6b7280;
        }

        /* ==== Tracking step ==== */
        .step {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #e5e7eb;
        }

        .line {
            height: 2px;
            flex: 1;
            background: #e5e7eb;
        }

        .step.is-active {
            background: #0d6efd;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, .15);
        }

        .line.is-active {
            background: #0d6efd;
        }

        .step.is-canceled {
            background: #dc3545;
            box-shadow: 0 0 0 4px rgba(220, 53, 69, .15);
        }

        .line.is-canceled {
            background: #dc3545;
        }
    </style>
</head>

{{-- Sticky footer: d-flex + flex-column + min-vh-100 --}}

<body class="bg-light d-flex flex-column min-vh-100">
    @include('layouts.header')

    {{-- Toàn bộ nội dung nằm trong <main>, giúp footer đẩy xuống đáy --}}
    <main class="flex-grow-1">
        <div class="container py-4">
            <div class="row g-4">
                {{-- LEFT: menu --}}
                <div class="col-md-3">
                    <div class="card-soft p-3 sidebar">
                        <div class="h6 mb-3">Tài khoản</div>
                        <div class="list-group" role="tablist">
                            <a class="list-group-item list-group-item-action active" data-bs-toggle="list" href="#tab-info" role="tab">
                                <i class="bi bi-person me-2"></i> Thông tin cá nhân
                            </a>
                            <a class="list-group-item list-group-item-action" data-bs-toggle="list" href="#tab-password" role="tab">
                                <i class="bi bi-shield-lock me-2"></i> Đổi mật khẩu
                            </a>
                            <a class="list-group-item list-group-item-action" data-bs-toggle="list" href="#tab-orders" role="tab">
                                <i class="bi bi-receipt me-2"></i> Đơn hàng
                            </a>
                            <a class="list-group-item list-group-item-action" data-bs-toggle="list" href="#tab-tracking" role="tab">
                                <i class="bi bi-truck me-2"></i> Theo dõi đơn hàng
                            </a>
                            <a class="list-group-item list-group-item-action" href="{{ route('home') }}">
                                <i class="bi bi-box-arrow-right me-2"></i> Trang Chủ
                            </a>
                            <a class="list-group-item list-group-item-action" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                        </div>
                    </div>
                </div>

                {{-- RIGHT: content --}}
                <div class="col-md-9">
                    <div class="tab-content">



                        {{-- Thông tin cá nhân --}}
                        <div class="tab-pane fade show active" id="tab-info" role="tabpanel">
                            <div class="card-soft p-4">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="bi bi-person text-success fs-5"></i>
                                    <div class="h6 mb-0">Thông tin cá nhân</div>
                                </div>

                                @if($user)
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-muted">Họ tên</label>
                                        <div class="fw-semibold">{{ $user->name ?? '—' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted">Email</label>
                                        <div class="fw-semibold">{{ $user->email ?? '—' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted">Số điện thoại</label>
                                        <div class="fw-semibold">{{ $user->phone ?? '—' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted">Ngày tạo</label>
                                        <div class="fw-semibold">{{ optional($user->created_at)->format('d/m/Y') }}</div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label text-muted">Địa chỉ</label>
                                        <div class="fw-semibold">{{ $user->address ?? '—' }}</div>
                                    </div>

                                    {{-- Nút Sửa (chỉ thêm dòng này) --}}
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="button"
                                            class="btn btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editProfileModal">
                                            <i class="bi bi-pencil-square me-1"></i> Sửa
                                        </button>
                                    </div>
                                </div>

                                @else
                                <div class="text-danger">Bạn chưa đăng nhập.</div>
                                @endif
                            </div>
                        </div>

                        {{-- Đổi mật khẩu --}}
                        <div class="tab-pane fade" id="tab-password" role="tabpanel">
                            <div class="card-soft p-4">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="bi bi-shield-lock text-success fs-5"></i>
                                    <div class="h6 mb-0">Đổi mật khẩu</div>
                                </div>
                                <div class="alert alert-info mb-3">Tính năng đang được phát triển.</div>
                            </div>
                        </div>

                        {{-- Đơn hàng --}}
                        <div class="tab-pane fade" id="tab-orders" role="tabpanel">
                            <div class="card-soft p-4">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="bi bi-receipt text-success fs-5"></i>
                                    <div class="h6 mb-0">Đơn hàng của tôi</div>
                                </div>
                                <ul class="nav nav-pills mb-3" role="tablist">
                                    <li class="nav-item">
                                        <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#od-pending" type="button" role="tab">Chờ giao hàng</button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#od-delivered" type="button" role="tab">Đã giao</button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#od-canceled" type="button" role="tab">Đã hủy</button>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="od-pending" role="tabpanel">
                                        <div class="text-muted">Chưa có đơn nào đang chờ giao.</div>
                                    </div>
                                    <div class="tab-pane fade" id="od-delivered" role="tabpanel">
                                        <div class="text-muted">Chưa có đơn đã giao.</div>
                                    </div>
                                    <div class="tab-pane fade" id="od-canceled" role="tabpanel">
                                        <div class="text-muted">Chưa có đơn đã hủy.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- THEO DÕI ĐƠN HÀNG --}}
                        <div class="tab-pane fade" id="tab-tracking" role="tabpanel">
                            <div class="card-soft p-4">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="bi bi-truck text-success fs-5"></i>
                                    <div class="h6 mb-0">Theo dõi đơn hàng</div>
                                </div>

                                @php
                                // Map trạng thái -> bước (0..3), canceled = -1
                                $stageMap = [
                                'da_dat'=>0,'dat_hang'=>0,'da_dat_hang'=>0,'pending'=>0,'placed'=>0,
                                'cho_chuyen_phat'=>1,'dang_xu_ly'=>1,'da_xuat_kho'=>1,'processing'=>1,'packed'=>1,'shipped'=>1,
                                'dang_trung_chuyen'=>2,'dang_giao'=>2,'in_transit'=>2,'delivering'=>2,
                                'da_giao'=>3,'delivered'=>3,
                                'da_huy'=>-1,'huy'=>-1,'canceled'=>-1,'failed'=>-1,'return'=>-1,
                                ];
                                @endphp

                                @if(isset($orders) && $orders->count())
                                <div class="vstack gap-3">
                                    @foreach($orders as $o)
                                    @php
                                    $st = $o->status ?? 'da_dat';
                                    $stage = $stageMap[$st] ?? 0; // -1 = canceled
                                    $isCan = ($stage === -1);

                                    // gom địa chỉ người nhận
                                    $recvAddress = trim(collect([
                                    $o->address ?? null,
                                    $o->ward_name ?? null,
                                    $o->district_name ?? null,
                                    $o->province_name ?? null,
                                    ])->filter()->implode(', '));

                                    // data cho popup (DỰNG TRƯỚC để tránh lỗi Blade trong attribute)
                                    $orderData = [
                                    'id' => $o->id,
                                    'code' => $o->code ?? (string) $o->id,
                                    'created_at' => optional($o->created_at)->format('d/m/Y H:i'),
                                    'status' => $o->status,
                                    'payment_status' => $o->payment_status,
                                    'payment_method' => $o->payment_method,
                                    'total' => (int) ($o->total ?? 0),

                                    // tài khoản đang đăng nhập
                                    'account_name' => $user->name ?? null,
                                    'account_email' => $user->email ?? null,
                                    'account_phone' => $user->phone ?? null,

                                    // người nhận
                                    'recv_name' => $o->fullname ?? null,
                                    'recv_phone' => $o->phone ?? null,
                                    'recv_email' => $o->email ?? null,
                                    'recv_address' => $recvAddress,
                                    ];
                                    @endphp

                                    <div class="border rounded-3 p-3">
                                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                                            <div class="fw-semibold">Đơn #{{ $o->code ?? $o->id }}</div>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="small text-muted">{{ $o->created_at?->format('d/m/Y H:i') }}</span>

                                                {{-- Nút mở popup chi tiết --}}
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-outline-primary btn-view-order"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#orderDetailModal"
                                                    data-order='@json($orderData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)'>
                                                    <i class="bi bi-eye me-1"></i> Chi tiết
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Bước trạng thái --}}
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="step {{ $isCan ? 'is-canceled' : ($stage>=0 ? 'is-active' : '') }}"></div>
                                            <div class="line {{ $isCan ? 'is-canceled' : ($stage>=1 ? 'is-active' : '') }}"></div>
                                            <div class="step {{ $isCan ? 'is-canceled' : ($stage>=1 ? 'is-active' : '') }}"></div>
                                            <div class="line {{ $isCan ? 'is-canceled' : ($stage>=2 ? 'is-active' : '') }}"></div>
                                            <div class="step {{ $isCan ? 'is-canceled' : ($stage>=2 ? 'is-active' : '') }}"></div>
                                            <div class="line {{ $isCan ? 'is-canceled' : ($stage>=3 ? 'is-active' : '') }}"></div>
                                            <div class="step {{ $isCan ? 'is-canceled' : ($stage>=3 ? 'is-active' : '') }}"></div>
                                        </div>
                                        <div class="d-flex justify-content-between small text-muted mt-2">
                                            <span>Đã đặt hàng</span>
                                            <span>Chờ chuyển phát</span>
                                            <span>Đang trung chuyển</span>
                                            <span>Đã giao đơn hàng</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="text-muted">Bạn chưa có đơn hàng nào để theo dõi.</div>
                                @endif
                            </div>
                        </div>


                    </div>{{-- /tab-content --}}
                </div>
            </div>
        </div>
    </main>

    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Modal cập nhật thông tin cá nhân --}}
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md" style="--bs-modal-margin: 8rem auto;">
            <div class="modal-content">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="modal-header">
                        <h5 class="modal-title">Cập nhật thông tin cá nhân</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Họ tên</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone', $user->phone) }}">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label">Địa chỉ</label>
                            <textarea name="address" rows="3"
                                class="form-control @error('address') is-invalid @enderror">{{ old('address', $user->address) }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal chi tiết đơn hàng (đặt cuối body). Thêm mt-4 để modal cách mép trên --}}
    <div class="modal fade" id="orderDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable"
            style="--bs-modal-margin: 10rem auto;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Đơn <span id="odCode">#—</span>
                        <small class="text-muted ms-2" id="odCreated">—</small>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card-soft p-3">
                                <div class="fw-semibold mb-2">Thông tin tài khoản</div>
                                <div class="small text-muted">Họ tên</div>
                                <div id="odAccName" class="mb-2">—</div>
                                <div class="small text-muted">Email</div>
                                <div id="odAccEmail" class="mb-2">—</div>
                                <div class="small text-muted">SĐT</div>
                                <div id="odAccPhone">—</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card-soft p-3">
                                <div class="fw-semibold mb-2">Người nhận</div>
                                <div class="small text-muted">Họ tên</div>
                                <div id="odRecvName" class="mb-2">—</div>
                                <div class="small text-muted">SĐT</div>
                                <div id="odRecvPhone" class="mb-2">—</div>
                                <div class="small text-muted">Email</div>
                                <div id="odRecvEmail" class="mb-2">—</div>
                                <div class="small text-muted">Địa chỉ</div>
                                <div id="odRecvAddress">—</div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="small text-muted">Trạng thái vận chuyển</div>
                            <div id="odStatus" class="fw-semibold">—</div>
                        </div>
                        <div class="col-md-4">
                            <div class="small text-muted">Thanh toán</div>
                            <div id="odPayState" class="fw-semibold">—</div>
                            <div id="odPayMethod" class="text-muted small">—</div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="small text-muted">Tổng tiền</div>
                            <div id="odTotal" class="fs-5 fw-bold">—</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a id="odDetailLink" href="#" class="btn btn-outline-secondary" target="_blank" rel="noopener">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Xem chi tiết
                    </a>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const STATES = {
                da_dat: 'Đã đặt',
                cho_chuyen_phat: 'Chờ chuyển phát',
                dang_trung_chuyen: 'Đang trung chuyển',
                da_giao: 'Đã giao',
                da_huy: 'Đã hủy'
            };
            const PAY_STATES = {
                chua_thanh_toan: 'Chưa thanh toán',
                da_thanh_toan: 'Đã thanh toán',
                that_bai: 'Thất bại',
                hoan_tien: 'Hoàn tiền'
            };
            const PAY_METHODS = {
                cod: 'COD',
                vnpay: 'VNPAY'
            };

            const fmtMoney = (v) => new Intl.NumberFormat('vi-VN').format(Number(v || 0)) + ' đ';
            const detailBase = @json(url('/orders')); // -> "/orders"

            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.btn-view-order');
                if (!btn) return;

                let od = {};
                try {
                    od = JSON.parse(btn.dataset.order || '{}');
                } catch {}

                // Fill modal
                document.getElementById('odCode').textContent = '#' + (od.code || od.id || '—');
                document.getElementById('odCreated').textContent = od.created_at || '—';

                document.getElementById('odAccName').textContent = od.account_name || '—';
                document.getElementById('odAccEmail').textContent = od.account_email || '—';
                document.getElementById('odAccPhone').textContent = od.account_phone || '—';

                document.getElementById('odRecvName').textContent = od.recv_name || '—';
                document.getElementById('odRecvPhone').textContent = od.recv_phone || '—';
                document.getElementById('odRecvEmail').textContent = od.recv_email || '—';
                document.getElementById('odRecvAddress').textContent = od.recv_address || '—';

                const stText = STATES[od.status] || od.status || '—';
                const payState = PAY_STATES[od.payment_status] || od.payment_status || '—';
                const payMethod = PAY_METHODS[od.payment_method] || od.payment_method || '—';

                document.getElementById('odStatus').textContent = stText;
                document.getElementById('odPayState').textContent = payState;
                document.getElementById('odPayMethod').textContent = 'Phương thức: ' + payMethod;
                document.getElementById('odTotal').textContent = fmtMoney(od.total);

                // Link chi tiết /orders/{id}
                const a = document.getElementById('odDetailLink');
                if (od.id) a.href = detailBase.replace(/\/$/, '') + '/' + od.id;
                else a.removeAttribute('href');
            });
        })();
    </script>
</body>

</html>