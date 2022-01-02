<?php

namespace App\Rules;

use App\Models\Receipts_reason;
use Illuminate\Contracts\Validation\Rule;

class ReceiptReasonExistsRule implements Rule
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
        $receiptReason = Receipts_reason::find($value);
        if ($receiptReason == null) {
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
        return 'Lý do thu không tồn tại';
    }
}
