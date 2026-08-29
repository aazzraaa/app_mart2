<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';

const props = defineProps({
    produk: Array,
});

const showModal = ref(false);
const search = ref('');
const filterKategori = ref('');
const deleteConfirmId = ref(null);

// Foto form state
const fotoType = ref('file');
const fileInput = ref(null);
const previewUrl = ref(null);

const form = useForm({
    nama_produk: '',
    harga: '',
    stok: '',
    kategori: '',
    deskripsi: '',
    foto: null,
    foto_url: '',
    foto_type: 'file',
});

// Kategori unik dari data
const kategoriList = computed(() => {
    const kategori = [...new Set(props.produk.map((p) => p.kategori).filter(Boolean))];
    return kategori.sort();
});

// Filter produk
const filteredProduk = computed(() => {
    let result = props.produk;
    const kw = search.value.toLowerCase().trim();
    if (kw) {
        result = result.filter((p) =>
            p.nama_produk?.toLowerCase().includes(kw) ||
            p.kategori?.toLowerCase().includes(kw)
        );
    }
    if (filterKategori.value) {
        result = result.filter((p) => p.kategori === filterKategori.value);
    }
    return result;
});

// Stok helper
function stokInfo(stok) {
    if (stok <= 0) return { label: 'Habis', color: '#B04050', bg: '#FDF0F1' };
    if (stok <= 5) return { label: 'Menipis', color: '#C4853A', bg: '#FDF3E7' };
    return { label: 'Tersedia', color: '#4A8B5C', bg: '#E8F5EC' };
}

function getFotoUrl(item) {
    if (!item.foto) return null;
    if (item.foto.startsWith('http')) return item.foto;
    return '/storage/' + item.foto;
}

// File input handler
function onFileChange(e) {
    const file = e.target.files[0];
    form.foto = file || null;
    if (file) {
        previewUrl.value = URL.createObjectURL(file);
    } else {
        previewUrl.value = null;
    }
}

// Watch URL input for preview
watch(() => form.foto_url, (val) => {
    if (fotoType.value === 'url' && val) {
        previewUrl.value = val;
    } else if (fotoType.value === 'url') {
        previewUrl.value = null;
    }
});

function switchFotoType(type) {
    fotoType.value = type;
    form.foto_type = type;
    form.foto = null;
    form.foto_url = '';
    previewUrl.value = null;
    if (fileInput.value) fileInput.value.value = '';
}

function clearPreview() {
    previewUrl.value = null;
    form.foto = null;
    form.foto_url = '';
    if (fileInput.value) fileInput.value.value = '';
}

function openModal() {
    form.reset();
    form.foto_type = 'file';
    fotoType.value = 'file';
    previewUrl.value = null;
    if (fileInput.value) fileInput.value.value = '';
    showModal.value = true;
}

function tambahProduk() {
    form.foto_type = fotoType.value;
    form.post(route('produk.store'), {
        onSuccess: () => {
            showModal.value = false;
            form.reset();
            form.foto_type = 'file';
            fotoType.value = 'file';
            previewUrl.value = null;
        },
    });
}

function requestDelete(id) {
    deleteConfirmId.value = id;
}

function cancelDelete() {
    deleteConfirmId.value = null;
}

function confirmDelete(id) {
    router.delete(route('produk.destroy', id), {
        preserveScroll: true,
        onSuccess: () => {
            deleteConfirmId.value = null;
        },
    });
}
</script>

