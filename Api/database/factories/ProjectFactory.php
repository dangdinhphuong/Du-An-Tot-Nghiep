<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "name" => $this->faker->name(),
            "hotline" => $this->faker->phoneNumber(),
            "description" => $this->faker->text(),
            "address" => $this->faker->address(),
            "cycle_collect" => mt_rand(1, 12),
            "extension_time" => mt_rand(1, 5)
        ];
    }
}
