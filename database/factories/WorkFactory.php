<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\Mechanic;
use App\Models\Work;
use App\Models\WorkType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Work>
 */
class WorkFactory extends Factory
{
	public function definition() : array
	{
		return [
			'start'   => $this->faker->date(),
			'end'     => $this->faker->optional()->date(),
			'state'   => $this->faker->numberBetween(0, 3),
			'mid'     => Mechanic::inRandomOrder()->first()->id ?? Mechanic::factory(),
			'cid'     => Clinic::inRandomOrder()->first()->id ?? Clinic::factory(),
			'comment' => $this->faker->sentence(),
			'patient' => $this->faker->name(),
			'cost'    => 0,
		];
	}

	public function configure() : WorkFactory|Factory
	{
		return $this->afterCreating(function (Work $work)
		{
			$workTypes = WorkType::inRandomOrder()->limit(rand(1, 3))->get();
			foreach ($workTypes as $type)
			{
				$work->workTypes()->attach($type->id, ['count' => rand(1, 5)]);
			}

			$work->load('workTypes');
			$total = $work->workTypes->sum(fn($t) => $t->cost * $t->pivot->count);
			$work->update(['cost' => $total]);
		});
	}
}
