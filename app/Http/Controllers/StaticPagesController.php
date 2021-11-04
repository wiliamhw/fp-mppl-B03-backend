<?php

namespace App\Http\Controllers;

use App\Http\Resources\StaticPageCollection;
use App\Http\Resources\StaticPageResource;
use App\Models\StaticPage;
use App\QueryBuilders\StaticPageBuilder;

/**
 * @group Static Page Management
 *
 * API Endpoints for managing static pages.
 */
class StaticPagesController extends Controller
{
    /**
     * Determine if any access to this resource require authorization.
     *
     * @var bool
     */
    protected static $requireAuthorization = true;

    /**
     * StaticPagesController constructor.
     */
    public function __construct()
    {
        if (self::$requireAuthorization || (auth()->user() !== null)) {
            $this->authorizeResource(StaticPage::class);
        }
    }

    /**
     * Resource Collection.
     * Display a collection of the static page resources in paginated document format.
     *
     * @authenticated
     *
     * @queryParam fields[static_pages] *string* - No-example
     * Comma-separated field/attribute names of the static_page resource to include in the response document.
     * The available fields for current endpoint are: `id`, `name`, `slug`, `content`, `youtube_video`, `layout`, `published`, `deleted_at`, `created_at`, `updated_at`.
     * @queryParam page[size] *integer* - No-example
     * Describe how many records to display in a collection.
     * @queryParam page[number] *integer* - No-example
     * Describe the number of current page to display.
     * @queryParam include *string* - No-example
     * Comma-separated relationship names to include in the response document.
     * The available relationships for current endpoint are: ***`None`***.
     * @queryParam sort *string* - No-example
     * Field/attribute to sort the resources in response document by.
     * The available fields for sorting operation in current endpoint are: `id`, `name`, `slug`, `content`, `youtube_video`, `layout`, `published`, `deleted_at`, `created_at`, `updated_at`.
     * @queryParam filter[`filterName`] *string* - No-example
     * Filter the resources by specifying *attribute name* or *query scope name*.
     * The available filters for current endpoint are: `id`, `name`, `slug`, `content`, `youtube_video`, `layout`, `published`, `deleted_at`, `created_at`, `updated_at`.
     * @qeuryParam search *string* - No-example
     * Filter the resources by specifying any keyword to search.
     *
     * @param \App\QueryBuilders\StaticPageBuilder $query
     *
     * @return StaticPageCollection
     */
    public function index(StaticPageBuilder $query): StaticPageCollection
    {
        return new StaticPageCollection($query->paginate());
    }

    /**
     * Show Resource.
     * Display a specific static page resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam staticPage required *integer* - No-example
     * The identifier of a specific static page resource.
     *
     * @queryParam fields[static_pages] *string* - No-example
     * Comma-separated field/attribute names of the static_page resource to include in the response document.
     * The available fields for current endpoint are: `id`, `name`, `slug`, `content`, `youtube_video`, `layout`, `published`, `deleted_at`, `created_at`, `updated_at`.
     * @queryParam include *string* - No-example
     * Comma-separated relationship names to include in the response document.
     * The available relationships for current endpoint are: ***`None`***.
     *
     * @param \App\QueryBuilders\StaticPageBuilder $query
     * @param \App\Models\StaticPage               $staticPage
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return StaticPageResource
     */
    public function show(StaticPageBuilder $query, StaticPage $staticPage): StaticPageResource
    {
        return new StaticPageResource($query->find($staticPage->getKey()));
    }
}
