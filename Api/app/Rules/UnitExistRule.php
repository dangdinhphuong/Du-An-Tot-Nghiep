<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UnitExistRule implements Rule
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
        if ($value == config('service_unit.electric.unit') || $value == config('service_unit.water.unit') || $value == config('service_unit.other.unit') ) {
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
        return 'Sai đơn vị cho phép';
    }
}
