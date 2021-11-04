<?php

namespace App\Http\Controllers;

use App\Http\Resources\AdminCollection;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use App\QueryBuilders\AdminBuilder;

/**
 * @group Admin Management
 *
 * API Endpoints for managing admins.
 */
class AdminsController extends Controller
{
    /**
     * Determine if any access to this resource require authorization.
     *
     * @var bool
     */
    protected static $requireAuthorization = true;

    /**
     * AdminsController constructor.
     */
    public function __construct()
    {
        if (self::$requireAuthorization || (auth()->user() !== null)) {
            $this->authorizeResource(Admin::class);
        }
    }

    /**
     * Resource Collection.
     * Display a collection of the cms admin resources in paginated document format.
     *
     * @authenticated
     *
     * @queryParam fields[cms_admins] *string* - No-example
     * Comma-separated field/attribute names of the cms_admin resource to include in the response document.
     * The available fields for current endpoint are: `id`, `name`, `email`, `password`, `remember_token`, `deleted_at`, `created_at`, `updated_at`.
     * @queryParam append *string* - No-example
     * Comma-separated mutated field/attribute names which you wish to append in the response document.
     * The available mutated fields for current endpoint are: `medium_profile_picture`, `small_profile_picture`.
     * @queryParam page[size] *integer* - No-example
     * Describe how many records to display in a collection.
     * @queryParam page[number] *integer* - No-example
     * Describe the number of current page to display.
     * @queryParam include *string* - No-example
     * Comma-separated relationship names to include in the response document.
     * The available relationships for current endpoint are: ***`None`***.
     * @queryParam sort *string* - No-example
     * Field/attribute to sort the resources in response document by.
     * The available fields for sorting operation in current endpoint are: `id`, `name`, `email`, `password`, `remember_token`, `deleted_at`, `created_at`, `updated_at`.
     * @queryParam filter[`filterName`] *string* - No-example
     * Filter the resources by specifying *attribute name* or *query scope name*.
     * The available filters for current endpoint are: `id`, `name`, `email`, `password`, `remember_token`, `deleted_at`, `created_at`, `updated_at`.
     * @qeuryParam search *string* - No-example
     * Filter the resources by specifying any keyword to search.
     *
     * @param \App\QueryBuilders\AdminBuilder $query
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return AdminCollection
     */
    public function index(AdminBuilder $query): AdminCollection
    {
        return new AdminCollection($query->paginate());
    }

    /**
     * Show Resource.
     * Display a specific cms admin resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam admin required *integer* - No-example
     * The identifier of a specific cms admin resource.
     *
     * @queryParam fields[cms_admins] *string* - No-example
     * Comma-separated field/attribute names of the cms_admin resource to include in the response document.
     * The available fields for current endpoint are: `id`, `name`, `email`, `password`, `remember_token`, `deleted_at`, `created_at`, `updated_at`.
     * @queryParam append *string* - No-example
     * Comma-separated mutated field/attribute names which you wish to append in the response document.
     * The available mutated fields for current endpoint are: `medium_profile_picture`, `small_profile_picture`.
     * @queryParam include *string* - No-example
     * Comma-separated relationship names to include in the response document.
     * The available relationships for current endpoint are: ***`None`***.
     *
     * @param \App\QueryBuilders\AdminBuilder $query
     * @param \App\Models\Admin               $admin
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return AdminResource
     */
    public function show(AdminBuilder $query, Admin $admin): AdminResource
    {
        return new AdminResource($query->find($admin->getKey()));
    }
}
