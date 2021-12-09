<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserWebinarGetRequest extends FormRequest
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
            'filter.user_id' => 'integer|between:0,4294967295',
            'filter.webinar_id' => 'integer|between:0,4294967295',
            'filter.payment_status' => 'string|min:2|max:45',
            'filter.payment_method' => 'string|min:2|max:45',
            'filter.feedback' => 'string|min:2|max:65535',
            'filter.payment_token' => 'string|min:2|max:45',
            'filter.user_webinar\.id' => 'integer|between:0,4294967295',
            'filter.user_webinar\.user_id' => 'integer|between:0,4294967295',
            'filter.user_webinar\.webinar_id' => 'integer|between:0,4294967295',
            'filter.user_webinar\.payment_status' => 'string|min:2|max:45',
            'filter.user_webinar\.payment_method' => 'string|min:2|max:45',
            'filter.user_webinar\.feedback' => 'string|min:2|max:65535',
            'filter.user_webinar\.payment_token' => 'string|min:2|max:45',
            'filter.user\.id' => 'integer|between:0,4294967295',
            'filter.user\.email' => 'string|email|min:11|max:255',
            'filter.user\.email_verified_at' => 'date',
            'filter.user\.password' => 'string|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,}$/',
            'filter.user\.name' => 'string|min:2|max:255',
            'filter.user\.phone_number' => 'string|regex:/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/|max:16',
            'filter.user\.remember_token' => 'string|min:2|max:100',
            'filter.user\.created_at' => 'date',
            'filter.user\.updated_at' => 'date',
            'filter.webinar\.id' => 'integer|between:0,4294967295',
            'filter.webinar\.category_id' => 'integer|between:0,4294967295',
            'filter.webinar\.title' => 'string|min:2|max:255',
            'filter.webinar\.description' => 'string|min:2|max:16777215',
            'filter.webinar\.start_at' => 'date',
            'filter.webinar\.end_at' => 'date',
            'filter.webinar\.price' => 'integer|between:0,4294967295',
            'filter.webinar\.type' => 'string|min:2|max:32',
            'filter.webinar\.zoom_id' => 'string|min:2|max:11',
            'filter.webinar\.max_participants' => 'integer|between:0,4294967295',
            'filter.webinar\.published_at' => 'date',
            'filter.webinar\.created_at' => 'date',
            'filter.webinar\.updated_at' => 'date',
            'page.number' => 'integer|min:1',
            'page.size' => 'integer|between:1,100',
            'search' => 'nullable|string|min:3|max:60',
        ];
    }
}
