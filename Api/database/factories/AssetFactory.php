<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\Asset_type;
use App\Models\Producer;
use App\Models\Unit_asset;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Asset::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'=>$this->faker->name,
            'price'=>rand(1000, 10000000),
            'description' => $this->faker->realText($maxNbChars = 150, $indexSize = 1),
            'min_inventory'=>rand(1, 100),
            'image' => $this->faker->realText($maxNbChars = 50, $indexSize = 1),
            'producer_id'=>Producer::all()->random()->id,
            'unit_asset_id'=>Unit_asset::all()->random()->id,
            'asset_type_id'=>Asset_type::all()->random()->id,

        ];
    }
}
