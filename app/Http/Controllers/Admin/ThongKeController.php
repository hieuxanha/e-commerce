<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ThongKeController extends Controller
{
    public function index(Request $request)
    {
        // Khoảng ngày (mặc định 30 ngày gần nhất) + chống chọn ngược
        $from = $request->date_from ?: now()->subDays(30)->toDateString();
        $to   = $request->date_to   ?: now()->toDateString();
        if ($from > $to) {
            [$from, $to] = [$to, $from];
        }

        // 1) Doanh thu theo ngày (LINE & cũng dùng cho BAR nếu bạn muốn)
        $revenueRows = DB::table('orders')
            ->selectRaw('DATE(created_at) as d, SUM(total) as sum_total')
            ->where('payment_status', 'da_thanh_toan')
            ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
            ->groupBy('d')
            ->orderBy('d')
            ->get();

        $lineLabels = $revenueRows->pluck('d')->map(fn($d) => date('d/m', strtotime($d)))->values();
        $lineData   = $revenueRows->pluck('sum_total')->map(fn($v) => (int) $v)->values();

        // 2) Doanh thu theo SẢN PHẨM (BAR) – KHÔNG còn dùng số lượng nữa
        $topProductRows = DB::table('order_items as oi')
            ->join('orders as o', 'o.id', '=', 'oi.order_id')
            ->where('o.payment_status', 'da_thanh_toan')
            ->whereBetween(DB::raw('DATE(o.created_at)'), [$from, $to])
            ->selectRaw('oi.product_id, oi.product_name, SUM(oi.total) as sum_total')
            ->groupBy('oi.product_id', 'oi.product_name')
            ->orderByDesc('sum_total')
            ->limit(10)
            ->get();

        $barLabels = $topProductRows->pluck('product_name')->values();
        $barData   = $topProductRows->pluck('sum_total')->map(fn($v) => (int) $v)->values();

        // 3) Tỉ trọng doanh thu theo PHƯƠNG THỨC THANH TOÁN (PIE)
        $payRows = DB::table('orders')
            ->selectRaw('payment_method, SUM(total) as sum_total')
            ->where('payment_status', 'da_thanh_toan')
            ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
            ->groupBy('payment_method')
            ->orderBy('payment_method')
            ->get();

        $methodMap = ['cod' => 'COD', 'vnpay' => 'VNPAY'];
        $pieLabels = $payRows->pluck('payment_method')->map(fn($m) => $methodMap[$m] ?? $m)->values();
        $pieData   = $payRows->pluck('sum_total')->map(fn($v) => (int) $v)->values();

        return view('admin.QL_Thongke', compact(
            'from',
            'to',
            'lineLabels',
            'lineData',
            'barLabels',
            'barData',
            'pieLabels',
            'pieData'
        ));
    }
}
