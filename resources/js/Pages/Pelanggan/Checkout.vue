<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ref, computed, onMounted } from 'vue'

/*
|--------------------------------------------------------------------------
| KERANJANG
|--------------------------------------------------------------------------
*/

const cart = ref([])


/*
|--------------------------------------------------------------------------
| FORM CHECKOUT
|--------------------------------------------------------------------------
*/

const form = useForm({
    nama_pelanggan: '',
    no_hp: '',
    kota: '',
    alamat: '',
    metode_pembayaran: 'cash',
    items: [],
})


/*
|--------------------------------------------------------------------------
| AMBIL DATA KERANJANG DARI LOCAL STORAGE
|--------------------------------------------------------------------------
*/

onMounted(() => {

    const savedCart = localStorage.getItem('keranjang')

    if (savedCart) {

        try {

            cart.value = JSON.parse(savedCart)

        } catch (error) {

            console.error(
                'Gagal membaca keranjang:',
                error
            )

            cart.value = []
        }

    }

    /*
    |--------------------------------------------------------------------------
    | MASUKKAN ITEM KERANJANG KE FORM
    |--------------------------------------------------------------------------
    */

    form.items = cart.value.map(item => ({
        produk_id: item.id,
        jumlah: item.jumlah,
    }))
})


/*
|--------------------------------------------------------------------------
| TOTAL HARGA
|--------------------------------------------------------------------------
*/

const totalHarga = computed(() => {

    return cart.value.reduce(
        (total, item) => {

            return total +
                Number(item.harga) *
                Number(item.jumlah)

        },
        0
    )
})


/*
|--------------------------------------------------------------------------
| TOTAL JUMLAH BARANG
|--------------------------------------------------------------------------
*/

const totalItem = computed(() => {

    return cart.value.reduce(
        (total, item) => {

            return total +
                Number(item.jumlah)

        },
        0
    )
})


/*
|--------------------------------------------------------------------------
| PROSES PESAN
|--------------------------------------------------------------------------
*/

function pesan() {

    /*
    |--------------------------------------------------------------------------
    | CEK KERANJANG
    |--------------------------------------------------------------------------
    */

    if (cart.value.length === 0) {

        alert(
            'Keranjang kamu masih kosong.'
        )

        return
    }


    /*
    |--------------------------------------------------------------------------
    | CEK NAMA
    |--------------------------------------------------------------------------
    */

    if (!form.nama_pelanggan.trim()) {

        alert(
            'Nama pelanggan wajib diisi.'
        )

        return
    }


    /*
    |--------------------------------------------------------------------------
    | CEK NO HP
    |--------------------------------------------------------------------------
    */

    if (!form.no_hp.trim()) {

        alert(
            'Nomor HP wajib diisi.'
        )

        return
    }


    /*
    |--------------------------------------------------------------------------
    | CEK KOTA
    |--------------------------------------------------------------------------
    */

    if (!form.kota.trim()) {

        alert(
            'Kota wajib diisi.'
        )

        return
    }


    /*
    |--------------------------------------------------------------------------
    | CEK ALAMAT
    |--------------------------------------------------------------------------
    */

    if (!form.alamat.trim()) {

        alert(
            'Alamat wajib diisi.'
        )

        return
    }


    /*
    |--------------------------------------------------------------------------
    | SIAPKAN ITEM
    |--------------------------------------------------------------------------
    */

    form.items = cart.value.map(item => ({

        produk_id: item.id,

        jumlah: item.jumlah,

    }))


    /*
    |--------------------------------------------------------------------------
    | KIRIM DATA KE LARAVEL
    |--------------------------------------------------------------------------
    |
    | Data yang dikirim:
    |
    | nama_pelanggan
    | no_hp
    | kota
    | alamat
    | metode_pembayaran
    | items
    |
    */

    form.post(
        route('checkout.store'),
        {

            preserveScroll: true,

            onSuccess: () => {

                /*
                |--------------------------------------------------------------
                | HAPUS KERANJANG SETELAH BERHASIL
                |--------------------------------------------------------------
                */

                localStorage.removeItem(
                    'keranjang'
                )
            },

            onError: (errors) => {

                console.error(
                    'Checkout gagal:',
                    errors
                )
            },
        }
    )
}

</script>


