<?php

namespace App\Rules;

use App\Models\Bed;
use Illuminate\Contracts\Validation\Rule;

class BedUniqueNameInRoomRule implements Rule
{
    public $room_id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($room_id)
    {
        $this->room_id = $room_id;
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
        $bed = Bed::where('name', $value)->where('room_id', $this->room_id)->count();
        if ($bed > 0) {
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
        return 'Tên giường đã tồn tại trong phòng';
    }
}
