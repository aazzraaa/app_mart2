<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    nama_pelanggan: '',
    kota: '',
});

const submit = () => {
    form.post(route('pelanggan.store'));
};
</script>

<template>
    <Head title="Tambah Pelanggan" />

    <AuthenticatedLayout>

        <!-- Header -->
        <template #header>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    Tambah Pelanggan
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Tambahkan pelanggan baru ke dalam sistem.
                </p>
            </div>
        </template>

        <!-- Content -->
        <div class="min-h-screen bg-gray-100 py-10">

            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">

                <div class="rounded-2xl bg-white p-8 shadow-sm">

                    <!-- Judul -->
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-800">
                            Informasi Pelanggan
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Isi data pelanggan dengan lengkap.
                        </p>
                    </div>

                    <form @submit.prevent="submit">

                        <!-- Nama -->
                        <div class="mb-6">
                            <label
                                class="mb-2 block text-sm font-semibold text-gray-700"
                            >
                                Nama Pelanggan
                            </label>

                            <input
                                v-model="form.nama_pelanggan"
                                type="text"
                                placeholder="Contoh: Andi Saputra"
                                class="w-full rounded-lg border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />

                            <p
                                v-if="form.errors.nama_pelanggan"
                                class="mt-2 text-sm text-red-500"
                            >
                                {{ form.errors.nama_pelanggan }}
                            </p>
                        </div>

                        <!-- Kota -->
                        <div class="mb-8">
                            <label
                                class="mb-2 block text-sm font-semibold text-gray-700"
                            >
                                Kota
                            </label>

                            <input
                                v-model="form.kota"
                                type="text"
                                placeholder="Contoh: Malang"
                                class="w-full rounded-lg border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />

                            <p
                                v-if="form.errors.kota"
                                class="mt-2 text-sm text-red-500"
                            >
                                {{ form.errors.kota }}
                            </p>
                        </div>

                        <!-- Tombol -->
                        <div class="flex items-center justify-end gap-3">

                            <Link
                                :href="route('pelanggan.index')"
                                class="rounded-lg bg-gray-100 px-5 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-200"
                            >
                                Batal
                            </Link>

                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                {{
                                    form.processing
                                        ? 'Menyimpan...'
                                        : 'Simpan Pelanggan'
                                }}
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </AuthenticatedLayout>
</template>