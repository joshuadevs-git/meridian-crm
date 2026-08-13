<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_schedules', function (Blueprint $table) {

            $table->id();

            $table->foreignId('employee_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Specific date of the schedule
            $table->date('schedule_date');

            // Working / Off / Leave
            $table->enum('status', [
                'Working',
                'Off',
                'Leave',
            ])->default('Working');

            // Working hours
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            // 1-hour break
            $table->time('break_start')->nullable();
            $table->time('break_end')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            // Prevent duplicate schedules for the same employee/date
            $table->unique([
                'employee_id',
                'schedule_date'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_schedules');
    }
};