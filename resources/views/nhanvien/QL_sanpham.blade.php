{{-- resources/views/admin/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard Nhân Viên</title>
    <link rel="stylesheet" href="{{ asset('css/nhanvien/QL_sanpham.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/nhanvien/nhanvien.css') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

            <!-- Danh sách sản phẩm -->
            <div class="ds_sp">
                <h4 class="mb-3">Danh Sách Sản Phẩm</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-white text-center">
                            <tr>
                                <th>STT</th>
                                <th>Tên Sản Phẩm</th>
                                <th>SKU</th>
                                <th>Giá</th>
                                <th>KM</th>
                                <th>Tồn Kho</th>
                                <th>Trạng Thái</th>
                                <th>Ảnh</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $index => $sp)
                            <tr class="align-middle text-center">
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
                                <td>{{ $sp->so_luong_ton_kho }}</td>
                                <td>{{ $sp->trang_thai }}</td>
                                <td>
                                    @if($sp->hinh_anh_chinh)
                                    <img src="{{ asset('storage/' . $sp->hinh_anh_chinh) }}" width="50" height="60">
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('nhanvien.sanpham.edit', $sp->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                                    <form action="{{ route('nhanvien.sanpham.destroy', $sp->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?')">Xóa</button>
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
    </script>

    {{-- Chart.js demo (tùy chọn) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>

</html>