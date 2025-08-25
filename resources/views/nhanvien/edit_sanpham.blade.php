<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý sản phẩm</title>

    <link rel="stylesheet" href="{{ asset('css/nhanvien/nhanvien.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nhanvien/timkiem.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nhanvien/edit_sanpham.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="app">
        @include('nhanvien.sidebar-nhanvien')

        <main>
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

            <div class="container my-4">
                <h3 class="mb-3">Sửa sản phẩm</h3>

                {{-- Hiển thị lỗi --}}
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Thông báo --}}
                @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('nhanvien.sanpham.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                                <input type="text" name="ten_san_pham" class="form-control"
                                    value="{{ old('ten_san_pham', $product->ten_san_pham) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">SKU <span class="text-danger">*</span></label>
                                <input type="text" name="sku" class="form-control"
                                    value="{{ old('sku', $product->sku) }}" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Giá (VND) <span class="text-danger">*</span></label>
                                    <input type="number" name="gia" class="form-control" min="0" step="1000"
                                        value="{{ old('gia', $product->gia) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Giá khuyến mãi</label>
                                    <input type="number" name="gia_khuyen_mai" class="form-control" min="0" step="1000"
                                        value="{{ old('gia_khuyen_mai', $product->gia_khuyen_mai) }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tồn kho <span class="text-danger">*</span></label>
                                    <input type="number" name="so_luong_ton_kho" class="form-control" min="0" step="1"
                                        value="{{ old('so_luong_ton_kho', $product->so_luong_ton_kho) }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Trạng thái</label>
                                    {{-- giá trị phải đúng enum controller: con_hang/het_hang/sap_ve/an --}}
                                    <select name="trang_thai" class="form-select">
                                        @php
                                        $status = old('trang_thai', $product->trang_thai);
                                        @endphp
                                        <option value="con_hang" {{ $status === 'con_hang' ? 'selected' : '' }}>Còn hàng</option>
                                        <option value="het_hang" {{ $status === 'het_hang' ? 'selected' : '' }}>Hết hàng</option>
                                        <option value="sap_ve" {{ $status === 'sap_ve'   ? 'selected' : '' }}>Sắp về</option>
                                        <option value="an" {{ $status === 'an'       ? 'selected' : '' }}>Ẩn</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-select" required>
                                        @foreach($categories as $c)
                                        <option value="{{ $c->id }}" @selected(old('category_id', $product->category_id) == $c->id)>
                                            {{ $c->ten_danh_muc }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Thương hiệu <span class="text-danger">*</span></label>
                                    <select name="brand_id" class="form-select" required>
                                        @foreach($brands as $b)
                                        <option value="{{ $b->id }}" @selected(old('brand_id', $product->brand_id) == $b->id)>
                                            {{ $b->ten_thuong_hieu }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mô tả ngắn</label>
                                <textarea name="mo_ta_ngan" class="form-control" rows="2" maxlength="500">{{ old('mo_ta_ngan', $product->mo_ta_ngan) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mô tả chi tiết</label>
                                <textarea name="mo_ta_chi_tiet" class="form-control" rows="5">{{ old('mo_ta_chi_tiet', $product->mo_ta_chi_tiet) }}</textarea>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-success">Lưu thay đổi</button>
                                <a href="{{ route('nhanvien.danhsachsanpham') }}" class="btn btn-secondary">Hủy</a>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- <label class="form-label">Hình ảnh hiện tại</label>
                            <div class="ratio ratio-3x4 border rounded overflow-hidden mb-2">
                                <img id="pd-image"
                                    src="{{ $product->hinh_anh_chinh ? asset('storage/'.$product->hinh_anh_chinh) : asset('img/placeholder-product.jpg') }}"
                                    alt="Ảnh sản phẩm" class="w-100 h-100" style="object-fit:cover;">
                            </div> -->

                            <div class="mb-2">
                                <img
                                    id="pd-image"
                                    src="{{ $product->hinh_anh_chinh
            ? asset('storage/'.$product->hinh_anh_chinh)
            : asset('img/placeholder-product.jpg') }}"
                                    alt="Ảnh sản phẩm"
                                    style="display:block;width:180px;height:240px;object-fit:cover;border:1px solid #dee2e6;border-radius:.5rem;">
                            </div>


                            <div class="mb-3">
                                <label class="form-label">Đổi hình ảnh</label>
                                <input type="file" name="hinh_anh_chinh" class="form-control" accept="image/*" onchange="previewImage(event)">
                                <div class="form-text">Tối đa 4MB. Nếu không chọn, ảnh cũ sẽ được giữ nguyên.</div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        function previewImage(e) {
            const file = e.target.files?.[0];
            if (!file) return;
            const url = URL.createObjectURL(file);
            const img = document.getElementById('pd-image');
            img.src = url;
            img.onload = () => URL.revokeObjectURL(url);
        }
    </script>
</body>

</html>