<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Riwayat Checkout" />

    <AuthenticatedLayout>

        <template #header>
            <div>
                <div
                    class="flex items-center gap-2 text-xs"
                    style="color:#B8A0A6;"
                >
                    <span>Dashboard</span>

                    <span>›</span>

                    <span style="color:#8B4557;">
                        Riwayat Checkout
                    </span>
                </div>

                <h2
                    class="mt-1 text-xl font-bold"
                    style="color:#5C2D38;"
                >
                    Riwayat Checkout
                </h2>

                <p
                    class="text-sm"
                    style="color:#A07882;"
                >
                    Lihat daftar transaksi checkout kamu
                </p>
            </div>
        </template>


        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

            <div
                class="rounded-xl border bg-white p-8 text-center"
                style="border-color:#F3E0E4;"
            >

                <div
                    class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl"
                    style="background-color:#F9EEF0;"
                >
                    🛒
                </div>

                <h3
                    class="mt-4 text-lg font-bold"
                    style="color:#5C2D38;"
                >
                    Riwayat Checkout
                </h3>

                <p
                    class="mt-1 text-sm"
                    style="color:#A07882;"
                >
                    Belum ada transaksi checkout.
                </p>

            </div>

        </div>

    </AuthenticatedLayout>
</template>