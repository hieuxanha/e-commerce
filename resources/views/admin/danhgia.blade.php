{{-- resources/views/admin/danhgia.blade.php --}}
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Quản lý đánh giá</title>
    <link rel="stylesheet" href="{{ asset('css/admin/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/nhanvien/timkiem.css') }}" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="app">
        <!-- Sidebar -->
        @include('admin.sidebar-admin')

        <!-- Main -->
        <main>
            <div class="top">
                <form class="top-search" action="#" method="GET" role="search">
                    <input type="text" name="q" class="top-search-input"
                        placeholder="Tìm sản phẩm, danh mục, thương hiệu..." autocomplete="off" />
                    <button class="top-search-btn" aria-label="Tìm kiếm">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7"></circle>
                            <line x1="16.65" y1="16.65" x2="21" y2="21"></line>
                        </svg>
                    </button>
                </form>
            </div>



            <div class="content">
                {{-- Flash --}}
                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0">
                        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
                @endif

                {{-- Tabs + Bộ lọc --}}
                <div class="card mb-3">
                    <div class="tabs">
                        <a class="tab {{ $status==='all'||!$status ? 'active' : '' }}"
                            href="{{ route('admin.reviews.index', array_merge(['status'=>'all'], request()->except('page'))) }}">
                            Tất cả <span class="badge bg-secondary ms-2">{{ $countAll ?? 0 }}</span>
                        </a>
                        <a class="tab {{ $status==='pending' ? 'active' : '' }}"
                            href="{{ route('admin.reviews.index', array_merge(['status'=>'pending'], request()->except('page'))) }}">
                            Chờ duyệt <span class="badge bg-warning text-dark ms-2">{{ $countPending ?? 0 }}</span>
                        </a>
                        <a class="tab {{ $status==='approved' ? 'active' : '' }}"
                            href="{{ route('admin.reviews.index', array_merge(['status'=>'approved'], request()->except('page'))) }}">
                            Đã duyệt <span class="badge bg-success ms-2">{{ $countApproved ?? 0 }}</span>
                        </a>
                    </div>

                    <form class="row g-2 align-items-end p-3 pt-0" method="get">
                        <div class="col-md-3">
                            <label class="form-label">Lọc theo User ID</label>
                            <input type="number" name="user_id" class="form-control" value="{{ $userId }}">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Tìm kiếm</label>
                            <input type="text" name="kw" class="form-control" value="{{ $keyword }}" placeholder="Sản phẩm, người dùng, nội dung...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="all" {{ $status==='all'||!$status ? 'selected' : '' }}>Tất cả</option>
                                <option value="pending" {{ $status==='pending' ? 'selected' : '' }}>Chờ duyệt</option>
                                <option value="approved" {{ $status==='approved' ? 'selected' : '' }}>Đã duyệt</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100"><i class="bi bi-search"></i> Lọc</button>
                        </div>
                    </form>
                </div>

                {{-- Bảng --}}
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Thời gian</th>
                                    <th>Sản phẩm</th>
                                    <th>Người đánh giá</th>
                                    <th class="text-center">Sao</th>
                                    <th>Nội dung</th>
                                    <th class="text-center">Trạng thái</th>
                                    <th class="text-end">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reviews as $rv)
                                <tr>
                                    <td style="white-space:nowrap">{{ $rv->created_at?->format('d/m/Y H:i') }}</td>
                                    <td>
                                        {{ $rv->product->ten_san_pham ?? 'N/A' }}
                                        <div class="small">
                                            <a href="{{ route('sanpham.chitiet.id', ['id'=>$rv->product_id]) }}" target="_blank">Xem sản phẩm</a>
                                        </div>
                                    </td>
                                    <td>
                                        <div>{{ $rv->user->name ?? 'User#'.$rv->user_id }}</div>
                                        <div class="text-muted small">{{ $rv->user->email ?? '' }}</div>
                                    </td>
                                    <td class="text-center">
                                        @for($i=1;$i<=5;$i++)
                                            <i class="bi {{ $i <= (int)$rv->rating ? 'bi-star-fill' : 'bi-star' }} text-warning"></i>
                                            @endfor
                                            <div class="small text-muted">{{ $rv->rating }}/5</div>
                                    </td>
                                    <td style="max-width:420px">
                                        @if($rv->comment)
                                        <div class="text-truncate" style="-webkit-line-clamp:3;display:-webkit-box;-webkit-box-orient:vertical;overflow:hidden;">
                                            {{ $rv->comment }}
                                        </div>
                                        @else
                                        <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($rv->approved)
                                        @if($rv->hidden)
                                        <span class="badge text-bg-secondary">Đã duyệt (Ẩn)</span>
                                        @else
                                        <span class="badge text-bg-success">Đã duyệt (Hiển thị)</span>
                                        @endif
                                        @else
                                        <span class="badge text-bg-warning">Chờ duyệt</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        {{-- DUYỆT --}}
                                        <form action="{{ route('admin.reviews.approve', $rv) }}" method="post" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-success"
                                                title="Hiển thị đánh giá trên trang khách"
                                                {{ $rv->approved && !$rv->hidden ? 'disabled' : '' }}>
                                                <i class="bi bi-check2-circle"></i> Duyệt
                                            </button>
                                        </form>

                                        {{-- ẨN --}}
                                        <form action="{{ route('admin.reviews.hide', $rv) }}" method="post" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-secondary"
                                                title="Ẩn đánh giá khỏi trang khách"
                                                {{ !$rv->approved || $rv->hidden ? 'disabled' : '' }}>
                                                <i class="bi bi-eye-slash"></i> Ẩn
                                            </button>
                                        </form>

                                        {{-- XÓA --}}
                                        <form action="{{ route('admin.reviews.destroy', $rv) }}" method="post" class="d-inline"
                                            onsubmit="return confirm('Xoá đánh giá này?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i> Xoá
                                            </button>
                                        </form>

                                        {{-- PHẢN HỒI --}}
                                        <form action="{{ route('admin.reviews.reply', $rv) }}" method="post" class="mt-2">
                                            @csrf
                                            <div class="input-group input-group-sm">
                                                <input type="text" name="content" class="form-control" placeholder="Nhập phản hồi..." required>
                                                <button class="btn btn-outline-primary"><i class="bi bi-send"></i></button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Không có đánh giá nào.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="p-3">
                        {{ $reviews->links() }}
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>