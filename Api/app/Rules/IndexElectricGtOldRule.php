<?php

namespace App\Rules;

use App\Models\ServiceIndex;
use App\Traits\TraitResponse;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class IndexElectricGtOldRule implements Rule
{
    protected $data;
    use TraitResponse;
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
        $serviceIndex = ServiceIndex::find($this->data);
        if ($serviceIndex == null) {
            return $this->responseApi("", 404, "Không tìm thấy bản ghi");
        }
        $previousMonth = Carbon::parse($serviceIndex->created_at)->subMonth();
        $modInsertDate =  date('Y-m-d H:i:s', strtotime($previousMonth));
        $serviceIndexsPrevious = ServiceIndex::where('created_at', 'like', '%' . $modInsertDate . '%')->where('room_id', $serviceIndex->room_id)->first();
        if ($serviceIndexsPrevious != null) {
            if ($serviceIndexsPrevious->index_electric > 0) {
                if ($value < $serviceIndexsPrevious->index_electric) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Chỉ số điện của tháng chốt này không thể nhỏ hơn tháng trước';
    }
}
