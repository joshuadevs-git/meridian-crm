<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {

            $table->date('period_start')
                ->nullable()
                ->after('payroll_date');

            $table->date('period_end')
                ->nullable()
                ->after('period_start');

            $table->index([
                'employee_id',
                'period_start',
                'period_end',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {

            $table->dropIndex([
                'payrolls_employee_id_period_start_period_end_index',
            ]);

            $table->dropColumn([
                'period_start',
                'period_end',
            ]);
        });
    }
};