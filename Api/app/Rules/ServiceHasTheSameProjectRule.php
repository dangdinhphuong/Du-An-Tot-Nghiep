<?php

namespace App\Rules;

use App\Models\ProjectService;
use Illuminate\Contracts\Validation\Rule;

class ServiceHasTheSameProjectRule implements Rule
{
    public $projectServiceId;
    public $projectID = [];
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($projectServiceId)
    {
        $this->projectServiceId = $projectServiceId;
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
        foreach ($this->projectServiceId as $projectService) {
            $service = ProjectService::find($projectService);
            if ($service != null) {
                $this->projectID[] = $service->project_id;
            }
        }
        if (count(array_unique($this->projectID)) === 1) {
            // dd('all values in array are the same');
            return true;
        } else {
            // dd('all values in array are not the same');
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Các dịch vụ dự án không thuộc cùng 1 dự án';
    }
}
