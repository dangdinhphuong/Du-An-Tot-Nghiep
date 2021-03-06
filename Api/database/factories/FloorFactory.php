<?php

namespace Database\Factories;

use App\Models\Building;
use App\Models\Floor;
use Illuminate\Database\Eloquent\Factories\Factory;

class FloorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Floor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "name" => $this->faker->name(),
            "total_area" => $this->faker->numberBetween(1, 6),
            "building_id" =>  Building::all()->random()->id
        ];
    }
}
