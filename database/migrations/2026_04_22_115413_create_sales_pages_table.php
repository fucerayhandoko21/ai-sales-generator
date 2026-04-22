<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete(); // kalau user dihapus, pages-nya ikut terhapus

            // Data produk (input dari user)
            $table->string('product_name');
            $table->text('description');
            $table->text('key_features');
            $table->string('target_audience');
            $table->string('price');
            $table->text('unique_selling_points')->nullable();

            // Hasil generate
            $table->longText('generated_html'); // pakai longText karena HTML bisa panjang
            $table->string('template')->default('modern'); // modern | minimal | bold

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_pages');
    }
};