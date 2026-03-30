<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::table('works', function (Blueprint $table)
		{
			// Если на поле wtid есть внешний ключ, сначала удалите его
			// $table->dropForeign(['wtid']); // раскомментируйте, если FK был создан

			$table->dropColumn(['count', 'wtid']);
		});
	}

	public function down(): void
	{
		Schema::table('works', function (Blueprint $table) {
			$table->integer('count')->nullable()->after('state');
			$table->integer('wtid')->nullable()->after('cid');

			// Если нужно восстановить внешний ключ
			// $table->foreign('wtid')->references('id')->on('work_types');
		});
	}
};
