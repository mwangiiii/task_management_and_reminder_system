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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('budget');
            $table->boolean('repeat');
            $table->text('description'); 
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('recurrency_id')->constrained('recurrencies')->onDelete('cascade');
            $table->string('currency');
            $table->integer('cost');
            $table->foreignId('completion_status_id')->constrained('completion__statuses')->onDelete('cascade');
            $table->dateTime('start_date'); 
            $table->dateTime('due_date');  
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Uncomment if needed
            $table->foreignId('parent_task_id')->nullable()->constrained('tasks')->onDelete('cascade');

            $table->timestamps(); 
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
