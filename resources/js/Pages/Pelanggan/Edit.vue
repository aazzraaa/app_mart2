<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    pelanggan: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    nama_pelanggan: props.pelanggan.nama_pelanggan || '',
    kota: props.pelanggan.kota || '',
});

function updatePelanggan() {
    form.put(route('pelanggan.update', props.pelanggan.id_pelanggan), {
        onSuccess: () => {
            // redirect otomatis oleh controller
        },
    });
}
</script>

<template>
    <Head :title="`Edit - ${pelanggan.nama_pelanggan}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-2 text-xs" style="color: #B8A0A6;">
                        <Link :href="route('pelanggan.index')" class="transition-colors hover:underline" style="color: #8B4557;">Pelanggan</Link>
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                        <Link :href="route('pelanggan.show', pelanggan.id_pelanggan)" class="transition-colors hover:underline" style="color: #8B4557;">{{ pelanggan.nama_pelanggan }}</Link>
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                        <span style="color: #5C2D38;">Edit</span>
                    </div>
                    <h2 class="mt-1 text-xl font-bold" style="color: #5C2D38;">Edit Pelanggan</h2>
                    <p class="text-sm" style="color: #A07882;">Ubah informasi pelanggan</p>
                </div>
                <Link
                    :href="route('pelanggan.show', pelanggan.id_pelanggan)"
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
        </template>

        <div class="mx-auto max-w-2xl px-4 py-6 sm:px-6 lg:px-8">

            <!-- FORM CARD -->
            <div
                class="overflow-hidden rounded-xl border bg-white"
                style="border-color: #F3E0E4; box-shadow: 0 1px 2px rgba(92,45,56,0.04);"
            >
                <!-- Card Header -->
                <div class="flex items-center gap-3 border-b px-5 py-4" style="border-color: #F8F0F2;">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl" style="background: linear-gradient(135deg, #8B4557, #B06878);">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold" style="color: #5C2D38;">Form Edit Pelanggan</h3>
                        <p class="text-xs" style="color: #A07882;">Ubah data lama dengan data baru</p>
                    </div>
                </div>

                <!-- Form Body -->
                <div class="p-5">
                    <!-- Current Info Hint -->
                    <div
                        class="mb-5 flex items-center gap-2.5 rounded-lg px-3.5 py-2.5"
                        style="background-color: #FDF8F9;"
                    >
                        <svg class="h-4 w-4 shrink-0" style="color: #8B4557;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                        <p class="text-xs" style="color: #8B7078;">
                            Mengedit pelanggan <strong style="color: #5C2D38;">{{ pelanggan.nama_pelanggan }}</strong> (#{{ pelanggan.id_pelanggan }})
                        </p>
                    </div>

                    <form @submit.prevent="updatePelanggan" class="space-y-5">
                        <!-- Nama -->
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold" style="color: #6B4A50;">Nama Pelanggan</label>
                            <input
                                v-model="form.nama_pelanggan"
                                type="text"
                                class="w-full rounded-lg border bg-white px-3.5 py-3 text-sm outline-none transition-all duration-200"
                                style="border-color: #EFE0E3; color: #5C2D38;"
                                onfocus="this.style.borderColor='#8B4557'; this.style.boxShadow='0 0 0 3px rgba(139,69,87,0.08)'"
                                onblur="this.style.borderColor='#EFE0E3'; this.style.boxShadow='none'"
                            />
                            <p v-if="form.errors.nama_pelanggan" class="mt-1.5 text-xs font-medium" style="color: #B04050;">
                                {{ form.errors.nama_pelanggan }}
                            </p>
                        </div>

                        <!-- Kota -->
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold" style="color: #6B4A50;">Kota</label>
                            <input
                                v-model="form.kota"
                                type="text"
                                class="w-full rounded-lg border bg-white px-3.5 py-3 text-sm outline-none transition-all duration-200"
                                style="border-color: #EFE0E3; color: #5C2D38;"
                                onfocus="this.style.borderColor='#8B4557'; this.style.boxShadow='0 0 0 3px rgba(139,69,87,0.08)'"
                                onblur="this.style.borderColor='#EFE0E3'; this.style.boxShadow='none'"
                            />
                            <p v-if="form.errors.kota" class="mt-1.5 text-xs font-medium" style="color: #B04050;">
                                {{ form.errors.kota }}
                            </p>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-3 pt-3" style="border-top: 1px solid #F8F0F2;">
                            <Link
                                :href="route('pelanggan.show', pelanggan.id_pelanggan)"
                                class="rounded-lg border px-4 py-2.5 text-sm font-semibold transition-all duration-200"
                                style="border-color: #EFE0E3; color: #6B4A50; background-color: white;"
                                onmouseenter="this.style.backgroundColor='#FDF8F9'"
                                onmouseleave="this.style.backgroundColor='white'"
                            >
                                Batal
                            </Link>
                            <button
                                type="submit"
                                :disabled="form.processing"
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
                                {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danger Zone -->
            <div
                class="mt-5 overflow-hidden rounded-xl border bg-white"
                style="border-color: #F8E0E0;"
            >
                <div class="px-5 py-4">
                    <h4 class="text-sm font-bold" style="color: #B04050;">Zona Berbahaya</h4>
                    <p class="mt-0.5 text-xs" style="color: #A07882;">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="border-t px-5 py-3 flex items-center justify-between" style="border-color: #FDF0F1; background-color: #FDF8F9;">
                    <div>
                        <p class="text-sm font-medium" style="color: #5C2D38;">Hapus pelanggan ini</p>
                        <p class="text-xs" style="color: #B8A0A6;">Data akan dihapus permanen dari sistem.</p>
                    </div>
                    <Link
                        :href="route('pelanggan.destroy', pelanggan.id_pelanggan)"
                        method="delete"
                        as="button"
                        class="shrink-0 rounded-lg border px-3.5 py-2 text-xs font-semibold transition-all duration-200"
                        style="border-color: #F0C8CC; color: #B04050; background-color: white;"
                        onmouseenter="this.style.backgroundColor='#FDF0F1'; this.style.borderColor='#E8B0B8'"
                        onmouseleave="this.style.backgroundColor='white'; this.style.borderColor='#F0C8CC'"
                    >
                        Hapus
                    </Link>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>