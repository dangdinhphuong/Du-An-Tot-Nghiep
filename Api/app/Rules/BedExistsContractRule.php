<?php

namespace App\Rules;

use App\Models\Contract;
use Illuminate\Contracts\Validation\Rule;

class BedExistsContractRule implements Rule
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
        $contract = Contract::where('bed_id', $value)->count();
        if ($contract < 1) {
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
        return 'Giường đã có hợp đồng';
    }
}
