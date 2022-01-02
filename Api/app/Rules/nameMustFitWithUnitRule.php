<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class nameMustFitWithUnitRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $data;
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
        foreach ($this->data as $d) {
            if ($d['unit'] == $value) {
                $projectService = $d;
            }
        }
        if ($projectService['name'] == config('service_unit.electric.name') && $value == config('service_unit.electric.unit')) {
            return true;
        } elseif ($projectService['name'] == config('service_unit.water.name') && $value == config('service_unit.water.unit')) {
            return true;
        } elseif ($projectService['name'] !== config('service_unit.water.name') && $projectService['name'] !== config('service_unit.electric.name') && $value == config('service_unit.other.unit')) {
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
        return 'Đơn vị va ten sai';
    }
}
