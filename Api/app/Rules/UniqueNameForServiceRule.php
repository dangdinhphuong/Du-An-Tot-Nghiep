<?php

namespace App\Rules;

use App\Models\ProjectService;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueNameForServiceRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $data;
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
        // DB::enableQueryLog();
        foreach ($this->data as $d) {
            if ($d['name'] == $value) {
                $projectService = $d;
            }
        }

        $arrToCheck = ProjectService::where('name', $projectService['name'])->where('project_id', $projectService['project_id'])->count();
        // dump(DB::getQueryLog());
        // dump($arrToCheck);
        if ($arrToCheck < 1) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Tên dịch vụ dự án đã tồn tại';
    }
}
