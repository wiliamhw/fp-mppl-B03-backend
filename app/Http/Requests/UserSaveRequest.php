<?php

namespace App\Http\Requests;

use App\Rules\DigitExist;
use App\Rules\LowercaseExist;
use App\Rules\UppercaseExist;
use Illuminate\Foundation\Http\FormRequest;

class UserSaveRequest extends FormRequest
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
            'email' => 'required|string|email|min:11|max:255|unique:users,email',
            'password' => ['required', 'string', 'min:8', new UppercaseExist(), new LowercaseExist(), new DigitExist()],
            'name' => 'required|string|min:2|max:255',
            'phone_number' => 'required|string|regex:/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/|max:16',
            'profile_picture'   => 'nullable|image|mimes:png,jpeg|max:2048'
        ];
    }
}
