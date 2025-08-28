{{-- resources/views/thongtingiaohang.blade.php --}}
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thông tin giao hàng</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">

    <style>
        .breadcrumb-x {
            background: #f7f7f7;
            padding: 12px 0;
            color: #6b7280;
            font-size: .95rem;
        }

        .checkout-wrap {
            padding: 24px 0 48px;
        }

        .checkout-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
        }

        .cart-summary {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
        }

        .thumb {
            width: 64px;
            height: 64px;
            object-fit: cover;
            border-radius: 8px;
        }

        .form-select,
        .form-control {
            height: 48px;
        }

        .muted {
            color: #6b7280;
        }

        .btn-ghost {
            border: 1px solid #e5e7eb;
            background: #fff;
        }

        .price {
            font-weight: 600;
        }
    </style>
</head>

<body>
    @include('layouts.header')

    {{-- Breadcrumb --}}
    <div class="breadcrumb-x">
        <div class="container">
            <a href="{{ url('/cart') }}">Giỏ hàng</a>
            <span class="mx-1">/</span>
            <a href="{{ url('/cart') }}" class="text-decoration-none muted">Thông tin giỏ hàng</a>
            <span class="mx-1">/</span>
            <strong>Phương thức thanh toán</strong>
        </div>
    </div>

    <div class="checkout-wrap">
        <div class="container">
            <div class="row g-4">
                {{-- LEFT: Form thông tin giao hàng --}}
                <div class="col-lg-7">
                    <div class="checkout-card p-4 p-md-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Thông tin giao hàng</h5>
                            <div class="small">
                                Bạn đã có tài khoản?
                                <a href="{{ url('/login') }}">Đăng nhập</a>
                            </div>
                        </div>

                        <form action="{{ url('/checkout/submit') }}" method="POST" id="checkout-form" novalidate>
                            @csrf

                            {{-- (Tuỳ chọn) lưu cả tên hiển thị để backend dùng tiện --}}
                            <input type="hidden" name="province_name" id="provinceName">
                            <input type="hidden" name="district_name" id="districtName">
                            <input type="hidden" name="ward_name" id="wardName">

                            <div class="row g-3">
                                <div class="col-12">
                                    <input type="text" name="fullname" class="form-control" placeholder="Họ và tên" value="{{ old('fullname') }}" required>
                                </div>
                                <div class="col-md-8">
                                    <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="phone" class="form-control" placeholder="Số điện thoại" value="{{ old('phone') }}" required>
                                </div>

                                <div class="col-12">
                                    <input type="text" name="address" class="form-control" placeholder="Địa chỉ" value="{{ old('address') }}" required>
                                </div>

                                <div class="col-md-4">
                                    <select id="provinceSelect" name="province_id" class="form-select" required
                                        data-old="{{ old('province_id') }}">
                                        <option value="">Chọn tỉnh / thành</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <select id="districtSelect" name="district_id" class="form-select" required disabled
                                        data-old="{{ old('district_id') }}">
                                        <option value="">Chọn quận / huyện</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <select id="wardSelect" name="ward_id" class="form-select" required disabled
                                        data-old="{{ old('ward_id') }}">
                                        <option value="">Chọn phường / xã</option>
                                    </select>
                                </div>


                                <div class="col-12">
                                    <textarea name="note" class="form-control" rows="3" placeholder="Ghi chú (không bắt buộc)">{{ old('note') }}</textarea>
                                </div>
                                {{-- PHƯƠNG THỨC THANH TOÁN --}}
                                <div class="col-12">
                                    <h6 class="mt-2 mb-3">Phương thức thanh toán</h6>

                                    <div class="list-group mb-3">

                                        <label class="list-group-item d-flex align-items-center gap-3">
                                            <input class="form-check-input m-0" type="radio" name="payment_method" value="cod" checked>
                                            <img src="{{ asset('img/cod-box.svg') }}" alt="COD" width="28" height="28" onerror="this.style.display='none'">
                                            <div>
                                                <div class="fw-semibold">Thanh toán khi giao hàng (COD)</div>
                                                <div class="text-muted small">Bạn sẽ thanh toán tiền mặt cho nhân viên giao hàng.</div>
                                            </div>
                                        </label>

                                        {{-- VNPAY --}}
                                        <label class="list-group-item d-flex align-items-center gap-3">
                                            <input class="form-check-input m-0" type="radio" name="payment_method" value="vnpay">
                                            <img src="{{ asset('img/vnpay.svg') }}" alt="VNPAY" width="28" height="28" onerror="this.style.display='none'">
                                            <div>
                                                <div class="fw-semibold">Thẻ ATM/Visa/Master/JCB/QR Pay qua cổng VNPAY</div>
                                                <div class="text-muted small">Hỗ trợ hầu hết ngân hàng nội địa và thẻ quốc tế.</div>
                                            </div>
                                        </label>


                                        {{-- Khối cấu hình chỉ hiện khi chọn VNPAY --}}
                                        <div id="vnpayBox" class="list-group-item" style="display:none;">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <img src="{{ asset('images/vnpay-banks.png') }}" alt="Banks" height="24" onerror="this.style.display='none'">
                                                <span class="small text-muted">Chọn phương thức qua cổng VNPAY</span>
                                            </div>

                                            {{-- Ví dụ vài phương án; backend đọc name="vnpay_bank_code" --}}
                                            <!-- <div class="row g-2">
                                                <div class="col-md-6">
                                                    <select name="vnpay_bank_code" class="form-select">
                                                        <option value="">Mặc định (VNPAY chọn)</option>
                                                        <option value="VNPAYQR">QR Pay</option>
                                                        <option value="VNBANK">Thẻ nội địa (ATM)</option>
                                                        <option value="INTCARD">Thẻ quốc tế (Visa/Master/JCB)</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <select name="vnpay_locale" class="form-select">
                                                        <option value="vn" selected>Tiếng Việt</option>
                                                        <option value="en">English</option>
                                                    </select>
                                                </div>
                                            </div> -->

                                            {{-- Tuỳ chọn ghi chú riêng cho VNPAY (không bắt buộc) --}}
                                            <div class="form-text mt-2">Sau khi đặt hàng, bạn sẽ được chuyển sang trang thanh toán VNPAY để hoàn tất.</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col- mt-2">
                                    <button type="submit" class="btn btn-primary px-4 py-2">
                                        thanh toán
                                    </button>
                                    <a href="{{ url('/cart') }}" class="btn btn-ghost mt-2">Giỏ hàng</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- RIGHT: Tóm tắt đơn hàng & mã giảm giá --}}
                <div class="col-lg-5">
                    <div class="cart-summary p-4 p-md-4">
                        {{-- Danh sách sản phẩm trong đơn --}}
                        <div class="mb-3">
                            @php
                            $cartItems = $cartItems ?? [];
                            $subtotal = $subtotal ?? 0;
                            $shipping = $shipping ?? 0;
                            $total = $total ?? ($subtotal + $shipping);
                            @endphp

                            @forelse($cartItems as $it)
                            <div class="d-flex align-items-center mb-3">
                                <img class="thumb me-3" src="{{ $it['image'] ?? asset('images/no-image.png') }}" alt="{{ $it['name'] ?? 'SP' }}">
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <div class="fw-semibold">{{ $it['name'] ?? 'Sản phẩm' }}</div>
                                            @if(!empty($it['variant'])) <div class="small muted">{{ $it['variant'] }}</div> @endif
                                            @if(!empty($it['color']) || !empty($it['size']))
                                            <div class="small muted">
                                                {{ $it['color'] ?? '' }} @if(!empty($it['color']) && !empty($it['size'])) / @endif {{ $it['size'] ?? '' }}
                                            </div>
                                            @endif
                                        </div>
                                        <div class="price">{{ number_format(($it['price'] ?? 0), 0, ',', '.') }}đ</div>
                                    </div>
                                    <div class="small muted mt-1">SL: {{ $it['qty'] ?? 1 }}</div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center text-muted py-3">Chưa có sản phẩm trong đơn.</div>
                            @endforelse
                        </div>

                        {{-- Mã giảm giá --}}
                        <form action="{{ url('/checkout/apply-coupon') }}" method="POST" class="mb-3">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="coupon_code" class="form-control" placeholder="Mã giảm giá" value="{{ old('coupon_code') }}">
                                <button class="btn btn-ghost" type="submit">Sử dụng</button>
                            </div>
                        </form>

                        {{-- Chương trình khách hàng thân thiết --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="fw-semibold">Chương trình khách hàng thân thiết</div>
                            <a class="btn btn-ghost btn-sm" href="{{ url('/login') }}">Đăng nhập</a>
                        </div>

                        <hr>

                        {{-- Tổng kết --}}
                        <div class="d-flex justify-content-between mb-2">
                            <span class="muted">Tạm tính</span>
                            <span>{{ number_format($subtotal, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="muted">Phí vận chuyển</span>
                            <span>{{ $shipping > 0 ? number_format($shipping, 0, ',', '.') . 'đ' : '—' }}</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <h6 class="mb-0">Tổng cộng</h6>
                            <div class="fs-5 fw-bold">{{ number_format($total, 0, ',', '.') }}đ</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Thêm JS nhỏ để bật/tắt khối VNPAY


        (function() {
            const vnpayBox = document.getElementById('vnpayBox');
            const radios = document.querySelectorAll('input[name="payment_method"]');
            const toggleVnpay = () => {
                const val = document.querySelector('input[name="payment_method"]:checked')?.value;
                vnpayBox.style.display = (val === 'vnpay') ? 'block' : 'none';
            };
            radios.forEach(r => r.addEventListener('change', toggleVnpay));
            toggleVnpay(); // init
        })();

        (function() {
            const DATA_URL = "{{ asset('data/DiaGioiHanhChinhVN-master/data.json') }}";

            const selProv = document.getElementById('provinceSelect');
            const selDist = document.getElementById('districtSelect');
            const selWard = document.getElementById('wardSelect');

            // lấy giá trị old() từ data-*
            const OLD_PROV = selProv?.dataset?.old || '';
            const OLD_DIST = selDist?.dataset?.old || '';
            const OLD_WARD = selWard?.dataset?.old || '';

            let PROVS = [];

            const opt = (v, t) => {
                const o = document.createElement('option');
                o.value = v;
                o.textContent = t;
                return o;
            };
            const reset = (sel, placeholder, disabled = true) => {
                sel.innerHTML = '';
                sel.appendChild(opt('', placeholder));
                sel.disabled = !!disabled;
            };
            const textOf = (sel) => sel.options[sel.selectedIndex]?.text || '';

            function syncNames() {
                const pn = document.getElementById('provinceName');
                const dn = document.getElementById('districtName');
                const wn = document.getElementById('wardName');
                if (pn) pn.value = textOf(selProv);
                if (dn) dn.value = textOf(selDist);
                if (wn) wn.value = textOf(selWard);
            }

            function fillProvinces() {
                reset(selProv, 'Chọn tỉnh / thành', false);
                PROVS.forEach(p => selProv.appendChild(opt(p.Id, p.Name)));
                if (OLD_PROV) {
                    selProv.value = String(OLD_PROV);
                    selProv.dispatchEvent(new Event('change'));
                }
                syncNames();
            }

            function fillDistricts(provId) {
                const prov = PROVS.find(p => String(p.Id) === String(provId));
                reset(selDist, 'Chọn quận / huyện', !prov);
                reset(selWard, 'Chọn phường / xã', true);
                if (!prov) {
                    syncNames();
                    return;
                }
                (prov.Districts || []).forEach(d => selDist.appendChild(opt(d.Id, d.Name)));
                if (OLD_DIST) {
                    selDist.value = String(OLD_DIST);
                    selDist.dispatchEvent(new Event('change'));
                }
                syncNames();
            }

            function fillWards(provId, distId) {
                const prov = PROVS.find(p => String(p.Id) === String(provId));
                const dist = prov ? (prov.Districts || []).find(d => String(d.Id) === String(distId)) : null;
                reset(selWard, 'Chọn phường / xã', !dist);
                if (!dist) {
                    syncNames();
                    return;
                }
                (dist.Wards || []).forEach(w => selWard.appendChild(opt(w.Id, w.Name)));
                if (OLD_WARD) selWard.value = String(OLD_WARD);
                syncNames();
            }

            selProv.addEventListener('change', () => fillDistricts(selProv.value));
            selDist.addEventListener('change', () => fillWards(selProv.value, selDist.value));
            selWard.addEventListener('change', syncNames);

            fetch(DATA_URL, {
                    cache: 'no-store'
                })
                .then(r => r.json())
                .then(json => {
                    PROVS = Array.isArray(json) ? json : (json?.data || []);
                    fillProvinces();
                })
                .catch(() => {
                    reset(selProv, 'Không tải được dữ liệu', true);
                    reset(selDist, '—', true);
                    reset(selWard, '—', true);
                });
        })();
    </script>

</body>

</html>


<!-- 🔹 Cách 2: Dùng package/API có sẵn

Có nhiều repo/public JSON đã liệt kê đủ tỉnh–huyện–xã VN, ví dụ:
👉 https://github.com/kenzouno1/DiaGioiHanhChinhVN

Cách dùng:

Tải file data.json về, lưu trong public/js/provinces.js hoặc storage/app/provinces.json.

Load bằng AJAX/JS → fill <select> động (người chọn Tỉnh → tự load Quận/Huyện → rồi Xã).
Như vậy không cần hardcode 63 tỉnh trong Blade. -->