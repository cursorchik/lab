<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('works', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->date('start');
            $table->date('end')->nullable();
            $table->integer('state');
            $table->integer('count'); // 0 - не начата; 1 - в работе; 2 - завершена; 3 - увезли
            $table->integer('mid')->comment('mechanicID');
            $table->integer('cid')->comment('clinicID');
            $table->integer('wtid')->comment('workTypeID');
            $table->string('comment')->nullable();
        });
    }

    /** Reverse the migrations */
    public function down(): void
    {
        Schema::dropIfExists('works');
    }
};
