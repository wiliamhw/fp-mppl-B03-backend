<?php

namespace App\Http\Controllers;

use App\Http\Resources\SeoMetaCollection;
use App\Http\Resources\SeoMetaResource;
use App\Models\SeoMeta;
use App\QueryBuilders\SeoMetaBuilder;

/**
 * @group Seo Meta Management
 *
 * API Endpoints for managing seo metas.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class SeoMetasController extends Controller
{
    /**
     * Determine if any access to this resource require authorization.
     *
     * @var bool
     */
    protected static $requireAuthorization = true;

    /**
     * SeoMetasController constructor.
     */
    public function __construct()
    {
        if (self::$requireAuthorization || (auth()->user() !== null)) {
            $this->authorizeResource(SeoMeta::class);
        }
    }

    /**
     * Resource Collection.
     * Display a collection of the seo meta resources in paginated document format.
     *
     * @authenticated
     *
     * @queryParam fields[seo_metas] *string* - No-example
     * Comma-separated field/attribute names of the seo_meta resource to include in the response document.
     * The available fields for current endpoint are: `id`, `seo_url`, `model`, `foreign_key`, `locale`, `seo_title`, `seo_description`, `open_graph_type`, `deleted_at`, `created_at`, `updated_at`.
     * @queryParam append *string* - No-example
     * Comma-separated mutated field/attribute names which you wish to append in the response document.
     * The available mutated fields for current endpoint are: `large_seo_image`, `small_seo_image`.
     * @queryParam page[size] *integer* - No-example
     * Describe how many records to display in a collection.
     * @queryParam page[number] *integer* - No-example
     * Describe the number of current page to display.
     * @queryParam include *string* - No-example
     * Comma-separated relationship names to include in the response document.
     * The available relationships for current endpoint are: ***`None`***.
     * @queryParam sort *string* - No-example
     * Field/attribute to sort the resources in response document by.
     * The available fields for sorting operation in current endpoint are: `id`, `seo_url`, `model`, `foreign_key`, `locale`, `seo_title`, `seo_description`, `open_graph_type`, `deleted_at`, `created_at`, `updated_at`.
     * @queryParam filter[`filterName`] *string* - No-example
     * Filter the resources by specifying *attribute name* or *query scope name*.
     * The available filters for current endpoint are: `id`, `seo_url`, `model`, `foreign_key`, `locale`, `seo_title`, `seo_description`, `open_graph_type`, `deleted_at`, `created_at`, `updated_at`.
     * @qeuryParam search *string* - No-example
     * Filter the resources by specifying any keyword to search.
     *
     * @param \App\QueryBuilders\SeoMetaBuilder $query
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return SeoMetaCollection
     */
    public function index(SeoMetaBuilder $query): SeoMetaCollection
    {
        return new SeoMetaCollection($query->paginate());
    }

    /**
     * Show Resource.
     * Display a specific seo meta resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam seoMeta required *integer* - No-example
     * The identifier of a specific seo meta resource.
     *
     * @queryParam fields[seo_metas] *string* - No-example
     * Comma-separated field/attribute names of the seo_meta resource to include in the response document.
     * The available fields for current endpoint are: `id`, `seo_url`, `model`, `foreign_key`, `locale`, `seo_title`, `seo_description`, `open_graph_type`, `deleted_at`, `created_at`, `updated_at`.
     * @queryParam append *string* - No-example
     * Comma-separated mutated field/attribute names which you wish to append in the response document.
     * The available mutated fields for current endpoint are: `large_seo_image`, `small_seo_image`.
     * @queryParam include *string* - No-example
     * Comma-separated relationship names to include in the response document.
     * The available relationships for current endpoint are: ***`None`***.
     *
     * @param \App\QueryBuilders\SeoMetaBuilder $query
     * @param \App\Models\SeoMeta               $seoMeta
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return SeoMetaResource
     */
    public function show(SeoMetaBuilder $query, SeoMeta $seoMeta): SeoMetaResource
    {
        return new SeoMetaResource($query->find($seoMeta->getKey()));
    }
}
