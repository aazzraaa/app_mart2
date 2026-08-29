<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Daftar" />

    <div class="flex min-h-screen">

        <!-- ============================== -->
        <!-- KIRI: BRAND PANEL (desktop)     -->
        <!-- ============================== -->
        <div
            class="hidden lg:flex lg:w-1/2 lg:flex-col lg:justify-between lg:p-12 xl:p-16"
            style="background: linear-gradient(160deg, #8B4557 0%, #6B3344 40%, #9B5B63 80%, #B06878 100%);"
        >
            <div class="absolute left-0 top-0 h-full w-1/2 overflow-hidden lg:relative">
                <div class="absolute -left-16 -top-16 h-64 w-64 rounded-full" style="background: rgba(255,255,255,0.05);"></div>
                <div class="absolute -bottom-20 right-10 h-80 w-80 rounded-full" style="background: rgba(255,255,255,0.04);"></div>
                <div class="absolute left-1/3 top-1/3 h-40 w-40 rounded-full" style="background: rgba(255,255,255,0.03);"></div>
            </div>

            <div class="relative z-10">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/15 backdrop-blur-sm">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.15c0 .415.336.75.75.75z" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-white">TokoKu</span>
                </div>

                <h1 class="mt-8 text-3xl font-bold leading-tight text-white xl:text-4xl">
                    Bergabung<br>Buat Akun Baru
                </h1>
                <p class="mt-4 max-w-sm text-sm leading-relaxed" style="color: #F0D0D6;">
                    Daftarkan akun sekarang untuk mulai kelola toko kamu secara gratis.
                </p>
            </div>

            <div class="relative z-10">
                <div class="flex gap-6">
                    <div>
                        <p class="text-2xl font-bold text-white">120+</p>
                        <p class="text-xs" style="color: #E8BCC4;">Pelanggan</p>
                    </div>
                    <div class="h-10 w-px" style="background: rgba(255,255,255,0.15);"></div>
                    <div>
                        <p class="text-2xl font-bold text-white">350+</p>
                        <p class="text-xs" style="color: #E8BCC4;">Transaksi</p>
                    </div>
                    <div class="h-10 w-px" style="background: rgba(255,255,255,0.15);"></div>
                    <div>
                        <p class="text-2xl font-bold text-white">86</p>
                        <p class="text-xs" style="color: #E8BCC4;">Produk</p>
                    </div>
                </div>

                <p class="mt-8 text-xs" style="color: #D4A0AB;">© 2025 TokoKu. Sistem Manajemen Toko.</p>
            </div>
        </div>

        <!-- ============================== -->
        <!-- KANAN: FORM REGISTER            -->
        <!-- ============================== -->
        <div class="flex w-full items-center justify-center px-6 py-12 lg:w-1/2 lg:px-12 xl:px-20">

            <!-- Mobile Brand -->
            <div class="mb-8 text-center lg:hidden">
                <div class="inline-flex items-center gap-2.5">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl" style="background: linear-gradient(135deg, #8B4557, #6B3344);">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.15c0 .415.336.75.75.75z" />
                        </svg>
                    </div>
                    <span class="text-lg font-bold" style="color: #5C2D38;">TokoKu</span>
                </div>
            </div>

            <!-- Form Card -->
            <div
                class="w-full max-w-sm rounded-2xl border bg-white p-7"
                style="border-color: #F3E0E4; box-shadow: 0 2px 8px rgba(92,45,56,0.06);"
            >
                <div class="mb-6">
                    <h2 class="text-xl font-bold" style="color: #5C2D38;">Buat Akun</h2>
                    <p class="mt-1 text-sm" style="color: #A07882;">Isi data berikut untuk membuat akun baru</p>
                </div>

                <!-- Error Summary -->
                <div
                    v-if="Object.keys(form.errors).length > 0"
                    class="mb-5 rounded-xl px-4 py-3"
                    style="background-color: #FDF0F1; border: 1px solid #F0C8CC;"
                >
                    <div class="flex items-center gap-2 mb-1.5">
                        <svg class="h-4 w-4 shrink-0" style="color: #B04050;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                        <p class="text-xs font-bold" style="color: #B04050;">Ada kesalahan, periksa kembali form di bawah</p>
                    </div>
                    <ul class="ml-6 list-disc text-xs" style="color: #963545;">
                        <li v-for="(error, key) in form.errors" :key="key">{{ error }}</li>
                    </ul>
                </div>

                <form @submit.prevent="submit" class="space-y-4">

                    <!-- NAMA -->
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold" style="color: #6B4A50;">Nama Lengkap</label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            placeholder="Contoh: Arlyyy"
                            class="w-full rounded-xl border bg-white px-4 py-3 text-sm outline-none transition-all duration-200"
                            style="border-color: #EFE0E3; color: #5C2D38;"
                            onfocus="this.style.borderColor='#8B4557'; this.style.boxShadow='0 0 0 3px rgba(139,69,87,0.08)'"
                            onblur="this.style.borderColor='#EFE0E3'; this.style.boxShadow='none'"
                        />
                        <p v-if="form.errors.name" class="mt-1 text-xs font-medium" style="color: #B04050;">{{ form.errors.name }}</p>
                    </div>

                    <!-- EMAIL -->
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold" style="color: #6B4A50;">Email</label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            placeholder="nama@email.com"
                            class="w-full rounded-xl border bg-white px-4 py-3 text-sm outline-none transition-all duration-200"
                            style="border-color: #EFE0E3; color: #5C2D38;"
                            onfocus="this.style.borderColor='#8B4557'; this.style.boxShadow='0 0 0 3px rgba(139,69,87,0.08)'"
                            onblur="this.style.borderColor='#EFE0E3'; this.style.boxShadow='none'"
                        />
                        <p v-if="form.errors.email" class="mt-1 text-xs font-medium" style="color: #B04050;">{{ form.errors.email }}</p>
                    </div>

                    <!-- PASSWORD -->
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold" style="color: #6B4A50;">Password</label>
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            placeholder="Minimal 8 karakter"
                            class="w-full rounded-xl border bg-white px-4 py-3 text-sm outline-none transition-all duration-200"
                            style="border-color: #EFE0E3; color: #5C2D38;"
                            onfocus="this.style.borderColor='#8B4557'; this.style.boxShadow='0 0 0 3px rgba(139,69,87,0.08)'"
                            onblur="this.style.borderColor='#EFE0E3'; this.style.boxShadow='none'"
                        />
                        <p v-if="form.errors.password" class="mt-1 text-xs font-medium" style="color: #B04050;">{{ form.errors.password }}</p>
                    </div>

                    <!-- PASSWORD CONFIRMATION -->
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold" style="color: #6B4A50;">Konfirmasi Password</label>
                        <input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            placeholder="Ulangi password"
                            class="w-full rounded-xl border bg-white px-4 py-3 text-sm outline-none transition-all duration-200"
                            style="border-color: #EFE0E3; color: #5C2D38;"
                            onfocus="this.style.borderColor='#8B4557'; this.style.boxShadow='0 0 0 3px rgba(139,69,87,0.08)'"
                            onblur="this.style.borderColor='#EFE0E3'; this.style.boxShadow='none'"
                        />
                        <p v-if="form.errors.password_confirmation" class="mt-1 text-xs font-medium" style="color: #B04050;">{{ form.errors.password_confirmation }}</p>
                    </div>

                    <!-- SUBMIT -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="flex w-full items-center justify-center gap-2 rounded-xl py-3 text-sm font-semibold text-white transition-all duration-200 disabled:cursor-not-allowed disabled:opacity-50"
                        style="background: linear-gradient(135deg, #8B4557, #6B3344); box-shadow: 0 4px 14px rgba(139,69,87,0.3);"
                        @mouseenter="$event.currentTarget.style.boxShadow='0 6px 20px rgba(139,69,87,0.4)'; $event.currentTarget.style.transform='translateY(-1px)'"
                        @mouseleave="$event.currentTarget.style.boxShadow='0 4px 14px rgba(139,69,87,0.3)'; $event.currentTarget.style.transform='translateY(0)'"
                    >
                        <svg v-if="form.processing" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span>{{ form.processing ? 'Memproses...' : 'Daftar Sekarang' }}</span>
                    </button>

                    <!-- Sudah punya akun? -->
                    <p class="mt-5 text-center text-sm" style="color: #A07882;">
                        Sudah punya akun?
                        <Link
                            :href="route('login')"
                            class="ml-1 font-semibold transition-colors"
                            style="color: #8B4557;"
                            onmouseenter="this.style.color='#6B3344'"
                            onmouseleave="this.style.color='#8B4557'"
                        >
                                Masuk di sini
                            </Link>
                    </p>
                </form>
            </div>

        </div>

    </div>
</template>