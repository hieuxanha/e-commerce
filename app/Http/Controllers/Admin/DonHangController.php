<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DonHangController extends Controller
{
    /**
     * GET /admin/don-hang
     * Query hỗ trợ:
     * - code: tìm theo mã đơn (like)
     * - q: tìm chung theo họ tên / email / SĐT / địa chỉ
     * - status: trạng thái vận chuyển
     * - payment_status: trạng thái thanh toán
     * - payment_method: phương thức thanh toán
     * - date_from, date_to (YYYY-MM-DD)
     * - sort: cột sắp xếp (created_at|updated_at|total), dir: asc|desc
     * - export=csv: xuất CSV (dùng cùng bộ lọc hiện tại)
     */
    public function index(Request $request)
    {
        $builder = Order::query()
            ->with(['user:id,name,email']) // nạp thông tin tài khoản đặt

            // lọc mã đơn
            ->when(
                $request->filled('code'),
                fn($q) =>
                $q->where('code', 'like', '%' . trim($request->code) . '%')
            )

            // tìm kiếm chung cả người nhận và tài khoản
            ->when($kw = trim((string)$request->q), function ($q) use ($kw) {
                $q->where(function ($qq) use ($kw) {
                    // người nhận (recipient)
                    $qq->where('fullname', 'like', "%{$kw}%")
                        ->orWhere('email',   'like', "%{$kw}%")
                        ->orWhere('phone',   'like', "%{$kw}%")
                        ->orWhere('address', 'like', "%{$kw}%");
                })->orWhereHas('user', function ($u) use ($kw) {
                    // tài khoản đặt
                    $u->where('name',  'like', "%{$kw}%")
                        ->orWhere('email', 'like', "%{$kw}%");
                });
            })

            // trạng thái
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('payment_status'), fn($q) => $q->where('payment_status', $request->payment_status))
            ->when($request->filled('payment_method'), fn($q) => $q->where('payment_method', $request->payment_method))

            // ngày
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->filled('date_to'),   fn($q) => $q->whereDate('created_at', '<=', $request->date_to));

        // sắp xếp
        $sortable = ['created_at', 'updated_at', 'total'];
        $sort = in_array($request->get('sort'), $sortable) ? $request->get('sort') : 'created_at';
        $dir  = $request->get('dir') === 'asc' ? 'asc' : 'desc';
        $builder->orderBy($sort, $dir);

        // export CSV (bổ sung cột user)
        if ($request->get('export') === 'csv') {
            return $this->exportCsv(clone $builder);
        }

        $orders = $builder->paginate(7)->withQueryString();
        return view('admin.QL_donhang', compact('orders'));
    }


    /**
     * PATCH /admin/don-hang/{order}/status
     * Cập nhật trạng thái 1 đơn.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:da_dat,cho_chuyen_phat,dang_trung_chuyen,da_giao',
        ]);

        $data = ['status' => $validated['status']];

        // Nếu chuyển sang "đã giao" → đánh dấu đã thanh toán + thời điểm
        if ($validated['status'] === 'da_giao') {
            $data['payment_status'] = 'da_thanh_toan';
            $data['paid_at']        = now();
        }

        $order->update($data);

        // (tuỳ chọn) gửi email khi đã giao — cần cấu hình mail & Mailable
        // if ($validated['status'] === 'da_giao' && $order->email) {
        //     try { Mail::to($order->email)->send(new \App\Mail\OrderDeliveredMail($order)); } catch (\Throwable $e) {}
        // }

        return back()->with('success', 'Cập nhật trạng thái thành công.');
    }

    /**
     * POST /admin/don-hang/bulk-update
     * Cập nhật nhiều đơn cùng lúc.
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'integer|exists:orders,id',
            'status' => 'required|in:da_dat,cho_chuyen_phat,dang_trung_chuyen,da_giao',
        ]);

        $update = ['status' => $validated['status']];

        if ($validated['status'] === 'da_giao') {
            $update['payment_status'] = 'da_thanh_toan';
            $update['paid_at']        = now();
        }

        Order::whereIn('id', $validated['order_ids'])->update($update);

        return back()->with('success', 'Đã cập nhật trạng thái cho các đơn đã chọn.');
    }

    /**
     * Xuất CSV theo bộ lọc hiện tại.
     */
    protected function exportCsv($query): StreamedResponse
    {
        $filename = 'orders_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        $columns = [
            'id',
            'code',
            // tài khoản đặt
            'user.name',
            'user.email',
            // người nhận
            'fullname',
            'email',
            'phone',
            'address',
            'province_name',
            'district_name',
            'ward_name',
            'total',
            'payment_status',
            'payment_method',
            'payment_ref',
            'status',
            'paid_at',
            'created_at',
            'updated_at',
        ];


        return response()->stream(function () use ($query, $columns) {
            $out = fopen('php://output', 'w');

            // BOM UTF-8 để mở Excel không lỗi font
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($out, $columns);

            $query->chunk(500, function ($rows) use ($out, $columns) {
                foreach ($rows as $r) {
                    $row = [];
                    foreach ($columns as $c) {
                        $row[] = data_get($r, $c);
                    }
                    fputcsv($out, $row);
                }
            });

            fclose($out);
        }, 200, $headers);
    }
}
