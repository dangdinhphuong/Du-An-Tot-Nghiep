<?php

namespace App\Rules;

use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class EndDayNotSurpassCycleDayRule implements Rule
{
    public $data;
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
        $endMonth = Carbon::createFromFormat('Y-m-d', $value);
        $startMonth = Carbon::createFromFormat('Y-m-d', $this->data['start_day']);
        $room = Room::with('floor.building.project')->find($this->data['room_id']);
        // dd($room->floor->building->project->cycle_collect);
        $cycle_collect = $room->floor->building->project->cycle_collect;
        $maxMonth = $startMonth->diffInMonths($endMonth);
        if ($maxMonth < $cycle_collect) {
            return false;
        }
        return true;
        // dd($endMonth, $startMonth);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Thời hạn hợp đồng nhỏ hơn chu kỳ thu';
    }
}
