<?php

namespace App\QueryBuilders;

use App\Http\Requests\UserWebinarGetRequest;
use App\Models\UserWebinar;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class UserWebinarBuilder extends Builder
{
    /**
     * Current HTTP Request object.
     *
     * @var UserWebinarGetRequest
     */
    protected $request;

    /**
     * UserWebinarBuilder constructor.
     *
     * @param UserWebinarGetRequest $request
     */
    public function __construct(UserWebinarGetRequest $request)
    {
        $this->request = $request;
        $this->builder = QueryBuilder::for(UserWebinar::class, $request);
    }

    /**
     * Get a list of allowed columns that can be selected.
     *
     * @return string[]
     */
    protected function getAllowedFields(): array
    {
        return [
            'user_webinar.id',
            'user_webinar.user_id',
            'user_webinar.webinar_id',
            'user_webinar.payment_status',
            'user_webinar.payment_method',
            'user_webinar.feedback',
            'user_webinar.payment_token',
            'user.id',
            'user.email',
            'user.email_verified_at',
            'user.password',
            'user.name',
            'user.phone_number',
            'user.remember_token',
            'user.created_at',
            'user.updated_at',
            'webinar.id',
            'webinar.category_id',
            'webinar.title',
            'webinar.description',
            'webinar.start_at',
            'webinar.end_at',
            'webinar.price',
            'webinar.type',
            'webinar.zoom_id',
            'webinar.max_participants',
            'webinar.published_at',
            'webinar.created_at',
            'webinar.updated_at',
        ];
    }

    /**
     * Get a list of allowed columns that can be used in any filter operations.
     *
     * @return array
     */
    protected function getAllowedFilters(): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('user_id'),
            AllowedFilter::exact('webinar_id'),
            'payment_status',
            'payment_method',
            'feedback',
            'user.email',
            'user.name',
            'user.phone_number',
            AllowedFilter::exact('webinar.category_id'),
            'webinar.title',
            'webinar.description',
            AllowedFilter::exact('webinar.start_at'),
            AllowedFilter::exact('webinar.end_at'),
            AllowedFilter::exact('webinar.price'),
            'webinar.type',
            'webinar.zoom_id',
            AllowedFilter::exact('webinar.max_participants'),
        ];
    }

    /**
     * Get a list of allowed relationships that can be used in any include operations.
     *
     * @return string[]
     */
    protected function getAllowedIncludes(): array
    {
        return [
            'user',
            'webinar',
            'paymentLogs',
        ];
    }

    /**
     * Get a list of allowed searchable columns which can be used in any search operations.
     *
     * @return string[]
     */
    protected function getAllowedSearch(): array
    {
        return [
            'payment_status',
            'payment_method',
            'feedback',
            'payment_token',
            'user.email',
            'user.name',
            'user.phone_number',
            'user.remember_token',
            'webinar.title',
            'webinar.description',
            'webinar.type',
            'webinar.zoom_id',
        ];
    }

    /**
     * Get a list of allowed columns that can be used in any sort operations.
     *
     * @return string[]
     */
    protected function getAllowedSorts(): array
    {
        return [
            'id',
            'user_id',
            'webinar_id',
            'payment_status',
            'payment_method',
            'payment_token',
        ];
    }

    /**
     * Get the default sort column that will be used in any sort operation.
     *
     * @return string
     */
    protected function getDefaultSort(): string
    {
        return 'id';
    }
}
