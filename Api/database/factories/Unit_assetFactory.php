<?php

namespace Database\Factories;

use App\Models\Unit_asset;
use Illuminate\Database\Eloquent\Factories\Factory;

class Unit_assetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Unit_asset::class;

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
