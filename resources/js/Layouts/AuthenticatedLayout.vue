<script setup>
import { ref, computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';

const page = usePage();

const user = computed(() => page.props.auth?.user);

const mobileMenuOpen = ref(false);
const userMenuOpen = ref(false);

const currentRoute = computed(() => route().current());

const navLinks = [
    {
        label: 'Dashboard',
        route: 'dashboard',
        icon: 'M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z',
    },

    {
        label: 'Data Pelanggan',
        route: 'pelanggan.index',
        icon: 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z',
    },

    {
        label: 'Produk',
        route: 'produk.index',
        icon: 'M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z',
    },

    // =========================
    // BELANJA
    // =========================
    {
        label: 'Belanja',
        route: 'belanja',
        icon: 'M3 3h2l2.4 12.2a2 2 0 002 1.6h7.8a2 2 0 001.9-1.4L21 8H6M10 21a1 1 0 11-2 0 1 1 0 012 0zm9 0a1 1 0 11-2 0 1 1 0 012 0z',
    },

    // =========================
    // RIWAYAT CHECKOUT
    // =========================
    {
        label: 'Riwayat',
        route: 'checkout.history',
        icon: 'M6 3.75h12A2.25 2.25 0 0120.25 6v12A2.25 2.25 0 0118 20.25H6A2.25 2.25 0 013.75 18V6A2.25 2.25 0 016 3.75zM8 8h8M8 12h8M8 16h5',
    },
];

function isActive(routeName) {
    return (
        currentRoute.value === routeName ||
        currentRoute.value?.startsWith(routeName + '.')
    );
}

function toggleUserMenu() {
    userMenuOpen.value = !userMenuOpen.value;
}

function closeMenus() {
    userMenuOpen.value = false;
    mobileMenuOpen.value = false;
}

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

function logout() {
    closeMenus();

    router.post('/logout');
}
</script>

<template>

    <div
        class="min-h-screen"
        style="background-color: #F8F3F4;"
    >

        <!-- ================================================= -->
        <!-- NAVBAR -->
        <!-- ================================================= -->

        <nav
            class="sticky top-0 z-50 border-b bg-white"
            style="border-color: #EFE0E3;"
        >

            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                <div class="flex h-16 items-center justify-between">

                    <!-- ================================================= -->
                    <!-- LOGO -->
                    <!-- ================================================= -->

                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl"
                            style="background: linear-gradient(135deg, #8B4557, #6B3344);"
                        >

                            <svg
                                class="h-5 w-5 text-white"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.15c0 .415.336.75.75.75z"
                                />

                            </svg>

                        </div>

                        <span
                            class="text-lg font-bold tracking-tight"
                            style="color: #5C2D38;"
                        >
                            Lumina Palette
                        </span>

                    </div>


                    <!-- ================================================= -->
                    <!-- NAVIGATION DESKTOP -->
                    <!-- ================================================= -->

                    <div class="hidden items-center gap-1 md:flex">

                        <Link
                            v-for="link in navLinks"
                            :key="link.route"
                            :href="route(link.route)"
                            class="relative flex items-center gap-2 rounded-lg px-3.5 py-2 text-sm font-medium transition-all"
                            :style="
                                isActive(link.route)
                                    ? 'color:#8B4557;background-color:#FBF0F2;'
                                    : 'color:#8B7078;'
                            "
                        >

                            <svg
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    :d="link.icon"
                                />

                            </svg>

                            {{ link.label }}

                        </Link>

                    </div>


                    <!-- ================================================= -->
                    <!-- RIGHT SIDE -->
                    <!-- ================================================= -->

                    <div class="flex items-center gap-2">

                        <!-- NOTIFICATION -->

                        <button
                            type="button"
                            class="relative flex h-9 w-9 items-center justify-center rounded-xl"
                            style="color:#8B7078;"
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
                                    d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"
                                />

                            </svg>

                            <span
                                class="absolute -right-0.5 -top-0.5 flex h-4 w-4 items-center justify-center rounded-full text-[10px] font-bold text-white"
                                style="background:linear-gradient(135deg,#8B4557,#6B3344);"
                            >
                                3
                            </span>

                        </button>


                        <div
                            class="mx-1 hidden h-6 w-px md:block"
                            style="background-color:#EFE0E3;"
                        ></div>


                        <!-- ================================================= -->
                        <!-- USER DESKTOP -->
                        <!-- ================================================= -->

                        <div class="relative hidden md:block">

                            <button
                                type="button"
                                class="flex items-center gap-2.5 rounded-xl px-2.5 py-1.5 transition-all"
                                :style="
                                    userMenuOpen
                                        ? 'background-color:#FBF0F2;'
                                        : 'background-color:transparent;'
                                "
                                @click.stop="toggleUserMenu"
                            >

                                <!-- AVATAR -->

                                <div
                                    class="flex h-8 w-8 items-center justify-center rounded-lg text-xs font-bold text-white"
                                    style="background:linear-gradient(135deg,#8B4557,#B06878);"
                                >

                                    {{
                                        user?.name
                                            ? user.name.charAt(0).toUpperCase()
                                            : 'U'
                                    }}

                                </div>


                                <!-- USER INFO -->

                                <div class="text-left">

                                    <p
                                        class="text-sm font-semibold leading-tight"
                                        style="color:#5C2D38;"
                                    >
                                        {{ user?.name || 'User' }}
                                    </p>

                                    <p
                                        class="text-[11px] leading-tight"
                                        style="color:#B8A0A6;"
                                    >
                                        {{ user?.email || '' }}
                                    </p>

                                </div>


                                <!-- ARROW -->

                                <svg
                                    class="ml-1 h-4 w-4 transition-transform duration-200"
                                    :class="{ 'rotate-180': userMenuOpen }"
                                    style="color:#B8A0A6;"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                                    />

                                </svg>

                            </button>


                            <!-- ================================================= -->
                            <!-- DROPDOWN -->
                            <!-- ================================================= -->

                            <Transition
                                enter-active-class="transition duration-150 ease-out"
                                enter-from-class="opacity-0 -translate-y-2 scale-95"
                                enter-to-class="opacity-100 translate-y-0 scale-100"
                                leave-active-class="transition duration-100 ease-in"
                                leave-from-class="opacity-100 translate-y-0 scale-100"
                                leave-to-class="opacity-0 -translate-y-2 scale-95"
                            >

                                <div
                                    v-if="userMenuOpen"
                                    class="absolute right-0 top-full mt-2 w-60 overflow-hidden rounded-xl border bg-white shadow-xl"
                                    style="
                                        border-color:#EFE0E3;
                                        z-index:9999;
                                    "
                                >

                                    <!-- USER INFO -->

                                    <div
                                        class="px-4 py-3"
                                        style="border-bottom:1px solid #F8F0F2;"
                                    >

                                        <p
                                            class="text-sm font-semibold"
                                            style="color:#5C2D38;"
                                        >
                                            {{ user?.name || 'User' }}
                                        </p>

                                        <p
                                            class="mt-0.5 text-xs"
                                            style="color:#B8A0A6;"
                                        >
                                            {{ user?.email || '' }}
                                        </p>

                                    </div>


                                    <!-- ================================================= -->
                                    <!-- LOGOUT -->
                                    <!-- ================================================= -->

                                    <button
                                        type="button"
                                        class="flex w-full items-center gap-3 px-4 py-3 text-left text-sm transition-colors hover:bg-red-50"
                                        style="color:#B04050;"
                                        @click="logout"
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
                                                d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"
                                            />

                                        </svg>

                                        <span>Keluar</span>

                                    </button>

                                </div>

                            </Transition>

                        </div>


                        <!-- ================================================= -->
                        <!-- MOBILE BUTTON -->
                        <!-- ================================================= -->

                        <button
                            type="button"
                            class="flex h-9 w-9 items-center justify-center rounded-xl md:hidden"
                            style="color:#8B7078;"
                            @click="mobileMenuOpen = !mobileMenuOpen"
                        >

                            <svg
                                v-if="!mobileMenuOpen"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"
                                />

                            </svg>

                            <svg
                                v-else
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6 18L18 6M6 6l12 12"
                                />

                            </svg>

                        </button>

                    </div>

                </div>

            </div>


            <!-- ================================================= -->
            <!-- MOBILE MENU -->
            <!-- ================================================= -->

            <div
                v-if="mobileMenuOpen"
                class="border-t bg-white px-4 pb-4 pt-2 md:hidden"
                style="border-color:#EFE0E3;"
            >

                <!-- NAV LINKS -->

                <div class="space-y-1">

                    <Link
                        v-for="link in navLinks"
                        :key="link.route"
                        :href="route(link.route)"
                        class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium"
                        :style="
                            isActive(link.route)
                                ? 'color:#8B4557;background-color:#FBF0F2;'
                                : 'color:#8B7078;'
                        "
                        @click="closeMenus"
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
                                :d="link.icon"
                            />

                        </svg>

                        {{ link.label }}

                    </Link>

                </div>


                <!-- ================================================= -->
                <!-- MOBILE USER -->
                <!-- ================================================= -->

                <div
                    class="mt-3 pt-3"
                    style="border-top:1px solid #F8F0F2;"
                >

                    <div class="flex items-center gap-3 px-3 py-2">

                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-lg text-xs font-bold text-white"
                            style="background:linear-gradient(135deg,#8B4557,#B06878);"
                        >

                            {{
                                user?.name
                                    ? user.name.charAt(0).toUpperCase()
                                    : 'U'
                            }}

                        </div>

                        <div>

                            <p
                                class="text-sm font-semibold"
                                style="color:#5C2D38;"
                            >
                                {{ user?.name || 'User' }}
                            </p>

                            <p
                                class="text-xs"
                                style="color:#B8A0A6;"
                            >
                                {{ user?.email || '' }}
                            </p>

                        </div>

                    </div>


                    <!-- ================================================= -->
                    <!-- MOBILE LOGOUT -->
                    <!-- ================================================= -->

                    <button
                        type="button"
                        class="mt-1 flex w-full items-center gap-3 rounded-xl px-3 py-3 text-left text-sm transition-colors hover:bg-red-50"
                        style="color:#B04050;"
                        @click="logout"
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
                                d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"
                            />

                        </svg>

                        <span>Keluar</span>

                    </button>

                </div>

            </div>

        </nav>


        <!-- ================================================= -->
        <!-- HEADER -->
        <!-- ================================================= -->

        <header
            v-if="$slots.header"
            class="border-b bg-white"
            style="border-color:#EFE0E3;"
        >

            <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">

                <slot name="header" />

            </div>

        </header>


        <!-- ================================================= -->
        <!-- CONTENT -->
        <!-- ================================================= -->

        <main>

            <slot />

        </main>

    </div>

</template>