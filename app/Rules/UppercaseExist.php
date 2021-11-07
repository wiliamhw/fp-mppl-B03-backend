<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * @SuppressWarnings(unused)
 */
class UppercaseExist implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return (preg_match("/^(?=.*?[A-Z]).*$/", $value) == 1);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must has at least one uppercase letter.';
    }
}
