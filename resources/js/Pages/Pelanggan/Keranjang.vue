<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { ref, computed, onMounted } from 'vue'

const cart = ref([])

onMounted(() => {
    loadCart()
})

function loadCart() {
    const savedCart = localStorage.getItem('keranjang')

    if (savedCart) {
        try {
            cart.value = JSON.parse(savedCart)
        } catch (error) {
            cart.value = []
        }
    }
}

function saveCart() {
    localStorage.setItem(
        'keranjang',
        JSON.stringify(cart.value)
    )
}

function getFotoUrl(item) {
    if (item.foto_url) {
        return item.foto_url
    }

    if (!item.foto) {
        return null
    }

    if (
        item.foto.startsWith('http://') ||
        item.foto.startsWith('https://')
    ) {
        return item.foto
    }

    return '/storage/' + item.foto
}

function tambah(item) {
    if (item.jumlah < item.stok) {
        item.jumlah++
        saveCart()
    }
}

function kurang(item) {
    if (item.jumlah > 1) {
        item.jumlah--
        saveCart()
    }
}

function hapus(id) {
    cart.value = cart.value.filter(
        item => item.id !== id
    )

    saveCart()
}

function kosongkan() {
    cart.value = []

    localStorage.removeItem('keranjang')
}

const totalItem = computed(() => {
    return cart.value.reduce(
        (total, item) => total + Number(item.jumlah),
        0
    )
})

const totalHarga = computed(() => {
    return cart.value.reduce(
        (total, item) =>
            total + Number(item.harga) * Number(item.jumlah),
        0
    )
})
</script>

