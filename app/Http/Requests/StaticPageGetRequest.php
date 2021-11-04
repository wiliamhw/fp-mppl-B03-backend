<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaticPageGetRequest extends FormRequest
{
    /**
     * Determine if the current user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
//        return (auth()->guard('api')->check() || auth()->guard('cms-api')->check());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'filter.id'                          => 'integer|between:0,18446744073709551615',
            'filter.name'                        => 'string|min:2|max:255',
            'filter.slug'                        => 'string|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/|min:2|max:255',
            'filter.content'                     => 'string|min:2|max:16777215',
            'filter.youtube_video'               => 'string|min:2|max:255',
            'filter.layout'                      => 'string|min:2|max:255',
            'filter.published'                   => 'string|min:2|max:255',
            'filter.deleted_at'                  => 'date',
            'filter.created_at'                  => 'date',
            'filter.updated_at'                  => 'date',
            'filter.static_pages\.id'            => 'integer|between:0,18446744073709551615',
            'filter.static_pages\.name'          => 'string|min:2|max:255',
            'filter.static_pages\.slug'          => 'string|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/|min:2|max:255',
            'filter.static_pages\.content'       => 'string|min:2|max:16777215',
            'filter.static_pages\.youtube_video' => 'string|min:2|max:255',
            'filter.static_pages\.layout'        => 'string|min:2|max:255',
            'filter.static_pages\.published'     => 'string|min:2|max:255',
            'filter.static_pages\.deleted_at'    => 'date',
            'filter.static_pages\.created_at'    => 'date',
            'filter.static_pages\.updated_at'    => 'date',
            'page.number'                        => 'integer|min:1',
            'page.size'                          => 'integer|between:1,100',
            'search'                             => 'nullable|string|min:3|max:60',
        ];
    }
}
