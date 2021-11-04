<?php

namespace Tests\Support\Database\Models;

use Cms\Models\Concerns\HasSeoMeta;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\Support\Database\Factories\PostFactory;

class Post extends Model
{
    use HasFactory;
    use HasSeoMeta;
    use SoftDeletes;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'published' => 'boolean',
    ];

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
        'post_category_id',
        'admin_id',
        'published',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return PostFactory
     */
    protected static function newFactory()
    {
        return new PostFactory();
    }
}
