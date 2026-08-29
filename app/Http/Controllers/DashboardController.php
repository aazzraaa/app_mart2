<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | TANGGAL
        |--------------------------------------------------------------------------
        */

        $today = Carbon::today();


        /*
        |--------------------------------------------------------------------------
        | STATISTIK UTAMA
        |--------------------------------------------------------------------------
        */

        // Total pelanggan
        $totalPelanggan = DB::table('tbl_pelanggan')->count();

        // Total seluruh transaksi
        $totalTransaksi = DB::table('orders')->count();

        // Total produk
        $totalProduk = DB::table('produks')->count();

        // Total pendapatan
        // Hanya transaksi yang sudah selesai
        $totalPendapatan = DB::table('orders')
            ->where('status', 'selesai')
            ->sum('total_harga');


        /*
        |--------------------------------------------------------------------------
        | GRAFIK 7 HARI
        |--------------------------------------------------------------------------
        */

        $sevenDaysLabels = [];
        $sevenDaysValues = [];

        for ($i = 6; $i >= 0; $i--) {

            $date = Carbon::today()->subDays($i);

            $sevenDaysLabels[] = $date->translatedFormat('D');

            $total = DB::table('orders')
                ->where('status', 'selesai')
                ->whereDate('created_at', $date->toDateString())
                ->sum('total_harga');

            $sevenDaysValues[] = (float) $total;
        }


        /*
        |--------------------------------------------------------------------------
        | GRAFIK 30 HARI
        |--------------------------------------------------------------------------
        |
        | Dibagi menjadi 10 periode.
        | Setiap periode = 3 hari.
        |
        */

        $thirtyDaysLabels = [];
        $thirtyDaysValues = [];

        for ($i = 0; $i < 10; $i++) {

            $startDate = Carbon::today()->subDays(
                29 - ($i * 3)
            );

            $endDate = $startDate->copy()->addDays(2);

            // Jangan melewati hari ini
            if ($endDate->greaterThan(Carbon::today())) {
                $endDate = Carbon::today();
            }

            $thirtyDaysLabels[] = 'M' . ($i + 1);

            $total = DB::table('orders')
                ->where('status', 'selesai')
                ->whereBetween('created_at', [
                    $startDate->startOfDay(),
                    $endDate->endOfDay(),
                ])
                ->sum('total_harga');

            $thirtyDaysValues[] = (float) $total;
        }


        /*
        |--------------------------------------------------------------------------
        | GRAFIK 1 TAHUN
        |--------------------------------------------------------------------------
        */

        $yearLabels = [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'Mei',
            'Jun',
            'Jul',
            'Agu',
            'Sep',
            'Okt',
            'Nov',
            'Des',
        ];

        $yearValues = [];

        for ($month = 1; $month <= 12; $month++) {

            $total = DB::table('orders')
                ->where('status', 'selesai')
                ->whereYear('created_at', Carbon::today()->year)
                ->whereMonth('created_at', $month)
                ->sum('total_harga');

            $yearValues[] = (float) $total;
        }


        /*
        |--------------------------------------------------------------------------
        | TRANSAKSI TERBARU
        |--------------------------------------------------------------------------
        */

        $recentTransactions = DB::table('orders')
            ->select(
                'id',
                'kode_order',
                'nama_pelanggan',
                'total_harga',
                'status',
                'created_at'
            )
            ->latest('created_at')
            ->limit(5)
            ->get()
            ->map(function ($order) {

                return [
                    'id' => $order->kode_order,

                    'customer' => $order->nama_pelanggan,

                    'amount' => (float) $order->total_harga,

                    'status' => strtolower($order->status),

                    'date' => Carbon::parse($order->created_at)
                        ->locale('id')
                        ->translatedFormat('d M Y, H:i'),
                ];
            });


        /*
        |--------------------------------------------------------------------------
        | PRODUK TERLARIS
        |--------------------------------------------------------------------------
        */

        $topProducts = DB::table('order_items')
            ->leftJoin(
                'produks',
                'produks.id',
                '=',
                'order_items.produk_id'
            )
            ->select(
                'order_items.produk_id',
                'order_items.nama_produk',
                'produks.kategori',
                DB::raw('SUM(order_items.jumlah) as sold')
            )
            ->groupBy(
                'order_items.produk_id',
                'order_items.nama_produk',
                'produks.kategori'
            )
            ->orderByDesc('sold')
            ->limit(5)
            ->get();

        $maxSold = $topProducts->max('sold') ?: 1;

        $topProducts = $topProducts->map(function ($product) use ($maxSold) {

            return [
                'name' => $product->nama_produk,

                'sold' => (int) $product->sold,

                'max' => (int) $maxSold,

                'category' => $product->kategori ?? 'Tanpa Kategori',
            ];
        });


        /*
        |--------------------------------------------------------------------------
        | RINGKASAN HARI INI
        |--------------------------------------------------------------------------
        */

        $todaySales = DB::table('orders')
            ->where('status', 'selesai')
            ->whereDate('created_at', $today->toDateString())
            ->sum('total_harga');


        $todayTransactions = DB::table('orders')
            ->where('status', 'selesai')
            ->whereDate('created_at', $today->toDateString())
            ->count();


        $newCustomersToday = DB::table('tbl_pelanggan')
            ->whereDate('created_at', $today->toDateString())
            ->count();


        $averageTransaction = $todayTransactions > 0
            ? $todaySales / $todayTransactions
            : 0;


        /*
        |--------------------------------------------------------------------------
        | KIRIM KE VUE
        |--------------------------------------------------------------------------
        */

        return Inertia::render('Dashboard', [

            /*
            | Statistik utama
            */

            'stats' => [
                'totalPelanggan' => $totalPelanggan,

                'totalTransaksi' => $totalTransaksi,

                'totalPendapatan' => (float) $totalPendapatan,

                'totalProduk' => $totalProduk,
            ],


            /*
            | Grafik
            */

            'chartData' => [

                '7 Hari' => [
                    'labels' => $sevenDaysLabels,
                    'values' => $sevenDaysValues,
                ],

                '30 Hari' => [
                    'labels' => $thirtyDaysLabels,
                    'values' => $thirtyDaysValues,
                ],

                '1 Tahun' => [
                    'labels' => $yearLabels,
                    'values' => $yearValues,
                ],
            ],


            /*
            | Transaksi terbaru
            */

            'recentTransactions' => $recentTransactions,


            /*
            | Produk terlaris
            */

            'topProducts' => $topProducts,


            /*
            | Ringkasan hari ini
            */

            'todaySummary' => [

                'sales' => (float) $todaySales,

                'transactions' => $todayTransactions,

                'newCustomers' => $newCustomersToday,

                'averageTransaction' => (float) $averageTransaction,
            ],
        ]);
    }
}