<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentInfoFactory extends Factory
{
    public $data = [];
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'student_code' => $this->faker->unique->countryCode(),
            'department' => $this->faker->name(),
            'student_year' => rand(1, 4),
            'nation' => $this->faker->country(),
            'religion' => $this->faker->name(),
            'CCCD' => $this->faker->numerify('#########'),
            'date_range' => $this->faker->dateTimeBetween(),
            'issued_by' => $this->faker->address(),
            'user_id' => $this->generateIDNumber(),
            'school' => collect(config('schools.schools'))->random()
        ];
    }
    public  function generateIDNumber()
    {
        $number = mt_rand(1, 50); // better than rand()
        // call the same function if the ID exists already
        if ($this->IDNumberExists($number)) {
            return $this->generateIDNumber();
        }

        // otherwise, it's valid and can be used
        return $number;
    }

    public  function IDNumberExists($number)
    {
        // query the database and return a boolean
        // for instance, it might look like this in Laravel

        if (in_array($number, $this->data)) {

            return true;
        } else {
            $this->data[] = $number;

            return false;
        }
    }
}
