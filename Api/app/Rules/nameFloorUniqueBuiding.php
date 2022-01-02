<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Floor;

class nameFloorUniqueBuiding implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
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
        $buidingUniqueName = request()->building_id;
        $notUniqueName = Floor::where('name', $value)->where('building_id', $buidingUniqueName)->exists();

        if ($notUniqueName == true) {
            return false;
        }
        return true;
    }

    public function message()
    {
        return 'tên này chỉ được tồn tại 1 lần duy nhất trong 1 tầng';
    }
}
