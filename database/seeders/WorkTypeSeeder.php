<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkTypeSeeder extends Seeder
{
	public function run(): void
	{
		if (DB::table('work_types')->count() > 0) return;

		$data = [
			['name' => 'Металллокерамическая коронка ( IPS InLine)',																'cost_clinic' => 2800,	'cost_mechanic' => 1400],
			['name' => 'Цельнолитая коронка (без напыления)',																		'cost_clinic' => 1350,	'cost_mechanic' => 440],
			['name' => 'Напыление цельнолитой коронки',																				'cost_clinic' => 200,	'cost_mechanic' => 0],
			['name' => 'Коронка штампованная',																						'cost_clinic' => 1150,	'cost_mechanic' => 620],
			['name' => 'Коронка штампованная комбинированная (фасета)',																'cost_clinic' => 1250,	'cost_mechanic' => 970],
			['name' => 'Культевая вкладка',																							'cost_clinic' => 1250,	'cost_mechanic' => 400],
			['name' => 'Культевая вкладка разборная',																				'cost_clinic' => 1450,	'cost_mechanic' => 400],
			['name' => 'Фрезерованная пластиковая коронка  PMMA',																	'cost_clinic' => 850,	'cost_mechanic' => 250],
			['name' => 'Безметалловая коронка (Диоксид циркония )',																	'cost_clinic' => 5500,	'cost_mechanic' => 1370],
			['name' => 'Вкладка из диоксида циркония',																				'cost_clinic' => 3000,	'cost_mechanic' => 1370],
			['name' => 'Вкладка из диоксида циркония (разборная)',																	'cost_clinic' => 3800,	'cost_mechanic' => 1500],
			['name' => 'Безметалловая коронка (дисиликат лития)(метод прессования)',												'cost_clinic' => 6000,	'cost_mechanic' => 1500],
			['name' => 'Безметалловая коронка (дисиликат лития)(метод фрезерования кон-ции)',										'cost_clinic' => 6500,	'cost_mechanic' => 1500],
			['name' => 'Wax-up (восковое моделирование 1ед)',																		'cost_clinic' => 600,	'cost_mechanic' => 150],
			['name' => 'Модель гипсовая для воскового Wax-Up',																		'cost_clinic' => 250,	'cost_mechanic' => 0],
			['name' => 'Wax-up (цифровое моделирование 1ед)',																		'cost_clinic' => 400,	'cost_mechanic' => 150],
			['name' => 'Модель полимерная после Wax-up (полимер)',																	'cost_clinic' => 800,	'cost_mechanic' => 250],
			['name' => 'Цельнолитая коронка (без напыления) +облицовка пластмассой Vilacril STC',									'cost_clinic' => 1750,	'cost_mechanic' => 0],
			['name' => 'Напыление бюгельного протеза',																				'cost_clinic' => 1200,	'cost_mechanic' => 0],
			['name' => 'Ложка индивидуальная',																						'cost_clinic' => 1300,	'cost_mechanic' => 520],
			['name' => 'Ложка индивидуальная (3D печать)',																			'cost_clinic' => 2600,	'cost_mechanic' => 600],
			['name' => 'Армировка литая',																							'cost_clinic' => 1250,	'cost_mechanic' => 520],
			['name' => 'Армировка сетчатая ( готовая,напыленная)',																	'cost_clinic' => 900,	'cost_mechanic' => 520],
			['name' => 'Прикусной валик на жестком базисе',																			'cost_clinic' => 1350,	'cost_mechanic' => 250],
			['name' => 'Бюгельный протез (кламмерная фиксация)',																	'cost_clinic' => 12500,	'cost_mechanic' => 3500],
			['name' => 'Бюгельный протез (замковая фиксация до 4 опор)',															'cost_clinic' => 14500,	'cost_mechanic' => 4000],
			['name' => 'Съемный протез (полная адентия)',																			'cost_clinic' => 4500,	'cost_mechanic' => 1900],
			['name' => 'Съемный протез (частичная адентия до 3-х зубов)',															'cost_clinic' => 4500,	'cost_mechanic' => 1900],
			['name' => 'Съемный протез ( NYLON)',																					'cost_clinic' => 7500,	'cost_mechanic' => 2850],
			['name' => 'Бюгельный протез  (Acetal)',																				'cost_clinic' => 7500,	'cost_mechanic' => 2850],
			['name' => 'Нейлоновый косметический протез (бабочка)',																	'cost_clinic' => 4000,	'cost_mechanic' => 1900],
			['name' => 'Починка акрилового протеза (линейный перелом)(до 2-х трещин)',												'cost_clinic' => 1000,	'cost_mechanic' => 450],
			['name' => 'Починка акрилового протеза (приварка кламмера)',															'cost_clinic' => 1200,	'cost_mechanic' => 450],
			['name' => 'Починка акрилового протеза (приварка до 3-х зубов)',														'cost_clinic' => 1300,	'cost_mechanic' => 450],
			['name' => 'Перебазировка акрилового протеза (метод формования горячей ванны)',											'cost_clinic' => 1500,	'cost_mechanic' => 925],
			['name' => 'Чистка акрилового протеза + полировка',																		'cost_clinic' => 700,	'cost_mechanic' => 450],
			['name' => 'Акриловый протез с опорами на имплантаты',																	'cost_clinic' => 8200,	'cost_mechanic' => 3000],
			['name' => 'Металлокерамическая коронка на винтовой фиксации (приливаемый абатмент)',									'cost_clinic' => 4200,	'cost_mechanic' => 1700],
			['name' => 'Металлокерамическая коронка на индивидуальном титановом абатменте (индив.абатмент +мк коронка)',			'cost_clinic' => 6500,	'cost_mechanic' => 1700],
			['name' => 'Металлокерамическая коронка на стандартном титановом основании (Lenmiriot основание +мк коронка)',			'cost_clinic' => 4200,	'cost_mechanic' => 1700],
			['name' => 'Металлокерамическая коронка на предоставляемой протетике',													'cost_clinic' => 3000,	'cost_mechanic' => 1700],
			['name' => 'Хирургический шаблон (1 опора )',																			'cost_clinic' => 4000,	'cost_mechanic' => 1000],
			['name' => 'Каждая последующая опора хирургического шаблона',															'cost_clinic' => 500,	'cost_mechanic' => 200],
			['name' => 'Рентгеноконтрастный шаблон',																				'cost_clinic' => 1000,	'cost_mechanic' => 400],
			['name' => 'Модель под Трансфер-чек ( с силиконовой маской)',															'cost_clinic' => 250,	'cost_mechanic' => 100],
			['name' => 'Трансфер-чек (1 опора)',																					'cost_clinic' => 150,	'cost_mechanic' => 100],
			['name' => 'Диоксид циркония на стандартном титановом основании (винтовая фиксация)(Lenmiriot+винт+коронка)',			'cost_clinic' => 7200,	'cost_mechanic' => 1900],
			['name' => 'Диоксид циркония на индивидуальном титановом абатменте (цементная фиксация) (абатмент+винт+коронка)',		'cost_clinic' => 8700,	'cost_mechanic' => 1900],
			['name' => 'Диоксид циркония на индивидуальном титановом абатменте (винтовая фиксация)(абатмент+винт+коронка)',			'cost_clinic' => 9000,	'cost_mechanic' => 1900],
			['name' => 'Диоксид циркония на циркониевом  абатменте (стандартное основание+цирконевый абатмент +винт+коронка)',		'cost_clinic' => 12950,	'cost_mechanic' => 2750],
			['name' => 'Коронка пластиковая на индивидуальном титановом абатменте (цементная фиксация)  (PMMA+абатмент +винт)',		'cost_clinic' => 4350,	'cost_mechanic' => 650],
			['name' => 'Коронка пластиковая на индивидуальном титановом абатменте (винтовая фиксация)(PMMA+абатмент +винт)',		'cost_clinic' => 4500,	'cost_mechanic' => 650],
			['name' => 'Коронка пластиковая на стандартном титановом основании (цементная фиксация) (PMMA+абатмент +винт)',			'cost_clinic' => 2000,	'cost_mechanic' => 450],
			['name' => 'Коронка пластиковая на стандартном титановом основании  (винтовая фиксация)(PMMA+абатмент +винт)',			'cost_clinic' => 2000,	'cost_mechanic' => 450],
			['name' => 'Модель диагностическая',																					'cost_clinic' => 250,	'cost_mechanic' => 100],
			['name' => 'Напыление индивидуального абатмента (титан-цирконий)',														'cost_clinic' => 200,	'cost_mechanic' => 0],
			['name' => 'Анодирование индивидуального абатмента',																	'cost_clinic' => 250,	'cost_mechanic' => 0],
			['name' => 'Чистка +полировка NYLON протеза',																			'cost_clinic' => 1200,	'cost_mechanic' => 450],
			['name' => 'Чистка +полировка  ACETAL протеза',																			'cost_clinic' => 1200,	'cost_mechanic' => 450],
			['name' => 'Pамена матрицы бюгельный протеза (1ед)( матрица входит в стоимость)',										'cost_clinic' => 1300,	'cost_mechanic' => 300],
			['name' => 'Каппа ортодонтическая (вакумформер)',																		'cost_clinic' => 1600,	'cost_mechanic' => 450],
			['name' => 'Каппа ортодонтическая (EXOCAD)(полимер)',																	'cost_clinic' => 5500,	'cost_mechanic' => 1000],
			['name' => 'Балка на 4-х опорах (литая)',																				'cost_clinic' => 8000,	'cost_mechanic' => 3000],
			['name' => 'ALL-on-4 (стоимсоть 1 ед циркония + опора под ключ FP1+FP2)',												'cost_clinic' => 8500,	'cost_mechanic' => 3100],
			['name' => 'ALL-on-4 (стоимсоть 1 ед циркония + опора под ключ FP3)',													'cost_clinic' => 9000,	'cost_mechanic' => 4000],
			['name' => 'Фрезерованная пластиковая коронка  PMMA на балке',															'cost_clinic' => 1000,	'cost_mechanic' => 400],
		];

		DB::table('work_types')->insert($data);
	}
}
