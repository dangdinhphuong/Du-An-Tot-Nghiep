<?php

namespace Database\Factories;

use App\Models\Floor;
use App\Models\Room;
use App\Models\Room_type;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Room::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "name" => $this->faker->name(),
            "room_type_id" => Room_type::all()->random()->id,
            "floor_id" => Floor::all()->random()->id
        ];
    }
}
