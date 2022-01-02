<?php

namespace Database\Factories;

use App\Models\Receipt;
use App\Models\User;
use App\Models\Receipts_reason;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ReceiptFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Receipt::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'collection_date' => date("Y-m-d"),
            'user_id' => User::all()->random()->id,
            'amount_of_money' => $this->faker->numberBetween($min = 10000000, $max = 150000000),
            'payment_type' =>  config('Receipt.payment_type.' . rand(1, 2)),
            'note' => $this->faker->text(100),
            'receipt_reason_id' => Receipts_reason::all()->random()->id,
        ];
    }
}
