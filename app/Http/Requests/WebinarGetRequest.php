<?php

namespace App\Http\Requests;

use App\Models\Webinar;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WebinarGetRequest extends FormRequest
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
            'filter.id' => 'integer|between:0,4294967295',
            'filter.category_id' => 'integer|between:0,4294967295',
            'filter.title' => 'string|min:2|max:255',
            'filter.description' => 'string|min:2|max:511',
            'filter.start_at' => 'date',
            'filter.end_at' => 'date',
            'filter.price' => 'integer|between:0,4294967295',
            'filter.type' => ['string', Rule::in(Webinar::TYPE)],
            'filter.zoom_id' => 'string|min:2|max:11',
            'filter.participants' => 'integer|between:0,4294967295',
            'filter.max_participants' => 'integer|between:0,4294967295',
            'filter.partner_name' => 'string|min:2|max:255',
            'filter.published_at' => 'date',
            'filter.created_at' => 'date',
            'filter.updated_at' => 'date',
            'page.number' => 'integer|min:1',
            'page.size' => 'integer|between:1,100',
            'search' => 'nullable|string|min:3|max:60',

            'filter.expired' => 'string|in:true,false,1,0'
        ];
    }
}
