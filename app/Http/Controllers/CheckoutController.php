<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Produk;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PROSES CHECKOUT
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelanggan' => [
                'required',
                'string',
                'max:255',
            ],

            'no_hp' => [
                'nullable',
                'string',
                'max:20',
            ],

            'kota' => [
                'nullable',
                'string',
                'max:100',
            ],

            'alamat' => [
                'nullable',
                'string',
            ],

            'metode_pembayaran' => [
                'required',
                'in:cash,transfer,qris',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.produk_id' => [
                'required',
                'integer',
                'exists:produks,id',
            ],

            'items.*.jumlah' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | CEK PRODUK DAN STOK
            |--------------------------------------------------------------------------
            */

            $totalHarga = 0;

            $itemsData = [];

            foreach ($validated['items'] as $item) {

                $produk = Produk::lockForUpdate()
                    ->findOrFail($item['produk_id']);

                if ($produk->stok < $item['jumlah']) {

                    throw new \Exception(
                        'Stok produk "' .
                        $produk->nama_produk .
                        '" tidak mencukupi. Stok tersisa: ' .
                        $produk->stok
                    );
                }

                $subtotal =
                    $produk->harga *
                    $item['jumlah'];

                $totalHarga += $subtotal;

                $itemsData[] = [
                    'produk' => $produk,
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $subtotal,
                ];
            }


            /*
            |--------------------------------------------------------------------------
            | BUAT KODE ORDER
            |--------------------------------------------------------------------------
            */

            $kodeOrder =
                'ORD-' .
                now()->format('YmdHis') .
                '-' .
                random_int(100, 999);


            /*
            |--------------------------------------------------------------------------
            | SIMPAN / CARI PELANGGAN
            |--------------------------------------------------------------------------
            |
            | Pelanggan dicari berdasarkan:
            | nama_pelanggan + no_hp
            |
            */

            $pelanggan = Pelanggan::where(
                'nama_pelanggan',
                $validated['nama_pelanggan']
            )
            ->where(
                'no_hp',
                $validated['no_hp'] ?? null
            )
            ->first();


            /*
            |--------------------------------------------------------------------------
            | JIKA PELANGGAN BELUM ADA
            |--------------------------------------------------------------------------
            */

            if (!$pelanggan) {

                $pelanggan = Pelanggan::create([
                    'nama_pelanggan' =>
                        $validated['nama_pelanggan'],

                    'no_hp' =>
                        $validated['no_hp'] ?? null,

                    'kota' =>
                        $validated['kota'] ?? null,

                    'alamat' =>
                        $validated['alamat'] ?? null,
                ]);

            } else {

                /*
                |--------------------------------------------------------------------------
                | JIKA SUDAH ADA, UPDATE DATA TERBARU
                |--------------------------------------------------------------------------
                */

                $pelanggan->update([
                    'kota' =>
                        $validated['kota'] ?? $pelanggan->kota,

                    'alamat' =>
                        $validated['alamat'] ?? $pelanggan->alamat,
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | BUAT ORDER
            |--------------------------------------------------------------------------
            */

            $order = Order::create([

                'kode_order' =>
                    $kodeOrder,

                'nama_pelanggan' =>
                    $validated['nama_pelanggan'],

                'no_hp' =>
                    $validated['no_hp'] ?? null,

                'alamat' =>
                    $validated['alamat'] ?? null,

                'total_harga' =>
                    $totalHarga,

                'metode_pembayaran' =>
                    $validated['metode_pembayaran'],

                'status' =>
                    'pending',
            ]);


            /*
            |--------------------------------------------------------------------------
            | SIMPAN ITEM ORDER + KURANGI STOK
            |--------------------------------------------------------------------------
            */

            foreach ($itemsData as $item) {

                $produk = $item['produk'];

                OrderItem::create([

                    'order_id' =>
                        $order->id,

                    'produk_id' =>
                        $produk->id,

                    'nama_produk' =>
                        $produk->nama_produk,

                    'harga' =>
                        $produk->harga,

                    'jumlah' =>
                        $item['jumlah'],

                    'subtotal' =>
                        $item['subtotal'],
                ]);


                /*
                |----------------------------------------------------------------------
                | KURANGI STOK
                |----------------------------------------------------------------------
                */

                $produk->decrement(
                    'stok',
                    $item['jumlah']
                );
            }


            /*
            |--------------------------------------------------------------------------
            | COMMIT
            |--------------------------------------------------------------------------
            */

            DB::commit();


            /*
            |--------------------------------------------------------------------------
            | REDIRECT KE HALAMAN SUKSES
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route(
                    'checkout.success',
                    $order->id
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->withErrors([
                    'checkout' =>
                        $e->getMessage(),
                ])
                ->withInput();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | CHECKOUT BERHASIL
    |--------------------------------------------------------------------------
    */

    public function success(Order $order)
    {
        $order->load('items');

        return inertia(
            'Pelanggan/Sukses',
            [
                'order' => $order,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RIWAYAT CHECKOUT
    |--------------------------------------------------------------------------
    */

    public function history()
    {
        $orders = Order::with('items')
            ->latest()
            ->get();

        return inertia(
            'Pelanggan/RiwayatCheckout',
            [
                'orders' => $orders,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS RIWAYAT CHECKOUT
    |--------------------------------------------------------------------------
    */

    public function destroy(Order $order)
    {
        DB::beginTransaction();

        try {

            /*
            |----------------------------------------------------------------------
            | Hapus item order terlebih dahulu
            |----------------------------------------------------------------------
            */

            $order->items()->delete();


            /*
            |----------------------------------------------------------------------
            | Hapus order
            |----------------------------------------------------------------------
            */

            $order->delete();


            /*
            |----------------------------------------------------------------------
            | Commit
            |----------------------------------------------------------------------
            */

            DB::commit();

            return back()->with(
                'success',
                'Riwayat checkout berhasil dihapus.'
            );

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->withErrors([
                'checkout' =>
                    $e->getMessage(),
            ]);
        }
    }
}