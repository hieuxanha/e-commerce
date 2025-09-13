{{-- resources/views/admin/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin Themmm</title>
    <link rel="stylesheet" href="">
    <link rel="stylesheet" href="{{ asset('css/nhanvien/QL_sanpham.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/nhanvien/nhanvien.css') }}" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- <link rel="stylesheet" href="{{ asset('css/login.css') }}" /> -->


</head>

<body>
    <div class="app">
        @include('admin.sidebar-admin')


        <!-- Main -->
        <main>
            <!-- Topbar -->
            <div class="top">
                <form class="top-search" action="#" method="GET" role="search">
                    <input
                        type="text"
                        name="q"
                        class="top-search-input"
                        placeholder="Tìm sản phẩm, danh mục, thương hiệu..."
                        autocomplete="off" />
                    <button class="top-search-btn" aria-label="Tìm kiếm">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7"></circle>
                            <line x1="16.65" y1="16.65" x2="21" y2="21"></line>
                        </svg>
                    </button>
                </form>
            </div>

            <div class="content">

                {{-- ==== THÔNG BÁO SESSION ==== --}}
                @if (session('ok'))
                <div class="alert alert-success" style="background:#d1e7dd;padding:10px;margin-bottom:15px;">
                    {{ session('ok') }}
                </div>
                @endif

                @if ($errors->any())
                <div class="alert alert-danger" style="background:#f8d7da;padding:10px;margin-bottom:15px;">
                    <ul style="margin:0;padding-left:20px;">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Tabs -->
                <div class="tabs">
                    <button class="tab-btn active" data-tab="brand">Thêm Thương hiệu</button>
                    <button class="tab-btn" data-tab="category">Thêm Danh mục</button>
                    <button class="tab-btn" data-tab="product">Thêm Sản phẩm</button>
                </div>

                <!-- PANELS -->
                <div class="tab-panels">
                    {{-- ========== THƯƠNG HIỆU ========== --}}
                    <section class="tab-panel active" id="tab-brand">
                        <h2 class="panel-title">Thêm Thương hiệu</h2>
                        <form class="form-grid" action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-row">
                                <label>Tên thương hiệu <span class="req">*</span></label>
                                <input type="text" name="ten_thuong_hieu" placeholder="VD: Logitech" required>
                            </div>
                            <div class="form-row">
                                <label>Logo (URL hoặc upload)</label>
                                <input type="text" name="logo_url" placeholder="https://...">
                            </div>
                            <div class="form-row">
                                <label>Mô tả</label>
                                <textarea name="mo_ta" rows="3" placeholder="Giới thiệu ngắn..."></textarea>
                            </div>
                            <div class="actions">
                                <button type="submit" class="btn primary">Lưu thương hiệu</button>
                            </div>
                        </form>
                    </section>

                    {{-- ========== DANH MỤC ========== --}}
                    <section class="tab-panel" id="tab-category">
                        <h2 class="panel-title">Thêm Danh mục</h2>
                        <form class="form-grid" action="{{ route('admin.categories.store') }}" method="POST">
                            @csrf
                            <div class="form-row">
                                <label>Tên danh mục <span class="req">*</span></label>
                                <input type="text" name="ten_danh_muc" placeholder="VD: Bàn phím" required>
                            </div>
                            <div class="form-row">
                                <label>Danh mục cha</label>
                                <select name="danh_muc_cha_id">
                                    <option value="">— Không có —</option>
                                    @isset($categories)
                                    @foreach($categories as $c)
                                    <option value="{{ $c->id }}">{{ $c->ten_danh_muc }}</option>
                                    @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="form-row">
                                <label>Mô tả</label>
                                <textarea name="mo_ta" rows="3"></textarea>
                            </div>
                            <div class="actions">
                                <button type="submit" class="btn primary">Lưu danh mục</button>
                            </div>
                        </form>
                    </section>

                    {{-- ========== SẢN PHẨM ========== --}}
                    <section class="tab-panel" id="tab-product">
                        <h2 class="panel-title">Thêm Sản phẩm</h2>
                        <form class="form-grid" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="grid-2">
                                <div class="form-row">
                                    <label>Tên sản phẩm <span class="req">*</span></label>
                                    <input type="text" name="ten_san_pham" placeholder="VD: Bàn phím cơ ..." required>
                                </div>
                                <div class="form-row">
                                    <label>SKU <span class="req">*</span></label>
                                    <input type="text" name="sku" placeholder="Mã SKU duy nhất" required>
                                </div>
                            </div>

                            <div class="grid-2">
                                <div class="form-row">
                                    <label>Giá (VND) <span class="req">*</span></label>
                                    <input type="text" name="gia" min="0" step="1000" placeholder="VD: 990000" required>
                                </div>
                                <div class="form-row">
                                    <label>Giá khuyến mại</label>
                                    <input type="text" name="gia_khuyen_mai" min="0" step="1000" placeholder="VD: 890000">
                                </div>
                            </div>

                            <div class="grid-2">
                                <div class="form-row">
                                    <label>Tồn kho</label>
                                    <input type="text" name="so_luong_ton_kho" min="0" step="1" value="0">
                                </div>
                                <div class="form-row">
                                    <label>Trạng thái</label>
                                    <select name="trang_thai">
                                        <option value="con_hang">Còn hàng</option>
                                        <option value="het_hang">Hết hàng</option>
                                        <option value="sap_ve">Sắp về</option>
                                        <option value="an">Ẩn</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid-2">
                                <div class="form-row">
                                    <label>Danh mục <span class="req">*</span></label>
                                    <select name="category_id" required>
                                        @isset($categories)
                                        @foreach($categories as $c)
                                        <option value="{{ $c->id }}">{{ $c->ten_danh_muc }}</option>
                                        @endforeach
                                        @else
                                        <option value="">(Chưa có dữ liệu)</option>
                                        @endisset
                                    </select>
                                </div>
                                <div class="form-row">
                                    <label>Thương hiệu <span class="req">*</span></label>
                                    <select name="brand_id" required>
                                        @isset($brands)
                                        @foreach($brands as $b)
                                        <option value="{{ $b->id }}">{{ $b->ten_thuong_hieu }}</option>
                                        @endforeach
                                        @else
                                        <option value="">(Chưa có dữ liệu)</option>
                                        @endisset
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <label>Hình ảnh chính (URL)</label>
                                <input type="file" name="hinh_anh_chinh" placeholder="https://...">
                            </div>

                            <div class="form-row">
                                <label>Mô tả ngắn</label>
                                <textarea name="mo_ta_ngan" rows="2" maxlength="500"></textarea>
                            </div>

                            <div class="form-row">
                                <label>Mô tả chi tiết</label>
                                <textarea name="mo_ta_chi_tiet" rows="5"></textarea>
                            </div>

                            <div class="actions">
                                <button type="submit" class="btn primary">Lưu sản phẩm</button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>




        </main>
    </div>
    {{-- Tabs JS (đơn giản) --}}
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
    <!-- Chart.js CDN (chỉ để vẽ demo UI) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>

</html>