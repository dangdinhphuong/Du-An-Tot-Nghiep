<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\TypeAnnounce;
class NameUniqueTypeAnnource implements Rule
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
        $oldName =request()->name;
        if($newName === $oldName){
            return true;
        }
        $kiemTra = TypeAnnounce::where('name',$newName)->count();
        if($kiemTra > 0){
            return false;
        }
        return true;
    }

    public function message()
    {
        return 'Tên này đã tồn tại.';
    }
}
