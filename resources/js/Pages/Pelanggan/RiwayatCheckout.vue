```vue
<script setup>
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';

const props = defineProps({
    orders: {
        type: Array,
        default: () => [],
    },
});

/*
|--------------------------------------------------------------------------
| STATE
|--------------------------------------------------------------------------
*/

const deleteConfirmId = ref(null);

// Filter aktif
const activePeriod = ref('all');

// Periode yang sedang dilihat
const currentDate = ref(new Date());


/*
|--------------------------------------------------------------------------
| MENU PERIODE
|--------------------------------------------------------------------------
*/

const periods = [
    {
        key: 'all',
        label: 'Semua',
    },
    {
        key: 'week',
        label: 'Mingguan',
    },
    {
        key: 'month',
        label: 'Bulanan',
    },
    {
        key: 'year',
        label: 'Tahunan',
    },
];


/*
|--------------------------------------------------------------------------
| FORMAT TANGGAL
|--------------------------------------------------------------------------
*/

function parseOrderDate(date) {
    if (!date) {
        return null;
    }

    const parsed = new Date(date);

    if (isNaN(parsed.getTime())) {
        return null;
    }

    return parsed;
}


/*
|--------------------------------------------------------------------------
| CEK APAKAH ORDER MASUK PERIODE
|--------------------------------------------------------------------------
*/

function isInSelectedPeriod(order) {
    if (activePeriod.value === 'all') {
        return true;
    }

    const orderDate = parseOrderDate(order.created_at);

    if (!orderDate) {
        return false;
    }

    const selectedDate = currentDate.value;

    /*
    |--------------------------------------------------------------------------
    | MINGGUAN
    |--------------------------------------------------------------------------
    */

    if (activePeriod.value === 'week') {

        const startOfWeek = new Date(selectedDate);

        const day = startOfWeek.getDay();

        // Senin = awal minggu
        const difference = day === 0 ? -6 : 1 - day;

        startOfWeek.setDate(
            startOfWeek.getDate() + difference
        );

        startOfWeek.setHours(0, 0, 0, 0);


        const endOfWeek = new Date(startOfWeek);

        endOfWeek.setDate(
            endOfWeek.getDate() + 7
        );

        return (
            orderDate >= startOfWeek &&
            orderDate < endOfWeek
        );
    }


    /*
    |--------------------------------------------------------------------------
    | BULANAN
    |--------------------------------------------------------------------------
    */

    if (activePeriod.value === 'month') {

        const startOfMonth = new Date(
            selectedDate.getFullYear(),
            selectedDate.getMonth(),
            1
        );

        const endOfMonth = new Date(
            selectedDate.getFullYear(),
            selectedDate.getMonth() + 1,
            1
        );

        return (
            orderDate >= startOfMonth &&
            orderDate < endOfMonth
        );
    }


    /*
    |--------------------------------------------------------------------------
    | TAHUNAN
    |--------------------------------------------------------------------------
    */

    if (activePeriod.value === 'year') {

        const startOfYear = new Date(
            selectedDate.getFullYear(),
            0,
            1
        );

        const endOfYear = new Date(
            selectedDate.getFullYear() + 1,
            0,
            1
        );

        return (
            orderDate >= startOfYear &&
            orderDate < endOfYear
        );
    }


    return true;
}


/*
|--------------------------------------------------------------------------
| FILTER ORDER
|--------------------------------------------------------------------------
*/

const filteredOrders = computed(() => {

    return props.orders.filter(order =>
        isInSelectedPeriod(order)
    );

});


/*
|--------------------------------------------------------------------------
| LABEL PERIODE
|--------------------------------------------------------------------------
*/

const periodLabel = computed(() => {

    const date = currentDate.value;

    if (activePeriod.value === 'all') {
        return 'Seluruh transaksi';
    }


    if (activePeriod.value === 'week') {

        const start = new Date(date);

        const day = start.getDay();

        const difference = day === 0 ? -6 : 1 - day;

        start.setDate(
            start.getDate() + difference
        );

        start.setHours(0, 0, 0, 0);


        const end = new Date(start);

        end.setDate(
            end.getDate() + 6
        );


        return `${start.toLocaleDateString(
            'id-ID',
            {
                day: 'numeric',
                month: 'short',
                year: 'numeric',
            }
        )} - ${end.toLocaleDateString(
            'id-ID',
            {
                day: 'numeric',
                month: 'short',
                year: 'numeric',
            }
        )}`;
    }


    if (activePeriod.value === 'month') {

        return date.toLocaleDateString(
            'id-ID',
            {
                month: 'long',
                year: 'numeric',
            }
        );
    }


    if (activePeriod.value === 'year') {

        return date.getFullYear();
    }


    return '';
});


/*
|--------------------------------------------------------------------------
| GANTI PERIODE
|--------------------------------------------------------------------------
*/

function changePeriod(period) {

    activePeriod.value = period;

    // Set kembali ke tanggal hari ini
    currentDate.value = new Date();

}


/*
|--------------------------------------------------------------------------
| PERIODE SEBELUMNYA
|--------------------------------------------------------------------------
*/

function previousPeriod() {

    const date = new Date(
        currentDate.value
    );


    if (activePeriod.value === 'week') {

        date.setDate(
            date.getDate() - 7
        );

    } else if (activePeriod.value === 'month') {

        date.setMonth(
            date.getMonth() - 1
        );

    } else if (activePeriod.value === 'year') {

        date.setFullYear(
            date.getFullYear() - 1
        );

    }


    currentDate.value = date;
}


/*
|--------------------------------------------------------------------------
| PERIODE BERIKUTNYA
|--------------------------------------------------------------------------
*/

function nextPeriod() {

    const date = new Date(
        currentDate.value
    );


    if (activePeriod.value === 'week') {

        date.setDate(
            date.getDate() + 7
        );

    } else if (activePeriod.value === 'month') {

        date.setMonth(
            date.getMonth() + 1
        );

    } else if (activePeriod.value === 'year') {

        date.setFullYear(
            date.getFullYear() + 1
        );

    }


    currentDate.value = date;
}


/*
|--------------------------------------------------------------------------
| KEMBALI KE PERIODE SEKARANG
|--------------------------------------------------------------------------
*/

function todayPeriod() {

    currentDate.value = new Date();

}


/*
|--------------------------------------------------------------------------
| KONFIRMASI HAPUS
|--------------------------------------------------------------------------
*/

function requestDelete(id) {

    deleteConfirmId.value = id;

}


function cancelDelete() {

    deleteConfirmId.value = null;

}


function confirmDelete(id) {

    router.delete(
        route('checkout.destroy', id),
        {
            preserveScroll: true,

            onSuccess: () => {

                deleteConfirmId.value = null;

            },
        }
    );

}


/*
|--------------------------------------------------------------------------
| CETAK STRUK
|--------------------------------------------------------------------------
*/

function cetakStruk(order) {

    const items = order.items || [];


    const itemRows = items.map(item => `

        <tr>

            <td class="produk">
                ${escapeHtml(item.nama_produk)}
            </td>

            <td class="qty">
                ${item.jumlah}
            </td>

            <td class="harga">
                Rp ${Number(
                    item.subtotal
                ).toLocaleString('id-ID')}
            </td>

        </tr>

    `).join('');


    const struk = `

        <!DOCTYPE html>

        <html lang="id">

        <head>

            <meta charset="UTF-8">

            <title>
                Struk ${escapeHtml(order.kode_order)}
            </title>

            <style>

                * {
                    box-sizing: border-box;
                }

                html,
                body {
                    margin: 0;
                    padding: 0;
                    background: white;
                }

                body {
                    font-family: Arial, Helvetica, sans-serif;
                    color: #222;
                    font-size: 13px;
                }

                .struk {
                    width: 80mm;
                    max-width: 80mm;
                    margin: 0 auto;
                    padding: 5mm;
                }

                .header {
                    text-align: center;
                    margin-bottom: 12px;
                }

                .header h1 {
                    margin: 0;
                    font-size: 22px;
                    font-weight: 700;
                    letter-spacing: 0.5px;
                }

                .header p {
                    margin: 4px 0 0;
                    font-size: 12px;
                    color: #666;
                }

                .line {
                    border-top: 1px dashed #777;
                    margin: 12px 0;
                }

                .info {
                    font-size: 13px;
                    line-height: 1.7;
                }

                .info-row {
                    display: flex;
                    align-items: flex-start;
                    margin-bottom: 2px;
                }

                .info-label {
                    width: 90px;
                    min-width: 90px;
                    font-weight: 600;
                }

                .info-value {
                    flex: 1;
                    word-break: break-word;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    table-layout: fixed;
                    font-size: 12px;
                }

                th {
                    padding-bottom: 7px;
                    border-bottom: 1px solid #bbb;
                    font-weight: 700;
                }

                th:first-child {
                    text-align: left;
                    width: 45%;
                }

                th:nth-child(2) {
                    text-align: center;
                    width: 15%;
                }

                th:nth-child(3) {
                    text-align: right;
                    width: 40%;
                }

                td {
                    padding: 7px 0;
                    vertical-align: top;
                }

                .produk {
                    text-align: left;
                    padding-right: 5px;
                    word-break: break-word;
                }

                .qty {
                    text-align: center;
                }

                .harga {
                    text-align: right;
                    white-space: nowrap;
                }

                .total {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    font-size: 17px;
                    font-weight: 700;
                    margin-top: 5px;
                }

                .total-price {
                    text-align: right;
                }

                .footer {
                    text-align: center;
                    margin-top: 20px;
                    font-size: 11px;
                    line-height: 1.5;
                    color: #777;
                }

                .footer p {
                    margin: 3px 0;
                }

                @page {
                    size: 80mm auto;
                    margin: 0;
                }

                @media print {

                    html,
                    body {
                        width: 80mm;
                        margin: 0;
                        padding: 0;
                    }

                    .struk {
                        width: 80mm;
                        max-width: 80mm;
                        margin: 0;
                        padding: 5mm;
                    }

                }

            </style>

        </head>

        <body>

            <div class="struk">

                <div class="header">

                    <h1>
                        SISTEM TOKO
                    </h1>

                    <p>
                        Struk Pembelian
                    </p>

                </div>


                <div class="line"></div>


                <div class="info">

                    <div class="info-row">

                        <div class="info-label">
                            Kode
                        </div>

                        <div class="info-value">
                            ${escapeHtml(
                                order.kode_order
                            )}
                        </div>

                    </div>


                    <div class="info-row">

                        <div class="info-label">
                            Pelanggan
                        </div>

                        <div class="info-value">
                            ${escapeHtml(
                                order.nama_pelanggan || '-'
                            )}
                        </div>

                    </div>


                    <div class="info-row">

                        <div class="info-label">
                            No. HP
                        </div>

                        <div class="info-value">
                            ${escapeHtml(
                                order.no_hp || '-'
                            )}
                        </div>

                    </div>


                    <div class="info-row">

                        <div class="info-label">
                            Alamat
                        </div>

                        <div class="info-value">
                            ${escapeHtml(
                                order.alamat || '-'
                            )}
                        </div>

                    </div>


                    <div class="info-row">

                        <div class="info-label">
                            Pembayaran
                        </div>

                        <div class="info-value">
                            ${escapeHtml(
                                order.metode_pembayaran || '-'
                            )}
                        </div>

                    </div>


                    <div class="info-row">

                        <div class="info-label">
                            Status
                        </div>

                        <div class="info-value">
                            ${escapeHtml(
                                order.status || '-'
                            )}
                        </div>

                    </div>

                </div>


                <div class="line"></div>


                <table>

                    <thead>

                        <tr>

                            <th>
                                Produk
                            </th>

                            <th>
                                Qty
                            </th>

                            <th>
                                Subtotal
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        ${itemRows}

                    </tbody>

                </table>


                <div class="line"></div>


                <div class="total">

                    <span>
                        TOTAL
                    </span>

                    <span class="total-price">

                        Rp ${Number(
                            order.total_harga
                        ).toLocaleString('id-ID')}

                    </span>

                </div>


                <div class="line"></div>


                <div class="footer">

                    <p>
                        Terima kasih telah berbelanja.
                    </p>

                    <p>
                        Struk ini dicetak secara otomatis.
                    </p>

                </div>

            </div>


            <script>

                window.onload = function() {

                    setTimeout(function() {

                        window.print();

                    }, 300);

                };

            <\/script>

        </body>

        </html>

    `;


    const printWindow = window.open(
        '',
        '_blank',
        'width=500,height=800'
    );


    if (!printWindow) {

        alert(
            'Popup diblokir browser. Silakan izinkan popup untuk mencetak struk.'
        );

        return;
    }


    printWindow.document.open();

    printWindow.document.write(struk);

    printWindow.document.close();

}


/*
|--------------------------------------------------------------------------
| ESCAPE HTML
|--------------------------------------------------------------------------
*/

function escapeHtml(value) {

    return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');

}

</script>


<template>

    <Head title="Riwayat Checkout" />


    <AuthenticatedLayout>

        <!-- =========================================================
             HEADER
        ========================================================== -->

        <template #header>

            <div>

                <div
                    class="flex items-center gap-2 text-xs"
                    style="color:#B8A0A6;"
                >

                    <span>
                        Dashboard
                    </span>


                    <svg
                        class="h-3 w-3"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2.5"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M8.25 4.5l7.5 7.5-7.5 7.5"
                        />

                    </svg>


                    <span style="color:#8B4557;">
                        Riwayat Checkout
                    </span>

                </div>


                <h2
                    class="mt-1 text-xl font-bold"
                    style="color:#5C2D38;"
                >

                    Riwayat Checkout

                </h2>


                <p
                    class="text-sm"
                    style="color:#A07882;"
                >

                    Melihat seluruh transaksi berdasarkan periode

                </p>

            </div>

        </template>


        <!-- =========================================================
             CONTENT
        ========================================================== -->

        <div
            class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8"
        >


            <!-- =====================================================
                 SUCCESS MESSAGE
            ====================================================== -->

            <div
                v-if="$page.props.flash?.success"
                class="mb-4 flex items-center gap-3 rounded-xl border px-4 py-3"
                style="
                    border-color:#D0E8D6;
                    background-color:#F4FBF5;
                    color:#3A7D4A;
                "
            >

                <div
                    class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full"
                    style="background-color:#E8F5EC;"
                >

                    <svg
                        class="h-4 w-4"
                        style="color:#4A8B5C;"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2.5"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M4.5 12.75l6 6 9-13.5"
                        />

                    </svg>

                </div>


                <p class="text-sm font-medium">

                    {{ $page.props.flash.success }}

                </p>

            </div>


            <!-- =====================================================
                 CARD
            ====================================================== -->

            <div
                class="overflow-hidden rounded-xl border bg-white"
                style="
                    border-color:#F3E0E4;
                    box-shadow:0 1px 2px rgba(92,45,56,0.04);
                "
            >


                <!-- =================================================
                     TITLE + PERIOD MENU
                ================================================== -->

                <div
                    class="border-b px-5 py-4"
                    style="border-color:#F8F0F2;"
                >

                    <div
                        class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between"
                    >


                        <!-- TITLE -->

                        <div>

                            <h3
                                class="text-base font-bold"
                                style="color:#5C2D38;"
                            >

                                Daftar Transaksi

                            </h3>


                            <p
                                class="mt-0.5 text-xs"
                                style="color:#B8A0A6;"
                            >

                                {{ filteredOrders.length }} transaksi
                                <span v-if="activePeriod !== 'all'">
                                    • {{ periodLabel }}
                                </span>

                            </p>

                        </div>


                        <!-- MENU PERIODE -->

                        <div
                            class="flex flex-wrap items-center gap-1 rounded-xl p-1"
                            style="background-color:#FDF5F7;"
                        >

                            <button
                                v-for="period in periods"
                                :key="period.key"
                                @click="changePeriod(period.key)"
                                type="button"
                                class="rounded-lg px-4 py-2 text-xs font-semibold transition-all duration-150"
                                :style="
                                    activePeriod === period.key
                                        ? {
                                            backgroundColor: '#8B4557',
                                            color: 'white',
                                            boxShadow: '0 1px 3px rgba(92,45,56,0.15)'
                                        }
                                        : {
                                            backgroundColor: 'transparent',
                                            color: '#A07882'
                                        }
                                "
                            >

                                {{ period.label }}

                            </button>

                        </div>

                    </div>


                    <!-- =================================================
                         NAVIGASI PERIODE
                    ================================================== -->

                    <div
                        v-if="activePeriod !== 'all'"
                        class="mt-4 flex flex-col gap-2 rounded-xl border px-3 py-2.5 sm:flex-row sm:items-center sm:justify-between"
                        style="
                            border-color:#F3E0E4;
                            background-color:#FFFAFB;
                        "
                    >

                        <!-- PREVIOUS -->

                        <button
                            @click="previousPeriod"
                            type="button"
                            class="flex items-center justify-center gap-1.5 rounded-lg px-3 py-2 text-xs font-semibold transition-all"
                            style="
                                color:#8B4557;
                                background-color:#F8EEF1;
                            "
                            onmouseenter="this.style.backgroundColor='#F1E2E6'"
                            onmouseleave="this.style.backgroundColor='#F8EEF1'"
                        >

                            <svg
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15.75 19.5L8.25 12l7.5-7.5"
                                />

                            </svg>

                            Sebelumnya

                        </button>


                        <!-- CURRENT PERIOD -->

                        <div
                            class="text-center"
                        >

                            <p
                                class="text-xs font-semibold"
                                style="color:#5C2D38;"
                            >

                                {{ periodLabel }}

                            </p>


                            <button
                                @click="todayPeriod"
                                type="button"
                                class="mt-0.5 text-[11px] font-medium underline"
                                style="color:#B58A95;"
                            >

                                Kembali ke periode sekarang

                            </button>

                        </div>


                        <!-- NEXT -->

                        <button
                            @click="nextPeriod"
                            type="button"
                            class="flex items-center justify-center gap-1.5 rounded-lg px-3 py-2 text-xs font-semibold transition-all"
                            style="
                                color:#8B4557;
                                background-color:#F8EEF1;
                            "
                            onmouseenter="this.style.backgroundColor='#F1E2E6'"
                            onmouseleave="this.style.backgroundColor='#F8EEF1'"
                        >

                            Berikutnya

                            <svg
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M8.25 4.5l7.5 7.5-7.5 7.5"
                                />

                            </svg>

                        </button>

                    </div>

                </div>


                <!-- =================================================
                     TABLE
                ================================================== -->

                <div class="overflow-x-auto">

                    <table class="w-full">


                        <!-- HEADER -->

                        <thead>

                            <tr
                                style="background-color:#FDF8F9;"
                            >

                                <th
                                    class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider"
                                    style="color:#A07882;"
                                >

                                    Kode Order

                                </th>


                                <th
                                    class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider"
                                    style="color:#A07882;"
                                >

                                    Pelanggan

                                </th>


                                <th
                                    class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider"
                                    style="color:#A07882;"
                                >

                                    Total

                                </th>


                                <th
                                    class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider"
                                    style="color:#A07882;"
                                >

                                    Pembayaran

                                </th>


                                <th
                                    class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider"
                                    style="color:#A07882;"
                                >

                                    Status

                                </th>


                                <th
                                    class="px-5 py-3 text-center text-[11px] font-bold uppercase tracking-wider"
                                    style="color:#A07882;"
                                >

                                    Aksi

                                </th>

                            </tr>

                        </thead>


                        <!-- =================================================
                             BODY
                        ================================================== -->

                        <tbody>

                            <template
                                v-for="order in filteredOrders"
                                :key="order.id"
                            >


                                <!-- NORMAL -->

                                <tr
                                    v-if="deleteConfirmId !== order.id"
                                    class="transition-colors duration-150"
                                    style="border-bottom:1px solid #F8F0F2;"
                                    onmouseenter="this.style.backgroundColor='#FDF8F9'"
                                    onmouseleave="this.style.backgroundColor='white'"
                                >


                                    <!-- KODE -->

                                    <td class="px-5 py-4">

                                        <p
                                            class="text-sm font-semibold"
                                            style="color:#5C2D38;"
                                        >

                                            {{ order.kode_order }}

                                        </p>


                                        <p
                                            v-if="order.created_at"
                                            class="mt-0.5 text-[11px]"
                                            style="color:#B8A0A6;"
                                        >

                                            {{
                                                new Date(
                                                    order.created_at
                                                ).toLocaleDateString(
                                                    'id-ID',
                                                    {
                                                        day: '2-digit',
                                                        month: 'short',
                                                        year: 'numeric'
                                                    }
                                                )
                                            }}

                                        </p>

                                    </td>


                                    <!-- PELANGGAN -->

                                    <td class="px-5 py-4">

                                        <p
                                            class="text-sm font-medium"
                                            style="color:#5C2D38;"
                                        >

                                            {{ order.nama_pelanggan }}

                                        </p>


                                        <p
                                            class="text-xs"
                                            style="color:#B8A0A6;"
                                        >

                                            {{ order.no_hp || '-' }}

                                        </p>

                                    </td>


                                    <!-- TOTAL -->

                                    <td class="px-5 py-4">

                                        <p
                                            class="text-sm font-bold"
                                            style="color:#8B4557;"
                                        >

                                            Rp
                                            {{
                                                Number(
                                                    order.total_harga
                                                ).toLocaleString('id-ID')
                                            }}

                                        </p>

                                    </td>


                                    <!-- PEMBAYARAN -->

                                    <td class="px-5 py-4">

                                        <span
                                            class="inline-flex rounded-md px-2.5 py-1 text-xs font-medium"
                                            style="
                                                background-color:#F3E8EC;
                                                color:#8B4557;
                                            "
                                        >

                                            {{ order.metode_pembayaran }}

                                        </span>

                                    </td>


                                    <!-- STATUS -->

                                    <td class="px-5 py-4">

                                        <span
                                            class="inline-flex items-center gap-1.5 text-xs font-medium"
                                            style="color:#4A8B5C;"
                                        >

                                            <span
                                                class="h-1.5 w-1.5 rounded-full"
                                                style="background-color:#4A8B5C;"
                                            ></span>


                                            {{ order.status }}

                                        </span>

                                    </td>


                                    <!-- AKSI -->

                                    <td class="px-5 py-4">

                                        <div
                                            class="flex items-center justify-center gap-2"
                                        >

                                            <!-- CETAK -->

                                            <button
                                                @click="cetakStruk(order)"
                                                type="button"
                                                class="flex items-center gap-1.5 rounded-lg px-3 py-2 text-xs font-semibold transition-all duration-150"
                                                style="
                                                    background-color:#F3E8EC;
                                                    color:#8B4557;
                                                "
                                                onmouseenter="this.style.backgroundColor='#EBD9DE'"
                                                onmouseleave="this.style.backgroundColor='#F3E8EC'"
                                                title="Cetak Struk"
                                            >

                                                <svg
                                                    class="h-4 w-4"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    stroke-width="1.8"
                                                >

                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M6 9V4.5h12V9M6 18H4.5A1.5 1.5 0 013 16.5v-6A1.5 1.5 0 014.5 9h15a1.5 1.5 0 011.5 1.5v6A1.5 1.5 0 0118 18h-1.5M6 14.25h12V21H6v-6.75z"
                                                    />

                                                </svg>


                                                Cetak

                                            </button>


                                            <!-- HAPUS -->

                                            <button
                                                @click="requestDelete(order.id)"
                                                type="button"
                                                class="flex items-center gap-1.5 rounded-lg px-3 py-2 text-xs font-semibold transition-all duration-150"
                                                style="
                                                    background-color:#FDF0F1;
                                                    color:#B04050;
                                                "
                                                onmouseenter="this.style.backgroundColor='#F9E0E3'"
                                                onmouseleave="this.style.backgroundColor='#FDF0F1'"
                                                title="Hapus"
                                            >

                                                <svg
                                                    class="h-4 w-4"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    stroke-width="1.8"
                                                >

                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M6 7.5h12M9.75 7.5V5.25h4.5V7.5m-6.75 2.25v8.25A1.5 1.5 0 009 17.25h6a1.5 1.5 0 001.5-1.5V9.75M10.5 12v3M13.5 12v3"
                                                    />

                                                </svg>


                                                Hapus

                                            </button>

                                        </div>

                                    </td>

                                </tr>


                                <!-- =================================================
                                     KONFIRMASI HAPUS
                                ================================================== -->

                                <tr
                                    v-else
                                    style="
                                        border-bottom:1px solid #F8F0F2;
                                        background-color:#FDF0F1;
                                    "
                                >

                                    <td
                                        colspan="6"
                                        class="px-5 py-4"
                                    >

                                        <div
                                            class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
                                        >

                                            <div
                                                class="flex items-center gap-2 text-sm"
                                                style="color:#B04050;"
                                            >

                                                <svg
                                                    class="h-5 w-5 shrink-0"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                >

                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"
                                                    />

                                                </svg>


                                                <span>

                                                    Yakin ingin menghapus transaksi
                                                    <strong>
                                                        {{ order.kode_order }}
                                                    </strong>
                                                    milik
                                                    <strong>
                                                        {{ order.nama_pelanggan }}
                                                    </strong>
                                                    ?

                                                </span>

                                            </div>


                                            <div class="flex gap-2">

                                                <button
                                                    @click="confirmDelete(order.id)"
                                                    type="button"
                                                    class="rounded-lg px-3 py-2 text-xs font-semibold text-white transition-all"
                                                    style="background-color:#B04050;"
                                                    onmouseenter="this.style.backgroundColor='#963545'"
                                                    onmouseleave="this.style.backgroundColor='#B04050'"
                                                >

                                                    Ya, Hapus

                                                </button>


                                                <button
                                                    @click="cancelDelete"
                                                    type="button"
                                                    class="rounded-lg border bg-white px-3 py-2 text-xs font-semibold"
                                                    style="
                                                        border-color:#EFE0E3;
                                                        color:#6B4A50;
                                                    "
                                                >

                                                    Batal

                                                </button>

                                            </div>

                                        </div>

                                    </td>

                                </tr>

                            </template>


                            <!-- =================================================
                                 EMPTY
                            ================================================== -->

                            <tr v-if="filteredOrders.length === 0">

                                <td
                                    colspan="6"
                                    class="px-5 py-16 text-center"
                                >

                                    <div
                                        class="flex flex-col items-center"
                                    >

                                        <div
                                            class="flex h-14 w-14 items-center justify-center rounded-xl"
                                            style="background-color:#F9EEF0;"
                                        >

                                            <svg
                                                class="h-7 w-7"
                                                style="color:#D4A0AB;"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                                stroke-width="1.5"
                                            >

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M3 10.5h18M6 6h12M5.25 3.75h13.5A1.5 1.5 0 0120.25 5.25v13.5a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V5.25a1.5 1.5 0 011.5-1.5z"
                                                />

                                            </svg>

                                        </div>


                                        <p
                                            class="mt-4 text-sm font-semibold"
                                            style="color:#5C2D38;"
                                        >

                                            Tidak ada transaksi

                                        </p>


                                        <p
                                            class="mt-1 text-xs"
                                            style="color:#B8A0A6;"
                                        >

                                            Tidak ada transaksi pada periode
                                            <span v-if="activePeriod !== 'all'">
                                                {{ periodLabel }}
                                            </span>
                                            <span v-else>
                                                ini.
                                            </span>

                                        </p>

                                    </div>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>


                <!-- =====================================================
                     FOOTER
                ====================================================== -->

                <div
                    class="flex items-center justify-between border-t px-5 py-3"
                    style="
                        border-color:#F8F0F2;
                        background-color:#FDF8F9;
                    "
                >

                    <span
                        class="text-xs"
                        style="color:#B8A0A6;"
                    >

                        Menampilkan

                        <strong style="color:#5C2D38;">
                            {{ filteredOrders.length }}
                        </strong>

                        dari

                        <strong style="color:#5C2D38;">
                            {{ orders.length }}
                        </strong>

                        transaksi

                    </span>


                    <span
                        class="text-xs"
                        style="color:#C4A0A8;"
                    >

                        Sistem Manajemen Toko

                    </span>

                </div>

            </div>

        </div>

    </AuthenticatedLayout>

</template>
```
