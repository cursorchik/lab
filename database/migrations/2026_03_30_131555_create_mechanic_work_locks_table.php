<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
		Schema::create('mechanic_work_locks', function (Blueprint $table) {
			$table->id();
			$table->foreignId('work_id')->constrained()->onDelete('cascade');
			$table->foreignId('mechanic_id')->constrained()->onDelete('cascade');
			$table->timestamp('locked_at')->useCurrent();
			$table->timestamps();
			$table->unique(['work_id', 'mechanic_id']);
		});
    }

    public function down(): void
    {
        Schema::dropIfExists('mechanic_work_locks');
    }
};
