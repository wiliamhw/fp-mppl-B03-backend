<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleCollection;
use App\Http\Resources\RoleResource;
use App\QueryBuilders\RoleBuilder;
use Spatie\Permission\Models\Role;

/**
 * @group Role Management
 *
 * API Endpoints for managing roles.
 */
class RolesController extends Controller
{
    /**
     * Determine if any access to this resource require authorization.
     *
     * @var bool
     */
    protected static $requireAuthorization = true;

    /**
     * RolesController constructor.
     */
    public function __construct()
    {
        if (self::$requireAuthorization || (auth()->user() !== null)) {
            $this->authorizeResource(Role::class);
        }
    }

    /**
     * Resource Collection.
     * Display a collection of the role resources in paginated document format.
     *
     * @authenticated
     *
     * @queryParam fields[roles] *string* - No-example
     * Comma-separated field/attribute names of the role resource to include in the response document.
     * The available fields for current endpoint are: `id`, `name`, `guard_name`, `created_at`, `updated_at`.
     * @queryParam page[size] *integer* - No-example
     * Describe how many records to display in a collection.
     * @queryParam page[number] *integer* - No-example
     * Describe the number of current page to display.
     * @queryParam include *string* - No-example
     * Comma-separated relationship names to include in the response document.
     * The available relationships for current endpoint are: `permissions`.
     * @queryParam sort *string* - No-example
     * Field/attribute to sort the resources in response document by.
     * The available fields for sorting operation in current endpoint are: `id`, `name`, `guard_name`, `created_at`, `updated_at`.
     * @queryParam filter[`filterName`] *string* - No-example
     * Filter the resources by specifying *attribute name* or *query scope name*.
     * The available filters for current endpoint are: `id`, `name`, `guard_name`, `created_at`, `updated_at`.
     * @qeuryParam search *string* - No-example
     * Filter the resources by specifying any keyword to search.
     *
     * @param \App\QueryBuilders\RoleBuilder $query
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return RoleCollection
     */
    public function index(RoleBuilder $query): RoleCollection
    {
        return new RoleCollection($query->paginate());
    }

    /**
     * Show Resource.
     * Display a specific role resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam role required *integer* - No-example
     * The identifier of a specific role resource.
     *
     * @queryParam fields[roles] *string* - No-example
     * Comma-separated field/attribute names of the role resource to include in the response document.
     * The available fields for current endpoint are: `id`, `name`, `guard_name`, `created_at`, `updated_at`.
     * @queryParam include *string* - No-example
     * Comma-separated relationship names to include in the response document.
     * The available relationships for current endpoint are: `permissions`.
     *
     * @param \App\QueryBuilders\RoleBuilder $query
     * @param \Spatie\Permission\Models\Role $role
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return RoleResource
     */
    public function show(RoleBuilder $query, Role $role): RoleResource
    {
        return new RoleResource($query->find($role->getKey()));
    }
}
