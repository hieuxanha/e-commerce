<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class OrderPlacedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order->relationLoaded('items') ? $order : $order->load('items');
    }

    public function build()
    {
        // Logo (tùy chọn)
        $logoCid = null;
        try {
            $logo = public_path('images/logo.png');
            if (is_readable($logo)) {
                $logoCid = $this->embed($logo);
            }
        } catch (\Throwable $e) {
            Log::warning('MAIL_EMBED_LOGO_FAIL', ['err' => $e->getMessage()]);
        }

        // 🔴 values(): đảm bảo key = 0..n trùng $loop->index
        $embeddedCids = [];

        foreach ($this->order->items->values() as $i => $it) {
            $embeddedCids[$i] = null;

            try {
                $raw = $it->image ?? null;
                if (!$raw) continue;

                // URL nội bộ (APP_URL/storage/...) -> đổi thành relative "products/xxx.jpg"
                if (preg_match('#^https?://#i', $raw)) {
                    $raw = $this->convertAppUrlToRel($raw) ?? null;
                    if (!$raw) continue; // URL ngoài: để Blade tự fallback dùng URL
                }

                // /storage/products/x.png -> products/x.png
                $rel = ltrim(preg_replace('#^/?storage/#i', '', $raw), '/');

                // Thử theo đúng nơi bạn đang có file
                $candidates = [
                    public_path('storage/' . $rel),          // public/storage/products/xxx.jpg
                    Storage::disk('public')->path($rel),   // storage/app/public/products/xxx.jpg
                ];

                foreach ($candidates as $p) {
                    if (is_readable($p)) {
                        $embeddedCids[$i] = $this->embed($p); // => "cid:...."
                        break;
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('MAIL_EMBED_ITEM_FAIL', ['idx' => $i, 'err' => $e->getMessage()]);
            }
        }

        return $this->subject('Xác nhận đơn hàng ' . $this->order->code)
            ->view('emails.order_placed')
            ->with([
                'order'        => $this->order,
                'logoCid'      => $logoCid,
                'embeddedCids' => $embeddedCids,
            ]);
    }

    private function convertAppUrlToRel(string $url): ?string
    {
        $app = rtrim(config('app.url'), '/'); // ví dụ http://127.0.0.1:8000
        if ($app && str_starts_with($url, $app . '/storage/')) {
            return ltrim(substr($url, strlen($app . '/storage/')), '/'); // products/xxx.jpg
        }
        return null;
    }
}
