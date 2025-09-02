<!DOCTYPE html>
<html lang="vi">

<body style="font-family:Arial, sans-serif; line-height:1.5; color:#111; margin:0; padding:0;">

    <div style="padding:16px 20px; border-bottom:1px solid #eee;">
        @if(!empty($logoCid))
        <img src="{{ $logoCid }}" alt="Logo" style="height:48px;">
        @else
        <strong>{{ config('app.name','Your Shop') }}</strong>
        @endif
    </div>

    <div style="padding:20px;">
        <h2 style="margin-top:0;">Cảm ơn bạn đã đặt hàng ❤️</h2>
        <p>Mã đơn: <strong>{{ $order->code }}</strong></p>
        <p>Khách hàng: {{ $order->fullname }} ({{ $order->phone }})</p>
        <p>Địa chỉ: {{ $order->address }}, {{ $order->ward_name }}, {{ $order->district_name }}, {{ $order->province_name }}</p>

        <h3>Chi tiết đơn hàng</h3>
        <table width="100%" cellpadding="6" cellspacing="0" style="border-collapse:collapse">
            <thead>
                <tr style="background:#f7f7f7;">
                    <th align="left">Sản phẩm</th>
                    <th align="right">SL</th>
                    <th align="right">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $it)
                <tr>
                    <td style="vertical-align:middle;">
                        @php
                        $idx = $loop->index;
                        $cid = $embeddedCids[$idx] ?? null;

                        if ($cid) {
                        $src = $cid; // ✅ ảnh embed
                        } else {
                        $raw = $it->image ?? null;
                        if (!$raw) {
                        $src = asset('images/no-image.png');
                        } elseif (preg_match('#^https?://#', $raw)) {
                        $src = $raw; // ảnh ngoài
                        } elseif (preg_match('#^/?storage/#', $raw)) {
                        $src = url($raw); // /storage/products/xxx.jpg
                        } else {
                        $src = asset('storage/'.ltrim($raw,'/')); // products/xxx.jpg
                        }
                        }
                        @endphp

                        <img src="{{ $src }}" alt="SP"
                            style="width:48px;height:48px;object-fit:cover;border-radius:6px;vertical-align:middle;margin-right:8px;">
                        <span style="vertical-align:middle;">{{ $it->product_name }}</span>
                    </td>
                    <td align="right" style="vertical-align:middle;">{{ $it->quantity }}</td>
                    <td align="right" style="vertical-align:middle;">{{ number_format($it->total,0,',','.') }}đ</td>
                </tr>
                @endforeach

                <tr>
                    <td colspan="3">
                        <hr style="border:none;border-top:1px solid #eee;">
                    </td>
                </tr>
                <tr>
                    <td align="left">Tạm tính</td>
                    <td></td>
                    <td align="right">{{ number_format($order->subtotal,0,',','.') }}đ</td>
                </tr>
                <tr>
                    <td align="left">Phí vận chuyển</td>
                    <td></td>
                    <td align="right">{{ number_format($order->shipping_fee,0,',','.') }}đ</td>
                </tr>
                <tr>
                    <td align="left"><strong>Tổng cộng</strong></td>
                    <td></td>
                    <td align="right"><strong>{{ number_format($order->total,0,',','.') }}đ</strong></td>
                </tr>
            </tbody>
        </table>

        <p>Phương thức thanh toán: {{ strtoupper($order->payment_method) }}</p>
        <p>Trạng thái thanh toán:
            <strong>
                @switch($order->payment_status)
                @case('da_thanh_toan') Đã thanh toán @break
                @case('that_bai') Thất bại @break
                @case('hoan_tien') Hoàn tiền @break
                @default Chưa thanh toán
                @endswitch
            </strong>
        </p>

        <p style="margin-top:24px">Mọi thắc mắc vui lòng phản hồi email này. Cảm ơn bạn đã mua sắm!</p>
    </div>

    <div style="padding:12px 20px; border-top:1px solid #eee; color:#6b7280; font-size:12px;">
        © {{ date('Y') }} {{ config('app.name','Your Shop') }}. All rights reserved.
    </div>

</body>

</html>