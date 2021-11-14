<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WebinarSaveRequest extends FormRequest
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
            'category_id' => 'required|integer|between:0,4294967295',
            'title' => 'required|string|min:2|max:255',
            'description' => 'required|string|min:2|max:511',
            'start_at' => 'required|date',
            'end_at' => 'required|date',
            'price' => 'required|integer|between:0,4294967295',
            'zoom_id' => 'required|string|min:2|max:11',
            'max_participants' => 'required|integer|between:0,4294967295',
            'partner_name' => 'required|string|min:2|max:255',
            'published_at' => 'required|date',
        ];
    }
}
