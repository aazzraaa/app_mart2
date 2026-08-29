<script setup>
import { computed, ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';

const props = defineProps({
    pelangganList: {
        type: Array,
        default: () => [],
    },
});

const showForm = ref(false);
const search = ref('');
const deleteConfirmId = ref(null);

const form = useForm({
    nama_pelanggan: '',
    kota: '',
});

const filteredPelanggan = computed(() => {
    const keyword = search.value.toLowerCase().trim();
    if (!keyword) return props.pelangganList;
    return props.pelangganList.filter((p) => {
        return (
            p.nama_pelanggan?.toLowerCase().includes(keyword) ||
            p.kota?.toLowerCase().includes(keyword)
        );
    });
});

const totalKota = computed(() => {
    return new Set(props.pelangganList.map((p) => p.kota).filter(Boolean)).size;
});

const tambahPelanggan = () => {
    form.post(route('pelanggan.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            showForm.value = false;
        },
    });
};

function requestDelete(id) {
    deleteConfirmId.value = id;
}

function cancelDelete() {
    deleteConfirmId.value = null;
}

function confirmDelete(id) {
    router.delete(route('pelanggan.destroy', id), {
        preserveScroll: true,
        onSuccess: () => {
            deleteConfirmId.value = null;
        },
    });
}
</script>

