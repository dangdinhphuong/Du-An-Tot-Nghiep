<?php

namespace App\Rules;

use App\Models\ProjectService;
use Illuminate\Contracts\Validation\Rule;

class OnlyPermamentServiceRule implements Rule
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
        $projectService = ProjectService::find($value);
        if ($projectService != null) {
            if ($projectService->permanent == 0) {
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Những dịch vụ thu theo tháng không thêm được vào hợp đồng';
    }
}
