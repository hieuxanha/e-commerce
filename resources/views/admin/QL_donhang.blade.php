{{-- resources/views/admin/donhang_show.blade.php --}}
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Chi tiết đơn #{{ $order->code }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .subtle {
            color: #6c757d;
            font-size: .95rem
        }

        .kv {
            min-width: 160px
        }
    </style>
</head>

<body class="bg-light">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h4 m-0">Chi tiết đơn hàng</h1>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">← Quay lại danh sách</a>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="fw-semibold mb-1">Mã đơn</div>
                        <div>{{ $order->code }}</div>
                        <div class="subtle mt-2">Tạo lúc: {{ $order->created_at?->format('d/m/Y H:i') }}</div>
                        @if($order->paid_at)
                        <div class="subtle">Thanh toán lúc: {{ \Illuminate\Support\Carbon::parse($order->paid_at)->format('d/m/Y H:i') }}</div>
                        @endif
                    </div>

                    <div class="col-md-4">
                        <div class="fw-semibold mb-1">Khách đặt (tài khoản)</div>
                        @if($order->user)
                        <div>{{ $order->user->name }}</div>
                        <div class="subtle">{{ $order->user->email }} — {{ $order->user->phone }}</div>
                        @else
                        <div class="subtle">— Không có tài khoản —</div>
                        @endif
                    </div>

                    <div class="col-md-4">
                        <div class="fw-semibold mb-1">Người nhận</div>
                        <div>{{ $order->fullname }} — {{ $order->phone }}</div>
                        <div class="subtle">
                            {{ $order->address }}
                            @if($order->ward_name), {{ $order->ward_name }} @endif
                            @if($order->district_name), {{ $order->district_name }} @endif
                            @if($order->province_name), {{ $order->province_name }} @endif
                        </div>
                    </div>
                </div>

                <hr class="my-3">

                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="fw-semibold mb-1">Thanh toán</div>
                        <div>PTTT: <span class="badge text-bg-secondary">{{ strtoupper($order->payment_method ?? '—') }}</span></div>
                        <div>Trạng thái:
                            @php
                            $PAYS = [
                            'chua_thanh_toan'=>'Chưa thanh toán',
                            'da_thanh_toan'=>'Đã thanh toán',
                            'that_bai'=>'Thất bại',
                            'hoan_tien'=>'Hoàn tiền'
                            ];
                            @endphp
                            <span class="badge text-bg-{{ [
              'chua_thanh_toan'=>'warning text-dark',
              'da_thanh_toan'=>'success',
              'that_bai'=>'danger',
              'hoan_tien'=>'secondary'
            ][$order->payment_status] ?? 'secondary' }}">
                                {{ $PAYS[$order->payment_status] ?? $order->payment_status }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="fw-semibold mb-1">Vận chuyển</div>
                        @php
                        $STATES = [
                        'da_dat'=>'Đã đặt',
                        'cho_chuyen_phat'=>'Chờ chuyển phát',
                        'dang_trung_chuyen'=>'Đang trung chuyển',
                        'da_giao'=>'Đã giao'
                        ];
                        @endphp
                        <div>Trạng thái: <span class="badge text-bg-{{ [
            'da_dat'=>'secondary','cho_chuyen_phat'=>'warning',
            'dang_trung_chuyen'=>'info','da_giao'=>'success'
          ][$order->status] ?? 'secondary' }}">{{ $STATES[$order->status] ?? $order->status }}</span></div>

                        {{-- Form cập nhật trạng thái nhanh --}}
                        <form class="mt-2 d-flex gap-2" method="POST" action="{{ route('admin.orders.updateStatus', $order) }}">
                            @csrf @method('PATCH')
                            <select name="status" class="form-select form-select-sm" style="max-width:220px">
                                @foreach(array_keys($STATES) as $st)
                                <option value="{{ $st }}" @selected($order->status===$st)>{{ $STATES[$st] }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-sm btn-primary">Cập nhật</button>
                        </form>
                    </div>

                    <div class="col-md-4">
                        <div class="fw-semibold mb-1">Tổng tiền</div>
                        <div class="d-flex justify-content-between"><span class="subtle">Tạm tính</span><span>{{ number_format($order->subtotal,0,',','.') }}đ</span></div>
                        <div class="d-flex justify-content-between"><span class="subtle">Phí vận chuyển</span><span>{{ number_format($order->shipping_fee,0,',','.') }}đ</span></div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between fw-bold"><span>Tổng</span><span>{{ number_format($order->total,0,',','.') }}đ</span></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Danh sách sản phẩm trong đơn --}}
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Sản phẩm</th>
                                <th class="text-end">Đơn giá</th>
                                <th class="text-end">SL</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->items as $i)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $i->product_name }}</div>
                                    @if($i->image)
                                    <div class="subtle">Ảnh: {{ $i->image }}</div>
                                    @endif
                                </td>
                                <td class="text-end">{{ number_format($i->price,0,',','.') }}đ</td>
                                <td class="text-end">{{ $i->quantity }}</td>
                                <td class="text-end fw-semibold">{{ number_format($i->total,0,',','.') }}đ</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted p-4">Không có sản phẩm.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>