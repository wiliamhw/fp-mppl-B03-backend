<?php

namespace App\Models;

use App\Models\Concerns\OldDateSerializer;
use App\Models\PaymentLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserWebinar extends Pivot
{
    use HasFactory;
    use OldDateSerializer;

    const PAYMENT_ON_PROGRESS = 'on-progress';
    const PAYMENT_FAILED = 'gagal';
    const PAYMENT_SUCCESS = 'berhasil';

    const PAYMENT_STATUS = [
        self::PAYMENT_ON_PROGRESS,
        self::PAYMENT_FAILED,
        self::PAYMENT_SUCCESS
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'webinar_id',
        'payment_status',
        'payment_method',
        'feedback',
        'payment_token',
    ];

//    /**
//     * Model relationship definition.
//     * UserWebinar has many PaymentLogs
//     *
//     * @return HasMany
//     */
//    public function paymentLogs(): HasMany
//    {
//        return $this->hasMany(PaymentLog::class, 'user_webinar_id');
//    }
}
