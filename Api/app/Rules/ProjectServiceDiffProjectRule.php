<?php

namespace App\Rules;

use App\Models\ProjectService;
use App\Models\Room;
use Illuminate\Contracts\Validation\Rule;

class ProjectServiceDiffProjectRule implements Rule
{
    public $room_id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($room_id)
    {
        //
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
        $room = Room::with(['floor.building.project'])->find($this->room_id)->first();
        $projectSerivce = ProjectService::find($value);
        if ($projectSerivce != null) {
            $project_id = $projectSerivce->project_id;
        } else {
            return false;
        }
        if ($room != null) {
            // dd($room);
            if ($project_id == $room->floor->building->project->id) {
                return true;
            }
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
        return 'Dịch vụ không thuộc về dự án';
    }
}
