{{-- resources/views/nhanvien/Ql_danhmuc.blade.php --}}
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Quản lý danh mục</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/nhanvien/QL_sanpham.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nhanvien/nhanvien.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .card-x {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <div class="app">
        @include('nhanvien.sidebar-nhanvien')

        <main>
            <!-- Topbar -->
            <div class="top">
                <form class="top-search" action="{{ route('nhanvien.categories.index') }}" method="GET" role="search">
                    <input type="text" name="q" class="top-search-input" placeholder="Tìm danh mục..." value="{{ $q ?? '' }}" autocomplete="off" />
                    <button class="top-search-btn" aria-label="Tìm kiếm">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7"></circle>
                            <line x1="16.65" y1="16.65" x2="21" y2="21"></line>
                        </svg>
                    </button>
                </form>
            </div>

            <div class="container-fluid py-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="m-0">Danh sách danh mục</h3>
                    <a class="btn btn-outline-secondary btn-sm" href="{{ route('nhanvien.categories.index') }}">Làm mới danh sách</a>
                </div>

                {{-- Flash --}}
                @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
                @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
                @endif

                <div class="row g-3">
                    <div class="col-12">
                        <div class="card-x p-3">
                            @if($categories->count() === 0)
                            <div class="alert alert-light border m-0">Chưa có danh mục nào.</div>
                            @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tên danh mục</th>
                                            <th>Mô tả</th>
                                            <th style="width:160px">Tạo lúc</th>
                                            <th style="width:160px">Cập nhật</th>
                                            <th class="text-center" style="width:160px">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($categories as $c)
                                        <tr>
                                            <td class="fw-semibold">{{ $c->ten_danh_muc }}</td>
                                            <td class="text-muted">
                                                <div class="text-truncate" style="max-width:420px">{{ $c->mo_ta }}</div>
                                            </td>
                                            <td>{{ optional($c->created_at)->format('d/m/Y H:i') }}</td>
                                            <td>{{ optional($c->updated_at)->format('d/m/Y H:i') }}</td>
                                            <td class="text-center">
                                                {{-- Sửa (mở modal) --}}
                                                <button class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editCategoryModal{{ $c->id }}">
                                                    Sửa
                                                </button>

                                                {{-- Xoá --}}
                                                <form action="{{ route('nhanvien.categories.destroy', $c) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Xoá danh mục này?');">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger">Xoá</button>
                                                </form>
                                            </td>
                                        </tr>

                                        {{-- Modal Sửa danh mục --}}
                                        <div class="modal fade" id="editCategoryModal{{ $c->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Sửa danh mục</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('nhanvien.categories.update', $c) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                                                                <input type="text" name="ten_danh_muc" class="form-control" required value="{{ $c->ten_danh_muc }}">
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Danh mục cha (tuỳ chọn)</label>
                                                                <select name="danh_muc_cha_id" class="form-select">
                                                                    <option value="">— Không chọn —</option>
                                                                    @foreach($categories as $opt)
                                                                    @if($opt->id !== $c->id)
                                                                    <option value="{{ $opt->id }}" {{ $c->danh_muc_cha_id == $opt->id ? 'selected' : '' }}>
                                                                        {{ $opt->ten_danh_muc }}
                                                                    </option>
                                                                    @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Mô tả</label>
                                                                <textarea name="mo_ta" class="form-control" rows="3">{{ $c->mo_ta }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Huỷ</button>
                                                            <button class="btn btn-primary">Lưu thay đổi</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                {{ $categories->links() }}
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Thêm nhanh danh mục (tuỳ chọn, bật lại nếu cần) --}}
                    {{--
                <div class="col-12">
                    <div class="card-x p-3">
                        <h5 class="mb-3">Thêm danh mục</h5>
                        <form action="{{ route('nhanvien.categories.store') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-md-4">
                        <label class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" name="ten_danh_muc" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Danh mục cha</label>
                        <select name="danh_muc_cha_id" class="form-select">
                            <option value="">— Không chọn —</option>
                            @foreach($categories as $opt)
                            <option value="{{ $opt->id }}">{{ $opt->ten_danh_muc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Mô tả</label>
                        <input type="text" name="mo_ta" class="form-control">
                    </div>
                    <div class="col-12">
                        <button class="btn btn-success">Lưu danh mục</button>
                    </div>
                    </form>
                </div>
            </div>
            --}}
    </div>
    </div>
    </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>