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
			'start'   			=> $this->faker->date(),
			'end'     			=> $this->faker->optional()->date(),
			'state'   			=> $this->faker->numberBetween(0, 3),
			'mid'     			=> Mechanic::inRandomOrder()->first()->id ?? Mechanic::factory(),
			'cid'     			=> Clinic::inRandomOrder()->first()->id ?? Clinic::factory(),
			'comment' 			=> $this->faker->sentence(),
			'patient' 			=> $this->faker->name(),
			'cost_clinic'		=> 0,
			'cost_mechanic'		=> 0,
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

			$totalClinic = 0;
			$totalMechanic = 0;

			foreach ($work->workTypes as $type)
			{
				$totalClinic += $type->cost_clinic * $type->pivot->count;
				$totalMechanic += $type->cost_mechanic * $type->pivot->count;
			}
			$work->update([
				'cost_clinic' => $totalClinic,
				'cost_mechanic' => $totalMechanic,
			]);
		});
	}
}
