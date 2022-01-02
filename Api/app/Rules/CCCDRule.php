<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CCCDRule implements Rule
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
        //
       
        if (strlen($value) == 9) {
            return true;
        }
        if (strlen($value) == 12) {
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
        return 'CCCD phải là 9 số và 12 số.';
    }
}
