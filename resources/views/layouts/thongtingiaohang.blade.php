{{-- resources/views/layouts/thongtingiaohang.blade.php --}}
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
            font-size: .95rem
        }

        .checkout-wrap {
            padding: 24px 0 48px
        }

        .checkout-card,
        .cart-summary {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px
        }

        .thumb {
            width: 64px;
            height: 64px;
            object-fit: cover;
            border-radius: 8px
        }

        .form-select,
        .form-control {
            height: 48px
        }

        .muted {
            color: #6b7280
        }

        .btn-ghost {
            border: 1px solid #e5e7eb;
            background: #fff
        }

        .price {
            font-weight: 600
        }
    </style>
</head>

<body>
    @include('layouts.header')

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
                {{-- LEFT --}}
                <div class="col-lg-7">
                    <div class="checkout-card p-4 p-md-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Thông tin giao hàng</h5>
                            <div class="small">Bạn đã có tài khoản? <a href="{{ url('/login') }}">Đăng nhập</a></div>
                        </div>

                        <form action="{{ url('/checkout/submit') }}" method="POST" id="checkout-form" novalidate>
                            @csrf
                            <input type="hidden" name="province_name" id="provinceName">
                            <input type="hidden" name="district_name" id="districtName">
                            <input type="hidden" name="ward_name" id="wardName">

                            <div class="row g-3">
                                <div class="col-12">
                                    <input type="text" name="fullname"
                                        class="form-control @error('fullname') is-invalid @enderror"
                                        placeholder="Họ và tên" value="{{ old('fullname') }}" required>
                                    @error('fullname') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-8">
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="Email" value="{{ old('email') }}" required>
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4">
                                    <input type="text" name="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        placeholder="Số điện thoại" value="{{ old('phone') }}" required>
                                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-12">
                                    <input type="text" name="address"
                                        class="form-control @error('address') is-invalid @enderror"
                                        placeholder="Địa chỉ" value="{{ old('address') }}" required>
                                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4">
                                    <select id="provinceSelect" name="province_id"
                                        class="form-select @error('province_id') is-invalid @enderror"
                                        required data-old="{{ old('province_id') }}">
                                        <option value="">Chọn tỉnh / thành</option>
                                    </select>
                                    @error('province_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4">
                                    <select id="districtSelect" name="district_id"
                                        class="form-select @error('district_id') is-invalid @enderror"
                                        required disabled data-old="{{ old('district_id') }}">
                                        <option value="">Chọn quận / huyện</option>
                                    </select>
                                    @error('district_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4">
                                    <select id="wardSelect" name="ward_id"
                                        class="form-select @error('ward_id') is-invalid @enderror"
                                        required disabled data-old="{{ old('ward_id') }}">
                                        <option value="">Chọn phường / xã</option>
                                    </select>
                                    @error('ward_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-12">
                                    <textarea name="note" rows="3"
                                        class="form-control @error('note') is-invalid @enderror"
                                        placeholder="Ghi chú (không bắt buộc)">{{ old('note') }}</textarea>
                                    @error('note') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- PHƯƠNG THỨC THANH TOÁN --}}
                                <div class="col-12">
                                    <h6 class="mt-2 mb-3">Phương thức thanh toán</h6>

                                    <div class="list-group mb-1">
                                        <label class="list-group-item d-flex align-items-center gap-3">
                                            <input class="form-check-input m-0" type="radio" name="payment_method" value="cod"
                                                {{ old('payment_method','cod') === 'cod' ? 'checked' : '' }}>
                                            <img src="{{ asset('img/cod-box.svg') }}" alt="COD" width="28" height="28" onerror="this.style.display='none'">
                                            <div>
                                                <div class="fw-semibold">Thanh toán khi giao hàng (COD)</div>
                                                <div class="text-muted small">Bạn sẽ thanh toán tiền mặt cho nhân viên giao hàng.</div>
                                            </div>
                                        </label>

                                        <label class="list-group-item d-flex align-items-center gap-3">
                                            <input class="form-check-input m-0" type="radio" name="payment_method" value="vnpay"
                                                {{ old('payment_method') === 'vnpay' ? 'checked' : '' }}>
                                            <img src="{{ asset('img/vnpay.svg') }}" alt="VNPAY" width="28" height="28" onerror="this.style.display='none'">
                                            <div>
                                                <div class="fw-semibold">Thẻ ATM/Visa/Master/JCB/QR Pay qua cổng VNPAY</div>
                                                <div class="text-muted small">Hỗ trợ hầu hết ngân hàng nội địa và thẻ quốc tế.</div>
                                            </div>
                                        </label>

                                        <div id="vnpayBox" class="list-group-item d-none">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <img src="{{ asset('images/vnpay-banks.png') }}" alt="Banks" height="24" onerror="this.style.display='none'">
                                                <span class="small text-muted">Chọn phương thức qua cổng VNPAY</span>
                                            </div>
                                            <div class="form-text mt-2">Sau khi đặt hàng, bạn sẽ được chuyển sang trang thanh toán VNPAY để hoàn tất.</div>
                                        </div>
                                    </div>
                                    @error('payment_method') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-12 mt-2">
                                    <button type="submit" class="btn btn-primary px-4 py-2">Thanh toán</button>
                                    <a href="{{ url('/cart') }}" class="btn btn-ghost mt-2">Giỏ hàng</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- RIGHT --}}
                <div class="col-lg-5">
                    <div class="cart-summary p-4 p-md-4">
                        @php
                        $cartItems = $cartItems ?? [];
                        $subtotal = (int) ($subtotal ?? 0);
                        $shipping = (int) ($shipping ?? 0);
                        $total = (int) ($total ?? ($subtotal + $shipping));
                        $appliedRaw = $appliedCoupon ?? (session('applied_coupon') ?? null);
                        $discount = (int) ($discount ?? 0);
                        $appliedCode = is_array($appliedRaw) ? ($appliedRaw['code'] ?? null)
                        : (is_string($appliedRaw) ? $appliedRaw : null);
                        @endphp

                        {{-- Sản phẩm --}}
                        <div class="mb-3">
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

                        {{-- Mã giảm giá (AJAX) --}}
                        <div id="couponBox" class="mb-3">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="coupon_code" id="couponInput"
                                    class="form-control {{ $errors->has('coupon') ? 'is-invalid' : '' }}"
                                    placeholder="Mã giảm giá"
                                    value="{{ old('coupon_code', $appliedCode ?? '') }}"
                                    {{ $appliedCode ? 'readonly' : '' }}>
                                <button class="btn btn-ghost" id="applyCouponBtn" type="button" {{ $appliedCode ? 'disabled' : '' }}>
                                    Sử dụng
                                </button>
                            </div>

                            <!-- {{-- Lỗi trả về từ submit() (server-side) --}}
                            @if($errors->has('coupon'))
                            <div id="couponError" class="invalid-feedback d-block mt-2">{{ $errors->first('coupon') }}</div>
                            @else
                            <div id="couponError" class="invalid-feedback d-block mt-2 d-none"></div>
                            @endif -->

                            {{-- Trạng thái AJAX --}}
                            <div id="couponStatus" class="small mt-2 {{ $appliedCode ? '' : 'd-none' }}">
                                @if($appliedCode)
                                <span class="text-success">
                                    <i class="bi bi-check-circle"></i>
                                    Đã áp dụng <strong id="appliedCode">{{ $appliedCode }}</strong>,
                                    giảm <strong id="appliedDiscount">{{ number_format($discount,0,',','.') }}đ</strong>.
                                </span>
                                <button class="btn btn-link btn-sm text-danger p-0 ms-2" id="removeCouponBtn" type="button">Gỡ mã</button>
                                @else
                                <span class="text-muted">Nhập mã để nhận ưu đãi.</span>
                                @endif
                            </div>

                            <div id="couponAlert" class="alert py-2 px-3 mt-2 d-none"></div>
                        </div>

                        <hr>

                        {{-- Tổng kết --}}
                        <div class="d-flex justify-content-between mb-2">
                            <span class="muted">Tạm tính</span>
                            <span id="subtotalText">{{ number_format($subtotal, 0, ',', '.') }}đ</span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="muted">Phí vận chuyển</span>
                            <span id="shippingText">{{ $shipping > 0 ? number_format($shipping, 0, ',', '.') . 'đ' : '—' }}</span>
                        </div>

                        {{-- Dòng giảm giá --}}
                        <div id="discountLine" class="justify-content-between mb-2 {{ $discount > 0 ? 'd-flex' : 'd-none' }}">
                            <span class="muted">Giảm mã <span id="discountCodeLabel">{{ $appliedCode ?? '' }}</span></span>
                            <span id="discountAmount" class="text-success">-{{ number_format($discount, 0, ',', '.') }}đ</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <h6 class="mb-0">Tổng cộng</h6>
                            <div class="fs-5 fw-bold" id="grandTotal">{{ number_format($total, 0, ',', '.') }}đ</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        /* Toggle VNPAY */
        (function() {
            const vnpayBox = document.getElementById('vnpayBox');
            const radios = document.querySelectorAll('input[name="payment_method"]');
            const toggleVnpay = () => {
                const val = document.querySelector('input[name="payment_method"]:checked')?.value;
                if (!vnpayBox) return;
                if (val === 'vnpay') vnpayBox.classList.remove('d-none');
                else vnpayBox.classList.add('d-none');
            };
            radios.forEach(r => r.addEventListener('change', toggleVnpay));
            toggleVnpay();
        })();

        /* Đổ dữ liệu tỉnh–huyện–xã */
        (function() {
            const DATA_URL = "{{ asset('data/DiaGioiHanhChinhVN-master/data.json') }}";
            const selProv = document.getElementById('provinceSelect');
            const selDist = document.getElementById('districtSelect');
            const selWard = document.getElementById('wardSelect');

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
            const reset = (sel, ph, disabled = true) => {
                sel.innerHTML = '';
                sel.appendChild(opt('', ph));
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

        /* ÁP/GỠ MÃ GIẢM GIÁ (AJAX) */
        (function() {
            const applyBtn = document.getElementById('applyCouponBtn');
            const input = document.getElementById('couponInput');
            const alertBox = document.getElementById('couponAlert');
            const statusBox = document.getElementById('couponStatus');
            const errorBlock = document.getElementById('couponError');

            const subtotalEl = document.getElementById('subtotalText');
            const shippingEl = document.getElementById('shippingText');
            const totalEl = document.getElementById('grandTotal');

            const discountLine = document.getElementById('discountLine');
            const discountAmountEl = document.getElementById('discountAmount');
            const discountCodeLbl = document.getElementById('discountCodeLabel');
            const appliedCodeEl = document.getElementById('appliedCode');
            const appliedDiscEl = document.getElementById('appliedDiscount');

            const CSRF = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const HIDE_MS = 5000;

            function fmt(n) {
                n = Math.max(0, parseInt(n || 0, 10));
                return n.toLocaleString('vi-VN') + 'đ';
            }

            // Hiện alert (2 giây) rồi tự ẩn
            function showAlert(msg, ok) {
                if (!alertBox) return;
                alertBox.classList.remove('d-none', 'alert-danger', 'alert-success');
                alertBox.classList.add(ok ? 'alert-success' : 'alert-danger');
                alertBox.textContent = msg || (ok ? 'Thành công' : 'Có lỗi xảy ra');
                setTimeout(() => alertBox.classList.add('d-none'), HIDE_MS);
            }

            // Hiện lỗi ngay dưới ô mã + viền đỏ và tự ẩn sau 2 giây
            function showInlineError(msg) {
                if (input) {
                    input.classList.add('is-invalid');
                }
                if (errorBlock) {
                    errorBlock.textContent = msg;
                    errorBlock.classList.remove('d-none');
                }
                showAlert(msg, false);
                setTimeout(() => {
                    if (input) {
                        input.classList.remove('is-invalid');
                    }
                    if (errorBlock) {
                        errorBlock.classList.add('d-none');
                    }
                }, HIDE_MS);
            }

            async function postJSON(url, payload) {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload || {})
                });
                let data = {};
                try {
                    data = await res.json();
                } catch (_) {}
                if (!res.ok || data.ok === false) throw (data?.message || 'Mã giảm giá không hợp lệ.');
                return data;
            }

            function showDiscountLine(show) {
                if (!discountLine) return;
                if (show) {
                    discountLine.classList.remove('d-none');
                    discountLine.classList.add('d-flex');
                } else {
                    discountLine.classList.add('d-none');
                    discountLine.classList.remove('d-flex');
                }
            }

            // Áp mã
            applyBtn?.addEventListener('click', async () => {
                const code = (input?.value || '').trim();
                if (!code) {
                    showInlineError('Vui lòng nhập mã giảm giá.');
                    return;
                }

                applyBtn.disabled = true;
                if (input) {
                    input.classList.remove('is-invalid');
                }
                if (errorBlock) {
                    errorBlock.classList.add('d-none');
                }

                try {
                    const data = await postJSON(`{{ url('/checkout/apply-coupon') }}`, {
                        coupon_code: code
                    });

                    subtotalEl && (subtotalEl.textContent = fmt(data.subtotal));
                    shippingEl && (shippingEl.textContent = data.shipping > 0 ? fmt(data.shipping) : '—');
                    totalEl && (totalEl.textContent = fmt(data.total));

                    showDiscountLine(true);
                    discountAmountEl.textContent = '-' + (parseInt(data.discount, 10) || 0).toLocaleString('vi-VN') + 'đ';
                    discountCodeLbl.textContent = data.coupon_code;

                    input.readOnly = true;
                    applyBtn.disabled = true;

                    statusBox?.classList.remove('d-none');
                    if (appliedCodeEl) appliedCodeEl.textContent = data.coupon_code;
                    if (appliedDiscEl) appliedDiscEl.textContent = fmt(data.discount);

                    if (!document.getElementById('removeCouponBtn')) {
                        const btn = document.createElement('button');
                        btn.id = 'removeCouponBtn';
                        btn.type = 'button';
                        btn.className = 'btn btn-link btn-sm text-danger p-0 ms-2';
                        btn.textContent = 'Gỡ mã';
                        btn.addEventListener('click', removeHandler);
                        statusBox.appendChild(btn);
                    } else {
                        document.getElementById('removeCouponBtn').disabled = false;
                    }

                    showAlert(data.message || 'Áp mã thành công!', true);
                } catch (e) {

                    showInlineError(String(e));
                    applyBtn.disabled = false;
                }
            });

            // Gỡ mã
            async function removeHandler() {
                const btn = document.getElementById('removeCouponBtn');
                btn && (btn.disabled = true);
                try {
                    const data = await postJSON(`{{ url('/checkout/remove-coupon') }}`, {});

                    subtotalEl && (subtotalEl.textContent = fmt(data.subtotal));
                    shippingEl && (shippingEl.textContent = data.shipping > 0 ? fmt(data.shipping) : '—');
                    totalEl && (totalEl.textContent = fmt(data.total));

                    showDiscountLine(false);

                    input.readOnly = false;
                    input.value = '';
                    applyBtn.disabled = false;

                    // Clear lỗi (nếu còn)
                    if (input) {
                        input.classList.remove('is-invalid');
                    }
                    if (errorBlock) {
                        errorBlock.classList.add('d-none');
                    }

                    if (statusBox) {
                        statusBox.innerHTML = '<span class="text-muted">Nhập mã để nhận ưu đãi.</span>';
                    }

                    showAlert(data.message || 'Đã gỡ mã giảm giá.', true);
                } catch (e) {
                    showInlineError(String(e));
                    btn && (btn.disabled = false);
                }
            }
            document.getElementById('removeCouponBtn')?.addEventListener('click', removeHandler);
        })();
    </script>
</body>

</html>