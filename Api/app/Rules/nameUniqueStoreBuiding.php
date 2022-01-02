<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Building;

class nameUniqueStoreBuiding implements Rule
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
        $projectUniqueName = request()->project_id;
        $notUniqueName = Building::where('name', $value)->where('project_id', $projectUniqueName)->exists();

        if ($notUniqueName == true) {
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
        return 'tên này chỉ được tồn tại 1 lần duy nhất trong toà nhà';
    }
}
