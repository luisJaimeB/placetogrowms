<script>
export default {
    name: "MicrositeShow",
};
</script>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";

defineProps({
    microsite: {
        type: Object,
        required: true
    },
});

const getLogoUrl = (path) => {
    return path ? `/microsite/logo/${path}` : null;
};
</script>

<template>

    <Head title="Microsites" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Microsites
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="microsite" class="mt-4">
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex flex-col items-center mb-6">
                            <img v-if="microsite.logo" :src="getLogoUrl(microsite.logo)" alt="Logo" class="h-32 w-32 rounded-full object-cover mb-4"/>
                            <h2 class="text-2xl font-bold mb-2">{{ microsite.name }}</h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <p class="text-lg"><strong>ID:</strong> {{ microsite.id }}</p>
                            <p class="text-lg"><strong>Tipo:</strong> {{ microsite.type_site.name }}</p>
                            <p class="text-lg"><strong>Categoría:</strong> {{ microsite.category.name }}</p>
                            <p class="text-lg"><strong>Expiración:</strong> {{ microsite.expiration }}</p>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <p>No se encontró el micrositio.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
