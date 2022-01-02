<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhoneMustStartWithZeroRUle implements Rule
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
        $array  = array_map('intval', str_split($value));

        if ($array[0] != 0) {
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Số điện thoại phải bắt đầu bằng số 0';
    }
}
