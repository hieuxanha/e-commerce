{{-- resources/views/layouts/danhsachsanpham.blade.php --}}
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sản phẩm</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/breadcrumb.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* Sidebar */
        .sidebar {
            background: #fff;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .1)
        }

        .sidebar h6 {
            font-weight: 700;
            margin: 15px 0 10px
        }

        .sidebar label {
            font-size: 14px;
            display: block;
            margin-bottom: 6px;
            cursor: pointer
        }

        /* ===== Product grid ===== */
        /* Reset mọi rule lạ ảnh hưởng .row > * (vd: max-width:63%) */
        .product-grid>* {
            max-width: 20% !important;
            flex: 0 0 auto
        }

        /* Card */
        .product-card {
            border: 1px solid #eee;
            border-radius: 10px;
            overflow: hidden;
            background: #fff;
            transition: .3s;
            position: relative;
            margin: 0 auto;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            box-shadow: 0 6px 16px rgba(0, 0, 0, .15);
            transform: translateY(-6px)
        }

        /* Khung ảnh: cố định tỉ lệ, ảnh không tràn */
        .product-thumb {
            position: relative;
            width: 100%;
            aspect-ratio: 1/1;
            background: #fff;
            display: block
        }

        .product-thumb img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block
        }

        /* Body & spacing */
        .product-card .card-body {
            padding: 10px 12px;
            display: flex;
            flex-direction: column
        }

        .card-title {
            font-size: 15px;
            line-height: 1.3;
            margin-bottom: 6px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .price {
            font-weight: 700;
            color: #e74c3c
        }

        .old-price {
            text-decoration: line-through;
            font-size: 13px;
            color: #888;
            margin-left: 5px
        }

        .discount-label {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #e74c3c;
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 4px;
            z-index: 2
        }

        .product-status {
            font-size: 13px;
            color: #28a745;
            margin-bottom: 5px
        }

        /* Bottom row sticks to bottom */
        .card-actions {
            margin-top: auto;
            display: flex;
            justify-content: space-between;
            align-items: center
        }

        /* Cart button */
        .cart-btn {
            width: 36px;
            height: 36px;
            border: none;
            border-radius: 50%;
            background: #6BCB77;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: .3s
        }

        .cart-btn:hover {
            background: #58b167
        }

        /* Carousel */
        .carousel-control-prev {
            left: -25px
        }

        .carousel-control-next {
            right: -25px
        }
    </style>
</head>

<body>
    {{-- Header --}}
    @include('layouts.header')

    {{-- Breadcrumb --}}
    @php
    $links = [
    'Trang chủ' => url('/'),
    'Sản phẩm' => url('/san-pham'),
    $category->ten_danh_muc => ''
    ];
    @endphp
    @include('components.breadcrumb', ['links' => $links])

    <div class="container my-4">
        <div class="row">
            {{-- Sidebar --}}
            <div class="col-md-3">
                <div class="sidebar">
                    <h6>Hãng sản xuất</h6>
                    @foreach ($brands as $brand)
                    <label>
                        <input type="checkbox" name="brand[]" value="{{ $brand->id }}">
                        {{ $brand->ten_thuong_hieu }}
                    </label>
                    @endforeach
                    <h6>Khoảng giá</h6>
                    <label><input type="checkbox"> Dưới 100k</label>
                    <label><input type="checkbox"> 100k - 200k</label>
                    <label><input type="checkbox"> 200k - 500k</label>

                    <h6>Kích thước</h6>
                    <label><input type="checkbox"> 26x21CM</label>
                    <label><input type="checkbox"> 35x30CM</label>
                    <label><input type="checkbox"> 45x40CM</label>
                    <label><input type="checkbox"> 80x30CM</label>
                    <label><input type="checkbox"> 90x40CM</label>
                </div>
            </div>

            {{-- Content --}}
            <div class="col-md-9">
                {{-- Banner --}}
                <div id="bannerCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="{{ asset('img/banner1.jpg') }}" class="d-block w-100" alt="Banner 1">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('img/banner1.jpg') }}" class="d-block w-100" alt="Banner 2">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('img/banner1.jpg') }}" class="d-block w-100" alt="Banner 3">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('img/banner1.jpg') }}" class="d-block w-100" alt="Banner 4">
                        </div>
                    </div>
                    <button class="carousel-control-prev me-2" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next ms-2" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="0" class="active"></button>
                        <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="1"></button>
                        <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="2"></button>
                        <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="3"></button>
                    </div>
                </div>

                <h4 class="mb-3">{{ $category->ten_danh_muc }} ({{ $products->total() }} sản phẩm)</h4>

                {{-- Sort --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <button class="btn btn-sm btn-outline-primary">Hàng mới</button>
                        <button class="btn btn-sm btn-outline-primary">Giá tăng dần</button>
                        <button class="btn btn-sm btn-outline-primary">Giá giảm dần</button>
                    </div>
                    <select class="form-select w-auto">
                        <option>Sắp xếp</option>
                        <option>Giá thấp nhất</option>
                        <option>Giá cao nhất</option>
                    </select>
                </div>

                {{-- ===== LIST SẢN PHẨM =====
             Mobile (xs): 2 sp/hàng (col-6)
             ≥ md:       4 sp/hàng (col-md-3)
             Gutter ngang khít (gx-2) & dọc vừa (gy-3) --}}
                <div class="row product-grid gx-2 gy-3">
                    @foreach ($products as $sp)
                    <div class="col-6 col-md-3">
                        <div class="product-card">
                            @if ($sp->gia_khuyen_mai && $sp->gia_goc > 0)
                            <div class="discount-label">
                                -{{ round(100 - (($sp->gia_khuyen_mai / $sp->gia_goc) * 100)) }}%
                            </div>
                            @endif

                            {{-- Ảnh --}}
                            <a class="product-thumb"
                                href="{{ !empty($sp->slug) ? route('sanpham.chitiet', ['slug' => $sp->slug]) : route('sanpham.chitiet.id', ['id' => $sp->id]) }}">
                                <img src="{{ asset('storage/' . $sp->hinh_anh_chinh) }}" alt="{{ $sp->ten_san_pham }}">
                            </a>

                            <div class="card-body">
                                <h6 class="card-title">{{ $sp->ten_san_pham }}</h6>
                                <div class="product-status">{{ $sp->trang_thai ?? 'Còn hàng' }}</div>

                                <p class="mb-2">
                                    <span class="price">{{ number_format($sp->gia_khuyen_mai ?? $sp->gia_goc, 0, ',', '.') }}đ</span>
                                    @if ($sp->gia_khuyen_mai && $sp->gia_goc > 0)
                                    <span class="old-price">{{ number_format($sp->gia_goc, 0, ',', '.') }}đ</span>
                                    @endif
                                </p>

                                <div class="card-actions">
                                    {{-- Link chi tiết --}}
                                    @if (!empty($sp->slug))
                                    <a href="{{ route('sanpham.chitiet', ['slug' => $sp->slug]) }}" class="btn btn-sm btn-outline-primary">Xem chi tiết</a>
                                    @else
                                    <a href="{{ route('sanpham.chitiet.id', ['id' => $sp->id]) }}" class="btn btn-sm btn-outline-primary">Xem chi tiết</a>
                                    @endif
                                    {{-- Nút giỏ --}}
                                    <button class="cart-btn add-to-cart-btn" title="Thêm vào giỏ" data-id="{{ $sp->id }}">
                                        <i class="fas fa-shopping-cart"></i>
                                    </button>


                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Phân trang --}}
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    @include('layouts.footer')

    {{-- JS Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (() => {
            const CSRF = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const cartBadge = document.getElementById('cartBadge');
            const dropdown = document.getElementById('cart-dropdown');

            document.addEventListener('click', async (e) => {
                const btn = e.target.closest('.add-to-cart-btn');
                if (!btn) return;

                const productId = btn.dataset.id;
                if (!productId || btn.dataset.busy === '1') return;
                btn.dataset.busy = '1';

                try {
                    const res = await fetch(`{{ route('cart.add') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            product_id: productId
                        })
                    });

                    if (res.status === 401) {
                        const back = encodeURIComponent(location.href);
                        location.href = "{{ route('login') }}?redirect=" + back;
                        return;
                    }

                    const data = await res.json().catch(() => ({}));
                    if (res.ok && data.status === 'success') {
                        if (cartBadge && typeof data.totalQuantity !== 'undefined') {
                            cartBadge.textContent = data.totalQuantity;
                        }
                        if (dropdown) {
                            const mini = await fetch(`{{ route('cart.mini') }}`, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });
                            dropdown.innerHTML = await mini.text();
                            dropdown.classList.add('show');
                        }
                    } else {
                        alert(data.message || 'Không thể thêm vào giỏ.');
                    }
                } catch (err) {
                    console.error(err);
                    alert('Lỗi mạng, thử lại nhé.');
                } finally {
                    setTimeout(() => (btn.dataset.busy = '0'), 300);
                }
            });
        })();
    </script>

</body>

</html>