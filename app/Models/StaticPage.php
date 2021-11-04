<?php

namespace App\Models;

use Cms\Contracts\SeoAttachedModel;
use Cms\Models\Concerns\HasSeoMeta;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\StaticPage.
 *
 * @property int                             $id
 * @property string                          $name
 * @property string                          $slug
 * @property string                          $content
 * @property string|null                     $youtube_video
 * @property string                          $layout
 * @property string                          $published
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Database\Factories\StaticPageFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|StaticPage findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|StaticPage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StaticPage newQuery()
 * @method static \Illuminate\Database\Query\Builder|StaticPage onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|StaticPage query()
 * @method static \Illuminate\Database\Eloquent\Builder|StaticPage whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaticPage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaticPage whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaticPage whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaticPage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaticPage whereLayout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaticPage wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaticPage whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaticPage whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaticPage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaticPage whereYoutubeVideo($value)
 * @method static \Illuminate\Database\Query\Builder|StaticPage withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|StaticPage withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Query\Builder|StaticPage withoutTrashed()
 * @mixin \Eloquent
 */
class StaticPage extends Model implements SeoAttachedModel
{
    use CascadeSoftDeletes;
    use HasFactory;
    use HasSeoMeta;
    use HasSlug;
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
        'name',
        'slug',
        'content',
        'youtube_video',
        'layout',
        'published',
    ];

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
     * Return the slug options for this model.
     *
     * @return SlugOptions
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}
