<?php

namespace Database\Factories;

use App\Models\StudentInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentRelativeFactory extends Factory
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
            'farther_name' => $this->faker->name(),
            'mother_name' => $this->faker->name(),
            'address_relative' => $this->faker->address(),
            'phone_relative' => $this->faker->phoneNumber(),
            'student_info_id' => $this->generateIDNumber()
        ];
    }
    public  function generateIDNumber()
    {
        $number = mt_rand(1, 50); // better than rand()
        // call the same function if the value exists already
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
