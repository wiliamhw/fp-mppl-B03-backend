<?php

namespace App\Http\Requests;

use App\Rules\DigitExist;
use App\Rules\LowercaseExist;
use App\Rules\UppercaseExist;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'             => 'nullable|string|email|min:11|max:255|unique:users,email',
            'password'          => ['nullable', 'string', 'min:8', new UppercaseExist(), new LowercaseExist(), new DigitExist()],
            'name'              => 'nullable|string|min:2|max:255',
            'phone_number'      => 'nullable|string|regex:/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/|max:16',
            'profile_picture'   => 'nullable|image|mimes:png,jpeg|max:2048'
        ];
    }
}
