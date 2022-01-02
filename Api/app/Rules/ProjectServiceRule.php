<?php

namespace App\Rules;

use App\Models\ProjectService;
use Illuminate\Contracts\Validation\Rule;

class ProjectServiceRule implements Rule
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
        $projectservice = ProjectService::find($value);
        if ($projectservice == null) {
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
        return 'Dịch vụ dự án không tồn tại.';
    }
}
