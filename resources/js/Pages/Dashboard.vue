<script setup>
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

/*
|--------------------------------------------------------------------------
| Periode Grafik
|--------------------------------------------------------------------------
*/
const selectedPeriod = ref('7 Hari');

const chartData = {
    '7 Hari': {
        labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
        values: [35, 55, 42, 75, 60, 90, 110],
    },
    '30 Hari': {
        labels: ['M1', 'M2', 'M3', 'M4', 'M5', 'M6', 'M7', 'M8', 'M9', 'M10'],
        values: [40, 65, 50, 80, 72, 95, 70, 110, 90, 130],
    },
    '1 Tahun': {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        values: [120, 150, 135, 180, 160, 210, 190, 230, 250, 220, 280, 320],
    },
};

const currentChart = computed(() => chartData[selectedPeriod.value]);

/*
|--------------------------------------------------------------------------
| SVG Chart Logic
|--------------------------------------------------------------------------
*/
const chartPoints = computed(() => {
    const values = currentChart.value.values;
    const width = 700, height = 250;
    const maxValue = Math.max(...values);
    const minValue = Math.min(...values);
    const paddingTop = 25, paddingBottom = 30;

    return values.map((value, index) => {
        const x = values.length === 1 ? width / 2 : (index / (values.length - 1)) * width;
        const range = maxValue - minValue || 1;
        const normalized = (value - minValue) / range;
        const y = height - paddingBottom - normalized * (height - paddingTop - paddingBottom);
        return { x, y, value };
    });
});

const linePath = computed(() => {
    const points = chartPoints.value;
    if (!points.length) return '';
    let path = `M ${points[0].x} ${points[0].y}`;
    for (let i = 1; i < points.length; i++) {
        const prev = points[i - 1], curr = points[i];
        const cp1x = prev.x + (curr.x - prev.x) / 2;
        const cp2x = curr.x - (curr.x - prev.x) / 2;
        path += ` C ${cp1x} ${prev.y}, ${cp2x} ${curr.y}, ${curr.x} ${curr.y}`;
    }
    return path;
});

const areaPath = computed(() => {
    const points = chartPoints.value;
    if (!points.length) return '';
    return `${linePath.value} L ${points[points.length - 1].x} 250 L ${points[0].x} 250 Z`;
});

/*
|--------------------------------------------------------------------------
| Data Tabel Transaksi
|--------------------------------------------------------------------------
*/
const recentTransactions = [
    { id: 'TRX-1023', customer: 'Budi Santoso', amount: 250000, status: 'selesai', date: 'Hari ini, 14:30' },
    { id: 'TRX-1022', customer: 'Siti Rahayu', amount: 180000, status: 'proses', date: 'Hari ini, 13:15' },
    { id: 'TRX-1021', customer: 'Ahmad Fauzi', amount: 420000, status: 'selesai', date: 'Hari ini, 11:40' },
    { id: 'TRX-1020', customer: 'Dewi Lestari', amount: 95000, status: 'pending', date: 'Hari ini, 10:05' },
    { id: 'TRX-1019', customer: 'Riko Pratama', amount: 310000, status: 'selesai', date: 'Kemarin, 17:20' },
];

/*
|--------------------------------------------------------------------------
| Data Top Produk
|--------------------------------------------------------------------------
*/
const topProducts = [
    { name: 'Kopi Arabica 250g', sold: 48, max: 48, category: 'Minuman' },
    { name: 'Teh Hijau Premium', sold: 35, max: 48, category: 'Minuman' },
    { name: 'Gula Aren 500g', sold: 29, max: 48, category: 'Bahan' },
    { name: 'Madu Hutan 350ml', sold: 22, max: 48, category: 'Madu' },
    { name: 'Kurma Medjool 1kg', sold: 18, max: 48, category: 'Buah' },
];

/*
|--------------------------------------------------------------------------
| Helper: format rupiah
|--------------------------------------------------------------------------
*/
function formatRupiah(val) {
    return 'Rp ' + val.toLocaleString('id-ID');
}

