<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TonKhoController extends Controller
{
    public function index(Request $request)
    {
        $q = Product::query()->orderByDesc('ngay_cap_nhat'); // đúng tên cột trong DB

        if ($s = $request->q) {
            $q->where(function ($x) use ($s) {
                $x->where('ten_san_pham', 'like', "%{$s}%")
                    ->orWhere('sku', 'like', "%{$s}%");
            });
        }
        if ($st = $request->status) {
            $q->where('trang_thai', $st);
        }
        if ($stock = $request->stock) {
            if ($stock === 'zero') $q->where('so_luong_ton_kho', 0);
            elseif ($stock === 'low') $q->where('so_luong_ton_kho', '>', 0)->where('so_luong_ton_kho', '<', 5);
            elseif ($stock === 'ok')  $q->where('so_luong_ton_kho', '>=', 5);
        }

        $products = $q->paginate(12)->withQueryString();

        return view('admin.QL_tonkho', compact('products'));
    }

    // +/- nhanh
    public function adjust(Request $request, Product $product)
    {
        $request->validate(['delta' => 'required|integer|min:-9999|max:9999']);
        $newQty = max(0, ($product->so_luong_ton_kho ?? 0) + (int)$request->delta);
        $product->so_luong_ton_kho = $newQty;
        $product->save();

        return back()->with('success', 'Đã cập nhật tồn kho.');
    }

    // Đặt số lượng tuyệt đối
    public function setQty(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'so_luong_ton_kho' => 'required|integer|min:0|max:1000000',
        ]);

        $p = Product::findOrFail($data['product_id']);
        $p->so_luong_ton_kho = (int)$data['so_luong_ton_kho'];
        $p->save();

        return back()->with('success', 'Đã đặt lại số lượng tồn.');
    }

    // Xuất CSV (id, sku, so_luong_ton_kho)
    public function export(Request $request)
    {
        $rows = Product::select('id', 'sku', 'so_luong_ton_kho')->orderBy('id')->get();
        $csv = "id,sku,so_luong_ton_kho\n";
        foreach ($rows as $r) {
            $csv .= "{$r->id},\"{$r->sku}\",{$r->so_luong_ton_kho}\n";
        }
        $filename = 'ton_kho_' . now()->format('Ymd_His') . '.csv';
        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

    // Nhập CSV (cột: id, so_luong_ton_kho) hoặc (id, delta)
    public function bulkAdjust(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:csv,txt|max:2048']);
        $fh = fopen($request->file('file')->getRealPath(), 'r');
        $header = fgetcsv($fh);
        $header = array_map('trim', $header ?: []);

        $modeAbsolute = in_array('so_luong_ton_kho', $header);
        $modeDelta    = in_array('delta', $header);

        if (!$modeAbsolute && !$modeDelta) {
            return back()->with('error', 'CSV phải có cột "so_luong_ton_kho" hoặc "delta".');
        }

        $idxId = array_search('id', $header);
        $idxQty = $modeAbsolute ? array_search('so_luong_ton_kho', $header) : -1;
        $idxDelta = $modeDelta ? array_search('delta', $header) : -1;

        $count = 0;
        while (($row = fgetcsv($fh)) !== false) {
            if ($idxId === false || !isset($row[$idxId])) continue;
            $id = (int) $row[$idxId];
            $p = Product::find($id);
            if (!$p) continue;

            if ($modeAbsolute && isset($row[$idxQty])) {
                $p->so_luong_ton_kho = max(0, (int)$row[$idxQty]);
            } elseif ($modeDelta && isset($row[$idxDelta])) {
                $p->so_luong_ton_kho = max(0, ($p->so_luong_ton_kho ?? 0) + (int)$row[$idxDelta]);
            } else continue;

            $p->save();
            $count++;
        }
        fclose($fh);

        return back()->with('success', "Đã xử lý {$count} dòng CSV.");
    }
}
