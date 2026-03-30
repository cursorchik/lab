<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('work_work_type', function (Blueprint $table)
		{
			$table->id();
			$table->foreignId('work_id')->constrained()->onDelete('restrict');
			$table->foreignId('work_type_id')->constrained('work_types', 'id')->onDelete('restrict');
			$table->integer('count');
			$table->timestamps();

			// Уникальность пары work_id + work_type_id
			$table->unique(['work_id', 'work_type_id']);
		});

		DB::afterCommit(function ()
		{
			$works = DB::table('works')->whereNotNull('wtid')->get();
			foreach ($works as $work)
			{
				DB::table('work_work_type')->insert([
					'work_id'      => $work->id,
					'work_type_id' => $work->wtid,
					'count'        => $work->count ?? 1,
					'created_at'   => now(),
					'updated_at'   => now(),
				]);
			}
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('work_work_type');
	}
};
