<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý sản phẩm</title>

    {{-- CSS chung --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nhanvien/nhanvien.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nhanvien/QL_sanpham.css') }}" />



    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Optional: Bootstrap JS (nếu cần modal, dropdown, v.v.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>
    <div class="app">
        @include('nhanvien.sidebar-nhanvien')
        <!-- Main -->
        <main>
            <!-- Topbar -->
            <div class="top">
                <form class="top-search" action="#" method="GET" role="search">
                    <input type="text" name="q" class="top-search-input" placeholder="Tìm sản phẩm, danh mục, thương hiệu..." autocomplete="off" />
                    <button class="top-search-btn" aria-label="Tìm kiếm">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7"></circle>
                            <line x1="16.65" y1="16.65" x2="21" y2="21"></line>
                        </svg>
                    </button>
                </form>
            </div>

            <div class="container">
                <h3>Sửa sản phẩm</h3>

                <form action="{{ route('nhanvien.sanpham.update', $product->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Tên sản phẩm</label>
                        <input type="text" name="ten_san_pham" class="form-control"
                            value="{{ old('ten_san_pham', $product->ten_san_pham) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">SKU</label>
                        <input type="text" name="sku" class="form-control"
                            value="{{ old('sku', $product->sku) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Giá</label>
                        <input type="number" name="gia" class="form-control"
                            value="{{ old('gia', $product->gia) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Giá khuyến mãi</label>
                        <input type="number" name="gia_khuyen_mai" class="form-control"
                            value="{{ old('gia_khuyen_mai', $product->gia_khuyen_mai) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tồn kho</label>
                        <input type="number" name="so_luong_ton_kho" class="form-control"
                            value="{{ old('so_luong_ton_kho', $product->so_luong_ton_kho) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="trang_thai" class="form-control">
                            <option value="Còn hàng" {{ $product->trang_thai == 'Còn hàng' ? 'selected' : '' }}>Còn hàng</option>
                            <option value="Hết hàng" {{ $product->trang_thai == 'Hết hàng' ? 'selected' : '' }}>Hết hàng</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">Lưu thay đổi</button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Hủy</a>
                </form>
            </div>

            <!-- Danh sách sản phẩm -->

        </main>
    </div>
</body>

</html>