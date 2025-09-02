{{-- resources/views/admin/phanquyen/index.blade.php --}}
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Phân quyền người dùng</title>
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/nhanvien/timkiem.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table td,
        .table th {
            vertical-align: middle
        }

        .role-badge {
            font-size: .85rem
        }
    </style>
</head>

<body>
    <div class="app">
        @include('admin.sidebar-admin')

        <main>
            <div class="top">
                <form class="top-search" action="{{ route('admin.phanquyen.index') }}" method="GET" role="search">
                    <input type="text" name="q" class="top-search-input" placeholder="Tìm theo tên, email, SĐT..." value="{{ $q }}" autocomplete="off" />
                    <select name="role" class="form-select ms-2" style="max-width:180px">
                        <option value="">-- Tất cả vai trò --</option>
                        <option value="khach_hang" @selected($role==='khach_hang' )>Khách hàng</option>
                        <option value="nhan_vien" @selected($role==='nhan_vien' )>Nhân viên</option>
                        <option value="admin" @selected($role==='admin' )>Admin</option>
                    </select>
                    <button class="top-search-btn ms-2" aria-label="Tìm kiếm">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7"></circle>
                            <line x1="16.65" y1="16.65" x2="21" y2="21"></line>
                        </svg>
                    </button>
                </form>
            </div>

            <div class="content p-3">
                @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
                @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Danh sách người dùng</h5>
                        <span class="text-muted">Hiển thị {{ $users->count() }} / {{ $users->total() }}</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:70px">STT</th>
                                        <th>Tên</th>
                                        <th>Email</th>
                                        <th>SĐT</th>
                                        <th>Giới tính</th>
                                        <th>Ngày sinh</th>
                                        <th>Địa chỉ</th>
                                        <th>Vai trò</th>
                                        <th style="width:240px">Đổi vai trò</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $u)
                                    <tr>
                                        <!-- <td>{{ $u->id ?? '—' }}</td> -->

                                        <td>
                                            {{-- STT liên tục theo phân trang --}}
                                            {{ $users->firstItem() + $loop->index }}
                                        </td>
                                        <td>{{ $u->name ?? '—' }}</td>
                                        <td>{{ $u->email }}</td>
                                        <td>{{ $u->phone }}</td>
                                        <td class="text-capitalize">{{ $u->gender ?? '—' }}</td>
                                        <td>{{ optional($u->dob)->format('d/m/Y') ?? '—' }}</td>
                                        <td>{{ $u->address }}</td>
                                        <td>
                                            @php
                                            $badge = match($u->role){
                                            'admin' => 'bg-danger',
                                            'nhan_vien' => 'bg-primary',
                                            default => 'bg-secondary'
                                            };
                                            @endphp
                                            <span class="badge {{ $badge }} role-badge">{{ $u->role }}</span>
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.phanquyen.updateRole', $u) }}" method="POST" class="d-flex gap-2">
                                                @csrf @method('PATCH')
                                                <select name="role" class="form-select">
                                                    <option value="khach_hang" @selected($u->role==='khach_hang')>Khách hàng</option>
                                                    <option value="nhan_vien" @selected($u->role==='nhan_vien')>Nhân viên</option>
                                                    <option value="admin" @selected($u->role==='admin')>Admin</option>
                                                </select>
                                                <button class="btn btn-sm btn-success">
                                                    <i class="bi bi-check2-circle"></i> Lưu
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">Không có dữ liệu phù hợp.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>