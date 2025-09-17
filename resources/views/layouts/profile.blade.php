{{-- resources/views/layouts/profile.blade.php --}}
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Hồ sơ của tôi</title>

    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/magiam.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        html,
        body {
            height: 100%
        }

        body {
            background: #f6f7fb
        }

        .card-soft {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px
        }

        .sidebar .list-group-item {
            border: none;
            border-radius: .6rem;
            margin-bottom: .25rem
        }

        .sidebar .list-group-item.active {
            background: #e8f7ef;
            color: #0f5132;
            font-weight: 600
        }

        .muted {
            color: #6b7280
        }

        /* ==== Tracking step (đơn hàng & thăng cấp bậc) ==== */
        .step {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #e5e7eb
        }

        .line {
            height: 2px;
            flex: 1;
            background: #e5e7eb
        }

        .step.is-active {
            background: #0d6efd;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, .15)
        }

        .line.is-active {
            background: #0d6efd
        }

        .step.is-canceled {
            background: #dc3545;
            box-shadow: 0 0 0 4px rgba(220, 53, 69, .15)
        }

        .line.is-canceled {
            background: #dc3545
        }

        .rank-img {
            width: 36px;
            height: 36px;
            object-fit: contain
        }
    </style>
</head>

<body class="bg-light d-flex flex-column min-vh-100">
    @include('layouts.header')

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
                                <i class="bi bi-receipt me-2"></i> Ưu đãi cho khách hàng
                            </a>
                            <a class="list-group-item list-group-item-action" data-bs-toggle="list" href="#tab-tracking" role="tab">
                                <i class="bi bi-truck me-2"></i> Theo dõi đơn hàng
                            </a>
                            <a class="list-group-item list-group-item-action" href="{{ route('home') }}">
                                <i class="bi bi-house me-2"></i> Trang Chủ
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
                                {{-- ====== TỔNG TIỀN ĐÃ CHI ====== --}}
                                <div class="card-soft p-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="h6 mb-0">Tổng số tiền bạn đã chi</div>
                                        <span class="badge text-bg-primary">Đã thanh toán</span>
                                    </div>
                                    <div class="display-6 fw-bold mt-2">
                                        {{ number_format((int)($totalSpent ?? 0), 0, ',', '.') }} đ
                                    </div>
                                    <!-- <div class="small text-muted mt-1">
                                        * Chỉ tính các đơn đã thanh toán và không bị hủy.
                                    </div> -->
                                </div>
                                {{-- ====== /TỔNG TIỀN ĐÃ CHI ====== --}}

                                @if($user)
                                {{-- ====== CẤP BẬC THÀNH VIÊN ====== --}}
                                @php
                                $level = $user?->membership_level ?? 'dong';
                                $levels = ['dong','bac','vang','kim_cuong'];
                                $labels = ['Đồng','Bạc','Vàng','Kim cương'];
                                $idx = array_search($level, $levels, true);
                                if ($idx === false) $idx = 0;
                                $imgs = [
                                'dong' => asset('images/ranks/dong.png'),
                                'bac' => asset('images/ranks/bac.png'),
                                'vang' => asset('images/ranks/vang.png'),
                                'kim_cuong' => asset('images/ranks/kim_cuong.png'),
                                ];
                                $badgeClass = match ($level) {
                                'kim_cuong' => 'text-bg-primary',
                                'vang' => 'text-bg-warning text-dark',
                                'bac' => 'text-bg-secondary',
                                default => 'text-bg-light text-dark',
                                };
                                @endphp

                                <div class="card-soft p-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="h6 mb-0">Hạng thành viên của bạn</div>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $imgs[$level] ?? '' }}" class="rank-img"
                                                alt="Hạng {{ $labels[$idx] ?? '' }}"
                                                onerror="this.style.display='none'">
                                            <span class="badge {{ $badgeClass }}">{{ $labels[$idx] ?? '' }}</span>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center gap-2">
                                        @for($i=0; $i<count($levels); $i++)
                                            <div class="step {{ $i <= $idx ? 'is-active' : '' }}">
                                    </div>
                                    @if($i < count($levels)-1)
                                        <div class="line {{ $i < $idx ? 'is-active' : '' }}">
                                </div>
                                @endif
                                @endfor
                            </div>
                            <div class="d-flex justify-content-between small text-muted mt-2">
                                @foreach($labels as $lb) <span>{{ $lb }}</span> @endforeach
                            </div>
                        </div>
                        {{-- ====== /CẤP BẬC THÀNH VIÊN ====== --}}

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

                            <div class="col-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
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

                {{-- ƯU ĐÃI CHO KHÁCH HÀNG --}}
                <div class="tab-pane fade" id="tab-orders" role="tabpanel">
                    <div class="card-soft p-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="bi bi-ticket-perforated text-success fs-5"></i>
                            <div class="h6 mb-0">Ưu đãi cho khách hàng</div>
                        </div>

                        @php
                        $curLevel = $user?->membership_level ?? 'dong';
                        $levelName = ['dong'=>'Đồng','bac'=>'Bạc','vang'=>'Vàng','kim_cuong'=>'Kim cương'][$curLevel] ?? $curLevel;
                        $lvLabel = ['dong'=>'Đồng','bac'=>'Bạc','vang'=>'Vàng','kim_cuong'=>'Kim cương'];
                        @endphp

                        <div class="alert alert-success small">
                            Bạn đang ở hạng <strong>{{ $levelName }}</strong>. Dưới đây là các mã đang hiệu lực dành cho hạng của bạn.
                        </div>

                        @if(isset($coupons) && $coupons->count())
                        <div class="row row-cols-1 row-cols-md-2 g-3">
                            @foreach($coupons as $cp)
                            @php
                            $isShip = ($cp->type ?? null) === 'free_shipping';
                            $isPercent = ($cp->type ?? null) === 'percent';
                            $isFixed = ($cp->type ?? null) === 'fixed';

                            $amountText = $isShip
                            ? 'Miễn phí vận chuyển'
                            : ($isPercent
                            ? rtrim(rtrim(number_format((float)$cp->value, 2, ',', '.'), '0'), ',') . '%'
                            : number_format((int)($cp->value ?? 0), 0, ',', '.') . ' đ');

                            $minTxt = $cp->min_subtotal ? number_format((int)$cp->min_subtotal, 0, ',', '.') . ' đ' : null;
                            $maxTxt = $cp->max_discount ? number_format((int)$cp->max_discount, 0, ',', '.') . ' đ' : null;

                            $scopeTxt = match($cp->ship_scope ?? null) {
                            'province' => 'nội tỉnh',
                            'nationwide' => 'toàn quốc',
                            default => ''
                            };

                            $modalId = 'cpModal_' . ($cp->id ?? $loop->index);
                            @endphp

                            <div class="col">
                                <div class="card border-success-subtle bg-success-subtle h-100">
                                    <div class="card-body d-flex flex-column">
                                        <div class="small text-success-emphasis mb-2">
                                            Mã: <span class="fw-semibold">{{ $cp->code }}</span>
                                            @if(!empty($cp->note))
                                            <i class="bi bi-info-circle ms-1 text-muted"
                                                data-bs-toggle="tooltip"
                                                title="{{ $cp->note }}"></i>
                                            @endif
                                        </div>

                                        <div class="p-3 text-center mb-3 border rounded-3 border-success-subtle bg-white">
                                            <div class="text-uppercase small text-success">Giảm</div>
                                            <div class="fs-2 fw-bold text-success mb-0">{{ $amountText }}</div>
                                        </div>

                                        <div class="small text-success-emphasis flex-grow-1">
                                            @if($isShip)
                                            Freeship {{ $scopeTxt }} @if($cp->min_subtotal) cho đơn từ {{ $minTxt }} @endif
                                            @elseif($isPercent)
                                            Giảm {{ $amountText }}
                                            @if($cp->max_discount) (tối đa {{ $maxTxt }}) @endif
                                            @if($cp->min_subtotal) cho đơn từ {{ $minTxt }} @endif
                                            @else
                                            Giảm {{ $amountText }}
                                            @if($cp->min_subtotal) cho đơn từ {{ $minTxt }} @endif
                                            @endif
                                        </div>

                                        <div class="mt-3 d-flex justify-content-between gap-2">
                                            <button type="button"
                                                class="btn btn-outline-success btn-sm flex-grow-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#{{ $modalId }}">
                                                <i class="bi bi-info-circle me-1"></i> Điều kiện
                                            </button>

                                            <button class="btn btn-success btn-sm flex-grow-1 coupon-copy" data-code="{{ $cp->code }}">
                                                <i class="bi bi-clipboard me-1"></i> Sao chép
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Modal điều kiện --}}
                            <div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h6 class="modal-title">Điều kiện áp dụng — {{ $cp->code }}</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="small text-muted mb-2">Hiệu lực</div>
                                            <div class="mb-3">
                                                {{ $cp->starts_at ? $cp->starts_at->format('d/m/Y H:i') : '—' }} →
                                                {{ $cp->ends_at ? $cp->ends_at->format('d/m/Y H:i') : '—' }}
                                            </div>

                                            @if(!empty($cp->eligible_levels))
                                            <div class="small text-muted mb-2">Hạng áp dụng</div>
                                            <div class="mb-3">
                                                {{ collect($cp->eligible_levels)->map(fn($lv)=>$lvLabel[$lv]??$lv)->implode(', ') }}
                                            </div>
                                            @else
                                            <div class="small text-muted mb-2">Hạng áp dụng</div>
                                            <div class="mb-3">Mọi hạng</div>
                                            @endif

                                            @if($cp->min_subtotal)
                                            <div class="small text-muted mb-2">Giá trị đơn tối thiểu</div>
                                            <div class="mb-3">{{ $minTxt }}</div>
                                            @endif

                                            @if(($cp->type ?? null) === 'percent' && $cp->max_discount)
                                            <div class="small text-muted mb-2">Giảm tối đa</div>
                                            <div class="mb-3">{{ $maxTxt }}</div>
                                            @endif

                                            @if(!empty($cp->note))
                                            <div class="small text-muted mb-2">Ghi chú</div>
                                            <div>{{ $cp->note }}</div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary coupon-copy" data-code="{{ $cp->code }}">
                                                <i class="bi bi-clipboard me-1"></i> Sao chép mã
                                            </button>
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-muted">Hiện chưa có ưu đãi nào dành riêng cho hạng {{ $levelName }}.</div>
                        @endif
                    </div>
                </div>

                {{-- THEO DÕI ĐƠN HÀNG --}}
                <div class="tab-pane fade" id="tab-tracking" role="tabpanel">
                    <div class="card-soft p-4">

                        {{-- Flash messages + danh sách hoàn kho --}}
                        @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        @if(session('restocked') && is_array(session('restocked')))
                        <div class="alert alert-light border mt-2">
                            <div class="fw-semibold mb-1">Đã hoàn kho:</div>
                            <ul class="mb-0">
                                @foreach(session('restocked') as $r)
                                <li>{{ $r['name'] }}: +{{ $r['returned'] }} (tồn hiện tại: {{ $r['now'] }})</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="bi bi-truck text-success fs-5"></i>
                            <div class="h6 mb-0">Theo dõi đơn hàng</div>
                        </div>

                        @php
                        $stageMap = [
                        'da_dat'=>0,'dat_hang'=>0,'da_dat_hang'=>0,'pending'=>0,'placed'=>0,
                        'cho_chuyen_phat'=>1,'da_xuat_kho'=>1,'processing'=>1,'packed'=>1,'shipped'=>1,
                        'dang_trung_chuyen'=>2,'in_transit'=>2,'delivering'=>2,'dang_giao'=>2,
                        'da_giao'=>3,'delivered'=>3,
                        'da_huy'=>-1,'huy'=>-1,'canceled'=>-1,'failed'=>-1,'return'=>-1,
                        ];
                        @endphp

                        @if(isset($orders) && $orders->count())
                        <div class="vstack gap-3">
                            @foreach($orders as $o)
                            @php
                            $st = $o->status ?? 'da_dat';
                            $stage = $stageMap[$st] ?? 0;
                            $isCan = ($stage === -1);

                            $recvAddress = trim(collect([
                            $o->address ?? null,
                            $o->ward_name ?? null,
                            $o->district_name ?? null,
                            $o->province_name ?? null
                            ])->filter()->implode(', '));

                            $orderData = [
                            'id' => $o->id,
                            'code' => $o->code ?? (string)$o->id,
                            'created_at' => optional($o->created_at)->format('d/m/Y H:i'),
                            'status' => $o->status,
                            'payment_status'=> $o->payment_status,
                            'payment_method'=> $o->payment_method,
                            'total' => (int)($o->total ?? 0),
                            'account_name' => $user->name ?? null,
                            'account_email' => $user->email ?? null,
                            'account_phone' => $user->phone ?? null,
                            'recv_name' => $o->fullname ?? null,
                            'recv_phone' => $o->phone ?? null,
                            'recv_email' => $o->email ?? null,
                            'recv_address' => $recvAddress,
                            ];

                            // Điều kiện cho phép hủy: chỉ ở 'da_dat' hoặc 'cho_chuyen_phat' và không phải đơn VNPAY đã thanh toán
                            $canCancel = in_array(($o->status ?? ''), ['da_dat','cho_chuyen_phat'], true)
                            && !(($o->payment_method ?? null) === 'vnpay' && ($o->payment_status ?? null) === 'da_thanh_toan');
                            @endphp

                            <div class="border rounded-3 p-3">
                                <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                                    <div class="fw-semibold">Đơn #{{ $o->code ?? $o->id }}</div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="small text-muted">{{ $o->created_at?->format('d/m/Y H:i') }}</span>

                                        {{-- Xem chi tiết --}}
                                        <button type="button" class="btn btn-sm btn-outline-primary btn-view-order"
                                            data-bs-toggle="modal" data-bs-target="#orderDetailModal"
                                            data-order='@json($orderData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)'>
                                            <i class="bi bi-eye me-1"></i> Chi tiết
                                        </button>

                                        {{-- Hủy đơn (nếu được phép) --}}
                                        @if($canCancel)
                                        <form method="POST" action="{{ route('order.cancel') }}" class="d-inline"
                                            onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn #{{ $o->code ?? $o->id }}?');">
                                            @csrf
                                            <input type="hidden" name="order_code" value="{{ $o->code }}">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-x-circle me-1"></i> Hủy đơn
                                            </button>
                                        </form>
                                        @endif
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
                                    <span>Đã đặt hàng</span><span>Chờ chuyển phát</span><span>Đang trung chuyển</span><span>Đã giao đơn hàng</span>
                                </div>

                                {{-- Nếu đã hủy, hiện thời điểm hủy nếu có --}}
                                @if(($o->status ?? '') === 'da_huy')
                                <div class="small text-danger mt-2">
                                    Đã hủy
                                    @if(!empty($o->canceled_at))
                                    lúc {{ \Carbon\Carbon::parse($o->canceled_at)->format('d/m/Y H:i') }}
                                    @endif
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>

                        {{-- Phân trang --}}
                        <div class="mt-3">
                            {{ $orders->links('pagination::bootstrap-5') }}
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
                    @csrf @method('PATCH')
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

    {{-- Modal chi tiết đơn hàng --}}
    <div class="modal fade" id="orderDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" style="--bs-modal-margin: 10rem auto;">
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

    {{-- Copy mã (handler cũ cho .btn-copy-code và .coupon-copy) --}}
    <script>
        (function() {
            // Tooltip
            const selector = '[data-bs-toggle="tooltip"]';
            const initTooltip = () => {
                document.querySelectorAll(selector).forEach(el => new bootstrap.Tooltip(el));
            };
            document.addEventListener('DOMContentLoaded', initTooltip);
            document.addEventListener('shown.bs.modal', initTooltip);

            // Copy mã
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.coupon-copy, .btn-copy-code');
                if (!btn) return;
                const code = btn.dataset.code || '';
                if (!code) return;

                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(code).then(() => {
                        const old = btn.innerHTML;
                        btn.innerHTML = '<i class="bi bi-check2 me-1"></i> Đã sao chép';
                        btn.classList.add('btn-success');
                        btn.classList.remove('btn-outline-success');

                        setTimeout(() => {
                            btn.innerHTML = old || '<i class="bi bi-clipboard me-1"></i> Sao chép';
                            // giữ màu nếu là nút trong card
                            if (!old || !old.includes('Điều kiện')) {
                                btn.classList.add('btn-success');
                            }
                        }, 1400);
                    });
                }
            });
        })();
    </script>

    {{-- Xem chi tiết đơn --}}
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
            const fmtMoney = v => new Intl.NumberFormat('vi-VN').format(Number(v || 0)) + ' đ';
            const detailBase = @json(url('/orders'));

            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.btn-view-order');
                if (!btn) return;
                let od = {};
                try {
                    od = JSON.parse(btn.dataset.order || '{}')
                } catch {}

                document.getElementById('odCode').textContent = '#' + (od.code || od.id || '—');
                document.getElementById('odCreated').textContent = od.created_at || '—';
                document.getElementById('odAccName').textContent = od.account_name || '—';
                document.getElementById('odAccEmail').textContent = od.account_email || '—';
                document.getElementById('odAccPhone').textContent = od.account_phone || '—';
                document.getElementById('odRecvName').textContent = od.recv_name || '—';
                document.getElementById('odRecvPhone').textContent = od.recv_phone || '—';
                document.getElementById('odRecvEmail').textContent = od.recv_email || '—';
                document.getElementById('odRecvAddress').textContent = od.recv_address || '—';
                document.getElementById('odStatus').textContent = STATES[od.status] || od.status || '—';
                document.getElementById('odPayState').textContent = PAY_STATES[od.payment_status] || od.payment_status || '—';
                document.getElementById('odPayMethod').textContent = 'Phương thức: ' + (PAY_METHODS[od.payment_method] || od.payment_method || '—');
                document.getElementById('odTotal').textContent = fmtMoney(od.total);

                const a = document.getElementById('odDetailLink');
                if (od.id) a.href = detailBase.replace(/\/$/, '') + '/' + od.id;
                else a.removeAttribute('href');
            });
        })();
    </script>

    {{-- Kích hoạt tab theo query ?tab=... (ví dụ ?tab=tracking sau khi hủy) --}}
    <script>
        (function() {
            const params = new URLSearchParams(location.search);
            const tab = params.get('tab');
            if (!tab) return;
            const el = document.querySelector(`[data-bs-toggle="list"][href="#tab-${tab}"]`);
            if (el) {
                const t = new bootstrap.Tab(el);
                t.show();
            }
        })();
    </script>
</body>

</html>