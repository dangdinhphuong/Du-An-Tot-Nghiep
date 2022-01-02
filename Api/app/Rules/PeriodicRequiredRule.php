<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PeriodicRequiredRule implements Rule
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
        if (request()->input('type') == 'SCPS') {
            if ($value == '' || $value == null || !in_array($value, config('maintain.periodic'))) {
                return true;
            } else {
                return false;
            }
        }
        if (request()->input('type') == 'KHDK') {
            if (in_array($value, config('maintain.periodic'))) {
                return true;
            } elseif ($value == '' || $value == null || !in_array($value, config('maintain.periodic'))) {
                return false;
            }
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Sự cố phát sinh không có định kỳ';
    }
}
