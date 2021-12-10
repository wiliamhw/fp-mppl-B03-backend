<?php

namespace App\Rules;

use App\Models\Webinar;
use Illuminate\Contracts\Validation\Rule;

class LimitParticipants implements Rule
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
        $webinar = Webinar::find($value);

        if ($webinar && $webinar->max_participants >= $webinar->participants + 1) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Kuota pendaftaran peserta pada webinar ini sudah dicapai.';
    }
}
