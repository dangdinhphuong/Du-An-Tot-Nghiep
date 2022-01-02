<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Building;

class nameUniqueUpdateBuiding implements Rule
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
    public function passes($attribute, $newName)
    {
        $oldName = request()->building->name;
        $oldBuiding = request()->building->project_id;
        $idFloor = request()->building->id;

        $buidingUniqueName = request()->project_id;
        $notUniqueName = Building::where('name', $newName)->where('project_id', $buidingUniqueName)->exists();
        $notRequestId = Building::where('name', $newName)->where('project_id', $buidingUniqueName)->first();

        if ($notUniqueName == true && $notRequestId->id !== $idFloor) {
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
        return 'Tên này đã tồn tại toà nhà của dự án.';
    }
}
