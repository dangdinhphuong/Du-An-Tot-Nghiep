<?php

namespace Database\Factories;

use App\Models\Asset_type;
use Illuminate\Database\Eloquent\Factories\Factory;

class Asset_typeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Asset_type::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'=>$this->faker->name,
        ];
    }
}
