<?php

namespace App\Services;

class VnpayService
{
    public function createPaymentUrl(array $params): string
    {
        $tmnCode   = config('vnpay.tmn');
        $secretKey = config('vnpay.hash_secret');
        $vnpUrl    = config('vnpay.url');

        // Chuẩn hóa/thêm mặc định
        $input = [
            'vnp_Version'   => config('vnpay.version', '2.1.0'),
            'vnp_Command'   => config('vnpay.command', 'pay'),
            'vnp_TmnCode'   => $tmnCode,
            'vnp_Amount'    => (int) $params['amount'] * 100, // BẮT BUỘC *100 và là integer
            'vnp_CurrCode'  => config('vnpay.curr', 'VND'),
            'vnp_TxnRef'    => (string)($params['txn_ref'] ?? now()->format('YmdHis') . rand(1000, 9999)),
            // OrderInfo KHÔNG DẤU
            'vnp_OrderInfo' => $this->stripAccents($params['order_info'] ?? ('Thanh toan don hang ' . ($params['txn_ref'] ?? ''))),
            'vnp_Locale'    => config('vnpay.locale', 'vn'),
            'vnp_ReturnUrl' => config('vnpay.return_url'),
            'vnp_IpAddr'    => $this->clientIp(),
            'vnp_CreateDate' => now('Asia/Ho_Chi_Minh')->format('YmdHis'),
        ];

        // Sort + build string ký theo RFC3986
        ksort($input);
        $queryToSign = urldecode(http_build_query($input, '', '&', PHP_QUERY_RFC3986));
        $secureHash  = hash_hmac('sha512', $queryToSign, $secretKey);

        // URL cuối cùng
        $finalUrl = $vnpUrl . '?' . http_build_query($input, '', '&', PHP_QUERY_RFC3986)
            . '&vnp_SecureHash=' . $secureHash;

        // Log để debug khi cần
        logger()->info('VNPAY: signed_query', ['query' => $queryToSign]);
        logger()->info('VNPAY: redirect', ['url' => $finalUrl]);

        return $finalUrl;
    }

    public function verifyReturn(array $query): bool
    {
        $secretKey = config('vnpay.hash_secret');

        // Lấy hash trả về rồi bỏ khỏi mảng
        $vnp_SecureHash = $query['vnp_SecureHash'] ?? null;
        unset($query['vnp_SecureHash'], $query['vnp_SecureHashType']);

        ksort($query);
        $queryToSign = urldecode(http_build_query($query, '', '&', PHP_QUERY_RFC3986));
        $calcHash    = hash_hmac('sha512', $queryToSign, $secretKey);

        logger()->info('VNPAY: verify', ['query' => $queryToSign, 'calc' => $calcHash, 'recv' => $vnp_SecureHash]);

        return hash_equals($calcHash, $vnp_SecureHash ?? '');
    }

    private function clientIp(): string
    {
        // Trả IP hợp lệ (tránh 127.0.0.1 nếu có reverse proxy)
        $keys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];
        foreach ($keys as $key) {
            $ipList = request()->server($key);
            if ($ipList) {
                foreach (explode(',', $ipList) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP)) {
                        return $ip;
                    }
                }
            }
        }
        // Fallback
        return request()->ip();
    }

    private function stripAccents(string $str): string
    {
        $str = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);
        return preg_replace('/[^A-Za-z0-9 \-\_\.#]/', ' ', $str);
    }
}
