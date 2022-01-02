<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Room_type;
use Illuminate\Database\Eloquent\Factories\Factory;

class Room_typeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Room_type::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "name" => $this->faker->name(),
            "price" => $this->faker->numberBetween(1, 6),
            "price_deposit" => $this->faker->numberBetween(1, 6),
            "number_bed" => $this->faker->numberBetween(1, 4),
            "project_id" => Project::all()->random()->id
        ];
    }
}
