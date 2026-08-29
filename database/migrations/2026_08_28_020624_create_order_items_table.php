<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {

            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->foreignId('produk_id')
                ->constrained('produks')
                ->cascadeOnDelete();

            $table->string('nama_produk');

            $table->decimal('harga', 15, 2);

            $table->integer('jumlah');

            $table->decimal('subtotal', 15, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};