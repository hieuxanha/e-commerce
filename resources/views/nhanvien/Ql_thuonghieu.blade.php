{{-- resources/views/nhanvien/Ql_thuonghieu.blade.php --}}
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Quản lý thương hiệu</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/nhanvien/QL_sanpham.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/nhanvien/nhanvien.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .brand-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
        }

        .brand-logo {
            width: 56px;
            height: 56px;
            object-fit: contain;
            border: 1px solid #eee;
            border-radius: 8px;
            background: #fff;
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
                <form class="top-search" action="{{ route('nhanvien.brands.index') }}" method="GET" role="search">
                    <input
                        type="text"
                        name="q"
                        class="top-search-input"
                        placeholder="Tìm thương hiệu..."
                        value="{{ $q ?? '' }}"
                        autocomplete="off" />
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
                    <h3 class="m-0">Danh sách thương hiệu</h3>
                    <a class="btn btn-outline-secondary btn-sm" href="{{ route('nhanvien.brands.index') }}">
                        Làm mới danh sách
                    </a>
                </div>

                {{-- Thông báo --}}
                @if(session('ok'))
                <div class="alert alert-success">{{ session('ok') }}</div>
                @endif
                @if ($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="m-0 ps-3">
                        @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="row g-3">
                    <div class="col-12">
                        <div class="brand-card p-3">
                            @if($brands->count() === 0)
                            <div class="alert alert-light border m-0">Chưa có thương hiệu nào.</div>
                            @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center" style="width:68px">Logo</th>
                                            <th>Tên thương hiệu</th>
                                            <th>Mô tả</th>
                                            <th style="width:160px">Tạo lúc</th>
                                            <th style="width:160px">Cập nhật</th>
                                            <th class="text-center" style="width:160px">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($brands as $b)
                                        @php
                                        $logo = $b->logo_url
                                        ? (preg_match('/^https?:\/\//', $b->logo_url) ? $b->logo_url : asset('storage/'.$b->logo_url))
                                        : asset('img/placeholder-brand.png');
                                        @endphp
                                        <tr>
                                            <td class="text-center">
                                                <img class="brand-logo" src="{{ $logo }}" alt="{{ $b->ten_thuong_hieu }}">
                                            </td>
                                            <td class="fw-semibold">{{ $b->ten_thuong_hieu }}</td>
                                            <td class="text-muted">
                                                <div class="text-truncate" style="max-width:420px">
                                                    {{ $b->mo_ta }}
                                                </div>
                                            </td>
                                            <td>{{ optional($b->created_at)->format('d/m/Y H:i') }}</td>
                                            <td>{{ optional($b->updated_at)->format('d/m/Y H:i') }}</td>
                                            <td class="text-center">
                                                {{-- Sửa --}}
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-outline-secondary btn-edit-brand"
                                                    data-id="{{ $b->id }}"
                                                    data-name="{{ $b->ten_thuong_hieu }}"
                                                    data-logo="{{ $b->logo_url }}"
                                                    data-desc="{{ $b->mo_ta }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#brandEditModal">
                                                    Sửa
                                                </button>

                                                {{-- Xoá --}}
                                                <form action="{{ route('nhanvien.brands.destroy', $b) }}"
                                                    method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Xoá thương hiệu này?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger">Xoá</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                {{ $brands->links() }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div> <!-- /row -->
            </div> <!-- /container-fluid -->
        </main>

        <!-- Modal sửa thương hiệu -->
        <div class="modal fade" id="brandEditModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" id="brandEditForm" class="modal-content">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Sửa thương hiệu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Tên thương hiệu <span class="text-danger">*</span></label>
                            <input type="text" name="ten_thuong_hieu" id="editBrandName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Logo URL</label>
                            <input type="text" name="logo_url" id="editBrandLogo" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <textarea name="mo_ta" id="editBrandDesc" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Lưu thay đổi</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function() {
            // URL mẫu để update (thay __ID__ bằng id thực tế)
            const updateUrlTpl = "{{ route('nhanvien.brands.update', ['brand' => '__ID__']) }}";

            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.btn-edit-brand');
                if (!btn) return;

                const id = btn.dataset.id;
                const name = btn.dataset.name || '';
                const logo = btn.dataset.logo || '';
                const desc = btn.dataset.desc || '';

                document.getElementById('editBrandName').value = name;
                document.getElementById('editBrandLogo').value = logo;
                document.getElementById('editBrandDesc').value = desc;

                const form = document.getElementById('brandEditForm');
                form.action = updateUrlTpl.replace('__ID__', id);
            });
        })();
    </script>
</body>

</html>