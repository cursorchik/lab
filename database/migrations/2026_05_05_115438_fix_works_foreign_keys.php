<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::table('works', function (Blueprint $table) {
			$table->unsignedBigInteger('mid')->change();
			$table->unsignedBigInteger('cid')->change();
		});

		Schema::table('works', function (Blueprint $table) {
			$table->foreign('mid')
				  ->references('id')
				  ->on('mechanics')
				  ->onDelete('restrict');

			$table->foreign('cid')
				  ->references('id')
				  ->on('clinics')
				  ->onDelete('restrict');
		});
	}

	public function down(): void
	{
		Schema::table('works', function (Blueprint $table) {
			$table->dropForeign(['mid']);
			$table->dropForeign(['cid']);

			$table->integer('mid')->change();
			$table->integer('cid')->change();
		});
	}
};