/*
|--------------------------------------------------------------------------
| Helper: status badge
|--------------------------------------------------------------------------
*/
function statusStyle(status) {
    switch (status) {
        case 'selesai':
            return { bg: '#E8F5EC', color: '#3A7D4A', label: 'Selesai' };
        case 'proses':
            return { bg: '#FDF3E7', color: '#A06A2A', label: 'Proses' };
        case 'pending':
            return { bg: '#FBF0F2', color: '#8B4557', label: 'Pending' };
        default:
            return { bg: '#F3F0F0', color: '#888', label: status };
    }
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>

        <!-- HEADER -->
        <template #header>
            <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-bold" style="color: #5C2D38;">Dashboard</h2>
                    <p class="text-sm" style="color: #A07882;">Ringkasan aktivitas toko kamu hari ini</p>
                </div>
                <div class="flex items-center gap-2 text-xs" style="color: #B8A0A6;">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                    {{ new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
                </div>
            </div>
        </template>

        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

            <!-- ================================================= -->
            <!-- STAT CARDS -->
            <!-- ================================================= -->
            <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

                <!-- Pelanggan -->
                <div
                    class="group flex items-center gap-4 rounded-xl border bg-white p-4 transition-all duration-200 hover:shadow-md"
                    style="border-color: #F3E0E4; box-shadow: 0 1px 2px rgba(92,45,56,0.04);"
                >
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl"
                        style="background-color: #F9EEF0;"
                    >
                        <svg class="h-6 w-6" style="color: #8B4557;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-medium" style="color: #A07882;">Total Pelanggan</p>
                        <p class="text-xl font-bold" style="color: #5C2D38;">120</p>
                    </div>
                    <!-- Mini bar chart -->
                    <div class="hidden items-end gap-[3px] sm:flex" style="height: 32px;">
                        <div class="w-[5px] rounded-sm" style="height: 40%; background-color: #F3D0D8;"></div>
                        <div class="w-[5px] rounded-sm" style="height: 65%; background-color: #E8B4C0;"></div>
                        <div class="w-[5px] rounded-sm" style="height: 50%; background-color: #E8B4C0;"></div>
                        <div class="w-[5px] rounded-sm" style="height: 80%; background-color: #D494A2;"></div>
                        <div class="w-[5px] rounded-sm" style="height: 60%; background-color: #D494A2;"></div>
                        <div class="w-[5px] rounded-sm" style="height: 90%; background-color: #8B4557;"></div>
                        <div class="w-[5px] rounded-sm" style="height: 100%; background-color: #8B4557;"></div>
                    </div>
                </div>

                <!-- Transaksi -->
                <div
                    class="group flex items-center gap-4 rounded-xl border bg-white p-4 transition-all duration-200 hover:shadow-md"
                    style="border-color: #F3E0E4; box-shadow: 0 1px 2px rgba(92,45,56,0.04);"
                >
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl"
                        style="background-color: #EDE3F0;"
                    >
                        <svg class="h-6 w-6" style="color: #7B4F8A;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-medium" style="color: #A07882;">Total Transaksi</p>
                        <p class="text-xl font-bold" style="color: #5C2D38;">350</p>
                    </div>
                    <div class="hidden items-end gap-[3px] sm:flex" style="height: 32px;">
                        <div class="w-[5px] rounded-sm" style="height: 55%; background-color: #E0D0E8;"></div>
                        <div class="w-[5px] rounded-sm" style="height: 40%; background-color: #E0D0E8;"></div>
                        <div class="w-[5px] rounded-sm" style="height: 70%; background-color: #D0B8DC;"></div>
                        <div class="w-[5px] rounded-sm" style="height: 85%; background-color: #C0A0D0;"></div>
                        <div class="w-[5px] rounded-sm" style="height: 50%; background-color: #C0A0D0;"></div>
                        <div class="w-[5px] rounded-sm" style="height: 95%; background-color: #7B4F8A;"></div>
                        <div class="w-[5px] rounded-sm" style="height: 75%; background-color: #7B4F8A;"></div>
                    </div>
                </div>

                <!-- Pendapatan -->
                <div
                    class="group flex items-center gap-4 rounded-xl border bg-white p-4 transition-all duration-200 hover:shadow-md"
                    style="border-color: #F3E0E4; box-shadow: 0 1px 2px rgba(92,45,56,0.04);"
                >
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl"
                        style="background-color: #E5F0E8;"
                    >
                        <svg class="h-6 w-6" style="color: #4A8B5C;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-medium" style="color: #A07882;">Pendapatan</p>
                        <p class="text-xl font-bold" style="color: #5C2D38;">Rp 15,8 Jt</p>
                    </div>
                    <div class="hidden items-end gap-[3px] sm:flex" style="height: 32px;">
                        <div class="w-[5px] rounded-sm" style="height: 30%; background-color: #C8E0CC;"></div>
                        <div class="w-[5px] rounded-sm" style="height: 50%; background-color: #B0D4B8;"></div>
                        <div class="w-[5px] rounded-sm" style="height: 45%; background-color: #B0D4B8;"></div>
                        <div class="w-[5px] rounded-sm" style="height: 70%; background-color: #98C8A4;"></div>
                        <div class="w-[5px] rounded-sm" style="height: 60%; background-color: #98C8A4;"></div>
                        <div class="w-[5px] rounded-sm" style="height: 85%; background-color: #4A8B5C;"></div>
                        <div class="w-[5px] rounded-sm" style="height: 100%; background-color: #4A8B5C;"></div>
                    </div>
                </div>

                <!-- Produk -->
                <div
                    class="group flex items-center gap-4 rounded-xl border bg-white p-4 transition-all duration-200 hover:shadow-md"
                    style="border-color: #F3E0E4; box-shadow: 0 1px 2px rgba(92,45,56,0.04);"
                >
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl"
                        style="background-color: #FDF3E7;"
                    >
                        <svg class="h-6 w-6" style="color: #C4853A;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-medium" style="color: #A07882;">Total Produk</p>
                        <p class="text-xl font-bold" style="color: #5C2D38;">86</p>
                    </div>
                    <div class="hidden items-end gap-[3px] sm:flex" style="height: 32px;">
                        <div class="w-[5px] rounded-sm" style="height: 100%; background-color: #F5D8A8;"></div>
                        <div class="w-[5px] rounded-sm" style="height: 100%; background-color: #F5D8A8;"></div>
                        <div class="w-[5px] rounded-sm" style="height: 100%; background-color: #F5D8A8;"></div>
                        <div class="w-[5px] rounded-sm" style="height: 100%; background-color: #F5D8A8;"></div>
                        <div class="w-[5px] rounded-sm" style="height: 60%; background-color: #ECC490;"></div>
                        <div class="w-[5px] rounded-sm" style="height: 60%; background-color: #C4853A;"></div>
                        <div class="w-[5px] rounded-sm" style="height: 60%; background-color: #C4853A;"></div>
                    </div>
                </div>
            </div>


            <!-- ================================================= -->
            <!-- CHART SECTION -->
            <!-- ================================================= -->
            <div
                class="mb-6 rounded-xl border bg-white p-5"
                style="border-color: #F3E0E4; box-shadow: 0 1px 2px rgba(92,45,56,0.04);"
            >
                <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-base font-bold" style="color: #5C2D38;">Grafik Penjualan</h3>
                        <p class="text-xs" style="color: #A07882;">Tren penjualan berdasarkan periode</p>
                    </div>

                    <!-- Pill Tabs -->
                    <div
                        class="inline-flex rounded-lg p-0.5"
                        style="background-color: #F8F0F2;"
                    >
                        <button
                            v-for="period in ['7 Hari', '30 Hari', '1 Tahun']"
                            :key="period"
                            @click="selectedPeriod = period"
                            class="rounded-md px-3.5 py-1.5 text-xs font-semibold transition-all duration-200"
                            :style="
                                selectedPeriod === period
                                    ? 'background-color: white; color: #8B4557; box-shadow: 0 1px 3px rgba(92,45,56,0.1);'
                                    : 'color: #A07882;'
                            "
                        >
                            {{ period }}
                        </button>
                    </div>
                </div>

                <!-- SVG Chart -->
                <div class="relative" style="height: 220px;">
                    <div class="absolute inset-0 flex flex-col justify-between">
                        <div class="border-t" style="border-color: #FAEEF0;"></div>
                        <div class="border-t" style="border-color: #FAEEF0;"></div>
                        <div class="border-t" style="border-color: #FAEEF0;"></div>
                        <div class="border-t" style="border-color: #FAEEF0;"></div>
                        <div class="border-t" style="border-color: #FAEEF0;"></div>
                    </div>

                    <svg
                        viewBox="0 0 700 250"
                        class="absolute inset-0 h-full w-full overflow-visible"
                        preserveAspectRatio="none"
                    >
                        <defs>
                            <linearGradient id="chartGrad" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#8B4557" stop-opacity="0.18" />
                                <stop offset="50%" stop-color="#B06878" stop-opacity="0.06" />
                                <stop offset="100%" stop-color="#D4A0AB" stop-opacity="0" />
                            </linearGradient>
                            <filter id="glow" x="-20%" y="-20%" width="140%" height="140%">
                                <feGaussianBlur stdDeviation="2.5" result="blur" />
                                <feMerge>
                                    <feMergeNode in="blur" />
                                    <feMergeNode in="SourceGraphic" />
                                </feMerge>
                            </filter>
                        </defs>

                        <path :d="areaPath" fill="url(#chartGrad)" class="transition-all duration-700 ease-out" />
                        <path
                            :d="linePath"
                            fill="none"
                            stroke="#8B4557"
                            stroke-width="2.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            filter="url(#glow)"
                            class="transition-all duration-700 ease-out"
                        />
                        <g v-for="point in chartPoints" :key="`${selectedPeriod}-${point.x}`">
                            <circle :cx="point.x" :cy="point.y" r="7" fill="#8B4557" opacity="0.08" class="transition-all duration-700" />
                            <circle :cx="point.x" :cy="point.y" r="4" fill="white" stroke="#8B4557" stroke-width="2" class="transition-all duration-700" />
                        </g>
                    </svg>
                </div>

                <!-- Labels -->
                <div class="mt-3 flex justify-between px-0.5 text-[11px] font-medium" style="color: #C4A0A8;">
                    <span v-for="label in currentChart.labels" :key="label">{{ label }}</span>
                </div>
            </div>


            <!-- ================================================= -->
            <!-- TABLE + SIDEBAR -->
            <!-- ================================================= -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                <!-- TRANSAKSI TERBARU (Table) -->
                <div
                    class="rounded-xl border bg-white lg:col-span-2"
                    style="border-color: #F3E0E4; box-shadow: 0 1px 2px rgba(92,45,56,0.04);"
                >
                    <div class="flex items-center justify-between border-b px-5 py-4" style="border-color: #F8F0F2;">
                        <div>
                            <h3 class="text-base font-bold" style="color: #5C2D38;">Transaksi Terbaru</h3>
                            <p class="text-xs" style="color: #A07882;">5 transaksi terakhir</p>
                        </div>
                        <Link
                            :href="route('pelanggan.index')"
                            class="text-xs font-semibold transition-colors"
                            style="color: #8B4557;"
                            onmouseenter="this.style.color='#6B3344'"
                            onmouseleave="this.style.color='#8B4557'"
                        >
                            Lihat Semua →
                        </Link>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr style="background-color: #FDF8F9;">
                                    <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold" style="color: #A07882;">Invoice</th>
                                    <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold" style="color: #A07882;">Pelanggan</th>
                                    <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold text-right" style="color: #A07882;">Nominal</th>
                                    <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold text-center" style="color: #A07882;">Status</th>
                                    <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold" style="color: #A07882;">Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(tx, idx) in recentTransactions"
                                    :key="tx.id"
                                    class="transition-colors"
                                    :style="idx < recentTransactions.length - 1 ? 'border-bottom: 1px solid #F8F0F2;' : ''"
                                    onmouseenter="this.style.backgroundColor='#FDF8F9'"
                                    onmouseleave="this.style.backgroundColor='transparent'"
                                >
                                    <td class="whitespace-nowrap px-5 py-3.5">
                                        <span class="font-mono text-xs font-semibold" style="color: #5C2D38;">{{ tx.id }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-3.5">
                                        <div class="flex items-center gap-2.5">
                                            <div
                                                class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg text-[10px] font-bold text-white"
                                                :style="`background: linear-gradient(135deg, ${idx % 2 === 0 ? '#8B4557' : '#7B4F8A'}, ${idx % 2 === 0 ? '#B06878' : '#9B6FAA'});`"
                                            >
                                                {{ tx.customer.charAt(0) }}
                                            </div>
                                            <span class="text-sm" style="color: #5C2D38;">{{ tx.customer }}</span>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-3.5 text-right">
                                        <span class="text-sm font-semibold" style="color: #5C2D38;">{{ formatRupiah(tx.amount) }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-3.5 text-center">
                                        <span
                                            class="inline-flex rounded-full px-2.5 py-0.5 text-[11px] font-semibold"
                                            :style="`background-color: ${statusStyle(tx.status).bg}; color: ${statusStyle(tx.status).color};`"
                                        >
                                            {{ statusStyle(tx.status).label }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-3.5">
                                        <span class="text-xs" style="color: #B8A0A6;">{{ tx.date }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- RIGHT SIDEBAR -->
                <div class="flex flex-col gap-6">

                    <!-- Top Produk -->
                    <div
                        class="rounded-xl border bg-white"
                        style="border-color: #F3E0E4; box-shadow: 0 1px 2px rgba(92,45,56,0.04);"
                    >
                        <div class="border-b px-5 py-4" style="border-color: #F8F0F2;">
                            <h3 class="text-base font-bold" style="color: #5C2D38;">Produk Terlaris</h3>
                            <p class="text-xs" style="color: #A07882;">Bulan ini</p>
                        </div>
                        <div class="divide-y" style="--tw-divide-opacity: 1; border-color: #F8F0F2;">
                            <div
                                v-for="(product, idx) in topProducts"
                                :key="product.name"
                                class="flex items-center gap-3 px-5 py-3 transition-colors"
                                style="border-bottom: 1px solid #F8F0F2;"
                                onmouseenter="this.style.backgroundColor='#FDF8F9'"
                                onmouseleave="this.style.backgroundColor='transparent'"
                            >
                                <!-- Rank -->
                                <span
                                    class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md text-[11px] font-bold"
                                    :style="
                                        idx < 3
                                            ? 'background: linear-gradient(135deg, #8B4557, #B06878); color: white;'
                                            : 'background-color: #F3E0E4; color: #A07882;'
                                    "
                                >
                                    {{ idx + 1 }}
                                </span>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium" style="color: #5C2D38;">{{ product.name }}</p>
                                    <div class="mt-1.5 flex items-center gap-2">
                                        <div class="h-1.5 flex-1 overflow-hidden rounded-full" style="background-color: #F3E0E4;">
                                            <div
                                                class="h-full rounded-full transition-all duration-500"
                                                :style="`width: ${(product.sold / product.max) * 100}%; background: linear-gradient(90deg, #8B4557, #B06878);`"
                                            ></div>
                                        </div>
                                        <span class="text-[11px] font-semibold shrink-0" style="color: #8B4557;">{{ product.sold }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan Hari Ini -->
                    <div
                        class="rounded-xl border bg-white p-5"
                        style="border-color: #F3E0E4; box-shadow: 0 1px 2px rgba(92,45,56,0.04);"
                    >
                        <h3 class="mb-4 text-base font-bold" style="color: #5C2D38;">Hari Ini</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm" style="color: #A07882;">Penjualan</span>
                                <span class="text-sm font-bold" style="color: #5C2D38;">Rp 1,2 Jt</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm" style="color: #A07882;">Transaksi</span>
                                <span class="text-sm font-bold" style="color: #5C2D38;">12</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm" style="color: #A07882;">Pelanggan Baru</span>
                                <span class="text-sm font-bold" style="color: #5C2D38;">3</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm" style="color: #A07882;">Rata-rata / Trx</span>
                                <span class="text-sm font-bold" style="color: #5C2D38;">Rp 100rb</span>
                            </div>
                        </div>

                        <div class="mt-4 pt-4" style="border-top: 1px solid #F8F0F2;">
                            <div class="flex gap-2">
                                <Link
                                    :href="route('pelanggan.index')"
                                    class="flex-1 rounded-lg py-2.5 text-center text-xs font-semibold text-white transition-all duration-200 hover:opacity-90"
                                    style="background: linear-gradient(135deg, #8B4557, #6B3344);"
                                >
                                    Laporan
                                </Link>
                                <Link
                                    :href="route('produk.index')"
                                    class="flex-1 rounded-lg border py-2.5 text-center text-xs font-semibold transition-all duration-200"
                                    style="border-color: #F3E0E4; color: #8B4557; background-color: #FBF0F2;"
                                    onmouseenter="this.style.backgroundColor='#F5E0E5'"
                                    onmouseleave="this.style.backgroundColor='#FBF0F2'"
                                >
                                    Produk
                                </Link>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>