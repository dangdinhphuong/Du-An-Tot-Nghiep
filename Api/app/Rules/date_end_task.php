<?php

namespace App\Rules;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Rule;

class date_end_task implements Rule
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
        //
        if(strtotime("-3 day", strtotime(request()->date_end))<strtotime(request()->date_start)){
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
        return 'Ngày kết thúc sau ngày bắt đầu tối thiểu 3 ngày';
    }
}