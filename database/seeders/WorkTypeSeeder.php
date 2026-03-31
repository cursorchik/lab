<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class WorkTypeSeeder extends Seeder
{
	public function run(): void
	{
		// Если данные уже есть – пропускаем
		if (DB::table('work_types')->count() > 0) {
			return;
		}

		$data = [
			['id' => 1, 'name' => 'Металллокерамическая коронка ( IPS InLine)', 'cost' => 2800, 'created_at' => null, 'updated_at' => '2026-03-19 17:12:33'],
			['id' => 2, 'name' => 'Цельнолитая коронка (без напыления)', 'cost' => 1350, 'created_at' => null, 'updated_at' => null],
			['id' => 3, 'name' => 'Напыление цельнолитой коронки', 'cost' => 200, 'created_at' => null, 'updated_at' => null],
			['id' => 4, 'name' => 'Коронка штампованная', 'cost' => 1150, 'created_at' => null, 'updated_at' => null],
			['id' => 5, 'name' => 'Коронка штампованная комбинированная (фасета)', 'cost' => 1250, 'created_at' => null, 'updated_at' => null],
			['id' => 6, 'name' => 'Культевая вкладка', 'cost' => 1250, 'created_at' => null, 'updated_at' => null],
			['id' => 7, 'name' => 'Культевая вкладка разборная', 'cost' => 1450, 'created_at' => null, 'updated_at' => null],
			['id' => 8, 'name' => 'Фрезерованная пластиковая коронка  PMMA', 'cost' => 850, 'created_at' => null, 'updated_at' => null],
			['id' => 9, 'name' => 'Безметалловая коронка (Диоксид циркония )', 'cost' => 5500, 'created_at' => null, 'updated_at' => null],
			['id' => 10, 'name' => 'Вкладка из диоксида циркония', 'cost' => 3000, 'created_at' => null, 'updated_at' => null],
			['id' => 11, 'name' => 'Вкладка из диоксида циркония (разборная)', 'cost' => 3800, 'created_at' => null, 'updated_at' => null],
			['id' => 12, 'name' => 'Безметалловая коронка (дисиликат лития)(метод прессования)', 'cost' => 6000, 'created_at' => null, 'updated_at' => null],
			['id' => 13, 'name' => 'Безметалловая коронка (дисиликат лития)(метод фрезерования кон-ции)', 'cost' => 6500, 'created_at' => null, 'updated_at' => null],
			['id' => 14, 'name' => 'Wax-up (восковое моделирование 1ед)', 'cost' => 600, 'created_at' => null, 'updated_at' => null],
			['id' => 15, 'name' => 'Модель гипсовая для воскового Wax-Up', 'cost' => 250, 'created_at' => null, 'updated_at' => null],
			['id' => 16, 'name' => 'Wax-up (цифровое моделирование 1ед)', 'cost' => 400, 'created_at' => null, 'updated_at' => null],
			['id' => 17, 'name' => 'Модель полимерная после Wax-up (полимер)', 'cost' => 800, 'created_at' => null, 'updated_at' => null],
			['id' => 18, 'name' => 'Цельнолитая коронка (без напыления) +облицовка пластмассой Vilacril STC', 'cost' => 1750, 'created_at' => null, 'updated_at' => null],
			['id' => 19, 'name' => 'Напыление бюгельного протеза', 'cost' => 1200, 'created_at' => null, 'updated_at' => null],
			['id' => 20, 'name' => 'Ложка индивидуальная', 'cost' => 1300, 'created_at' => null, 'updated_at' => null],
			['id' => 21, 'name' => 'Ложка индивидуальная (3D печать)', 'cost' => 2600, 'created_at' => null, 'updated_at' => null],
			['id' => 22, 'name' => 'Армировка литая', 'cost' => 1250, 'created_at' => null, 'updated_at' => null],
			['id' => 23, 'name' => 'Армировка сетчатая ( готовая,напыленная)', 'cost' => 900, 'created_at' => null, 'updated_at' => null],
			['id' => 24, 'name' => 'Прикусной валик на жестком базисе', 'cost' => 1350, 'created_at' => null, 'updated_at' => null],
			['id' => 25, 'name' => 'Бюгельный протез (кламмерная фиксация)', 'cost' => 12500, 'created_at' => null, 'updated_at' => null],
			['id' => 26, 'name' => 'Бюгельный протез (замковая фиксация до 4 опор)', 'cost' => 14500, 'created_at' => null, 'updated_at' => null],
			['id' => 27, 'name' => 'Съемный протез (полная адентия)', 'cost' => 4500, 'created_at' => null, 'updated_at' => null],
			['id' => 28, 'name' => 'Съемный протез (частичная адентия до 3-х зубов)', 'cost' => 4500, 'created_at' => null, 'updated_at' => null],
			['id' => 29, 'name' => 'Съемный протез ( NYLON)', 'cost' => 7500, 'created_at' => null, 'updated_at' => null],
			['id' => 30, 'name' => 'Бюгельный протез  (Acetal)', 'cost' => 7500, 'created_at' => null, 'updated_at' => null],
			['id' => 31, 'name' => 'Нейлоновый косметический протез (бабочка)', 'cost' => 4000, 'created_at' => null, 'updated_at' => null],
			['id' => 32, 'name' => 'Починка акрилового протеза (линейный перелом)(до 2-х трещин)', 'cost' => 1000, 'created_at' => null, 'updated_at' => null],
			['id' => 33, 'name' => 'Починка акрилового протеза (приварка кламмера)', 'cost' => 1200, 'created_at' => null, 'updated_at' => null],
			['id' => 34, 'name' => 'Починка акрилового протеза (приварка до 3-х зубов)', 'cost' => 1300, 'created_at' => null, 'updated_at' => null],
			['id' => 35, 'name' => 'Перебазировка акрилового протеза (метод формования горячей ванны)', 'cost' => 1500, 'created_at' => null, 'updated_at' => null],
			['id' => 36, 'name' => 'Чистка акрилового протеза + полировка', 'cost' => 700, 'created_at' => null, 'updated_at' => null],
			['id' => 37, 'name' => 'Акриловый протез с опорами на имплантаты', 'cost' => 8200, 'created_at' => null, 'updated_at' => null],
			['id' => 38, 'name' => 'Металлокерамическая коронка на винтовой фиксации (приливаемый абатмент)', 'cost' => 4200, 'created_at' => null, 'updated_at' => null],
			['id' => 39, 'name' => 'Металлокерамическая коронка на индивидуальном титановом абатменте (индив.абатмент +мк коронка)', 'cost' => 6500, 'created_at' => null, 'updated_at' => null],
			['id' => 40, 'name' => 'Металлокерамическая коронка на стандартном титановом основании (Lenmiriot основание +мк коронка)', 'cost' => 4200, 'created_at' => null, 'updated_at' => null],
			['id' => 41, 'name' => 'Металлокерамическая коронка на предоставляемой протетике', 'cost' => 3000, 'created_at' => null, 'updated_at' => null],
			['id' => 42, 'name' => 'Хирургический шаблон (1 опора )', 'cost' => 4000, 'created_at' => null, 'updated_at' => null],
			['id' => 43, 'name' => 'Каждая последующая опора хирургического шаблона', 'cost' => 500, 'created_at' => null, 'updated_at' => null],
			['id' => 44, 'name' => 'Рентгеноконтрастный шаблон', 'cost' => 1000, 'created_at' => null, 'updated_at' => null],
			['id' => 45, 'name' => 'Модель под Трансфер-чек ( с силиконовой маской)', 'cost' => 250, 'created_at' => null, 'updated_at' => null],
			['id' => 46, 'name' => 'Трансфер-чек (1 опора)', 'cost' => 150, 'created_at' => null, 'updated_at' => null],
			['id' => 47, 'name' => 'Диоксид циркония на стандартном титановом основании (винтовая фиксация)(Lenmiriot+винт+коронка)', 'cost' => 7200, 'created_at' => null, 'updated_at' => null],
			['id' => 48, 'name' => 'Диоксид циркония на индивидуальном титановом абатменте (цементная фиксация) (абатмент+винт+коронка)', 'cost' => 8700, 'created_at' => null, 'updated_at' => null],
			['id' => 49, 'name' => 'Диоксид циркония на индивидуальном титановом абатменте (винтовая фиксация)(абатмент+винт+коронка)', 'cost' => 9000, 'created_at' => null, 'updated_at' => null],
			['id' => 50, 'name' => 'Диоксид циркония на циркониевом  абатменте (стандартное основание+цирконевый абатмент +винт+коронка)', 'cost' => 12950, 'created_at' => null, 'updated_at' => null],
			['id' => 51, 'name' => 'Коронка пластиковая на индивидуальном титановом абатменте (цементная фиксация)  (PMMA+абатмент +винт)', 'cost' => 4350, 'created_at' => null, 'updated_at' => null],
			['id' => 52, 'name' => 'Коронка пластиковая на индивидуальном титановом абатменте (винтовая фиксация)(PMMA+абатмент +винт)', 'cost' => 4500, 'created_at' => null, 'updated_at' => null],
			['id' => 53, 'name' => 'Коронка пластиковая на стандартном титановом основании (цементная фиксация) (PMMA+абатмент +винт)', 'cost' => 2000, 'created_at' => null, 'updated_at' => null],
			['id' => 54, 'name' => 'Коронка пластиковая на стандартном титановом основании  (винтовая фиксация)(PMMA+абатмент +винт)', 'cost' => 2000, 'created_at' => null, 'updated_at' => null],
			['id' => 55, 'name' => 'Модель диагностическая', 'cost' => 250, 'created_at' => null, 'updated_at' => null],
			['id' => 56, 'name' => 'Напыление индивидуального абатмента (титан-цирконий)', 'cost' => 200, 'created_at' => null, 'updated_at' => null],
			['id' => 57, 'name' => 'Анодирование индивидуального абатмента', 'cost' => 250, 'created_at' => null, 'updated_at' => null],
			['id' => 58, 'name' => 'Чистка +полировка NYLON протеза', 'cost' => 1200, 'created_at' => null, 'updated_at' => null],
			['id' => 59, 'name' => 'Чистка +полировка  ACETAL протеза', 'cost' => 1200, 'created_at' => null, 'updated_at' => null],
			['id' => 60, 'name' => 'Pамена матрицы бюгельный протеза (1ед)( матрица входит в стоимость)', 'cost' => 1300, 'created_at' => null, 'updated_at' => null],
			['id' => 61, 'name' => 'Каппа ортодонтическая (вакумформер)', 'cost' => 1600, 'created_at' => null, 'updated_at' => null],
			['id' => 62, 'name' => 'Каппа ортодонтическая (EXOCAD)(полимер)', 'cost' => 5500, 'created_at' => null, 'updated_at' => null],
			['id' => 63, 'name' => 'Балка на 4-х опорах (литая)', 'cost' => 8000, 'created_at' => null, 'updated_at' => null],
		];

		DB::table('work_types')->insert($data);
		// Сбросить автоинкремент на следующий ID (64)
		DB::statement('ALTER TABLE work_types AUTO_INCREMENT = 64');
	}
}
