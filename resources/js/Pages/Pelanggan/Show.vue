<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    pelanggan: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Head :title="`Detail - ${pelanggan.nama_pelanggan}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-2 text-xs" style="color: #B8A0A6;">
                        <Link :href="route('pelanggan.index')" class="transition-colors hover:underline" style="color: #8B4557;">Pelanggan</Link>
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                        <span style="color: #5C2D38;">{{ pelanggan.nama_pelanggan }}</span>
                    </div>
                    <h2 class="mt-1 text-xl font-bold" style="color: #5C2D38;">Detail Pelanggan</h2>
                    <p class="text-sm" style="color: #A07882;">Informasi lengkap pelanggan</p>
                </div>
                <div class="flex items-center gap-2">
                    <Link
                        :href="route('pelanggan.edit', pelanggan.id_pelanggan)"
                        class="flex items-center gap-2 rounded-lg border px-4 py-2 text-sm font-semibold transition-all duration-200"
                        style="border-color: #F3E0E4; color: #8B4557; background-color: white;"
                        onmouseenter="this.style.backgroundColor='#FBF0F2'"
                        onmouseleave="this.style.backgroundColor='white'"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                        Edit
                    </Link>
                    <Link
                        :href="route('pelanggan.index')"
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
            <div
                v-if="$page.props.flash?.success"
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

            <!-- PROFILE CARD -->
            <div
                class="overflow-hidden rounded-xl border bg-white"
                style="border-color: #F3E0E4; box-shadow: 0 1px 2px rgba(92,45,56,0.04);"
            >
                <!-- Header Banner -->
                <div class="h-28 relative" style="background: linear-gradient(135deg, #8B4557 0%, #6B3344 50%, #9B5B63 100%);">
                    <div class="absolute -right-6 -top-6 h-32 w-32 rounded-full" style="background: rgba(255,255,255,0.06);"></div>
                    <div class="absolute -bottom-8 left-6">
                        <div
                            class="flex h-20 w-20 items-center justify-center rounded-2xl text-2xl font-bold text-white border-4 border-white"
                            style="background: linear-gradient(135deg, #8B4557, #B06878); box-shadow: 0 4px 12px rgba(139,69,87,0.3);"
                        >
                            {{ pelanggan.nama_pelanggan?.charAt(0)?.toUpperCase() }}
                        </div>
                    </div>
                </div>

                <!-- Info -->
                <div class="px-6 pt-12 pb-6">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <h3 class="text-xl font-bold" style="color: #5C2D38;">{{ pelanggan.nama_pelanggan }}</h3>
                            <div class="mt-2 flex flex-wrap items-center gap-3">
                                <span class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1 text-xs font-medium" style="background-color: #E8F5EC; color: #4A8B5C;">
                                    <span class="h-1.5 w-1.5 rounded-full" style="background-color: #4A8B5C;"></span>
                                    Aktif
                                </span>
                                <span class="text-xs" style="color: #C4A0A8;">ID: #{{ pelanggan.id_pelanggan }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Grid -->
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-lg p-4" style="background-color: #FDF8F9;">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="h-4 w-4" style="color: #A07882;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                                <span class="text-xs font-semibold" style="color: #A07882;">Nama Pelanggan</span>
                            </div>
                            <p class="text-sm font-bold" style="color: #5C2D38;">{{ pelanggan.nama_pelanggan }}</p>
                        </div>

                        <div class="rounded-lg p-4" style="background-color: #FDF8F9;">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="h-4 w-4" style="color: #A07882;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                                <span class="text-xs font-semibold" style="color: #A07882;">Kota</span>
                            </div>
                            <p class="text-sm font-bold" style="color: #5C2D38;">{{ pelanggan.kota || '-' }}</p>
                        </div>

                        <div class="rounded-lg p-4" style="background-color: #FDF8F9;">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="h-4 w-4" style="color: #A07882;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5l-3.9 19.5m-2.1-19.5l-3.9 19.5" />
                                </svg>
                                <span class="text-xs font-semibold" style="color: #A07882;">ID Pelanggan</span>
                            </div>
                            <p class="font-mono text-sm font-bold" style="color: #5C2D38;">#{{ pelanggan.id_pelanggan }}</p>
                        </div>

                        <div class="rounded-lg p-4" style="background-color: #FDF8F9;">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="h-4 w-4" style="color: #A07882;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                <span class="text-xs font-semibold" style="color: #A07882;">Terdaftar</span>
                            </div>
                            <p class="text-sm font-bold" style="color: #5C2D38;">
                                {{ pelanggan.created_at ? new Date(pelanggan.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>