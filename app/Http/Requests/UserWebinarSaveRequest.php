<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

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
            'webinar_id' => [
                'required', 'integer', 'between:0,4294967295', 'exists:webinars,id',
                Rule::unique('user_webinar')->where(function ($query) {
                    return $query->where('webinar_id', $this->webinar_id)->where('user_id', Auth::id());
                })
            ],
            'payment_method' => 'required|string|min:2|max:45',
            'feedback' => 'nullable|string|min:2|max:65535',
            'payment_token' => 'nullable|string|min:2|max:45',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'webinar_id.unique' => 'User telah terdaftar pada webinar ini.',
        ];
    }
}
