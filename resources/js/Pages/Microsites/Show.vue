<script>
export default {
    name: "MicrositeShow",
};
</script>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import {computed} from "vue";
import SetLocale from "@/Components/SetLocale.vue";

defineProps({
    microsite: {
        type: Object,
        required: true
    },
});

const assetsUrl = computed(() => {
    return '/microsite/assets';
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
                    <div class="microsite-show">
                        <div class="header-section">
                            <div class="card">
                                <h2 class="text-2xl text-center font-bold mb-2">{{ microsite.name }}</h2>

                                <div class="bg-white p-4 rounded-lg shadow flex items-center space-x-4 mb-2">
                                    <div class="flex-shrink-0 hidden md:block">
                                        <img alt="icon_id" :src="`${assetsUrl}/idIcon.png`" class="h-12 w-12 object-cover">
                                    </div>
                                    <div>
                                        <p class="text-lg"><strong>ID:</strong> {{ microsite.id }}</p>
                                    </div>
                                </div>

                                <div class="bg-white p-4 rounded-lg shadow flex items-center space-x-4 mb-2">
                                    <div class="flex-shrink-0 hidden md:block">
                                        <img alt="icon_id" :src="`${assetsUrl}/type.png`" class="h-12 w-12 object-cover">
                                    </div>
                                    <div>
                                        <p class="text-lg"><strong>Tipo:</strong> {{ microsite.type_site.name }}</p>
                                    </div>
                                </div>

                                <div class="bg-white p-4 rounded-lg shadow flex items-center space-x-4 mb-2">
                                    <div class="flex-shrink-0 hidden md:block">
                                        <img alt="icon_id" :src="`${assetsUrl}/categories.png`" class="h-12 w-12 object-cover">
                                    </div>
                                    <div>
                                        <p class="text-lg"><strong>Categoría:</strong> {{ microsite.category.name }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="logo-section">
                                <img v-if="microsite.logo" :src="getLogoUrl(microsite.logo)" alt="Logo" class="logo-image"/>
                            </div>
                        </div>

                        <div class="details-section">
                            <div class="card flex space-x-4">
                                <div class="bg-white p-4 rounded-lg shadow flex items-center space-x-4 flex-1">
                                    <div class="flex-shrink-0 hidden md:block">
                                        <img alt="icon_id" :src="`${assetsUrl}/expiration.png`" class="h-12 w-12 object-cover">
                                    </div>
                                    <div>
                                        <p class="text-lg"><strong>Expiración:</strong> {{ microsite.expiration }}</p>
                                        <SetLocale></SetLocale>
                                    </div>
                                </div>
                                <div class="bg-white p-4 rounded-lg shadow flex items-center space-x-4 flex-1">
                                    <div class="flex-shrink-0 hidden md:block">
                                        <img alt="icon_id" :src="`${assetsUrl}/currency.png`" class="h-12 w-12 object-cover">
                                    </div>
                                    <div>
                                        <p class="text-lg"><strong>Currency:</strong> {{ microsite.currencies[0].code }} </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="payment-section">
                            <h2>Payment History</h2>
                            <table>
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>fecha1</td>
                                    <td>1000</td>
                                    <td>VISA</td>
                                    <td>Approved</td>
                                </tr>
                                </tbody>
                            </table>
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

<style>
.microsite-show {
    max-width: 800px;
    margin: 40px auto;
    padding: 20px;
}

.header-section {
    display: flex;
    justify-content: space-between;
    align-items: stretch;
}

.card, .logo-section {
    flex: 1;
    margin: 10px;
    padding: 20px;
    background-color: #f7f7f7;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.logo-section {
    display: flex;
    justify-content: center;
    align-items: center;
}

.logo-image {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.banner img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.details-section {
    margin-top: 20px;
}

.payment-section {
    margin-top: 40px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}

th {
    background-color: #f0f0f0;
}
</style>
