<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Concerns\OldDateSerializer;
use App\Models\User;
use App\Models\UserWebinar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Webinar extends Model
{
    use HasFactory;
    use OldDateSerializer;

    const TYPE_PAID = 'Berbayar';
    const TYPE_FREE = 'Gratis';

    const TYPE = [
        self::TYPE_PAID,
        self::TYPE_FREE
    ];

    const STATUS_HAS_START      = 'Sudah dimulai';
    const STATUS_HASNT_START    = 'Belum dimulai';

    const STATUS = [
        self::STATUS_HAS_START,
        self::STATUS_HASNT_START
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var string[]
     */
    protected $dates = [
        'start_at',
        'end_at',
        'published_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'category_id',
        'title',
        'description',
        'start_at',
        'end_at',
        'price',
        'type',
        'zoom_id',
        'max_participants',
        'published_at',
    ];

    /**
     * Model relationship definition.
     * Webinar belongs to Category
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

//    /**
//     * Model relationship definition.
//     * Webinar has many UserWebinars
//     *
//     * @return HasMany
//     */
//    public function userWebinars(): HasMany
//    {
//        return $this->hasMany(UserWebinar::class, 'webinar_id');
//    }

//    /**
//     * Model relationship definition.
//     * Webinar belongs to many Users
//     *
//     * @return BelongsToMany
//     */
//    public function users(): BelongsToMany
//    {
//        return $this->belongsToMany(User::class, 'user_webinar');
//    }

    /**
     * Check whether current webinar is published or not.
     *
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->published_at !== null;
    }

    /**
     * Generate type based on price.
     *
     */
    public function generateType(): void
    {
        $this['type'] = ($this->price === 0) ? self::TYPE_FREE : self::TYPE_PAID;
    }

    /**
     * Get webinar status based on start_at and end_at.
     *
     * @return string
     */
    public function getStatusAttribute(): string
    {
        return ($this->end_at <= now()) ? self::STATUS_HAS_START : self::STATUS_HASNT_START;
    }

    public function scopeExpired(Builder $query, bool $isExpired): Builder
    {
        return $query->where('end_at', ($isExpired) ? '<=' : '>', now());
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published_at', '!=', null);
    }
}
