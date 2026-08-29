<?php

use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\CheckoutController;
use App\Models\Produk;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


/*
|--------------------------------------------------------------------------
| HALAMAN AWAL
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});


/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| ROUTE YANG HARUS LOGIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PELANGGAN
    |--------------------------------------------------------------------------
    */

    Route::get('/pelanggan', [PelangganController::class, 'index'])
        ->name('pelanggan.index');

    Route::post('/pelanggan', [PelangganController::class, 'store'])
        ->name('pelanggan.store');

    Route::get('/pelanggan/{pelanggan}', [PelangganController::class, 'show'])
        ->name('pelanggan.show');

    Route::get('/pelanggan/{pelanggan}/edit', [PelangganController::class, 'edit'])
        ->name('pelanggan.edit');

    Route::put('/pelanggan/{pelanggan}', [PelangganController::class, 'update'])
        ->name('pelanggan.update');

    Route::delete('/pelanggan/{pelanggan}', [PelangganController::class, 'destroy'])
        ->name('pelanggan.destroy');


    /*
    |--------------------------------------------------------------------------
    | BELANJA
    |--------------------------------------------------------------------------
    */

    Route::get('/belanja', function () {

        $produk = Produk::where('stok', '>', 0)
            ->orderBy('nama_produk')
            ->get();

        return Inertia::render('Pelanggan/Belanja', [
            'produk' => $produk,
        ]);

    })->name('belanja');


    /*
    |--------------------------------------------------------------------------
    | KERANJANG
    |--------------------------------------------------------------------------
    */

    Route::get('/keranjang', function () {

        return Inertia::render('Pelanggan/Keranjang');

    })->name('keranjang');

    Route::delete(
    '/riwayat-checkout/{order}',
    [CheckoutController::class, 'destroy']
)->name('checkout.destroy');


    /*
    |--------------------------------------------------------------------------
    | CHECKOUT
    |--------------------------------------------------------------------------
    */

    Route::get('/checkout', function () {

        return Inertia::render('Pelanggan/Checkout');

    })->name('checkout');


    /*
    |--------------------------------------------------------------------------
    | PROSES CHECKOUT
    |--------------------------------------------------------------------------
    */

    Route::post('/checkout', [CheckoutController::class, 'store'])
        ->name('checkout.store');


    /*
    |--------------------------------------------------------------------------
    | CHECKOUT BERHASIL
    |--------------------------------------------------------------------------
    */

    Route::get('/checkout/sukses/{order}', [CheckoutController::class, 'success'])
        ->name('checkout.success');


    /*
    |--------------------------------------------------------------------------
    | PRODUK
    |--------------------------------------------------------------------------
    */

    Route::resource('produk', ProdukController::class);

    Route::get('/checkout/history', [CheckoutController::class, 'history'])
    ->middleware(['auth'])
    ->name('checkout.history');

});


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';