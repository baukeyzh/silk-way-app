<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PhoneVerification extends Model
{
    protected $fillable = [
        'phone',
        'purpose',
        'code_hash',
        'attempts',
        'expires_at',
        'last_sent_at',
    ];

    protected $casts = [
        'expires_at'   => 'datetime',
        'last_sent_at' => 'datetime',
        'attempts'     => 'integer',
    ];

    public const PURPOSE_DRIVER_REGISTRATION = 'driver_registration';
    public const PURPOSE_DRIVER_LOGIN        = 'driver_login';
    public const MAX_ATTEMPTS               = 5;
    public const CODE_TTL_SECONDS           = 600; // 10 minutes
    public const RESEND_THROTTLE_SECONDS    = 60;

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isMaxAttemptsReached(): bool
    {
        return $this->attempts >= self::MAX_ATTEMPTS;
    }

    public function canResend(): bool
    {
        return $this->last_sent_at->diffInSeconds(Carbon::now()) >= self::RESEND_THROTTLE_SECONDS;
    }

    public function secondsUntilResendAllowed(): int
    {
        $elapsed = (int) $this->last_sent_at->diffInSeconds(Carbon::now());
        return max(0, self::RESEND_THROTTLE_SECONDS - $elapsed);
    }
}
