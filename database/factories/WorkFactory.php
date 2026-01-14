<?php

namespace Database\Factories;

use App\Models\Work;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Work>
 */
class WorkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'start'     => $this->faker->dateTimeBetween('-2 year'),
            'end'       => $this->faker->date('Y-m-d', '+4 week'),
            'state'     => 0,
            'count'     => $this->faker->numberBetween(1, 20),
            'mid'       => $this->faker->numberBetween(1, 5),
            'cid'       => $this->faker->numberBetween(1, 11),
            'wtid'      => $this->faker->numberBetween(1, 63),
            'comment'   => $this->faker->sentence(),
            'patient'   => $this->faker->firstName(),
            'cost'      => 0,
        ];
    }
}
