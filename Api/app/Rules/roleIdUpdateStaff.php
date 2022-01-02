<?php

namespace App\Rules;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Rule;

class roleIdUpdateStaff implements Rule
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
        if(request()->role_id==2||request()->role_id=="2"){
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
        return 'Chức vụ không khả dụng';
    }
}
