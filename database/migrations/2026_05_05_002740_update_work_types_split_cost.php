<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::table('work_types', function (Blueprint $table) {
			$table->renameColumn('cost', 'cost_clinic');
			$table->unsignedInteger('cost_mechanic')->default(0)->after('cost_clinic');
		});
	}

	public function down(): void
	{
		Schema::table('work_types', function (Blueprint $table) {
			$table->renameColumn('cost_clinic', 'cost');
			$table->dropColumn('cost_mechanic');
		});
	}
};
