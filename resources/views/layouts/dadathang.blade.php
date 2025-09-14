{{-- resources/views/layouts/dadathang.blade.php --}}
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Đã đặt hàng</title>

    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .card-soft {
            border: 1px solid #e5e7eb;
            border-radius: 12px
        }

        .thumb {
            width: 56px;
            height: 56px;
            object-fit: cover;
            border-radius: 8px
        }

        .step {
            width: 12px;
            height: 12px;
            border-radius: 999px;
            background: #0ea5e9
        }

        .line {
            height: 3px;
            border-radius: 99px;
            background: #e5e7eb;
            flex: 1
        }
    </style>
</head>

<body>
    @include('layouts.header')

    <div class="container my-4 my-md-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="d-flex align-items-center mb-2">
                    <a href="{{ route('home') }}" class="btn btn-link p-0 me-2"><i class="bi bi-x-lg"></i></a>
                    <h3 class="mb-0 fw-bold">Đã đặt hàng</h3>
                </div>
                <div class="text-muted mb-3">
                    Đang đến <span class="fw-semibold">{{ $eta_from }}</span>–<span class="fw-semibold">{{ $eta_to }}</span>
                </div>

                {{-- bước trạng thái --}}
                <div class="card card-soft p-3 mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="step"></div>
                        <div class="line"></div>
                        <div class="step" style="opacity:.35"></div>
                        <div class="line"></div>
                        <div class="step" style="opacity:.35"></div>
                        <div class="line"></div>
                        <div class="step" style="opacity:.35"></div>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mt-2">
                        <span>Đã đặt hàng</span>
                        <span>Chờ chuyển phát</span>
                        <span>Đang trung chuyển</span>
                        <span>Đã giao đơn hàng</span>
                    </div>
                </div>

                {{-- địa chỉ --}}
                <div class="card card-soft p-3 mb-3">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-geo-alt text-primary fs-4"></i>
                        <div>
                            <div class="fw-semibold">{{ $shippingInfo['fullname'] ?? '' }} @if(!empty($shippingInfo['phone'])) ({{ $shippingInfo['phone'] }}) @endif</div>
                            <div class="text-muted">
                                {{ $shippingInfo['address'] ?? '' }}
                                @if(!empty($shippingInfo['ward_name'])), {{ $shippingInfo['ward_name'] }} @endif
                                @if(!empty($shippingInfo['district_name'])), {{ $shippingInfo['district_name'] }} @endif
                                @if(!empty($shippingInfo['province_name'])), {{ $shippingInfo['province_name'] }} @endif
                            </div>
                            @if(!empty($shippingInfo['note']))
                            <div class="small text-muted mt-1">Ghi chú: {{ $shippingInfo['note'] }}</div>
                            @endif

                            <a href="{{ route('checkout.info') }}" class="btn btn-outline-secondary btn-sm mt-2">Thay đổi địa chỉ</a>
                        </div>
                    </div>
                </div>

                {{-- danh sách sản phẩm + tổng tiền --}}
                <div class="card card-soft p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-light text-dark">Mã đơn: {{ $orderCode }}</span>
                        <span class="small text-muted">COD</span>
                    </div>

                    @foreach($items as $it)
                    <div class="d-flex align-items-center justify-content-between py-2">
                        <div class="d-flex align-items-center gap-2">

                            <img class="thumb" src="{{ $it['image'] }}" alt="{{ $it['name'] }}">
                            <div>
                                <div class="fw-semibold">{{ $it['name'] }}</div>
                                <div class="text-muted small">x{{ $it['qty'] }}</div>
                            </div>
                        </div>
                        <div class="fw-semibold">{{ number_format($it['total'],0,',','.') }}đ</div>
                    </div>
                    @if(!$loop->last)
                    <hr class="my-2">@endif
                    @endforeach

                    <div class="mt-2">
                        <div class="d-flex justify-content-between text-muted">
                            <span>Tạm tính</span><span>{{ number_format($subtotal,0,',','.') }}đ</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted">
                            <span>Phí vận chuyển</span><span>{{ number_format($shipping,0,',','.') }}đ</span>
                        </div>


                        {{-- DÒNG GIẢM GIÁ (nếu có) --}}
                        @if(!empty($discount) && (int)$discount > 0)
                        <div class="d-flex justify-content-between text-success">
                            <span>
                                Giảm mã
                                @if(!empty($coupon_code))
                                <strong>{{ $coupon_code }}</strong>
                                @endif
                            </span>
                            <span>-{{ number_format((int)$discount,0,',','.') }}đ</span>
                        </div>
                        @endif
                        <hr>
                        <div class="d-flex justify-content-between fs-5">
                            <span>Tổng cộng</span><strong>{{ number_format($total,0,',','.') }}đ</strong>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('home') }}" class="btn btn-primary">Tiếp tục mua sắm</a>
                    <a href="#" class="btn btn-outline-danger">Hủy đơn hàng</a>
                </div>

            </div>
        </div>
    </div>

    @include('layouts.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>