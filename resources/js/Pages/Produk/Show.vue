<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    produk: Object,
});

function getFotoUrl() {
    if (!props.produk.foto) return null;
    if (props.produk.foto.startsWith('http')) return props.produk.foto;
    return '/storage/' + props.produk.foto;
}

function stokInfo(stok) {
    if (stok <= 0) return { label: 'Habis', color: '#B04050', bg: '#FDF0F1' };
    if (stok <= 5) return { label: 'Menipis', color: '#C4853A', bg: '#FDF3E7' };
    return { label: 'Tersedia', color: '#4A8B5C', bg: '#E8F5EC' };
}
</script>

<template>
    <Head :title="`Detail - ${produk.nama_produk}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-2 text-xs" style="color: #B8A0A6;">
                        <Link :href="route('produk.index')" class="transition-colors hover:underline" style="color: #8B4557;">Produk</Link>
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                        <span style="color: #5C2D38;">{{ produk.nama_produk }}</span>
                    </div>
                    <h2 class="mt-1 text-xl font-bold" style="color: #5C2D38;">Detail Produk</h2>
                </div>
                <div class="flex items-center gap-2">
                    <Link :href="route('produk.edit', produk.id)"
                        class="flex items-center gap-2 rounded-lg border px-4 py-2 text-sm font-semibold transition-all duration-200"
                        style="border-color: #F3E0E4; color: #8B4557; background-color: white;"
                        onmouseenter="this.style.backgroundColor='#FBF0F2'"
                        onmouseleave="this.style.backgroundColor='white'"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" />
                        </svg>
                        Edit
                    </Link>
                    <Link :href="route('produk.index')"
                        class="flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold transition-all duration-200"
                        style="color: #A07882;"
                        onmouseenter="this.style.backgroundColor='#FDF8F9'"
                        onmouseleave="this.style.backgroundColor='transparent'"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Kembali
                    </Link>
                </div>
            </div>
        </template>

        <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8">

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

            <div class="overflow-hidden rounded-xl border bg-white" style="border-color: #F3E0E4; box-shadow: 0 1px 2px rgba(92,45,56,0.04);">

                <!-- Image -->
                <div class="h-64 overflow-hidden" style="background-color: #FDF8F9;">
                    <img v-if="getFotoUrl()" :src="getFotoUrl()" :alt="produk.nama_produk" class="h-full w-full object-cover" />
                    <div v-else class="flex h-full w-full items-center justify-center">
                        <svg class="h-16 w-16" style="color: #E0CDD2;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z" />
                        </svg>
                    </div>
                </div>

                <!-- Info -->
                <div class="p-6">
                    <div class="flex flex-wrap items-start gap-3">
                        <h3 class="flex-1 text-xl font-bold" style="color: #5C2D38;">{{ produk.nama_produk }}</h3>
                        <span v-if="produk.kategori" class="rounded-md px-2.5 py-1 text-xs font-bold uppercase tracking-wide" style="background-color: #F3E8EC; color: #8B4557;">{{ produk.kategori }}</span>
                    </div>

                    <p class="mt-2 text-2xl font-bold" style="color: #8B4557;">Rp {{ Number(produk.harga).toLocaleString('id-ID') }}</p>

                    <div class="mt-4">
                        <span class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1 text-xs font-semibold"
                            :style="`background-color: ${stokInfo(produk.stok).bg}; color: ${stokInfo(produk.stok).color};`"
                        >
                            <span class="h-1.5 w-1.5 rounded-full" :style="`background-color: ${stokInfo(produk.stok).color};`"></span>
                            Stok: {{ produk.stok }} · {{ stokInfo(produk.stok).label }}
                        </span>
                    </div>

                    <div v-if="produk.deskripsi" class="mt-5 rounded-lg p-4" style="background-color: #FDF8F9;">
                        <p class="mb-1 text-xs font-semibold" style="color: #A07882;">Deskripsi</p>
                        <p class="text-sm leading-relaxed" style="color: #5C2D38;">{{ produk.deskripsi }}</p>
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-4 sm:grid-cols-3">
                        <div class="rounded-lg p-3" style="background-color: #FDF8F9;">
                            <p class="text-[11px] font-semibold" style="color: #A07882;">ID Produk</p>
                            <p class="mt-0.5 font-mono text-sm font-bold" style="color: #5C2D38;">#{{ produk.id }}</p>
                        </div>
                        <div class="rounded-lg p-3" style="background-color: #FDF8F9;">
                            <p class="text-[11px] font-semibold" style="color: #A07882;">Harga</p>
                            <p class="mt-0.5 text-sm font-bold" style="color: #5C2D38;">Rp {{ Number(produk.harga).toLocaleString('id-ID') }}</p>
                        </div>
                        <div class="rounded-lg p-3" style="background-color: #FDF8F9;">
                            <p class="text-[11px] font-semibold" style="color: #A07882;">Terdaftar</p>
                            <p class="mt-0.5 text-sm font-bold" style="color: #5C2D38;">{{ produk.created_at ? new Date(produk.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>