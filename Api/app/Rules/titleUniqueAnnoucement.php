<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Announcement;
class titleUniqueAnnoucement implements Rule
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
        $oldName =request()->title;
        if($newName === $oldName){
            return true;
        }
        $kiemTra = Announcement::where('name',$newName)->count();
        if($kiemTra > 0){
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
        return 'The validation error message.';
    }
}
