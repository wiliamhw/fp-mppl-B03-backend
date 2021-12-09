<?php

namespace App\Models;

use App\Models\Concerns\OldDateSerializer;
use App\Models\PaymentLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserWebinar extends Pivot
{
    use HasFactory;
    use OldDateSerializer;

    const PAYMENT_IN_PROGRESS = 'menunggu_pembayaran';
    const PAYMENT_FAILED = 'gagal';
    const PAYMENT_SUCCESS = 'berhasil';

    const PAYMENT_STATUS = [
        self::PAYMENT_IN_PROGRESS,
        self::PAYMENT_FAILED,
        self::PAYMENT_SUCCESS
    ];

    const PAYMENT_STATUS_NAME = [
        self::PAYMENT_IN_PROGRESS   => 'Menunggu Pembayaran',
        self::PAYMENT_FAILED        => 'Gagal',
        self::PAYMENT_SUCCESS       => 'Sukses',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'webinar_id',
        'payment_method',
        'feedback',
        'payment_token',
    ];

    /**
     * Model relationship definition.
     * User belongs to a Webinars
     *
     * @return BelongsTo
     */
    public function webinar(): BelongsTo
    {
        return $this->BelongsTo(Webinar::class);
    }

    /**
     * Model relationship definition.
     * User belongs to a User
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }

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
