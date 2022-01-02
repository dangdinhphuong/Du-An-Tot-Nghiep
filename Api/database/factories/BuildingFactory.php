<?php

namespace Database\Factories;

use App\Models\Building;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class BuildingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Building::class;

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
            "note" => $this->faker->text(),
            "project_id" =>  Project::all()->random()->id
        ];
    }
}
