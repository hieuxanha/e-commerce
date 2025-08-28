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
    </style>
</head>

<body class="bg-light">
    @include('layouts.header')

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
                            </div>
                            @else
                            <div class="text-danger">Bạn chưa đăng nhập.</div>
                            @endif
                        </div>
                    </div>

                    {{-- Đổi mật khẩu (tĩnh) --}}
                    <div class="tab-pane fade" id="tab-password" role="tabpanel">
                        <div class="card-soft p-4">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <i class="bi bi-shield-lock text-success fs-5"></i>
                                <div class="h6 mb-0">Đổi mật khẩu</div>
                            </div>
                            <div class="alert alert-info mb-3">Tính năng đang được phát triển.</div>

                            <form class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Mật khẩu hiện tại</label>
                                    <input type="password" class="form-control" placeholder="••••••••" disabled>
                                </div>
                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                    <label class="form-label">Mật khẩu mới</label>
                                    <input type="password" class="form-control" placeholder="••••••••" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nhập lại mật khẩu mới</label>
                                    <input type="password" class="form-control" placeholder="••••••••" disabled>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-success" disabled>Cập nhật mật khẩu</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Đơn hàng (tĩnh với 3 danh mục) --}}
                    <div class="tab-pane fade" id="tab-orders" role="tabpanel">
                        <div class="card-soft p-4">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <i class="bi bi-receipt text-success fs-5"></i>
                                <div class="h6 mb-0">Đơn hàng của tôi</div>
                            </div>

                            <ul class="nav nav-pills mb-3" role="tablist">
                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#od-pending" type="button" role="tab">
                                        Chờ giao hàng
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#od-delivered" type="button" role="tab">
                                        Đã giao
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#od-canceled" type="button" role="tab">
                                        Đã hủy
                                    </button>
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

                </div>{{-- /tab-content --}}
            </div>
        </div>
    </div>

    @include('layouts.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>