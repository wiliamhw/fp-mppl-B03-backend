<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserWebinarSaveRequest extends FormRequest
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
            'user_id' => 'required|integer|between:0,4294967295',
            'webinar_id' => 'required|integer|between:0,4294967295',
            'payment_status' => 'required|string|min:2|max:45',
            'payment_method' => 'required|string|min:2|max:45',
            'feedback' => 'required|string|min:2|max:65535',
            'payment_token' => 'required|string|min:2|max:45',
        ];
    }
}
