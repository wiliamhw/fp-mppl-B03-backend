<?php

namespace App\Http\Controllers;

use App\Exceptions\Concerns\HandleApiExceptions;
use App\Http\Requests\UserWebinarSaveRequest;
use App\Http\Resources\UserWebinarCollection;
use App\Http\Resources\UserWebinarResource;
use App\Models\UserWebinar;
use App\Models\Webinar;
use App\QueryBuilders\UserWebinarBuilder;
use Cms\Resources\Concerns\StripResourceElements;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * @group User Webinar Management
 *
 * API Endpoints for managing user webinars.
 */
class UserWebinarsController extends Controller
{
    use HandleApiExceptions;
    use StripResourceElements;

    /**
     * Resource Collection.
     * Display a collection of the user webinar resources in paginated document format.
     *
     * @authenticated
     *
     * @queryParam fields[user_webinars] *string* - No-example
     * Comma-separated field/attribute names of the user_webinar resource to include in the response document.
     * The available fields for current endpoint are: `id`, `user_id`, `webinar_id`, `payment_status`, `payment_method`, `feedback`, `payment_token`.
     * @queryParam fields[user] *string* - No-example
     * Comma-separated field/attribute names of the user resource to include in the response document.
     * The available fields for current endpoint are: `id`, `email`, `email_verified_at`, `password`, `name`, `phone_number`, `remember_token`, `created_at`, `updated_at`.
     * @queryParam fields[webinar] *string* - No-example
     * Comma-separated field/attribute names of the webinar resource to include in the response document.
     * The available fields for current endpoint are: `id`, `category_id`, `title`, `description`, `start_at`, `end_at`, `price`, `type`, `zoom_id`, `max_participants`, `published_at`, `created_at`, `updated_at`.
     * @queryParam page[size] *integer* - No-example
     * Describe how many records to display in a collection.
     * @queryParam page[number] *integer* - No-example
     * Describe the number of current page to display.
     * @queryParam include *string* - No-example
     * Comma-separated relationship names to include in the response document.
     * The available relationships for current endpoint are: `user`, `webinar`, `paymentLogs`.
     * @queryParam sort *string* - No-example
     * Field/attribute to sort the resources in response document by.
     * The available fields for sorting operation in current endpoint are: `id`, `user_id`, `webinar_id`, `payment_status`, `payment_method`, `feedback`, `payment_token`.
     * @queryParam filter[`filterName`] *string* - No-example
     * Filter the resources by specifying *attribute name* or *query scope name*.
     * The available filters for current endpoint are: `id`, `user_id`, `webinar_id`, `payment_status`, `payment_method`, `feedback`, `payment_token`, `user.id`, `user.email`, `user.email_verified_at`, `user.password`, `user.name`, `user.phone_number`, `user.remember_token`, `user.created_at`, `user.updated_at`, `webinar.id`, `webinar.category_id`, `webinar.title`, `webinar.description`, `webinar.start_at`, `webinar.end_at`, `webinar.price`, `webinar.type`, `webinar.zoom_id`, `webinar.max_participants`, `webinar.published_at`, `webinar.created_at`, `webinar.updated_at`.
     * @qeuryParam search *string* - No-example
     * Filter the resources by specifying any keyword to search.
     *
     * @param \App\QueryBuilders\UserWebinarBuilder $query
     * @return UserWebinarCollection
     */
    public function index(UserWebinarBuilder $query): UserWebinarCollection
    {
        return new UserWebinarCollection($query->query()->where('user_id', Auth::id())->paginate());
    }

