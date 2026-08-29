<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_pelanggan', function (Blueprint $table) {
            $table->string('no_hp', 20)->nullable()->after('nama_pelanggan');
            $table->text('alamat')->nullable()->after('kota');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_pelanggan', function (Blueprint $table) {
            $table->dropColumn(['no_hp', 'alamat']);
        });
    }
};