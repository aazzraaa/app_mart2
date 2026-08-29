<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    produk: Object,
});

const fotoType = ref('keep');
const fileInput = ref(null);
const previewUrl = ref(null);

const form = useForm({
    nama_produk: props.produk.nama_produk || '',
    harga: props.produk.harga || '',
    stok: props.produk.stok || '',
    kategori: props.produk.kategori || '',
    deskripsi: props.produk.deskripsi || '',
    foto: null,
    foto_url: '',
    foto_type: 'keep',
});

function onFileChange(e) {
    const file = e.target.files[0];
    form.foto = file || null;
    if (file) {
        previewUrl.value = URL.createObjectURL(file);
    } else {
        previewUrl.value = null;
    }
}

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

function getCurrentFoto() {
    if (!props.produk.foto) return null;
    if (props.produk.foto.startsWith('http')) return props.produk.foto;
    return '/storage/' + props.produk.foto;
}

function updateProduk() {
    form.foto_type = fotoType.value;
    form.put(route('produk.update', props.produk.id), {
        onSuccess: () => {},
    });
}
</script>

<template>
    <Head :title="`Edit - ${produk.nama_produk}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-2 text-xs" style="color: #B8A0A6;">
                        <Link :href="route('produk.index')" class="transition-colors hover:underline" style="color: #8B4557;">Produk</Link>
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                        <Link :href="route('produk.show', produk.id)" class="transition-colors hover:underline" style="color: #8B4557;">{{ produk.nama_produk }}</Link>
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                        <span style="color: #5C2D38;">Edit</span>
                    </div>
                    <h2 class="mt-1 text-xl font-bold" style="color: #5C2D38;">Edit Produk</h2>
                    <p class="text-sm" style="color: #A07882;">Ubah informasi produk</p>
                </div>
                <Link :href="route('produk.show', produk.id)"
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
            <div class="overflow-hidden rounded-xl border bg-white" style="border-color: #F3E0E4; box-shadow: 0 1px 2px rgba(92,45,56,0.04);">
                <div class="flex items-center gap-3 border-b px-5 py-4" style="border-color: #F8F0F2;">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl" style="background: linear-gradient(135deg, #8B4557, #B06878);">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold" style="color: #5C2D38;">Form Edit Produk</h3>
                        <p class="text-xs" style="color: #A07882;">Mengedit: {{ produk.nama_produk }}</p>
                    </div>
                </div>

                <div class="p-5">
                    <div class="space-y-4">

                        <!-- FOTO -->
                        <div>
                            <label class="mb-2 block text-xs font-semibold" style="color: #6B4A50;">Foto Produk</label>

                            <div class="mb-3 inline-flex rounded-lg p-0.5" style="background-color: #F8F0F2;">
                                <button @click="switchFotoType('keep')"
                                    class="flex items-center gap-1.5 rounded-md px-3 py-1.5 text-xs font-semibold transition-all duration-200"
                                    :style="fotoType === 'keep' ? 'background-color: white; color: #8B4557; box-shadow: 0 1px 3px rgba(92,45,56,0.1);' : 'color: #A07882;'"
                                >Pertahankan</button>
                                <button @click="switchFotoType('file')"
                                    class="flex items-center gap-1.5 rounded-md px-3 py-1.5 text-xs font-semibold transition-all duration-200"
                                    :style="fotoType === 'file' ? 'background-color: white; color: #8B4557; box-shadow: 0 1px 3px rgba(92,45,56,0.1);' : 'color: #A07882;'"
                                >Upload Baru</button>
                                <button @click="switchFotoType('url')"
                                    class="flex items-center gap-1.5 rounded-md px-3 py-1.5 text-xs font-semibold transition-all duration-200"
                                    :style="fotoType === 'url' ? 'background-color: white; color: #8B4557; box-shadow: 0 1px 3px rgba(92,45,56,0.1);' : 'color: #A07882;'"
                                >URL Baru</button>
                            </div>

                            <!-- Keep current -->
                            <div v-if="fotoType === 'keep'">
                                <div v-if="getCurrentFoto()" class="h-40 w-full overflow-hidden rounded-lg" style="background-color: #FDF8F9;">
                                    <img :src="getCurrentFoto()" class="h-full w-full object-cover" />
                                </div>
                                <div v-else class="flex h-32 items-center justify-center rounded-lg" style="background-color: #FDF8F9;">
                                    <p class="text-xs" style="color: #C4A0A8;">Tidak ada foto</p>
                                </div>
                            </div>

                            <!-- File upload -->
                            <div v-if="fotoType === 'file'">
                                <input ref="fileInput" type="file" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" @change="onFileChange" />
                                <div v-if="!previewUrl"
                                    @click="fileInput?.click()"
                                    class="flex cursor-pointer flex-col items-center gap-2 rounded-lg border-2 border-dashed p-5 transition-colors"
                                    style="border-color: #EFE0E3; background-color: #FDF8F9;"
                                    @mouseenter="$event.currentTarget.style.borderColor='#8B4557'"
                                    @mouseleave="$event.currentTarget.style.borderColor='#EFE0E3'"
                                >
                                    <svg class="h-7 w-7" style="color: #D4A0AB;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                    </svg>
                                    <p class="text-xs font-medium" style="color: #A07882;">Klik untuk pilih foto baru</p>
                                </div>
                            </div>

                            <!-- URL input -->
                            <div v-if="fotoType === 'url' && !previewUrl">
                                <input v-model="form.foto_url" type="url" placeholder="https://contoh.com/foto-baru.jpg"
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
                                    class="absolute right-2 top-2 flex h-7 w-7 items-center justify-center rounded-lg bg-white/90 shadow-sm"
                                    style="color: #B04050; backdrop-filter: blur(4px);"
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
                            <input v-model="form.nama_produk" type="text"
                                class="w-full rounded-lg border bg-white px-3.5 py-3 text-sm outline-none transition-all duration-200"
                                style="border-color: #EFE0E3; color: #5C2D38;"
                                onfocus="this.style.borderColor='#8B4557'; this.style.boxShadow='0 0 0 3px rgba(139,69,87,0.08)'"
                                onblur="this.style.borderColor='#EFE0E3'; this.style.boxShadow='none'"
                            />
                            <p v-if="form.errors.nama_produk" class="mt-1.5 text-xs font-medium" style="color: #B04050;">{{ form.errors.nama_produk }}</p>
                        </div>

                        <!-- HARGA + STOK -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="mb-1.5 block text-xs font-semibold" style="color: #6B4A50;">Harga (Rp)</label>
                                <input v-model="form.harga" type="number" min="0"
                                    class="w-full rounded-lg border bg-white px-3.5 py-3 text-sm outline-none transition-all duration-200"
                                    style="border-color: #EFE0E3; color: #5C2D38;"
                                    onfocus="this.style.borderColor='#8B4557'; this.style.boxShadow='0 0 0 3px rgba(139,69,87,0.08)'"
                                    onblur="this.style.borderColor='#EFE0E3'; this.style.boxShadow='none'"
                                />
                                <p v-if="form.errors.harga" class="mt-1.5 text-xs font-medium" style="color: #B04050;">{{ form.errors.harga }}</p>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-xs font-semibold" style="color: #6B4A50;">Stok</label>
                                <input v-model="form.stok" type="number" min="0"
                                    class="w-full rounded-lg border bg-white px-3.5 py-3 text-sm outline-none transition-all duration-200"
                                    style="border-color: #EFE0E3; color: #5C2D38;"
                                    onfocus="this.style.borderColor='#8B4557'; this.style.boxShadow='0 0 0 3px rgba(139,69,87,0.08)'"
                                    onblur="this.style.borderColor='#EFE0E3'; this.style.boxShadow='none'"
                                />
                                <p v-if="form.errors.stok" class="mt-1.5 text-xs font-medium" style="color: #B04050;">{{ form.errors.stok }}</p>
                            </div>
                        </div>

                        <!-- KATEGORI -->
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold" style="color: #6B4A50;">Kategori</label>
                            <input v-model="form.kategori" type="text"
                                class="w-full rounded-lg border bg-white px-3.5 py-3 text-sm outline-none transition-all duration-200"
                                style="border-color: #EFE0E3; color: #5C2D38;"
                                onfocus="this.style.borderColor='#8B4557'; this.style.boxShadow='0 0 0 3px rgba(139,69,87,0.08)'"
                                onblur="this.style.borderColor='#EFE0E3'; this.style.boxShadow='none'"
                            />
                        </div>

                        <!-- DESKRIPSI -->
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold" style="color: #6B4A50;">Deskripsi</label>
                            <textarea v-model="form.deskripsi" rows="3"
                                class="w-full rounded-lg border bg-white px-3.5 py-3 text-sm outline-none transition-all duration-200 resize-none"
                                style="border-color: #EFE0E3; color: #5C2D38;"
                                onfocus="this.style.borderColor='#8B4557'; this.style.boxShadow='0 0 0 3px rgba(139,69,87,0.08)'"
                                onblur="this.style.borderColor='#EFE0E3'; this.style.boxShadow='none'"
                            ></textarea>
                        </div>

                        <!-- BUTTONS -->
                        <div class="flex items-center justify-end gap-3 pt-3" style="border-top: 1px solid #F8F0F2;">
                            <Link :href="route('produk.show', produk.id)"
                                class="rounded-lg border px-4 py-2.5 text-sm font-semibold transition-all duration-200"
                                style="border-color: #EFE0E3; color: #6B4A50; background-color: white;"
                                onmouseenter="this.style.backgroundColor='#FDF8F9'"
                                onmouseleave="this.style.backgroundColor='white'"
                            >Batal</Link>
                            <button @click="updateProduk" :disabled="form.processing"
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
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="mt-5 overflow-hidden rounded-xl border bg-white" style="border-color: #F8E0E0;">
                <div class="px-5 py-4">
                    <h4 class="text-sm font-bold" style="color: #B04050;">Zona Berbahaya</h4>
                    <p class="mt-0.5 text-xs" style="color: #A07882;">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="border-t px-5 py-3 flex items-center justify-between" style="border-color: #FDF0F1; background-color: #FDF8F9;">
                    <div>
                        <p class="text-sm font-medium" style="color: #5C2D38;">Hapus produk ini</p>
                        <p class="text-xs" style="color: #B8A0A6;">Data dan foto akan dihapus permanen.</p>
                    </div>
                    <Link :href="route('produk.destroy', produk.id)" method="delete" as="button"
                        class="shrink-0 rounded-lg border px-3.5 py-2 text-xs font-semibold transition-all duration-200"
                        style="border-color: #F0C8CC; color: #B04050; background-color: white;"
                        onmouseenter="this.style.backgroundColor='#FDF0F1'; this.style.borderColor='#E8B0B8'"
                        onmouseleave="this.style.backgroundColor='white'; this.style.borderColor='#F0C8CC'"
                    >Hapus</Link>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>