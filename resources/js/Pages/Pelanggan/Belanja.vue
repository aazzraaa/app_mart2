<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { ref, computed, onMounted } from 'vue'

const props = defineProps({
    produk: {
        type: Array,
        default: () => [],
    },
})

const search = ref('')
const kategori = ref('')
const cart = ref([])

onMounted(() => {
    const savedCart = localStorage.getItem('keranjang')

    if (savedCart) {
        try {
            cart.value = JSON.parse(savedCart)
        } catch (error) {
            cart.value = []
        }
    }
})

function simpanCart() {
    localStorage.setItem('keranjang', JSON.stringify(cart.value))
}

const kategoriList = computed(() => {
    return [
        ...new Set(
            props.produk
                .map(item => item.kategori)
                .filter(Boolean)
        )
    ].sort()
})

const produkFiltered = computed(() => {
    let data = props.produk

    const keyword = search.value.toLowerCase().trim()

    if (keyword) {
        data = data.filter(item =>
            item.nama_produk?.toLowerCase().includes(keyword) ||
            item.kategori?.toLowerCase().includes(keyword)
        )
    }

    if (kategori.value) {
        data = data.filter(item =>
            item.kategori === kategori.value
        )
    }

    return data
})

const jumlahKeranjang = computed(() => {
    return cart.value.reduce(
        (total, item) => total + Number(item.jumlah),
        0
    )
})

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

function tambahKeranjang(item) {
    const index = cart.value.findIndex(
        cartItem => cartItem.id === item.id
    )

    if (index !== -1) {
        if (cart.value[index].jumlah < item.stok) {
            cart.value[index].jumlah++
        }
    } else {
        cart.value.push({
            id: item.id,
            nama_produk: item.nama_produk,
            harga: Number(item.harga),
            stok: Number(item.stok),
            foto: item.foto,
            foto_url: item.foto_url,
            foto_type: item.foto_type,
            jumlah: 1,
        })
    }

    simpanCart()
}

function sudahDiKeranjang(id) {
    return cart.value.some(item => item.id === id)
}
</script>

<template>
    <Head title="Belanja" />

    <div class="min-h-screen bg-[#FDF8F9]">

       <!-- NAVBAR -->
<nav
    class="sticky top-0 z-40 border-b bg-white/95 backdrop-blur"
    style="border-color: #F3E0E4;"
>

    <div
        class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8"
    >

        <!-- KIRI -->
        <div class="flex items-center gap-4">

            <!-- KEMBALI -->
            <Link
                :href="route('dashboard')"
                class="flex items-center gap-2 rounded-xl border px-4 py-2 text-sm font-semibold transition hover:bg-[#FBF0F2]"
                style="border-color:#EFE0E3; color:#8B4557;"
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
                        d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"
                    />
                </svg>

                Kembali
            </Link>

            <!-- TOKO -->
            <div>
                <h1
                    class="text-xl font-bold"
                    style="color: #5C2D38;"
                >
                    Toko Kamu
                </h1>

                <p
                    class="text-xs"
                    style="color: #A07882;"
                >
                    Belanja mudah dan cepat
                </p>
            </div>

        </div>


        <!-- KERANJANG -->
        <Link
            :href="route('keranjang')"
            class="relative flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold text-white transition"
            style="background: linear-gradient(135deg, #8B4557, #6B3344);"
        >

            <svg
                class="h-5 w-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="1.8"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.508 1.89m0 0L7.5 15.75h9.75l2.25-10.025H5.231z"
                />

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M7.5 15.75h-.75a1.5 1.5 0 000 3h10.5"
                />

                <circle
                    cx="8.25"
                    cy="20.25"
                    r="1"
                />

                <circle
                    cx="17.25"
                    cy="20.25"
                    r="1"
                />
            </svg>

            Keranjang

            <span
                v-if="jumlahKeranjang > 0"
                class="flex h-5 min-w-5 items-center justify-center rounded-full bg-white px-1 text-[10px] font-bold"
                style="color: #8B4557;"
            >
                {{ jumlahKeranjang }}
            </span>

        </Link>

    </div>