    /**
     * Create Resource.
     * Create a new user webinar resource.
     *
     * @authenticated
     *
     * @param \App\Http\Requests\UserWebinarSaveRequest $request
     * @param \App\Models\UserWebinar $userWebinar
     *
     * @return JsonResponse
     */
    public function store(UserWebinarSaveRequest $request, UserWebinar $userWebinar): JsonResponse
    {
        $webinar = Webinar::where('id', $request->webinar_id)->firstOrFail();
        if ($webinar->max_participants < $webinar->participants + 1) {
            throw new AuthorizationException(
                'Kuota pendaftaran peserta pada webinar ini sudah dicapai.',
            );
        }

        $userWebinar->fill($request->only($userWebinar->offsetGet('fillable')));
        $userWebinar->user_id = (int) Auth::id();
        $userWebinar->payment_status = UserWebinar::PAYMENT_IN_PROGRESS;
        $userWebinar->save();

        $resource = (new UserWebinarResource($userWebinar))
            ->additional(['info' => 'The new user webinar has been saved.']);
        unset($resource['webinar']);

        return $resource->toResponse($request)->setStatusCode(201);
    }

    /**
     * Show Resource.
     * Display a specific user webinar resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam userWebinar required *integer* - No-example
     * The identifier of a specific user webinar resource.
     *
     * @queryParam fields[user_webinars] *string* - No-example
     * Comma-separated field/attribute names of the user_webinar resource to include in the response document.
     * The available fields for current endpoint are: `id`, `user_id`, `webinar_id`, `payment_status`, `payment_method`, `feedback`, `payment_token`.
     * @queryParam fields[user] *string* - No-example
     * Comma-separated field/attribute names of the user resource to include in the response document.
     * The available fields for current endpoint are: `id`, `email`, `email_verified_at`, `password`, `name`, `phone_number`, `remember_token`, `created_at`, `updated_at`.
     * @queryParam fields[webinar] *string* - No-example
     * Comma-separated field/attribute names of the webinar resource to include in the response document.
     * The available fields for current endpoint are: `id`, `category_id`, `title`, `description`, `start_at`, `end_at`, `price`, `type`, `zoom_id`, `max_participants`, `published_at`, `created_at`, `updated_at`.
     * @queryParam include *string* - No-example
     * Comma-separated relationship names to include in the response document.
     * The available relationships for current endpoint are: `user`, `webinar`, `paymentLogs`.
     *
     * @param \App\QueryBuilders\UserWebinarBuilder $query
     * @param int $webinarId
     * @return UserWebinarResource
     */
    public function show(UserWebinarBuilder $query, int $webinarId): UserWebinarResource
    {
        Webinar::find($webinarId)?->firstOrFail();
        return new UserWebinarResource(
            $query->query()
                ->where('user_id', Auth::id())
                ->where('webinar_id', $webinarId)
                ->firstOrFail()
        );
    }

    /**
     * Update Resource.
     * Update a specific user webinar resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam userWebinar required *integer* - No-example
     * The identifier of a specific user webinar resource.
     *
     * @param \App\Http\Requests\UserWebinarSaveRequest $request
     * @param \App\Models\UserWebinar $userWebinar
     *
     * @return UserWebinarResource
     */
    public function update(UserWebinarSaveRequest $request, UserWebinar $userWebinar): UserWebinarResource
    {
        $userWebinar->fill($request->only($userWebinar->offsetGet('fillable')));

        if ($userWebinar->isDirty()) {
            $userWebinar->save();
        }

        return (new UserWebinarResource($userWebinar))
            ->additional(['info' => 'User webinar has been updated.']);
    }

    /**
     * Delete Resource.
     * Delete a specific user webinar resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam userWebinar required *integer* - No-example
     * The identifier of a specific user webinar resource.
     *
     * @param int $webinarId
     * @return UserWebinarResource
     */
    public function destroy(int $webinarId): UserWebinarResource
    {
        Webinar::find($webinarId)?->firstOrFail();
        $userWebinar = UserWebinar::where('webinar_id', $webinarId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        $userWebinar->delete();

        return (new UserWebinarResource($userWebinar->makeHidden('webinar')))
            ->additional(['info' => 'The user webinar has been deleted.']);
    }
}
