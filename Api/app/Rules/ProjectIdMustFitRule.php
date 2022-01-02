<?php

namespace App\Rules;

use App\Models\ProjectService;
use Illuminate\Contracts\Validation\Rule;

class ProjectIdMustFitRule implements Rule
{
    protected $data;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
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
        foreach ($this->data as $projectService) {
            $projectServiceToTest = ProjectService::find($projectService['id']);
            if ($projectServiceToTest != null) {
                if ($projectServiceToTest->project_id != $projectService['project_id']) {
                    return false;
                } else {
                    return true;
                }
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
        return 'Dịch vụ không thuộc dự án';
    }
}