</nav>

        <!-- CONTENT -->
        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

            <!-- HEADER -->
            <div class="mb-6">
                <p
                    class="text-xs font-semibold uppercase tracking-wider"
                    style="color: #A07882;"
                >
                    Katalog Produk
                </p>

                <h2
                    class="mt-1 text-2xl font-bold"
                    style="color: #5C2D38;"
                >
                    Mau belanja apa hari ini? 🛍️
                </h2>

                <p
                    class="mt-1 text-sm"
                    style="color: #A07882;"
                >
                    Pilih produk yang kamu inginkan dan masukkan ke keranjang.
                </p>
            </div>

            <!-- FILTER -->
            <div class="mb-7 flex flex-col gap-3 sm:flex-row">

                <div class="relative flex-1">
                    <svg
                        class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2"
                        style="color: #C4A0A8;"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"
                        />
                    </svg>

                    <input
                        v-model="search"
                        type="text"
                        placeholder="Cari produk..."
                        class="w-full rounded-xl border bg-white py-3 pl-10 pr-4 text-sm outline-none"
                        style="border-color: #EFE0E3; color: #5C2D38;"
                    />
                </div>

                <select
                    v-model="kategori"
                    class="rounded-xl border bg-white px-4 py-3 text-sm outline-none"
                    style="border-color: #EFE0E3; color: #5C2D38;"
                >
                    <option value="">
                        Semua Kategori
                    </option>

                    <option
                        v-for="item in kategoriList"
                        :key="item"
                        :value="item"
                    >
                        {{ item }}
                    </option>
                </select>

            </div>

            <!-- PRODUCT GRID -->
            <div
                v-if="produkFiltered.length"
                class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
            >

                <div
                    v-for="item in produkFiltered"
                    :key="item.id"
                    class="overflow-hidden rounded-2xl border bg-white transition duration-200 hover:-translate-y-1 hover:shadow-lg"
                    style="border-color: #F3E0E4;"
                >

                    <!-- IMAGE -->
                    <div
                        class="relative h-52 overflow-hidden"
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
                                class="h-14 w-14"
                                style="color: #D4A0AB;"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z"
                                />
                            </svg>
                        </div>

                        <span
                            v-if="item.kategori"
                            class="absolute left-3 top-3 rounded-lg bg-white/90 px-2.5 py-1 text-[10px] font-bold uppercase"
                            style="color: #8B4557;"
                        >
                            {{ item.kategori }}
                        </span>

                    </div>

                    <!-- INFO -->
                    <div class="p-4">

                        <h3
                            class="truncate text-base font-bold"
                            style="color: #5C2D38;"
                        >
                            {{ item.nama_produk }}
                        </h3>

                        <p
                            class="mt-1 text-lg font-bold"
                            style="color: #8B4557;"
                        >
                            Rp {{ Number(item.harga).toLocaleString('id-ID') }}
                        </p>

                        <p
                            v-if="item.deskripsi"
                            class="mt-2 line-clamp-2 text-xs"
                            style="color: #A07882;"
                        >
                            {{ item.deskripsi }}
                        </p>

                        <div class="mt-3 flex items-center justify-between">

                            <span
                                class="text-xs"
                                style="color: #A07882;"
                            >
                                Stok: {{ item.stok }}
                            </span>

                            <span
                                v-if="item.stok <= 5"
                                class="text-[11px] font-semibold"
                                style="color: #C4853A;"
                            >
                                Stok terbatas
                            </span>

                        </div>

                        <!-- BUTTON -->
                        <button
                            @click="tambahKeranjang(item)"
                            :disabled="item.stok <= 0"
                            class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition disabled:cursor-not-allowed disabled:opacity-40"
                            style="background-color: #F9EEF0; color: #8B4557;"
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
                                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.508 1.89m0 0L7.5 15.75h9.75l2.25-10.025H5.231z"
                                />
                            </svg>

                            <span v-if="item.stok <= 0">
                                Stok Habis
                            </span>

                            <span v-else-if="sudahDiKeranjang(item.id)">
                                + Tambah Lagi
                            </span>

                            <span v-else>
                                Tambah ke Keranjang
                            </span>
                        </button>

                    </div>

                </div>

            </div>

            <!-- EMPTY -->
            <div
                v-else
                class="rounded-2xl border bg-white py-20 text-center"
                style="border-color: #F3E0E4;"
            >
                <div
                    class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl"
                    style="background-color: #F9EEF0;"
                >
                    <svg
                        class="h-8 w-8"
                        style="color: #D4A0AB;"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m21 21-4.35-4.35m2.1-5.4a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z"
                        />
                    </svg>
                </div>

                <h3
                    class="mt-4 font-bold"
                    style="color: #5C2D38;"
                >
                    Produk tidak ditemukan
                </h3>

                <p
                    class="mt-1 text-sm"
                    style="color: #A07882;"
                >
                    Coba kata kunci atau kategori lainnya.
                </p>
            </div>

        </main>
    </div>
</template>