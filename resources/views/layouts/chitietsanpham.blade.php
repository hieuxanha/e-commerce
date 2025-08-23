{{-- resources/views/layouts/chitietsanpham.blade.php --}}
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $product->ten_san_pham ?? 'Chi tiết sản phẩm' }}</title>

    {{-- CSS dùng chung --}}
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/breadcrumb.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Bootstrap (nếu bạn đang dùng) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-detail .main-image img {
            object-fit: contain;
            max-height: 450px
        }

        .thumb-images img {

            width: 80px;
            height: 80px;
            object-fit: cover
        }

        .text-small {
            font-size: .9rem
        }

        /* sản phẩm liên quan */
        .product-card {
            transition: all 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-10px);
            /* nhô lên 6px */
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            /* đổ bóng đậm hơn */
        }

        /* chinh sach */
        .policy-box ul li {
            display: flex;
            align-items: center;
            padding: 6px 0;
            font-size: 14px;
            color: #333;
        }

        .policy-box ul li i {
            font-size: 18px;
            color: #28a745;
            /* màu xanh lá */
            margin-right: 8px;
        }
    </style>
</head>

<body>

    {{-- Header dùng chung --}}
    @include('layouts.header')

    @php
    $links = [
    'Trang chủ' => url('/'),
    'Sản phẩm' => url('/home'), // đổi link nếu có trang danh mục riêng
    $product->ten_san_pham ?? '' => ''
    ];
    @endphp
    @include('components.breadcrumb', ['links' => $links])

    {{-- Nội dung --}}
    <div class="product-detail container py-4">
        <div class="row g-4">
            {{-- Cột ảnh --}}
            <div class="col-md-5">
                <div class="main-image border rounded p-2 mb-3 bg-white">
                    @php
                    $mainImg = !empty($product->hinh_anh_chinh)
                    ? asset('storage/'.$product->hinh_anh_chinh)
                    : asset('images/no-image.png'); // fallback
                    @endphp
                    <img src="{{ $mainImg }}" alt="{{ $product->ten_san_pham }}" class="img-fluid w-100">
                </div>

                <div class="thumb-images d-flex gap-2">
                    {{-- Ảnh nhỏ (có thể lặp qua bảng images riêng nếu bạn có) --}}
                    <img src="{{ $mainImg }}" class="img-thumbnail" alt="thumb">
                </div>
            </div>

            {{-- Cột thông tin + sidebar bên phải --}}
            <div class="col-md-7">
                <div class="row">
                    {{-- Thông tin sản phẩm --}}
                    <div class="col-md-8">
                        <h2 class="mb-1">{{ $product->ten_san_pham }}</h2>
                        <div class="mb-2 text-muted text-small">
                            Mã SP: <strong>{{ $product->sku }}</strong>
                            @if(isset($product->luot_xem))
                            &nbsp;|&nbsp; Lượt xem: {{ (int) $product->luot_xem }}
                            @endif
                        </div>
                        {{-- Mô tả chi tiết --}}
                        @if(!empty($product->mo_ta_chi_tiet))
                        <div class="mt-4 mb-2">
                            <h5>Mô tả chi tiết</h5>
                            <div class="text-secondary">
                                {!! nl2br(e($product->mo_ta_chi_tiet)) !!}
                            </div>
                        </div>
                        @endif

                        {{-- Giá --}}
                        <div class="mb-3">
                            @if(!empty($product->gia_khuyen_mai))
                            <div class="text-muted">
                                <del>{{ number_format($product->gia, 0, ',', '.') }}đ</del>
                            </div>
                            <div class="text-danger fs-4 fw-bold">
                                {{ number_format($product->gia_khuyen_mai, 0, ',', '.') }}đ
                            </div>
                            <small class="text-success">
                                (Tiết kiệm: {{ max(0, round(100 - $product->gia_khuyen_mai / max(1,$product->gia) * 100)) }}%)
                            </small>
                            @else
                            <div class="text-danger fs-4 fw-bold">
                                {{ number_format($product->gia, 0, ',', '.') }}đ
                            </div>
                            @endif
                        </div>

                        {{-- Mô tả ngắn --}}
                        @if(!empty($product->mo_ta_ngan))
                        <div class="product-desc mb-3">
                            {!! nl2br(e($product->mo_ta_ngan)) !!}
                        </div>
                        @endif

                        {{-- Nút hành động --}}
                        <div class="d-flex gap-3 mt-4">
                            <form method="POST" action="{{ route('cart.add') }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="btn btn-warning btn-lg">THÊM VÀO GIỎ</button>
                            </form>

                            <a href="{{ route('cart.checkout') }}" class="btn btn-success btn-lg">MUA NGAY</a>
                        </div>
                    </div>

                    {{-- Sidebar bên phải --}}
                    {{-- Sidebar bên phải --}}
                    <div class="col-md-4">
                        <div class="policy-box bg-white border rounded p-3 mb-3">
                            <h5 class="mb-3 fw-bold text-success">CHÍNH SÁCH MUA HÀNG</h5>
                            <ul class="list-unstyled m-0">
                                <li><i class="bi bi-credit-card"></i> Thanh toán thuận tiện</li>
                                <li><i class="bi bi-shield-check"></i> Sản phẩm 100% chính hãng</li>
                                <li><i class="bi bi-truck"></i> Bảo hành nhanh chóng</li>
                                <li><i class="bi bi-box-seam"></i> Giao hàng toàn quốc</li>
                            </ul>
                        </div>

                        <div class="policy-box bg-white border rounded p-3">
                            <h5 class="mb-3 fw-bold text-success">HOTLINE HỖ TRỢ</h5>
                            <ul class="list-unstyled m-0">
                                <li><i class="bi bi-telephone"></i> Hotline CSKH: <span class="fw-bold text-danger">0349.296.461</span></li>
                                <li><i class="bi bi-telephone"></i> Tư vấn mua hàng: <span class="fw-bold text-danger">0349.296.461</span></li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        {{-- Sản phẩm liên quan --}}
        @isset($related)
        @if($related->count())
        <hr class="my-5">
        <h4 class="mb-3">Sản phẩm liên quan</h4>
        <div class="row g-3">
            @foreach($related as $r)
            @php
            $rImg = !empty($r->hinh_anh_chinh) ? asset('storage/'.$r->hinh_anh_chinh) : asset('images/no-image.png');
            $rPrice = $r->gia_khuyen_mai ?? $r->gia;
            // ✅ Link CHÍNH XÁC: ưu tiên slug, fallback id
            $rUrl = !empty($r->slug)
            ? route('sanpham.chitiet', ['slug' => $r->slug])
            : route('sanpham.chitiet.id', ['id' => $r->id]);
            @endphp
            <div class="col-6 col-md-3">
                <a href="{{ $rUrl }}" class="text-decoration-none text-dark">
                    <div class="card h-100 shadow-sm product-card" style="max-width: 55%;">
                        <img class="card-img-top" src="{{ $rImg }}" alt="{{ $r->ten_san_pham }}"
                            style="aspect-ratio:1/1;object-fit:cover">
                        <div class="card-body p-3">
                            <div class="small text-wrap" style="display: -webkit-box;
                             -webkit-line-clamp: 2;
                             -webkit-box-orient: vertical;
                             overflow: hidden;
                             min-height: 3rem;"
                                title="{{ $r->ten_san_pham }}">
                                {{ $r->ten_san_pham }}
                            </div>
                            <strong class="text-danger d-block mt-2">
                                {{ number_format($rPrice, 0, ',', '.') }}đ
                            </strong>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        @endif
        @endisset

    </div>

    {{-- Footer dùng chung --}}
    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>