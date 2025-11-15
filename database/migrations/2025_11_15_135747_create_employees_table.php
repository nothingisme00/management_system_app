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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete()
                ->comment('One-to-one relationship with users');
            $table->string('employee_id', 50)->unique()->comment('Auto-generated unique employee identifier');
            $table->foreignId('department_id')->nullable()
                ->constrained('departments')
                ->nullOnDelete();
            $table->foreignId('position_id')->nullable()
                ->constrained('positions')
                ->nullOnDelete();
            $table->string('phone_number', 20)->nullable();
            $table->text('address')->nullable();
            $table->date('join_date')->comment('Date employee joined company');
            $table->date('termination_date')->nullable();
            $table->enum('employment_status', ['active', 'inactive', 'on_leave', 'terminated'])
                ->default('active');
            $table->timestamps();

            // Indexes for performance
            $table->index('employee_id');
            $table->index('department_id');
            $table->index('position_id');
            $table->index('employment_status');
            $table->index('join_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
