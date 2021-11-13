<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategorySaveRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\QueryBuilders\CategoryBuilder;
use Illuminate\Http\JsonResponse;

/**
 * @group Category Management
 *
 * API Endpoints for managing categories.
 */
class CategoriesController extends Controller
{
    /**
     * Determine if any access to this resource require authorization.
     *
     * @var bool
     */
    protected static $requireAuthorization = false;

    /**
     * CategoriesController constructor.
     */
    public function __construct()
    {
        if (self::$requireAuthorization || (auth()->user() !== null)) {
            $this->authorizeResource(Category::class);
        }
    }

    /**
     * Resource Collection.
     * Display a collection of the category resources in paginated document format.
     *
     * @authenticated
     *
     * @queryParam fields[categories] *string* - No-example
     * Comma-separated field/attribute names of the category resource to include in the response document.
     * The available fields for current endpoint are: `id`, `name`, `created_at`, `updated_at`.
     * @queryParam page[size] *integer* - No-example
     * Describe how many records to display in a collection.
     * @queryParam page[number] *integer* - No-example
     * Describe the number of current page to display.
     * @queryParam include *string* - No-example
     * Comma-separated relationship names to include in the response document.
     * The available relationships for current endpoint are: `webinars`.
     * @queryParam sort *string* - No-example
     * Field/attribute to sort the resources in response document by.
     * The available fields for sorting operation in current endpoint are: `id`, `name`, `created_at`, `updated_at`.
     * @queryParam filter[`filterName`] *string* - No-example
     * Filter the resources by specifying *attribute name* or *query scope name*.
     * The available filters for current endpoint are: `id`, `name`, `created_at`, `updated_at`.
     * @qeuryParam search *string* - No-example
     * Filter the resources by specifying any keyword to search.
     *
     * @param \App\QueryBuilders\CategoryBuilder $query
     *
     * @return CategoryCollection
     */
    public function index(CategoryBuilder $query): CategoryCollection
    {
        return new CategoryCollection($query->paginate());
    }

    /**
     * Create Resource.
     * Create a new category resource.
     *
     * @authenticated
     *
     * @param \App\Http\Requests\CategorySaveRequest $request
     * @param \App\Models\Category $category
     *
     * @return JsonResponse
     */
    public function store(CategorySaveRequest $request, Category $category): JsonResponse
    {
        $category->fill($request->only($category->offsetGet('fillable')))
            ->save();

        $resource = (new CategoryResource($category))
            ->additional(['info' => 'The new category has been saved.']);

        return $resource->toResponse($request)->setStatusCode(201);
    }

    /**
     * Show Resource.
     * Display a specific category resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam category required *integer* - No-example
     * The identifier of a specific category resource.
     *
     * @queryParam fields[categories] *string* - No-example
     * Comma-separated field/attribute names of the category resource to include in the response document.
     * The available fields for current endpoint are: `id`, `name`, `created_at`, `updated_at`.
     * @queryParam include *string* - No-example
     * Comma-separated relationship names to include in the response document.
     * The available relationships for current endpoint are: `webinars`.
     *
     * @param \App\QueryBuilders\CategoryBuilder $query
     * @param \App\Models\Category $category
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return CategoryResource
     */
    public function show(CategoryBuilder $query, Category $category): CategoryResource
    {
        return new CategoryResource($query->find($category->getKey()));
    }

    /**
     * Update Resource.
     * Update a specific category resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam category required *integer* - No-example
     * The identifier of a specific category resource.
     *
     * @param \App\Http\Requests\CategorySaveRequest $request
     * @param \App\Models\Category $category
     *
     * @return CategoryResource
     */
    public function update(CategorySaveRequest $request, Category $category): CategoryResource
    {
        $category->fill($request->only($category->offsetGet('fillable')));

        if ($category->isDirty()) {
            $category->save();
        }

        return (new CategoryResource($category))
            ->additional(['info' => 'The category has been updated.']);
    }

    /**
     * Delete Resource.
     * Delete a specific category resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam category required *integer* - No-example
     * The identifier of a specific category resource.
     *
     * @param \App\Models\Category $category
     *
     * @throws \Exception
     *
     * @return CategoryResource
     */
    public function destroy(Category $category): CategoryResource
    {
        $category->delete();

        return (new CategoryResource($category))
            ->additional(['info' => 'The category has been deleted.']);
    }
}
