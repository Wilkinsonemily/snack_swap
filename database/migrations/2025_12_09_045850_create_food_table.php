<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('foods', function (Blueprint $table) {
            $table->id();
            $table->string('off_code', 64)->nullable()->unique();
            $table->string('name');
            $table->string('brand')->nullable();
            $table->string('image')->nullable();
            $table->string('country', 4)->nullable();
            
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            
            $table->float('calories')->nullable();
            $table->float('sugar')->nullable();
            $table->float('fat')->nullable();
            $table->unsignedInteger('sodium')->nullable();
            $table->float('protein')->nullable();
            $table->float('fiber')->nullable();

            $table->boolean('is_healthy')->default(false);
            $table->boolean('is_vegan')->default(false);
            $table->boolean('is_gluten_free')->default(false);

            $table->text('health_reason')->nullable();

            $table->timestamps();
            $table->unique(['name', 'brand']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('foods');
    }
};