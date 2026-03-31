<?php

namespace Database\Seeders;

use App\Models\Clinic;
use Illuminate\Database\Seeder;

class ClinicSeeder extends Seeder
{
	public function run(): void
	{
		$clinics = [
			['name' => 'Дента Смайл (Мира)'],
			['name' => 'Дента Смайл (Суворова)'],
			['name' => 'Дента Смайл (Камсомольский)'],
			['name' => 'Дента Смайл (Фрунзе)'],
			['name' => 'Клиника "Сибирская"'],
			['name' => 'Томская стоматология'],
			['name' => 'ДенталАрт (Ковалева)'],
			['name' => 'ДенталАрт (Герцена)'],
			['name' => 'Дентас'],
			['name' => 'Харизма'],
			['name' => 'Ушайка'],
		];

		foreach ($clinics as $clinic) {
			Clinic::firstOrCreate(['name' => $clinic['name']]);
		}
	}
}
