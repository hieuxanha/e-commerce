<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Quản lý khách hàng</title>

    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/nhanvien/timkiem.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        .table td,
        .table th {
            vertical-align: middle
        }

        .addr {
            max-width: 340px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .badge-role {
            font-size: .85rem
        }

        .kpi {
            border-radius: 14px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .08)
        }
    </style>
</head>

<body>
    <div class="app">
        @include('admin.sidebar-admin')

        <main>
            {{-- Top search --}}
            <div class="top">
                <form class="top-search" action="{{ route('admin.QL_khachhang.index') }}" method="GET" role="search">
                    <input type="text" name="q" class="top-search-input" placeholder="Tìm theo tên / email / SĐT..."
                        value="{{ $q }}" autocomplete="off">
                    <button class="top-search-btn ms-2" aria-label="Tìm kiếm">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7"></circle>
                            <line x1="16.65" y1="16.65" x2="21" y2="21"></line>
                        </svg>
                    </button>
                </form>
            </div>

            <div class="content p-3">
                {{-- KPIs --}}
                <div class="row g-3 mb-2">
                    <div class="col-6 col-md-3">
                        <div class="kpi p-3 bg-white">
                            <div class="text-muted small">Tổng người dùng</div>
                            <div class="fs-3 fw-bold">{{ number_format($stats['total']) }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="kpi p-3 bg-white">
                            <div class="text-muted small">Khách hàng</div>
                            <div class="fs-3 fw-bold">{{ number_format($stats['customers']) }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="kpi p-3 bg-white">
                            <div class="text-muted small">Nhân viên</div>
                            <div class="fs-3 fw-bold">{{ number_format($stats['staff']) }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="kpi p-3 bg-white">
                            <div class="text-muted small">Admin</div>
                            <div class="fs-3 fw-bold">{{ number_format($stats['admins']) }}</div>
                        </div>
                    </div>
                </div>

                {{-- Table --}}
                <div class="card">
                    <div class="card-header fw-semibold">Danh sách người dùng</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:70px">STT</th>
                                        <th>Tên</th>
                                        <th>Email / SĐT</th>
                                        <th>Giới tính</th>
                                        <th>Vai trò</th>
                                        <th>Hạng</th> {{-- THÊM CỘT HẠNG --}}
                                        <th>Địa chỉ</th>
                                        <th class="text-end">SL đơn</th>
                                        <th class="text-end">Đã Từng chi</th>
                                        <th class="text-end" style="width:90px">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $u)
                                    @php
                                    $badgeRole = ['khach_hang'=>'secondary','nhan_vien'=>'info','admin'=>'danger'][$u->role] ?? 'secondary';
                                    $genderTxt = $u->gender === 'nam' ? 'Nam' : ($u->gender === 'nu' ? 'Nữ' : '—');

                                    // thống kê (fallback 0 nếu chưa withCount/withSum)
                                    $ordersCount = (int)($u->orders_count ?? 0);
                                    $spentTotal = (int)($u->orders_sum_total ?? 0);

                                    // ---- HẠNG THÀNH VIÊN ----
                                    // Ưu tiên cột membership_level; nếu rỗng thì tính từ tổng đã chi theo logic của Model
                                    $levelKey = $u->membership_level ?: \App\Models\User::levelByTotal($spentTotal);
                                    $levelLabels = ['dong'=>'Đồng','bac'=>'Bạc','vang'=>'Vàng','kim_cuong'=>'Kim cương'];
                                    $levelBadges = ['dong'=>'secondary','bac'=>'info','vang'=>'warning','kim_cuong'=>'primary'];
                                    $levelLabel = $levelLabels[$levelKey] ?? '—';
                                    $levelBadge = $levelBadges[$levelKey] ?? 'secondary';
                                    @endphp
                                    <tr>
                                        <td>{{ ($users->currentPage()-1)*$users->perPage() + $loop->iteration }}</td>

                                        <td class="fw-semibold">{{ $u->name ?? '—' }}</td>

                                        <td>
                                            <div>{{ $u->email }}</div>
                                            <div class="text-muted small">{{ $u->phone }}</div>
                                        </td>

                                        <td>{{ $genderTxt }}</td>

                                        <td>
                                            <span class="badge bg-{{ $badgeRole }} badge-role">
                                                {{ str_replace('_',' ', $u->role) }}
                                            </span>
                                        </td>

                                        {{-- Hạng thành viên --}}
                                        <td>
                                            <span class="badge bg-{{ $levelBadge }}">{{ $levelLabel }}</span>
                                        </td>

                                        <td class="addr" title="{{ $u->address }}">{{ $u->address }}</td>

                                        {{-- SL đơn & Đã chi --}}
                                        <td class="text-end">{{ number_format($ordersCount) }}</td>
                                        <td class="text-end">{{ number_format($spentTotal, 0, ',', '.') }}đ</td>

                                        {{-- Thao tác --}}
                                        <td class="text-end">
                                            @if($ordersCount > 0)
                                            <button class="btn btn-sm btn-outline-secondary" disabled
                                                title="Khách hàng đã có đơn, không thể xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            @else
                                            <form action="{{ route('admin.QL_khachhang.destroy', $u) }}" method="POST"
                                                onsubmit="return confirm('Xóa khách hàng này? Hành động không thể hoàn tác!')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    {{-- cập nhật colspan cho đúng tổng số cột (10) --}}
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-4">Không có người dùng phù hợp.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer">
                        {{ $users->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>