{{-- resources/views/admin/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard Nhân Viên</title>

    {{-- CSS khu vực nhân viên --}}
    <link rel="stylesheet" href="{{ asset('css/nhanvien/QL_sanpham.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/nhanvien/nhanvien.css') }}" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap JS (modal, dropdown, ...) --}}
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

            <!-- Danh sách sản phẩm -->
            <div class="ds_sp">
                <h4 class="mb-3">Danh Sách Sản Phẩm</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th>STT</th>
                                <th class="text-start">Tên Sản Phẩm</th>
                                <th>SKU</th>
                                <th>Giá</th>
                                <th>KM</th>
                                {{-- <th>Tồn Kho</th>
                            <th>Trạng Thái</th> --}}
                                <th>Ảnh</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $index => $sp)
                            <tr class="text-center">
                                <td>{{ $index + 1 }}</td>
                                <td class="text-start">{{ $sp->ten_san_pham }}</td>
                                <td>{{ $sp->sku }}</td>
                                <td>{{ number_format($sp->gia, 0, ',', '.') }}đ</td>
                                <td>
                                    @if($sp->gia_khuyen_mai)
                                    {{ number_format($sp->gia_khuyen_mai, 0, ',', '.') }}đ
                                    @else
                                    -
                                    @endif
                                </td>
                                {{-- <td>{{ $sp->so_luong_ton_kho }}</td>
                                <td>{{ $sp->trang_thai }}</td> --}}
                                <td>
                                    @if($sp->hinh_anh_chinh)
                                    <img src="{{ asset('storage/' . $sp->hinh_anh_chinh) }}" width="50" height="60" alt="Ảnh">
                                    @else
                                    -
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    {{-- Nút Chi tiết: truyền dữ liệu bằng nhiều data-* an toàn --}}
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-info text-white btn-detail"
                                        data-bs-toggle="modal"
                                        data-bs-target="#productDetailModal"

                                        {{-- Truyền dữ liệu --}}
                                        data-name="{{ $sp->ten_san_pham }}"
                                        data-sku="{{ $sp->sku }}"
                                        data-gia="{{ number_format($sp->gia, 0, ',', '.') }}đ"
                                        data-giakm="{{ $sp->gia_khuyen_mai ? number_format($sp->gia_khuyen_mai, 0, ',', '.') . 'đ' : '-' }}"
                                        data-ton="{{ $sp->so_luong_ton_kho ?? '-' }}"
                                        data-trangthai="{{ $sp->trang_thai ?? '-' }}"
                                        data-brand="{{ optional($sp->brand)->ten_thuong_hieu ?? '-' }}"
                                        data-category="{{ optional($sp->category)->ten_danh_muc ?? '-' }}"
                                        data-mota-ngan="{{ $sp->mo_ta_ngan ?? '-' }}"
                                        data-mota="{{ $sp->mo_ta_chi_tiet ?? '-' }}"
                                        data-image="{{ $sp->hinh_anh_chinh ? asset('storage/' . $sp->hinh_anh_chinh) : '' }}">
                                        Chi tiết
                                    </button>

                                    <a href="{{ route('nhanvien.sanpham.edit', $sp->id) }}"
                                        class="btn btn-sm btn-warning">Sửa</a>

                                    <form action="{{ route('nhanvien.sanpham.destroy', $sp->id) }}"
                                        method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?')">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <!-- Modal Chi Tiết Sản Phẩm -->
    <div class="modal fade" id="productDetailModal" tabindex="-1" aria-labelledby="productDetailLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="productDetailLabel" class="modal-title">Chi tiết sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="ratio ratio-3x4">
                                <img id="pd-image" src="" alt="Ảnh sản phẩm" class="w-100 h-100" style="object-fit:cover;">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <table class="table table-sm mb-0">
                                <tbody>
                                    <tr>
                                        <th style="width: 160px;">Tên sản phẩm</th>
                                        <td id="pd-name">-</td>
                                    </tr>
                                    <tr>
                                        <th>SKU</th>
                                        <td id="pd-sku">-</td>
                                    </tr>
                                    <tr>
                                        <th>Giá</th>
                                        <td id="pd-gia">-</td>
                                    </tr>
                                    <tr>
                                        <th>Giá khuyến mãi</th>
                                        <td id="pd-giakm">-</td>
                                    </tr>
                                    <tr>
                                        <th>Tồn kho</th>
                                        <td id="pd-ton">-</td>
                                    </tr>
                                    <tr>
                                        <th>Trạng thái</th>
                                        <td id="pd-trangthai">-</td>
                                    </tr>
                                    <tr>
                                        <th>Thương hiệu</th>
                                        <td id="pd-brand">-</td>
                                    </tr>
                                    <tr>
                                        <th>Danh mục</th>
                                        <td id="pd-category">-</td>
                                    </tr>
                                    <tr>
                                        <th>Mô tả ngắn</th>
                                        <td id="pd-mota-ngan">-</td>
                                    </tr>
                                    <tr>
                                        <th>Mô tả chi tiết</th>
                                        <td id="pd-mota" style="white-space: pre-line;">-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- row -->
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    {{-- JS Tabs (nếu có) --}}
    <script>
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                const target = btn.dataset.tab;
                document.querySelectorAll('.tab-panel').forEach(p => {
                    p.classList.toggle('active', p.id === 'tab-' + target);
                });
            });
        });

        // Nạp dữ liệu vào modal từ các data-* thuộc tính
        document.querySelectorAll('.btn-detail').forEach(btn => {
            btn.addEventListener('click', function() {
                const set = (id, v) => {
                    const el = document.getElementById(id);
                    if (el) el.textContent = (v ?? '-') === '' ? '-' : v;
                };

                set('pd-name', this.getAttribute('data-name'));
                set('pd-sku', this.getAttribute('data-sku'));
                set('pd-gia', this.getAttribute('data-gia'));
                set('pd-giakm', this.getAttribute('data-giakm'));
                set('pd-ton', this.getAttribute('data-ton'));
                set('pd-trangthai', this.getAttribute('data-trangthai'));
                set('pd-brand', this.getAttribute('data-brand'));
                set('pd-category', this.getAttribute('data-category'));
                set('pd-mota-ngan', this.getAttribute('data-mota-ngan'));
                set('pd-mota', this.getAttribute('data-mota'));

                const img = document.getElementById('pd-image');
                if (img) {
                    const src = this.getAttribute('data-image') || '';
                    if (src) {
                        img.src = src;
                        img.classList.remove('d-none');
                    } else {
                        img.src = '';
                        img.classList.add('d-none');
                    }
                }

                const st = document.getElementById('pd-trangthai');
                if (st) {
                    const val = (st.textContent || '').toLowerCase();
                    st.className = '';
                    st.classList.add('badge');
                    if (val.includes('active') || val.includes('hiển thị') || val.includes('còn bán')) {
                        st.classList.add('text-bg-success');
                    } else if (val.includes('ẩn') || val.includes('ngừng')) {
                        st.classList.add('text-bg-secondary');
                    } else {
                        st.classList.add('text-bg-light', 'text-dark');
                    }
                }
            });
        });
    </script>

    {{-- Chart.js (nếu cần) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>

</html>