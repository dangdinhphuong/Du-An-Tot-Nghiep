<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ServiceIndexStatusRule implements Rule
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
        if ($value == config('Months.status.chua_chot') || $value ==  config('Months.status.da_chot')) {
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
        return 'Sai định dạng trạng thái';
    }
}
