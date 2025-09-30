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
        Schema::create('damage_reports', function (Blueprint $table) {
            $table->id();
            $table->string('reporter_name');
            $table->string('position');
            $table->string('department');
            $table->string('phone');
            $table->string('email');
            $table->string('category');
            $table->string('subcategory');
            $table->json('impact')->after('subcategory');
            $table->text('description');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('photo_path')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('damage_reports');
    }
};
