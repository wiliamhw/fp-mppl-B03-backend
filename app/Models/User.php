<?php

namespace App\Models;

use App\Models\CollabRequest;
use App\Models\Comment;
use App\Models\DiscussionTopic;
use App\Models\UserWebinar;
use App\Models\Webinar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use HasFactory, HasApiTokens;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var string[]
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'email',
        'password',
        'name',
        'phone_number',
    ];

//    /**
//     * Model relationship definition.
//     * User has many CollabRequests
//     *
//     * @return HasMany
//     */
//    public function collabRequests(): HasMany
//    {
//        return $this->hasMany(CollabRequest::class, 'user_id');
//    }
//
//    /**
//     * Model relationship definition.
//     * User has many Comments
//     *
//     * @return HasMany
//     */
//    public function comments(): HasMany
//    {
//        return $this->hasMany(Comment::class, 'user_id');
//    }
//
//    /**
//     * Model relationship definition.
//     * User belongs to many DiscussionTopics
//     *
//     * @return BelongsToMany
//     */
//    public function discussionTopics(): BelongsToMany
//    {
//        return $this->belongsToMany(DiscussionTopic::class, 'comments');
//    }
//
//    /**
//     * Model relationship definition.
//     * User has many UserWebinars
//     *
//     * @return HasMany
//     */
//    public function userWebinars(): HasMany
//    {
//        return $this->hasMany(UserWebinar::class, 'user_id');
//    }
//
//    /**
//     * Model relationship definition.
//     * User belongs to many Webinars
//     *
//     * @return BelongsToMany
//     */
//    public function webinars(): BelongsToMany
//    {
//        return $this->belongsToMany(Webinar::class, 'user_webinar');
//    }
}
