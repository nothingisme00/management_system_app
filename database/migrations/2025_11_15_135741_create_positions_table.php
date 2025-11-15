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
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code', 20)->unique()->comment('Unique position code');
            $table->text('description')->nullable();
            $table->integer('level')->default(1)->comment('Hierarchical level (1=lowest)');
            $table->foreignId('department_id')->nullable()
                ->constrained('departments')
                ->nullOnDelete()
                ->comment('Associated department (optional)');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes
            $table->index('is_active');
            $table->index('level');
            $table->index('code');
            $table->index('department_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
