<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('clinic_work_locks', function (Blueprint $table) {
			$table->id();
			$table->foreignId('work_id')->constrained()->onDelete('cascade');
			$table->foreignId('clinic_id')->constrained()->onDelete('cascade');
			$table->timestamp('locked_at')->useCurrent();
			$table->timestamps();

			$table->unique(['work_id', 'clinic_id']);
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('clinic_work_locks');
	}
};