<template>
    <Head title="Data Pelanggan" />

    <AuthenticatedLayout>

        <!-- HEADER -->
        <template #header>
            <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-2 text-xs" style="color: #B8A0A6;">
                        <span>Dashboard</span>
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                        <span style="color: #8B4557;">Pelanggan</span>
                    </div>
                    <h2 class="mt-1 text-xl font-bold" style="color: #5C2D38;">Data Pelanggan</h2>
                    <p class="text-sm" style="color: #A07882;">Kelola data pelanggan toko kamu</p>
                </div>
            </div>
        </template>

        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

            <!-- Toast Success -->
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

            <!-- MAIN CARD -->
            <div
                class="overflow-hidden rounded-xl border bg-white"
                style="border-color: #F3E0E4; box-shadow: 0 1px 2px rgba(92,45,56,0.04);"
            >

                <!-- TOOLBAR -->
                <div class="flex flex-col gap-4 border-b px-5 py-4 sm:flex-row sm:items-center sm:justify-between" style="border-color: #F8F0F2;">
                    <div>
                        <h3 class="text-base font-bold" style="color: #5C2D38;">Daftar Pelanggan</h3>
                        <div class="mt-0.5 flex items-center gap-3 text-xs" style="color: #B8A0A6;">
                            <span class="font-semibold" style="color: #8B4557;">{{ pelangganList.length }}</span>
                            <span>pelanggan</span>
                            <span>·</span>
                            <span class="font-semibold" style="color: #8B4557;">{{ totalKota }}</span>
                            <span>kota</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <div class="relative">
                            <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2" style="color: #C4A0A8;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                            <input
                                v-model="search"
                                type="text"
                                placeholder="Cari nama atau kota..."
                                class="w-full rounded-lg border bg-white py-2 pl-9 pr-3 text-sm outline-none transition-all duration-200 sm:w-64"
                                style="border-color: #EFE0E3; color: #5C2D38;"
                                onfocus="this.style.borderColor='#8B4557'; this.style.boxShadow='0 0 0 3px rgba(139,69,87,0.08)'"
                                onblur="this.style.borderColor='#EFE0E3'; this.style.boxShadow='none'"
                            />
                        </div>
                        <button
                            @click="showForm = !showForm"
                            class="flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold text-white transition-all duration-200"
                            :style="showForm ? 'background-color: #5C2D38;' : 'background: linear-gradient(135deg, #8B4557, #6B3344); box-shadow: 0 2px 8px rgba(139,69,87,0.25);'"
                            @mouseenter="$event.currentTarget.style.opacity='0.9'"
                            @mouseleave="$event.currentTarget.style.opacity='1'"
                        >
                            <svg v-if="!showForm" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            <svg v-else class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span class="hidden sm:inline">{{ showForm ? 'Tutup' : 'Tambah' }}</span>
                        </button>
                    </div>
                </div>

                <!-- FORM -->
                <Transition
                    enter-active-class="transition-all duration-300 ease-out"
                    enter-from-class="max-h-0 opacity-0"
                    enter-to-class="max-h-96 opacity-100"
                    leave-active-class="transition-all duration-200 ease-in"
                    leave-from-class="max-h-96 opacity-100"
                    leave-to-class="max-h-0 opacity-0"
                >
                    <div v-if="showForm" class="overflow-hidden border-b" style="border-color: #F8F0F2; background-color: #FDF8F9;">
                        <div class="px-5 py-5">
                            <div class="mb-4 flex items-center gap-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg" style="background: linear-gradient(135deg, #8B4557, #B06878);">
                                    <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold" style="color: #5C2D38;">Pelanggan Baru</h4>
                                    <p class="text-xs" style="color: #A07882;">Isi data di bawah untuk menambahkan</p>
                                </div>
                            </div>
                            <form @submit.prevent="tambahPelanggan" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                                <div>
                                    <label class="mb-1.5 block text-xs font-semibold" style="color: #6B4A50;">Nama Pelanggan</label>
                                    <input v-model="form.nama_pelanggan" type="text" placeholder="Budi Santoso"
                                        class="w-full rounded-lg border bg-white px-3 py-2.5 text-sm outline-none transition-all duration-200"
                                        style="border-color: #EFE0E3; color: #5C2D38;"
                                        onfocus="this.style.borderColor='#8B4557'; this.style.boxShadow='0 0 0 3px rgba(139,69,87,0.08)'"
                                        onblur="this.style.borderColor='#EFE0E3'; this.style.boxShadow='none'"
                                    />
                                    <p v-if="form.errors.nama_pelanggan" class="mt-1 text-xs font-medium" style="color: #B04050;">{{ form.errors.nama_pelanggan }}</p>
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-xs font-semibold" style="color: #6B4A50;">Kota</label>
                                    <input v-model="form.kota" type="text" placeholder="Jakarta"
                                        class="w-full rounded-lg border bg-white px-3 py-2.5 text-sm outline-none transition-all duration-200"
                                        style="border-color: #EFE0E3; color: #5C2D38;"
                                        onfocus="this.style.borderColor='#8B4557'; this.style.boxShadow='0 0 0 3px rgba(139,69,87,0.08)'"
                                        onblur="this.style.borderColor='#EFE0E3'; this.style.boxShadow='none'"
                                    />
                                    <p v-if="form.errors.kota" class="mt-1 text-xs font-medium" style="color: #B04050;">{{ form.errors.kota }}</p>
                                </div>
                                <div class="flex items-end sm:col-span-2">
                                    <button type="submit" :disabled="form.processing"
                                        class="flex items-center gap-2 rounded-lg px-5 py-2.5 text-sm font-semibold text-white transition-all duration-200 disabled:cursor-not-allowed disabled:opacity-50"
                                        style="background: linear-gradient(135deg, #8B4557, #6B3344); box-shadow: 0 2px 8px rgba(139,69,87,0.25);"
                                    >
                                        <svg v-if="form.processing" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                        </svg>
                                        <svg v-else class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                        {{ form.processing ? 'Menyimpan...' : 'Simpan Pelanggan' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </Transition>

                <!-- TABLE -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr style="background-color: #FDF8F9;">
                                <th class="whitespace-nowrap px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider" style="color: #A07882;">Pelanggan</th>
                                <th class="whitespace-nowrap px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider" style="color: #A07882;">Kota</th>
                                <th class="whitespace-nowrap px-5 py-3 text-center text-[11px] font-bold uppercase tracking-wider" style="color: #A07882;">Status</th>
                                <th class="whitespace-nowrap px-5 py-3 text-right text-[11px] font-bold uppercase tracking-wider" style="color: #A07882;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="pelanggan in filteredPelanggan" :key="pelanggan.id_pelanggan">

                                <!-- Normal Row -->
                                <tr v-if="deleteConfirmId !== pelanggan.id_pelanggan"
                                    class="transition-colors duration-150"
                                    style="border-bottom: 1px solid #F8F0F2;"
                                    onmouseenter="this.style.backgroundColor='#FDF8F9'"
                                    onmouseleave="this.style.backgroundColor='transparent'"
                                >
                                    <td class="whitespace-nowrap px-5 py-3.5">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-xs font-bold text-white"
                                                :style="`background: linear-gradient(135deg, ${pelanggan.id_pelanggan % 3 === 0 ? '#8B4557' : pelanggan.id_pelanggan % 3 === 1 ? '#7B4F8A' : '#4A7B9B'}, ${pelanggan.id_pelanggan % 3 === 0 ? '#B06878' : pelanggan.id_pelanggan % 3 === 1 ? '#9B6FAA' : '#6B9BBB'});`"
                                            >
                                                {{ pelanggan.nama_pelanggan?.charAt(0)?.toUpperCase() }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold" style="color: #5C2D38;">{{ pelanggan.nama_pelanggan }}</p>
                                                <p class="text-[11px]" style="color: #C4A0A8;">#{{ pelanggan.id_pelanggan }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-3.5">
                                        <span class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1 text-xs font-medium" style="background-color: #F3E8EC; color: #8B4557;">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                            </svg>
                                            {{ pelanggan.kota }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-3.5 text-center">
                                        <span class="inline-flex items-center gap-1.5 text-xs font-medium" style="color: #4A8B5C;">
                                            <span class="h-1.5 w-1.5 rounded-full" style="background-color: #4A8B5C;"></span>
                                            Aktif
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-3.5 text-right">
                                        <div class="flex items-center justify-end gap-1">

                                            <!-- LIHAT -->
                                            <Link :href="route('pelanggan.show', pelanggan.id_pelanggan)"
                                                class="flex h-8 w-8 items-center justify-center rounded-lg transition-colors duration-150"
                                                style="color: #A07882;"
                                                onmouseenter="this.style.backgroundColor='#F3E0E4'; this.style.color='#8B4557'"
                                                onmouseleave="this.style.backgroundColor='transparent'; this.style.color='#A07882'"
                                                title="Lihat Detail"
                                            >
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </Link>

                                            <!-- EDIT -->
                                            <Link :href="route('pelanggan.edit', pelanggan.id_pelanggan)"
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

                                            <!-- DELETE -->
                                            <button @click="requestDelete(pelanggan.id_pelanggan)"
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
                                    </td>
                                </tr>

                                <!-- Delete Confirmation Row -->
                                <tr v-else class="transition-colors duration-150" style="border-bottom: 1px solid #F8F0F2; background-color: #FDF0F1;">
                                    <td colspan="4" class="px-5 py-3">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2 text-sm" style="color: #B04050;">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                                </svg>
                                                <span class="font-medium">Hapus <strong>{{ pelanggan.nama_pelanggan }}</strong>?</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button @click="confirmDelete(pelanggan.id_pelanggan)"
                                                    class="rounded-lg px-3 py-1.5 text-xs font-semibold text-white transition-all duration-150"
                                                    style="background-color: #B04050;"
                                                    onmouseenter="this.style.backgroundColor='#963545'"
                                                    onmouseleave="this.style.backgroundColor='#B04050'"
                                                >Ya, Hapus</button>
                                                <button @click="cancelDelete"
                                                    class="rounded-lg border px-3 py-1.5 text-xs font-semibold transition-all duration-150"
                                                    style="border-color: #EFE0E3; color: #6B4A50; background-color: white;"
                                                    onmouseenter="this.style.backgroundColor='#FDF8F9'"
                                                    onmouseleave="this.style.backgroundColor='white'"
                                                >Batal</button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </template>

                            

                            <!-- EMPTY STATE -->
                            <tr v-if="filteredPelanggan.length === 0">
                                <td colspan="4" class="px-5 py-16">
                                    <div class="flex flex-col items-center">
                                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl" style="background-color: #F9EEF0;">
                                            <svg class="h-8 w-8" style="color: #D4A0AB;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                            </svg>
                                        </div>
                                        <h4 class="mt-4 text-sm font-bold" style="color: #5C2D38;">{{ search ? 'Pelanggan tidak ditemukan' : 'Belum ada pelanggan' }}</h4>
                                        <p class="mt-1 text-xs" style="color: #B8A0A6;">{{ search ? 'Coba kata kunci lain.' : 'Tambahkan pelanggan pertama kamu.' }}</p>
                                        <button v-if="!search" @click="showForm = true"
                                            class="mt-4 rounded-lg px-4 py-2 text-xs font-semibold text-white transition-all duration-200"
                                            style="background: linear-gradient(135deg, #8B4557, #6B3344);"
                                        >+ Tambah Pelanggan</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- FOOTER -->
                <div class="flex flex-col gap-2 border-t px-5 py-3 text-xs sm:flex-row sm:items-center sm:justify-between" style="border-color: #F8F0F2; background-color: #FDF8F9;">
                    <span style="color: #B8A0A6;">
                        Menampilkan <span class="font-semibold" style="color: #5C2D38;">{{ filteredPelanggan.length }}</span> dari <span class="font-semibold" style="color: #5C2D38;">{{ pelangganList.length }}</span> pelanggan
                        <span v-if="search" style="color: #8B4557;">(filter aktif)</span>
                    </span>
                    <span style="color: #C4A0A8;">Sistem Manajemen Toko</span>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>