<template>
    <Head title="Keranjang" />

    <div class="min-h-screen bg-[#FDF8F9]">

        <!-- NAVBAR -->
        <nav
            class="border-b bg-white"
            style="border-color: #F3E0E4;"
        >
            <div
                class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8"
            >

                <div>
                    <h1
                        class="text-xl font-bold"
                        style="color: #5C2D38;"
                    >
                        Keranjang 🛒
                    </h1>

                    <p
                        class="text-xs"
                        style="color: #A07882;"
                    >
                        {{ totalItem }} barang
                    </p>
                </div>

                <Link
                    :href="route('belanja')"
                    class="rounded-xl border px-4 py-2 text-sm font-semibold"
                    style="border-color: #EFE0E3; color: #8B4557;"
                >
                    ← Lanjut Belanja
                </Link>

            </div>
        </nav>

        <main
            class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8"
        >

            <!-- KOSONG -->
            <div
                v-if="cart.length === 0"
                class="rounded-2xl border bg-white py-20 text-center"
                style="border-color: #F3E0E4;"
            >

                <div
                    class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl"
                    style="background-color: #F9EEF0;"
                >
                    <svg
                        class="h-10 w-10"
                        style="color: #C994A1;"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.508 1.89m0 0L7.5 15.75h9.75l2.25-10.025H5.231z"
                        />
                    </svg>
                </div>

                <h2
                    class="mt-5 text-lg font-bold"
                    style="color: #5C2D38;"
                >
                    Keranjang masih kosong
                </h2>

                <p
                    class="mt-1 text-sm"
                    style="color: #A07882;"
                >
                    Yuk pilih produk yang kamu mau.
                </p>

                <Link
                    :href="route('belanja')"
                    class="mt-5 inline-flex rounded-xl px-5 py-2.5 text-sm font-semibold text-white"
                    style="background: linear-gradient(135deg, #8B4557, #6B3344);"
                >
                    Mulai Belanja
                </Link>

            </div>

            <!-- ADA ISI -->
            <div
                v-else
                class="grid grid-cols-1 gap-6 lg:grid-cols-3"
            >

                <!-- LIST -->
                <div class="space-y-3 lg:col-span-2">

                    <div
                        v-for="item in cart"
                        :key="item.id"
                        class="flex gap-4 rounded-2xl border bg-white p-4"
                        style="border-color: #F3E0E4;"
                    >

                        <!-- FOTO -->
                        <div
                            class="h-24 w-24 shrink-0 overflow-hidden rounded-xl"
                            style="background-color: #F9EEF0;"
                        >
                            <img
                                v-if="getFotoUrl(item)"
                                :src="getFotoUrl(item)"
                                :alt="item.nama_produk"
                                class="h-full w-full object-cover"
                            />

                            <div
                                v-else
                                class="flex h-full items-center justify-center"
                            >
                                <svg
                                    class="h-8 w-8"
                                    style="color: #D4A0AB;"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159"
                                    />
                                </svg>
                            </div>
                        </div>

                        <!-- DETAIL -->
                        <div class="min-w-0 flex-1">

                            <div class="flex justify-between gap-3">

                                <div>
                                    <h3
                                        class="truncate font-bold"
                                        style="color: #5C2D38;"
                                    >
                                        {{ item.nama_produk }}
                                    </h3>

                                    <p
                                        class="mt-1 text-sm font-semibold"
                                        style="color: #8B4557;"
                                    >
                                        Rp {{ Number(item.harga).toLocaleString('id-ID') }}
                                    </p>
                                </div>

                                <button
                                    @click="hapus(item.id)"
                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg"
                                    style="color: #B04050;"
                                    title="Hapus"
                                >
                                    ✕
                                </button>

                            </div>

                            <div class="mt-4 flex items-center justify-between">

                                <!-- JUMLAH -->
                                <div
                                    class="flex items-center overflow-hidden rounded-lg border"
                                    style="border-color: #EFE0E3;"
                                >

                                    <button
                                        @click="kurang(item)"
                                        class="h-8 w-9 font-bold"
                                        style="color: #8B4557;"
                                    >
                                        −
                                    </button>

                                    <span
                                        class="flex h-8 w-9 items-center justify-center border-x text-sm font-semibold"
                                        style="border-color: #EFE0E3; color: #5C2D38;"
                                    >
                                        {{ item.jumlah }}
                                    </span>

                                    <button
                                        @click="tambah(item)"
                                        :disabled="item.jumlah >= item.stok"
                                        class="h-8 w-9 font-bold disabled:opacity-30"
                                        style="color: #8B4557;"
                                    >
                                        +
                                    </button>

                                </div>

                                <!-- SUBTOTAL -->
                                <p
                                    class="text-sm font-bold"
                                    style="color: #5C2D38;"
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

                            <p
                                class="mt-2 text-[11px]"
                                style="color: #B8A0A6;"
                            >
                                Stok tersedia: {{ item.stok }}
                            </p>

                        </div>

                    </div>

                    <!-- KOSONGKAN -->
                    <div class="flex justify-end pt-2">
                        <button
                            @click="kosongkan"
                            class="text-xs font-semibold"
                            style="color: #B04050;"
                        >
                            Kosongkan Keranjang
                        </button>
                    </div>

                </div>

                <!-- RINGKASAN -->
                <div>
                    <div
                        class="sticky top-6 rounded-2xl border bg-white p-5"
                        style="border-color: #F3E0E4;"
                    >

                        <h2
                            class="font-bold"
                            style="color: #5C2D38;"
                        >
                            Ringkasan Pesanan
                        </h2>

                        <div
                            class="mt-5 space-y-3 text-sm"
                        >
                            <div class="flex justify-between">
                                <span style="color: #A07882;">
                                    Total barang
                                </span>

                                <span
                                    class="font-semibold"
                                    style="color: #5C2D38;"
                                >
                                    {{ totalItem }}
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span style="color: #A07882;">
                                    Subtotal
                                </span>

                                <span
                                    class="font-semibold"
                                    style="color: #5C2D38;"
                                >
                                    Rp {{ totalHarga.toLocaleString('id-ID') }}
                                </span>
                            </div>
                        </div>

                        <div
                            class="my-5 border-t"
                            style="border-color: #F3E0E4;"
                        ></div>

                        <div class="flex items-center justify-between">
                            <span
                                class="font-bold"
                                style="color: #5C2D38;"
                            >
                                Total
                            </span>

                            <span
                                class="text-xl font-bold"
                                style="color: #8B4557;"
                            >
                                Rp {{ totalHarga.toLocaleString('id-ID') }}
                            </span>
                        </div>

                        <Link
                            :href="route('checkout')"
                            class="mt-5 flex w-full items-center justify-center rounded-xl px-5 py-3 text-sm font-bold text-white"
                            style="background: linear-gradient(135deg, #8B4557, #6B3344);"
                        >
                            Lanjut Checkout →
                        </Link>

                    </div>
                </div>

            </div>

        </main>
    </div>
</template>