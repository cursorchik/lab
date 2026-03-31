<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mechanic;

class MechanicSeeder extends Seeder
{
	public function run(): void
	{
		$mechanics = [
			['name' => 'Антон'],
			['name' => 'Ольга'],
			['name' => 'Мехман'],
			['name' => 'Леша китаец'],
			['name' => 'Леша Старший'],
		];

		foreach ($mechanics as $mechanic) {
			Mechanic::firstOrCreate(['name' => $mechanic['name']]);
		}
	}
}
