<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'name',
        'phone',
        'gender',
        'dob',
        'password',
        'address',
        'role',
        'membership_level', // <-- thêm dòng này
    ];

    protected $hidden = ['password'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }

    /** Các trạng thái đơn được coi là đã giao (đổi theo DB của bạn nếu khác) */
    public static function deliveredStatuses(): array
    {
        return ['da_giao', 'delivered'];
    }

    /** Tổng tiền các đơn đã giao */
    public function totalSpent(): int
    {
        return (int) $this->orders()
            ->whereIn('status', self::deliveredStatuses())
            ->sum('total');
    }

    /** Tính cấp bậc theo tổng chi tiêu (tuỳ chỉnh ngưỡng tại đây) */
    public static function levelByTotal(int $total): string
    {
        return match (true) {
            $total >= 3_000_000 => 'kim_cuong',
            $total >= 2_000_000 => 'vang',
            $total >= 5_000_000  => 'bac',
            default               => 'dong',
        };
    }

    /**
     * Đồng bộ cột membership_level theo tổng chi tiêu và trả về thông tin
     * để view hiển thị tiến độ/bao nhiêu nữa lên bậc.
     */
    public function syncMembershipLevel(): array
    {
        $thresholds = [
            'dong'      => 0,
            'bac'       => 1_000_000,
            'vang'      => 2_000_000,
            'kim_cuong' => 3_000_000,
        ];
        $ladder = ['dong' => 'bac', 'bac' => 'vang', 'vang' => 'kim_cuong', 'kim_cuong' => null];

        $total = $this->totalSpent();
        $level = self::levelByTotal($total);

        if ($this->membership_level !== $level) {
            $this->membership_level = $level;
            $this->saveQuietly();
        }

        $next = $ladder[$level] ?? null;
        $need = $next ? max(0, $thresholds[$next] - $total) : 0;

        $curMin = $thresholds[$level];
        $curMax = $next ? $thresholds[$next] : $thresholds['kim_cuong'];
        $progress = $curMax > $curMin
            ? (int) round(100 * min(max($total - $curMin, 0), $curMax - $curMin) / ($curMax - $curMin))
            : 100;

        return [
            'total_spent' => $total,
            'level'       => $level,
            'next_level'  => $next,
            'need_next'   => $need,
            'progress'    => $progress,
            'thresholds'  => $thresholds,
        ];
    }
}
