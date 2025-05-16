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
        Schema::create('work_contributors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_log_id')->constrained()->onDelete('cascade');
            $table->string('employee_name');
            $table->decimal('hours_spent', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_contributors');
    }
};