<template>

    <Head title="Checkout" />


    <div
        class="min-h-screen bg-[#FDF8F9]"
    >


        <!-- =========================================================
             NAVBAR
        ========================================================== -->

        <nav
            class="border-b bg-white"
            style="border-color:#F3E0E4;"
        >

            <div
                class="mx-auto flex max-w-5xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8"
            >

                <div>

                    <h1
                        class="text-xl font-bold"
                        style="color:#5C2D38;"
                    >
                        Checkout
                    </h1>

                    <p
                        class="text-xs"
                        style="color:#A07882;"
                    >
                        Lengkapi data pesanan kamu
                    </p>

                </div>


                <Link
                    :href="route('keranjang')"
                    class="rounded-xl border px-4 py-2 text-sm font-semibold transition hover:bg-[#FDF8F9]"
                    style="
                        border-color:#EFE0E3;
                        color:#8B4557;
                    "
                >
                    ← Keranjang
                </Link>

            </div>

        </nav>



        <!-- =========================================================
             MAIN
        ========================================================== -->

        <main
            class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8"
        >


            <!-- =====================================================
                 ERROR CHECKOUT
            ====================================================== -->

            <div
                v-if="form.errors.checkout"
                class="mb-5 rounded-xl border px-4 py-3 text-sm"
                style="
                    border-color:#F2C7CD;
                    background-color:#FDF0F1;
                    color:#B04050;
                "
            >

                {{ form.errors.checkout }}

            </div>



            <!-- =====================================================
                 ERROR UMUM
            ====================================================== -->

            <div
                v-if="Object.keys(form.errors).length > 0 && !form.errors.checkout"
                class="mb-5 rounded-xl border px-4 py-3 text-sm"
                style="
                    border-color:#F2C7CD;
                    background-color:#FDF0F1;
                    color:#B04050;
                "
            >

                <p
                    class="font-semibold"
                >
                    Periksa kembali data checkout kamu.
                </p>

            </div>



            <!-- =====================================================
                 KERANJANG KOSONG
            ====================================================== -->

            <div
                v-if="cart.length === 0"
                class="rounded-2xl border bg-white py-20 text-center"
                style="border-color:#F3E0E4;"
            >

                <div
                    class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full"
                    style="background-color:#F3E8EC;"
                >

                    <svg
                        class="h-7 w-7"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.7"
                        style="color:#8B4557;"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3 3h2l2.4 11.2a2 2 0 002 1.6h8.9a2 2 0 001.9-1.4L22 7H6"
                        />

                        <circle
                            cx="10"
                            cy="20"
                            r="1"
                        />

                        <circle
                            cx="18"
                            cy="20"
                            r="1"
                        />

                    </svg>

                </div>


                <h2
                    class="text-lg font-bold"
                    style="color:#5C2D38;"
                >
                    Keranjang kosong
                </h2>


                <p
                    class="mt-1 text-sm"
                    style="color:#A07882;"
                >
                    Silakan pilih produk terlebih dahulu.
                </p>


                <Link
                    :href="route('belanja')"
                    class="mt-5 inline-flex rounded-xl px-5 py-2.5 text-sm font-semibold text-white transition hover:opacity-90"
                    style="
                        background:linear-gradient(
                            135deg,
                            #8B4557,
                            #6B3344
                        );
                    "
                >
                    Kembali Belanja
                </Link>

            </div>



            <!-- =====================================================
                 CHECKOUT
            ====================================================== -->

            <div
                v-else
                class="grid grid-cols-1 gap-6 lg:grid-cols-5"
            >



                <!-- =================================================
                     FORM PELANGGAN
                ================================================== -->

                <div
                    class="rounded-2xl border bg-white p-6 lg:col-span-3"
                    style="border-color:#F3E0E4;"
                >

                    <h2
                        class="text-lg font-bold"
                        style="color:#5C2D38;"
                    >
                        Data Pelanggan
                    </h2>


                    <p
                        class="mt-1 text-xs"
                        style="color:#A07882;"
                    >
                        Masukkan informasi pelanggan untuk pesanan.
                    </p>



                    <!-- =============================================
                         NAMA PELANGGAN
                    ============================================== -->

                    <div class="mt-6">

                        <label
                            class="mb-1.5 block text-sm font-semibold"
                            style="color:#6B4A50;"
                        >
                            Nama Pelanggan
                        </label>


                        <input
                            v-model="form.nama_pelanggan"
                            type="text"
                            placeholder="Masukkan nama pelanggan"
                            autocomplete="name"
                            class="w-full rounded-xl border px-4 py-3 text-sm outline-none transition focus:ring-2 focus:ring-[#F3E0E4]"
                            :class="{
                                'border-red-300': form.errors.nama_pelanggan
                            }"
                            style="
                                border-color:#EFE0E3;
                                color:#5C2D38;
                            "
                        />


                        <p
                            v-if="form.errors.nama_pelanggan"
                            class="mt-1 text-xs"
                            style="color:#B04050;"
                        >
                            {{ form.errors.nama_pelanggan }}
                        </p>

                    </div>



                    <!-- =============================================
                         NO HP
                    ============================================== -->

                    <div class="mt-4">

                        <label
                            class="mb-1.5 block text-sm font-semibold"
                            style="color:#6B4A50;"
                        >
                            Nomor HP
                        </label>


                        <input
                            v-model="form.no_hp"
                            type="tel"
                            inputmode="numeric"
                            placeholder="08xxxxxxxxxx"
                            autocomplete="tel"
                            class="w-full rounded-xl border px-4 py-3 text-sm outline-none transition focus:ring-2 focus:ring-[#F3E0E4]"
                            :class="{
                                'border-red-300': form.errors.no_hp
                            }"
                            style="
                                border-color:#EFE0E3;
                                color:#5C2D38;
                            "
                        />


                        <p
                            v-if="form.errors.no_hp"
                            class="mt-1 text-xs"
                            style="color:#B04050;"
                        >
                            {{ form.errors.no_hp }}
                        </p>

                    </div>



                    <!-- =============================================
                         KOTA
                    ============================================== -->

                    <div class="mt-4">

                        <label
                            class="mb-1.5 block text-sm font-semibold"
                            style="color:#6B4A50;"
                        >
                            Kota
                        </label>


                        <input
                            v-model="form.kota"
                            type="text"
                            placeholder="Contoh: Bandung"
                            autocomplete="address-level2"
                            class="w-full rounded-xl border px-4 py-3 text-sm outline-none transition focus:ring-2 focus:ring-[#F3E0E4]"
                            :class="{
                                'border-red-300': form.errors.kota
                            }"
                            style="
                                border-color:#EFE0E3;
                                color:#5C2D38;
                            "
                        />


                        <p
                            v-if="form.errors.kota"
                            class="mt-1 text-xs"
                            style="color:#B04050;"
                        >
                            {{ form.errors.kota }}
                        </p>

                    </div>



                    <!-- =============================================
                         ALAMAT
                    ============================================== -->

                    <div class="mt-4">

                        <label
                            class="mb-1.5 block text-sm font-semibold"
                            style="color:#6B4A50;"
                        >
                            Alamat Lengkap
                        </label>


                        <textarea
                            v-model="form.alamat"
                            rows="4"
                            placeholder="Masukkan alamat lengkap pelanggan"
                            autocomplete="street-address"
                            class="w-full resize-none rounded-xl border px-4 py-3 text-sm outline-none transition focus:ring-2 focus:ring-[#F3E0E4]"
                            :class="{
                                'border-red-300': form.errors.alamat
                            }"
                            style="
                                border-color:#EFE0E3;
                                color:#5C2D38;
                            "
                        ></textarea>


                        <p
                            v-if="form.errors.alamat"
                            class="mt-1 text-xs"
                            style="color:#B04050;"
                        >
                            {{ form.errors.alamat }}
                        </p>

                    </div>



                    <!-- =============================================
                         PEMBAYARAN
                    ============================================== -->

                    <div class="mt-6">

                        <label
                            class="mb-3 block text-sm font-semibold"
                            style="color:#6B4A50;"
                        >
                            Metode Pembayaran
                        </label>


                        <div class="space-y-2">



                            <!-- CASH -->

                            <label
                                class="flex cursor-pointer items-center gap-3 rounded-xl border p-4 transition"
                                :style="
                                    form.metode_pembayaran === 'cash'
                                        ? 'border-color:#8B4557; background-color:#FDF8F9;'
                                        : 'border-color:#EFE0E3;'
                                "
                            >

                                <input
                                    v-model="form.metode_pembayaran"
                                    type="radio"
                                    value="cash"
                                />


                                <div>

                                    <p
                                        class="text-sm font-semibold"
                                        style="color:#5C2D38;"
                                    >
                                        Cash
                                    </p>


                                    <p
                                        class="text-xs"
                                        style="color:#A07882;"
                                    >
                                        Bayar langsung
                                    </p>

                                </div>

                            </label>



                            <!-- TRANSFER -->

                            <label
                                class="flex cursor-pointer items-center gap-3 rounded-xl border p-4 transition"
                                :style="
                                    form.metode_pembayaran === 'transfer'
                                        ? 'border-color:#8B4557; background-color:#FDF8F9;'
                                        : 'border-color:#EFE0E3;'
                                "
                            >

                                <input
                                    v-model="form.metode_pembayaran"
                                    type="radio"
                                    value="transfer"
                                />


                                <div>

                                    <p
                                        class="text-sm font-semibold"
                                        style="color:#5C2D38;"
                                    >
                                        Transfer Bank
                                    </p>


                                    <p
                                        class="text-xs"
                                        style="color:#A07882;"
                                    >
                                        Pembayaran melalui transfer
                                    </p>

                                </div>

                            </label>



                            <!-- QRIS -->

                            <label
                                class="flex cursor-pointer items-center gap-3 rounded-xl border p-4 transition"
                                :style="
                                    form.metode_pembayaran === 'qris'
                                        ? 'border-color:#8B4557; background-color:#FDF8F9;'
                                        : 'border-color:#EFE0E3;'
                                "
                            >

                                <input
                                    v-model="form.metode_pembayaran"
                                    type="radio"
                                    value="qris"
                                />


                                <div>

                                    <p
                                        class="text-sm font-semibold"
                                        style="color:#5C2D38;"
                                    >
                                        QRIS
                                    </p>


                                    <p
                                        class="text-xs"
                                        style="color:#A07882;"
                                    >
                                        Bayar menggunakan QRIS
                                    </p>

                                </div>

                            </label>

                        </div>


                        <p
                            v-if="form.errors.metode_pembayaran"
                            class="mt-1 text-xs"
                            style="color:#B04050;"
                        >
                            {{ form.errors.metode_pembayaran }}
                        </p>

                    </div>

                </div>



                <!-- =================================================
                     RINGKASAN PESANAN
                ================================================== -->

                <div
                    class="lg:col-span-2"
                >

                    <div
                        class="sticky top-6 rounded-2xl border bg-white p-6"
                        style="border-color:#F3E0E4;"
                    >


                        <h2
                            class="font-bold"
                            style="color:#5C2D38;"
                        >
                            Ringkasan Pesanan
                        </h2>


                        <p
                            class="mt-1 text-xs"
                            style="color:#A07882;"
                        >
                            {{ totalItem }} barang dalam keranjang
                        </p>



                        <!-- =========================================
                             ITEMS
                        ========================================== -->

                        <div
                            class="mt-5 max-h-72 space-y-4 overflow-y-auto pr-1"
                        >

                            <div
                                v-for="item in cart"
                                :key="item.id"
                                class="flex justify-between gap-3"
                            >

                                <div
                                    class="min-w-0"
                                >

                                    <p
                                        class="truncate text-sm font-semibold"
                                        style="color:#5C2D38;"
                                    >
                                        {{ item.nama_produk }}
                                    </p>


                                    <p
                                        class="mt-0.5 text-xs"
                                        style="color:#A07882;"
                                    >
                                        {{ item.jumlah }}
                                        ×
                                        Rp
                                        {{
                                            Number(
                                                item.harga
                                            ).toLocaleString('id-ID')
                                        }}
                                    </p>

                                </div>


                                <p
                                    class="shrink-0 text-sm font-semibold"
                                    style="color:#5C2D38;"
                                >
                                    Rp
                                    {{
                                        (
                                            Number(item.harga) *
                                            Number(item.jumlah)
                                        ).toLocaleString('id-ID')
                                    }}
                                </p>

                            </div>

                        </div>



                        <!-- =========================================
                             GARIS
                        ========================================== -->

                        <div
                            class="my-5 border-t"
                            style="border-color:#F3E0E4;"
                        ></div>



                        <!-- =========================================
                             TOTAL BARANG
                        ========================================== -->

                        <div
                            class="flex justify-between"
                        >

                            <span
                                class="text-sm"
                                style="color:#A07882;"
                            >
                                Total barang
                            </span>


                            <span
                                class="text-sm font-semibold"
                                style="color:#5C2D38;"
                            >
                                {{ totalItem }}
                            </span>

                        </div>



                        <!-- =========================================
                             TOTAL BAYAR
                        ========================================== -->

                        <div
                            class="mt-3 flex items-center justify-between gap-3"
                        >

                            <span
                                class="font-bold"
                                style="color:#5C2D38;"
                            >
                                Total Bayar
                            </span>


                            <span
                                class="text-xl font-bold"
                                style="color:#8B4557;"
                            >
                                Rp
                                {{
                                    totalHarga.toLocaleString('id-ID')
                                }}
                            </span>

                        </div>



                        <!-- =========================================
                             BUTTON CHECKOUT
                        ========================================== -->

                        <button
                            @click="pesan"
                            :disabled="form.processing"
                            class="mt-6 flex w-full items-center justify-center rounded-xl px-5 py-3.5 text-sm font-bold text-white transition hover:opacity-90 disabled:cursor-not-allowed disabled:opacity-50"
                            style="
                                background:linear-gradient(
                                    135deg,
                                    #8B4557,
                                    #6B3344
                                );
                            "
                        >

                            <svg
                                v-if="form.processing"
                                class="mr-2 h-4 w-4 animate-spin"
                                fill="none"
                                viewBox="0 0 24 24"
                            >

                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                ></circle>

                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                                ></path>

                            </svg>


                            {{
                                form.processing
                                    ? 'Memproses Pesanan...'
                                    : 'Buat Pesanan'
                            }}

                        </button>



                        <p
                            class="mt-3 text-center text-[11px]"
                            style="color:#B8A0A6;"
                        >
                            Pastikan data pesanan sudah benar sebelum membuat pesanan.
                        </p>

                    </div>

                </div>

            </div>

        </main>

    </div>

</template>