<template>
    <Head title="Produk" />

    <AuthenticatedLayout>

        <template #header>
            <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-2 text-xs" style="color: #B8A0A6;">
                        <span>Dashboard</span>
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                        <span style="color: #8B4557;">Produk</span>
                    </div>
                    <h2 class="mt-1 text-xl font-bold" style="color: #5C2D38;">Data Produk</h2>
                    <p class="text-sm" style="color: #A07882;">Kelola produk toko kamu</p>
                </div>
            </div>
        </template>

        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

            <!-- Toast -->
            <div v-if="$page.props.flash?.success"
                class="mb-4 flex items-center gap-3 rounded-xl border bg-white px-4 py-3"
                style="border-color: #D0E8D6; box-shadow: 0 2px 8px rgba(74,139,92,0.1);"
            >
                <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full" style="background-color: #E8F5EC;">
                    <svg class="h-4 w-4" style="color: #4A8B5C;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </div>
                <p class="text-sm font-medium" style="color: #3A7D4A;">{{ $page.props.flash.success }}</p>
            </div>

            <!-- TOOLBAR -->
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                    <!-- Search -->
                    <div class="relative">
                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2" style="color: #C4A0A8;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                        <input v-model="search" type="text" placeholder="Cari produk..."
                            class="w-full rounded-lg border bg-white py-2 pl-9 pr-3 text-sm outline-none transition-all duration-200 sm:w-56"
                            style="border-color: #EFE0E3; color: #5C2D38;"
                            onfocus="this.style.borderColor='#8B4557'; this.style.boxShadow='0 0 0 3px rgba(139,69,87,0.08)'"
                            onblur="this.style.borderColor='#EFE0E3'; this.style.boxShadow='none'"
                        />
                    </div>

                    <!-- Filter Kategori -->
                    <select v-if="kategoriList.length > 0" v-model="filterKategori"
                        class="appearance-none rounded-lg border bg-white py-2 pl-3 pr-8 text-sm outline-none transition-all duration-200"
                        style="border-color: #EFE0E3; color: #5C2D38;"
                        onfocus="this.style.borderColor='#8B4557'; this.style.boxShadow='0 0 0 3px rgba(139,69,87,0.08)'"
                        onblur="this.style.borderColor='#EFE0E3'; this.style.boxShadow='none'"
                    >
                        <option value="">Semua Kategori</option>
                        <option v-for="kat in kategoriList" :key="kat" :value="kat">{{ kat }}</option>
                    </select>

                    <!-- Info count -->
                    <span class="text-xs" style="color: #B8A0A6;">
                        <span class="font-semibold" style="color: #8B4557;">{{ filteredProduk.length }}</span> produk
                    </span>
                </div>

                <button @click="openModal"
                    class="flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold text-white transition-all duration-200"
                    style="background: linear-gradient(135deg, #8B4557, #6B3344); box-shadow: 0 2px 8px rgba(139,69,87,0.25);"
                    @mouseenter="$event.currentTarget.style.opacity='0.9'"
                    @mouseleave="$event.currentTarget.style.opacity='1'"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Produk
                </button>
            </div>

            <!-- PRODUCT GRID -->
            <div v-if="filteredProduk.length > 0" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

                <div v-for="item in filteredProduk" :key="item.id"
                    class="group relative rounded-xl border bg-white transition-all duration-200"
                    style="border-color: #F3E0E4; box-shadow: 0 1px 2px rgba(92,45,56,0.04);"
                    @mouseenter="$event.currentTarget.style.boxShadow='0 8px 24px rgba(139,69,87,0.1)'; $event.currentTarget.style.transform='translateY(-2px)'"
                    @mouseleave="$event.currentTarget.style.boxShadow='0 1px 2px rgba(92,45,56,0.04)'; $event.currentTarget.style.transform='translateY(0)'"
                >

                    <!-- Delete Confirmation Overlay -->
                    <div v-if="deleteConfirmId === item.id"
                        class="absolute inset-0 z-10 flex flex-col items-center justify-center gap-3 rounded-xl"
                        style="background-color: rgba(253,240,241,0.97); backdrop-filter: blur(4px);"
                    >
                        <svg class="h-8 w-8" style="color: #B04050;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                        <p class="text-sm font-semibold" style="color: #B04050;">Hapus produk ini?</p>
                        <div class="flex gap-2">
                            <button @click="confirmDelete(item.id)"
                                class="rounded-lg px-4 py-1.5 text-xs font-semibold text-white transition-colors"
                                style="background-color: #B04050;"
                                onmouseenter="this.style.backgroundColor='#963545'"
                                onmouseleave="this.style.backgroundColor='#B04050'"
                            >Ya, Hapus</button>
                            <button @click="cancelDelete"
                                class="rounded-lg border px-4 py-1.5 text-xs font-semibold transition-colors"
                                style="border-color: #EFE0E3; color: #6B4A50; background: white;"
                                onmouseenter="this.style.backgroundColor='#FDF8F9'"
                                onmouseleave="this.style.backgroundColor='white'"
                            >Batal</button>
                        </div>
                    </div>

                    <!-- Image -->
                    <div class="relative h-44 overflow-hidden" style="background-color: #FDF8F9;">
                        <img v-if="getFotoUrl(item)" :src="getFotoUrl(item)" :alt="item.nama_produk"
                            class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                        />
                        <div v-else class="flex h-full w-full items-center justify-center">
                            <svg class="h-12 w-12" style="color: #E0CDD2;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z" />
                            </svg>
                        </div>
                        <!-- Kategori badge -->
                        <span v-if="item.kategori"
                            class="absolute left-3 top-3 rounded-md px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide"
                            style="background-color: rgba(255,255,255,0.9); color: #8B4557; backdrop-filter: blur(4px);"
                        >{{ item.kategori }}</span>
                    </div>

                    <!-- Info -->
                    <div class="p-4">
                        <h3 class="truncate text-sm font-bold" style="color: #5C2D38;" :title="item.nama_produk">
                            {{ item.nama_produk }}
                        </h3>
                        <p class="mt-1 text-lg font-bold" style="color: #8B4557;">
                            Rp {{ Number(item.harga).toLocaleString('id-ID') }}
                        </p>

                        <!-- Stok -->
                        <div class="mt-2.5 flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 rounded-md px-2 py-0.5 text-[11px] font-semibold"
                                :style="`background-color: ${stokInfo(item.stok).bg}; color: ${stokInfo(item.stok).color};`"
                            >
                                <span class="h-1.5 w-1.5 rounded-full" :style="`background-color: ${stokInfo(item.stok).color};`"></span>
                                Stok: {{ item.stok }} · {{ stokInfo(item.stok).label }}
                            </span>
                        </div>

                        <!-- Actions -->
                        <div class="mt-3 flex items-center gap-1 border-t pt-3" style="border-color: #F8F0F2;">
                            <Link :href="route('produk.show', item.id)"
                                class="flex h-8 w-8 items-center justify-center rounded-lg transition-colors duration-150"
                                style="color: #A07882;"
                                onmouseenter="this.style.backgroundColor='#F3E0E4'; this.style.color='#8B4557'"
                                onmouseleave="this.style.backgroundColor='transparent'; this.style.color='#A07882'"
                                title="Lihat"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </Link>
                            <Link :href="route('produk.edit', item.id)"
                                class="flex h-8 w-8 items-center justify-center rounded-lg transition-colors duration-150"
                                style="color: #A07882;"
                                onmouseenter="this.style.backgroundColor='#F3E0E4'; this.style.color='#8B4557'"
                                onmouseleave="this.style.backgroundColor='transparent'; this.style.color='#A07882'"
                                title="Edit"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </Link>
                            <button @click="requestDelete(item.id)"
                                class="flex h-8 w-8 items-center justify-center rounded-lg transition-colors duration-150"
                                style="color: #A07882;"
                                onmouseenter="this.style.backgroundColor='#FDF0F1'; this.style.color='#B04050'"
                                onmouseleave="this.style.backgroundColor='transparent'; this.style.color='#A07882'"
                                title="Hapus"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- EMPTY STATE -->
            <div v-else
                class="flex flex-col items-center rounded-xl border bg-white px-6 py-16"
                style="border-color: #F3E0E4; box-shadow: 0 1px 2px rgba(92,45,56,0.04);"
            >
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl" style="background-color: #F9EEF0;">
                    <svg class="h-8 w-8" style="color: #D4A0AB;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                </div>
                <h4 class="mt-4 text-sm font-bold" style="color: #5C2D38;">{{ search || filterKategori ? 'Produk tidak ditemukan' : 'Belum ada produk' }}</h4>
                <p class="mt-1 text-xs" style="color: #B8A0A6;">{{ search || filterKategori ? 'Coba filter lain.' : 'Tambahkan produk pertama kamu.' }}</p>
                <button v-if="!search && !filterKategori" @click="openModal"
                    class="mt-4 rounded-lg px-4 py-2 text-xs font-semibold text-white transition-all duration-200"
                    style="background: linear-gradient(135deg, #8B4557, #6B3344);"
                >+ Tambah Produk</button>
            </div>
        </div>

        <!-- ============================================= -->
        <!-- MODAL TAMBAH PRODUK                          -->
        <!-- ============================================= -->
        <Teleport to="body">
            <Transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div v-if="showModal" class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto bg-black/40 px-4 py-8" @click.self="showModal = false">
                    <Transition
                        enter-active-class="transition duration-200 ease-out"
                        enter-from-class="opacity-0 translate-y-4 scale-95"
                        enter-to-class="opacity-100 translate-y-0 scale-100"
                        leave-active-class="transition duration-150 ease-in"
                        leave-from-class="opacity-100 translate-y-0 scale-100"
                        leave-to-class="opacity-0 translate-y-4 scale-95"
                    >
                        <div v-if="showModal" class="w-full max-w-xl rounded-xl border bg-white shadow-2xl" style="border-color: #F3E0E4;">

                            <!-- Modal Header -->
                            <div class="flex items-center justify-between border-b px-5 py-4" style="border-color: #F8F0F2;">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-lg" style="background: linear-gradient(135deg, #8B4557, #B06878);">
                                        <svg class="h-4.5 w-4.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-bold" style="color: #5C2D38;">Tambah Produk</h3>
                                        <p class="text-xs" style="color: #A07882;">Lengkapi data produk</p>
                                    </div>
                                </div>
                                <button @click="showModal = false"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg transition-colors"
                                    style="color: #B8A0A6;"
                                    onmouseenter="this.style.backgroundColor='#FDF8F9'"
                                    onmouseleave="this.style.backgroundColor='transparent'"
                                >
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Modal Body -->
                            <div class="max-h-[70vh] overflow-y-auto p-5">
                                <div class="space-y-4">

                                    <!-- FOTO SECTION -->
                                    <div>
                                        <label class="mb-2 block text-xs font-semibold" style="color: #6B4A50;">Foto Produk</label>

                                        <!-- Tabs -->
                                        <div class="mb-3 inline-flex rounded-lg p-0.5" style="background-color: #F8F0F2;">
                                            <button @click="switchFotoType('file')"
                                                class="flex items-center gap-1.5 rounded-md px-3 py-1.5 text-xs font-semibold transition-all duration-200"
                                                :style="fotoType === 'file' ? 'background-color: white; color: #8B4557; box-shadow: 0 1px 3px rgba(92,45,56,0.1);' : 'color: #A07882;'"
                                            >
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                                </svg>
                                                Upload File
                                            </button>
                                            <button @click="switchFotoType('url')"
                                                class="flex items-center gap-1.5 rounded-md px-3 py-1.5 text-xs font-semibold transition-all duration-200"
                                                :style="fotoType === 'url' ? 'background-color: white; color: #8B4557; box-shadow: 0 1px 3px rgba(92,45,56,0.1);' : 'color: #A07882;'"
                                            >
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                                                </svg>
                                                URL Link
                                            </button>
                                        </div>

                                        <!-- File Upload Area -->
                                        <div v-if="fotoType === 'file'">
                                            <input ref="fileInput" type="file" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" @change="onFileChange" />
                                            <div v-if="!previewUrl"
                                                @click="fileInput?.click()"
                                                class="flex cursor-pointer flex-col items-center gap-2 rounded-lg border-2 border-dashed p-6 transition-colors"
                                                style="border-color: #EFE0E3; background-color: #FDF8F9;"
                                                @mouseenter="$event.currentTarget.style.borderColor='#8B4557'; $event.currentTarget.style.backgroundColor='#FBF0F2'"
                                                @mouseleave="$event.currentTarget.style.borderColor='#EFE0E3'; $event.currentTarget.style.backgroundColor='#FDF8F9'"
                                            >
                                                <svg class="h-8 w-8" style="color: #D4A0AB;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z" />
                                                </svg>
                                                <p class="text-xs font-medium" style="color: #A07882;">Klik untuk pilih foto</p>
                                                <p class="text-[11px]" style="color: #C4A0A8;">JPG, PNG, Webp (maks 2MB)</p>
                                            </div>
                                        </div>

                                        <!-- URL Input -->
                                        <div v-if="fotoType === 'url' && !previewUrl">
                                            <input v-model="form.foto_url" type="url" placeholder="https://contoh.com/foto-produk.jpg"
                                                class="w-full rounded-lg border bg-white px-3 py-2.5 text-sm outline-none transition-all duration-200"
                                                style="border-color: #EFE0E3; color: #5C2D38;"
                                                onfocus="this.style.borderColor='#8B4557'; this.style.boxShadow='0 0 0 3px rgba(139,69,87,0.08)'"
                                                onblur="this.style.borderColor='#EFE0E3'; this.style.boxShadow='none'"
                                            />
                                        </div>

                                        <!-- Preview -->
                                        <div v-if="previewUrl" class="relative">
                                            <img :src="previewUrl" alt="Preview" class="h-40 w-full rounded-lg object-cover" style="background-color: #FDF8F9;" />
                                            <button @click="clearPreview"
                                                class="absolute right-2 top-2 flex h-7 w-7 items-center justify-center rounded-lg bg-white/90 text-xs shadow-sm transition-colors"
                                                style="color: #B04050; backdrop-filter: blur(4px);"
                                                onmouseenter="this.style.backgroundColor='white'"
                                                onmouseleave="this.style.backgroundColor='rgba(255,255,255,0.9)'"
                                                title="Hapus foto"
                                            >
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>

                                        <p v-if="form.errors.foto" class="mt-1 text-xs font-medium" style="color: #B04050;">{{ form.errors.foto }}</p>
                                    </div>

                                    <!-- NAMA -->
                                    <div>
                                        <label class="mb-1.5 block text-xs font-semibold" style="color: #6B4A50;">Nama Produk</label>
                                        <input v-model="form.nama_produk" type="text" placeholder="Contoh: Kopi Arabica 250g"
                                            class="w-full rounded-lg border bg-white px-3 py-2.5 text-sm outline-none transition-all duration-200"
                                            style="border-color: #EFE0E3; color: #5C2D38;"
                                            onfocus="this.style.borderColor='#8B4557'; this.style.boxShadow='0 0 0 3px rgba(139,69,87,0.08)'"
                                            onblur="this.style.borderColor='#EFE0E3'; this.style.boxShadow='none'"
                                        />
                                        <p v-if="form.errors.nama_produk" class="mt-1 text-xs font-medium" style="color: #B04050;">{{ form.errors.nama_produk }}</p>
                                    </div>

                                    <!-- HARGA + STOK -->
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="mb-1.5 block text-xs font-semibold" style="color: #6B4A50;">Harga (Rp)</label>
                                            <input v-model="form.harga" type="number" min="0" placeholder="50000"
                                                class="w-full rounded-lg border bg-white px-3 py-2.5 text-sm outline-none transition-all duration-200"
                                                style="border-color: #EFE0E3; color: #5C2D38;"
                                                onfocus="this.style.borderColor='#8B4557'; this.style.boxShadow='0 0 0 3px rgba(139,69,87,0.08)'"
                                                onblur="this.style.borderColor='#EFE0E3'; this.style.boxShadow='none'"
                                            />
                                            <p v-if="form.errors.harga" class="mt-1 text-xs font-medium" style="color: #B04050;">{{ form.errors.harga }}</p>
                                        </div>
                                        <div>
                                            <label class="mb-1.5 block text-xs font-semibold" style="color: #6B4A50;">Stok</label>
                                            <input v-model="form.stok" type="number" min="0" placeholder="10"
                                                class="w-full rounded-lg border bg-white px-3 py-2.5 text-sm outline-none transition-all duration-200"
                                                style="border-color: #EFE0E3; color: #5C2D38;"
                                                onfocus="this.style.borderColor='#8B4557'; this.style.boxShadow='0 0 0 3px rgba(139,69,87,0.08)'"
                                                onblur="this.style.borderColor='#EFE0E3'; this.style.boxShadow='none'"
                                            />
                                            <p v-if="form.errors.stok" class="mt-1 text-xs font-medium" style="color: #B04050;">{{ form.errors.stok }}</p>
                                        </div>
                                    </div>

                                    <!-- KATEGORI -->
                                    <div>
                                        <label class="mb-1.5 block text-xs font-semibold" style="color: #6B4A50;">Kategori</label>
                                        <input v-model="form.kategori" type="text" placeholder="Contoh: Minuman"
                                            class="w-full rounded-lg border bg-white px-3 py-2.5 text-sm outline-none transition-all duration-200"
                                            style="border-color: #EFE0E3; color: #5C2D38;"
                                            onfocus="this.style.borderColor='#8B4557'; this.style.boxShadow='0 0 0 3px rgba(139,69,87,0.08)'"
                                            onblur="this.style.borderColor='#EFE0E3'; this.style.boxShadow='none'"
                                        />
                                    </div>

                                    <!-- DESKRIPSI -->
                                    <div>
                                        <label class="mb-1.5 block text-xs font-semibold" style="color: #6B4A50;">Deskripsi</label>
                                        <textarea v-model="form.deskripsi" rows="3" placeholder="Deskripsi produk (opsional)"
                                            class="w-full rounded-lg border bg-white px-3 py-2.5 text-sm outline-none transition-all duration-200 resize-none"
                                            style="border-color: #EFE0E3; color: #5C2D38;"
                                            onfocus="this.style.borderColor='#8B4557'; this.style.boxShadow='0 0 0 3px rgba(139,69,87,0.08)'"
                                            onblur="this.style.borderColor='#EFE0E3'; this.style.boxShadow='none'"
                                        ></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Footer -->
                            <div class="flex items-center justify-end gap-2 border-t px-5 py-4" style="border-color: #F8F0F2;">
                                <button @click="showModal = false"
                                    class="rounded-lg border px-4 py-2 text-sm font-semibold transition-colors"
                                    style="border-color: #EFE0E3; color: #6B4A50; background: white;"
                                    onmouseenter="this.style.backgroundColor='#FDF8F9'"
                                    onmouseleave="this.style.backgroundColor='white'"
                                >Batal</button>
                                <button @click="tambahProduk" :disabled="form.processing"
                                    class="flex items-center gap-2 rounded-lg px-5 py-2 text-sm font-semibold text-white transition-all duration-200 disabled:cursor-not-allowed disabled:opacity-50"
                                    style="background: linear-gradient(135deg, #8B4557, #6B3344); box-shadow: 0 2px 8px rgba(139,69,87,0.25);"
                                >
                                    <svg v-if="form.processing" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                    <svg v-else class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                    {{ form.processing ? 'Menyimpan...' : 'Simpan Produk' }}
                                </button>
                            </div>
                        </div>
                    </Transition>
                </div>
            </Transition>
        </Teleport>

    </AuthenticatedLayout>
</template>