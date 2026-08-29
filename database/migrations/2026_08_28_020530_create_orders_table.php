<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('kode_order')->unique();

            $table->string('nama_pelanggan');

            $table->string('no_hp')->nullable();

            $table->text('alamat')->nullable();

            $table->decimal('total_harga', 15, 2);

            $table->string('metode_pembayaran')
                ->default('cash');

            $table->string('status')
                ->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};