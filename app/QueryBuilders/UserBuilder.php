<?php

namespace App\QueryBuilders;

use App\Http\Requests\UserGetRequest;
use App\Models\Concerns\MediaAllowedAppends;
use App\Models\User;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class UserBuilder extends Builder
{
    use MediaAllowedAppends;

    /**
     * Current HTTP Request object.
     *
     * @var UserGetRequest
     */
    protected $request;

    /**
     * UserBuilder constructor.
     *
     * @param UserGetRequest $request
     */
    public function __construct(UserGetRequest $request)
    {
        $this->request = $request;
        $this->builder = QueryBuilder::for(User::class, $request);
    }

    /**
     * Get a list of allowed columns that can be selected.
     *
     * @return string[]
     */
    protected function getAllowedFields(): array
    {
        return [
            'users.id',
            'users.email',
            'users.name',
            'users.phone_number',
            'users.created_at',
            'users.updated_at',
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
            'email',
            'name',
            'phone_number',
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
            'collabRequests',
            'comments',
            'userWebinars',
            'discussionTopics',
            'webinars',
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
            'email',
            'name',
            'phone_number',
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
            'email',
            'name',
            'phone_number',
            'created_at',
            'updated_at',
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

    /**
     * Get default query builder.
     *
     * @return QueryBuilder
     */
    public function query(): QueryBuilder
    {
        return parent::query()
            ->allowedAppends($this->getMediaAllowedAppends());
    }
}
