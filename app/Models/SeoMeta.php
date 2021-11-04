<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\SeoMeta.
 *
 * @property int                             $id
 * @property string|null                     $attachable_type
 * @property int|null                        $attachable_id
 * @property string|null                     $locale
 * @property string|null                     $seo_url
 * @property string                          $seo_title
 * @property string                          $seo_description
 * @property string                          $open_graph_type
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $seo_image
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 *
 * @method static \Database\Factories\SeoMetaFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMeta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMeta newQuery()
 * @method static \Illuminate\Database\Query\Builder|SeoMeta onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMeta query()
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMeta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMeta whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMeta whereForeignKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMeta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMeta whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMeta whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMeta whereOpenGraphType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMeta whereSeoDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMeta whereSeoTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMeta whereSeoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMeta whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|SeoMeta withTrashed()
 * @method static \Illuminate\Database\Query\Builder|SeoMeta withoutTrashed()
 * @mixin \Eloquent
 */
class SeoMeta extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use LogsActivity;
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var string[]
     */
    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'attachable_type',
        'attachable_id',
        'locale',
        'seo_url',
        'seo_title',
        'seo_description',
        'seo_content',
        'open_graph_type',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'seo_metas';

    /**
     * Define the activity log options for the model.
     *
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    /**
     * Get the custom seo_image Attribute.
     *
     * @return string
     */
    public function getSeoImageAttribute(): string
    {
        $image = $this->getFirstMediaUrl('seo_image', 'seo_image_large');

        return ($image === '') ? config('seo.image') : asset($image);
    }

    /**
     * Get the custom seo_image Attribute.
     *
     * @return string
     */
    public function getSeoImageSmallAttribute(): string
    {
        $image = $this->getFirstMediaUrl('seo_image', 'seo_image_small');

        return ($image === '') ? '' : asset($image);
    }

    /**
     * Register media collections.
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('seo_image')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png'])
            ->registerMediaConversions(function () {
                $this->addMediaConversion('seo_image_large')
                    ->crop('crop-center', 1200, 630)
                    ->quality(config('cms.media_quality'))
                    ->sharpen(5)
                    ->optimize();

                $this->addMediaConversion('seo_image_small')
                    ->crop('crop-center', 240, 126)
                    ->quality(config('cms.media_quality'))
                    ->sharpen(10)
                    ->optimize();
            });
    }
}
