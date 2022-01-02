<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Floor;

class nameFloorUnique implements Rule
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
        $oldName = request()->floor->name;
        $oldBuiding = request()->floor->building_id;
        $idFloor = request()->floor->id;

        $buidingUniqueName = request()->building_id;
        $notUniqueName =Floor::where('name', $newName)->where('building_id',$buidingUniqueName)->exists();
        $notRequestId =Floor::where('name', $newName)->where('building_id',$buidingUniqueName)->first();

        if ($notUniqueName == true && $notRequestId->id !== $idFloor) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return 'Tên này đã tồn tại tầng của toà nhà.';
    }
}
