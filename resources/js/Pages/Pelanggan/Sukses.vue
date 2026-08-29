<script setup>
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({
    order: {
        type: Object,
        required: true,
    },
})

function formatRupiah(value) {
    return Number(value).toLocaleString('id-ID')
}

function formatMetode(value) {
    const metode = {
        cash: 'Cash',
        transfer: 'Transfer Bank',
        qris: 'QRIS',
    }

    return metode[value] || value
}
</script>

<template>
    <Head title="Pesanan Berhasil" />

    <div
        class="flex min-h-screen items-center justify-center bg-[#FDF8F9] px-4 py-10"
    >

        <div
            class="w-full max-w-xl rounded-3xl border bg-white p-6 shadow-sm sm:p-8"
            style="border-color: #F3E0E4;"
        >

            <!-- SUCCESS ICON -->
            <div class="text-center">

                <div
                    class="mx-auto flex h-20 w-20 items-center justify-center rounded-full"
                    style="background-color: #E8F5EC;"
                >
                    <svg
                        class="h-10 w-10"
                        style="color: #4A8B5C;"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m4.5 12.75 6 6 9-13.5"
                        />
                    </svg>
                </div>

                <h1
                    class="mt-5 text-2xl font-bold"
                    style="color: #5C2D38;"
                >
                    Pesanan Berhasil! 🎉
                </h1>

                <p
                    class="mt-2 text-sm"
                    style="color: #A07882;"
                >
                    Terima kasih, pesanan kamu sudah berhasil dibuat.
                </p>

            </div>

            <!-- KODE ORDER -->
            <div
                class="mt-7 rounded-2xl p-5 text-center"
                style="background-color: #FDF8F9;"
            >

                <p
                    class="text-xs"
                    style="color: #A07882;"
                >
                    Kode Pesanan
                </p>

                <p
                    class="mt-1 text-xl font-bold tracking-wide"
                    style="color: #8B4557;"
                >
                    {{ order.kode_order }}
                </p>

            </div>

            <!-- DATA -->
            <div class="mt-6 space-y-4">

                <div class="flex justify-between gap-4">
                    <span
                        class="text-sm"
                        style="color: #A07882;"
                    >
                        Nama Pelanggan
                    </span>

                    <span
                        class="text-right text-sm font-semibold"
                        style="color: #5C2D38;"
                    >
                        {{ order.nama_pelanggan }}
                    </span>
                </div>

                <div
                    v-if="order.no_hp"
                    class="flex justify-between gap-4"
                >
                    <span
                        class="text-sm"
                        style="color: #A07882;"
                    >
                        Nomor HP
                    </span>

                    <span
                        class="text-right text-sm font-semibold"
                        style="color: #5C2D38;"
                    >
                        {{ order.no_hp }}
                    </span>
                </div>

                <div
                    v-if="order.alamat"
                    class="flex justify-between gap-4"
                >
                    <span
                        class="text-sm"
                        style="color: #A07882;"
                    >
                        Alamat
                    </span>

                    <span
                        class="max-w-[60%] text-right text-sm font-semibold"
                        style="color: #5C2D38;"
                    >
                        {{ order.alamat }}
                    </span>
                </div>

                <div class="flex justify-between gap-4">
                    <span
                        class="text-sm"
                        style="color: #A07882;"
                    >
                        Pembayaran
                    </span>

                    <span
                        class="text-right text-sm font-semibold"
                        style="color: #5C2D38;"
                    >
                        {{ formatMetode(order.metode_pembayaran) }}
                    </span>
                </div>

                <div class="flex justify-between gap-4">
                    <span
                        class="text-sm"
                        style="color: #A07882;"
                    >
                        Status
                    </span>

                    <span
                        class="rounded-full px-3 py-1 text-xs font-bold capitalize"
                        style="background-color: #FFF3E7; color: #C4853A;"
                    >
                        {{ order.status }}
                    </span>
                </div>

            </div>

            <!-- ITEMS -->
            <div
                class="mt-6 border-t pt-5"
                style="border-color: #F3E0E4;"
            >

                <h2
                    class="text-sm font-bold"
                    style="color: #5C2D38;"
                >
                    Detail Pesanan
                </h2>

                <div class="mt-4 space-y-3">

                    <div
                        v-for="item in order.items"
                        :key="item.id"
                        class="flex items-center justify-between gap-3"
                    >

                        <div>
                            <p
                                class="text-sm font-semibold"
                                style="color: #5C2D38;"
                            >
                                {{ item.nama_produk }}
                            </p>

                            <p
                                class="text-xs"
                                style="color: #A07882;"
                            >
                                {{ item.jumlah }} ×
                                Rp {{ formatRupiah(item.harga) }}
                            </p>
                        </div>

                        <p
                            class="text-sm font-bold"
                            style="color: #5C2D38;"
                        >
                            Rp {{ formatRupiah(item.subtotal) }}
                        </p>

                    </div>

                </div>

            </div>

            <!-- TOTAL -->
            <div
                class="mt-5 flex items-center justify-between rounded-xl p-4"
                style="background-color: #F9EEF0;"
            >

                <span
                    class="font-bold"
                    style="color: #5C2D38;"
                >
                    Total Bayar
                </span>

                <span
                    class="text-xl font-bold"
                    style="color: #8B4557;"
                >
                    Rp {{ formatRupiah(order.total_harga) }}
                </span>

            </div>

            <!-- BUTTON -->
            <Link
                :href="route('belanja')"
                class="mt-6 flex w-full items-center justify-center rounded-xl px-5 py-3 text-sm font-bold text-white"
                style="background: linear-gradient(135deg, #8B4557, #6B3344);"
            >
                Kembali Belanja
            </Link>

        </div>

    </div>
</template>