<?php

namespace App\Rules;

use App\Models\Contract;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class CollectDateRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $id;
    public function __construct($id)
    {
        $this->id = $id;
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
        $contract = Contract::find($this->id);
        $value = Carbon::parse($value);
        $startDay = Carbon::parse($contract->start_day);
        $endDay = Carbon::parse($contract->end_day);
        if ($startDay->gt($value) || $value->gt($endDay)) {
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
        return 'Ngày thu không đúng';
    }
}
