<?php

namespace Database\Factories;

use App\Models\Announcement;
use App\Models\TypeAnnounce;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnnouncementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Announcement::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'level' => array_rand(config('maintain.level')),
            'title' => $this->faker->title(),
            'content' => $this->faker->sentence(),
            'user_id' => User::all()->random()->id,
            'type_announce_id' => TypeAnnounce::all()->random()->id,
            'range' => mt_rand(1, 10),
            'date_start' => $this->faker->date(),
            'date_end' => $this->faker->date(),
        ];
    }
}
