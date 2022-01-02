<?php

namespace Database\Factories;

use App\Models\Receipts_reason;
use Illuminate\Database\Eloquent\Factories\Factory;

class Receipts_reasonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Receipts_reason::class;
    public function definition()
    {
        return [
            'title' => $this->faker->name(),
            'description' => $this->faker->paragraph(15),
        ];
    }
}
