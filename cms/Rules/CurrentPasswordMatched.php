<?php

namespace Cms\Rules;

use ErrorException;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class CurrentPasswordMatched implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameters)
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @throws ErrorException
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $admin = cms_admin();

        return Hash::check($value, data_get($admin, 'password'));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The given :attribute does not match.';
    }
}
