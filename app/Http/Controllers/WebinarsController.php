<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebinarSaveRequest;
use App\Http\Resources\WebinarCollection;
use App\Http\Resources\WebinarResource;
use App\Models\Webinar;
use App\QueryBuilders\WebinarBuilder;
use Illuminate\Http\JsonResponse;

/**
 * @group Webinar Management
 *
 * API Endpoints for managing webinars.
 */
class WebinarsController extends Controller
{
    /**
     * Determine if any access to this resource require authorization.
     *
     * @var bool
     */
    protected static $requireAuthorization = false;

    /**
     * WebinarsController constructor.
     */
    public function __construct()
    {
        if (self::$requireAuthorization || (auth()->user() !== null)) {
            $this->authorizeResource(Webinar::class);
        }
    }

    /**
     * Resource Collection.
     * Display a collection of the webinar resources in paginated document format.
     *
     * @authenticated
     *
     * @queryParam fields[webinars] *string* - No-example
     * Comma-separated field/attribute names of the webinar resource to include in the response document.
     * The available fields for current endpoint are: `id`, `category_id`, `title`, `description`, `start_at`, `end_at`, `price`, `zoom_id`, `max_participants`, `partner_name`, `published_at`, `created_at`, `updated_at`.
     * @queryParam fields[category] *string* - No-example
     * Comma-separated field/attribute names of the category resource to include in the response document.
     * The available fields for current endpoint are: `id`, `name`, `created_at`, `updated_at`.
     * @queryParam page[size] *integer* - No-example
     * Describe how many records to display in a collection.
     * @queryParam page[number] *integer* - No-example
     * Describe the number of current page to display.
     * @queryParam include *string* - No-example
     * Comma-separated relationship names to include in the response document.
     * The available relationships for current endpoint are: `category`, `userWebinars`, `users`.
     * @queryParam sort *string* - No-example
     * Field/attribute to sort the resources in response document by.
     * The available fields for sorting operation in current endpoint are: `id`, `category_id`, `title`, `description`, `start_at`, `end_at`, `price`, `zoom_id`, `max_participants`, `partner_name`, `published_at`, `created_at`, `updated_at`.
     * @queryParam filter[`filterName`] *string* - No-example
     * Filter the resources by specifying *attribute name* or *query scope name*.
     * The available filters for current endpoint are: `id`, `category_id`, `title`, `description`, `start_at`, `end_at`, `price`, `zoom_id`, `max_participants`, `partner_name`, `published_at`, `created_at`, `updated_at`, `category.id`, `category.name`, `category.created_at`, `category.updated_at`.
     * @qeuryParam search *string* - No-example
     * Filter the resources by specifying any keyword to search.
     *
     * @param \App\QueryBuilders\WebinarBuilder $query
     *
     * @return WebinarCollection
     */
    public function index(WebinarBuilder $query): WebinarCollection
    {
        return new WebinarCollection($query->paginate());
    }

    /**
     * Create Resource.
     * Create a new webinar resource.
     *
     * @authenticated
     *
     * @param \App\Http\Requests\WebinarSaveRequest $request
     * @param \App\Models\Webinar $webinar
     *
     * @return JsonResponse
     */
    public function store(WebinarSaveRequest $request, Webinar $webinar): JsonResponse
    {
        $webinar->fill($request->only($webinar->offsetGet('fillable')))
            ->save();

        $resource = (new WebinarResource($webinar))
            ->additional(['info' => 'The new webinar has been saved.']);

        return $resource->toResponse($request)->setStatusCode(201);
    }

    /**
     * Show Resource.
     * Display a specific webinar resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam webinar required *integer* - No-example
     * The identifier of a specific webinar resource.
     *
     * @queryParam fields[webinars] *string* - No-example
     * Comma-separated field/attribute names of the webinar resource to include in the response document.
     * The available fields for current endpoint are: `id`, `category_id`, `title`, `description`, `start_at`, `end_at`, `price`, `zoom_id`, `max_participants`, `partner_name`, `published_at`, `created_at`, `updated_at`.
     * @queryParam fields[category] *string* - No-example
     * Comma-separated field/attribute names of the category resource to include in the response document.
     * The available fields for current endpoint are: `id`, `name`, `created_at`, `updated_at`.
     * @queryParam include *string* - No-example
     * Comma-separated relationship names to include in the response document.
     * The available relationships for current endpoint are: `category`, `userWebinars`, `users`.
     *
     * @param \App\QueryBuilders\WebinarBuilder $query
     * @param \App\Models\Webinar $webinar
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return WebinarResource
     */
    public function show(WebinarBuilder $query, Webinar $webinar): WebinarResource
    {
        return new WebinarResource($query->find($webinar->getKey()));
    }

    /**
     * Update Resource.
     * Update a specific webinar resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam webinar required *integer* - No-example
     * The identifier of a specific webinar resource.
     *
     * @param \App\Http\Requests\WebinarSaveRequest $request
     * @param \App\Models\Webinar $webinar
     *
     * @return WebinarResource
     */
    public function update(WebinarSaveRequest $request, Webinar $webinar): WebinarResource
    {
        $webinar->fill($request->only($webinar->offsetGet('fillable')));

        if ($webinar->isDirty()) {
            $webinar->save();
        }

        return (new WebinarResource($webinar))
            ->additional(['info' => 'The webinar has been updated.']);
    }

    /**
     * Delete Resource.
     * Delete a specific webinar resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam webinar required *integer* - No-example
     * The identifier of a specific webinar resource.
     *
     * @param \App\Models\Webinar $webinar
     *
     * @throws \Exception
     *
     * @return WebinarResource
     */
    public function destroy(Webinar $webinar): WebinarResource
    {
        $webinar->delete();

        return (new WebinarResource($webinar))
            ->additional(['info' => 'The webinar has been deleted.']);
    }
}
