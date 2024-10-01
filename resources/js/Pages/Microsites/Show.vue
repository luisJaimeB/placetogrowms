<script>
export default {
    name: "MicrositeShow",
};
</script>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import {computed, onMounted} from "vue";
import {useI18n} from "vue-i18n";
import { useToast } from "vue-toastification";

defineProps({
    microsite: {
        type: Object,
        required: true
    },
    payments: {
        type: Object,
        required: true
    },
    flash: {
        type: Object,
        required: false,
        default: () => ({})
    }
});

const toast = useToast();

const { t } = useI18n();

const assetsUrl = computed(() => {
    return '/microsite/assets';
});
const getLogoUrl = (path) => {
    return path ? `/microsite/logo/${path}` : null;
};

/**onMounted(() => {
    if (flash.message) {
        toast.error(flash.message);
    }
});**/

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
                    <div class="microsite-show bg-white rounded-xl shadow-2xl overflow-hidden p-4">
                        <!-- Header Section -->
                        <div class="header-section flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                            <div class="card flex-1 p-4 bg-gray-50 rounded-lg shadow-md">
                                <h2 class="text-2xl font-bold text-center mb-4">{{ microsite.name }}</h2>
                                <!-- Microsite Details -->
                                <div class="flex flex-col space-y-4">
                                    <div class="flex items-center p-4 bg-white rounded-lg shadow-sm">
                                        <div class="flex-shrink-0 hidden md:block">
                                            <img :src="`${assetsUrl}/idIcon.png`" alt="ID Icon" class="h-12 w-12 object-cover"/>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-lg"><strong>{{ t('fields.id') }}:</strong> {{ microsite.id }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center p-4 bg-white rounded-lg shadow-sm">
                                        <div class="flex-shrink-0 hidden md:block">
                                            <img :src="`${assetsUrl}/type.png`" alt="Type Icon" class="h-12 w-12 object-cover"/>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-lg"><strong>{{ t('fields.type') }}:</strong> {{ microsite.type_site.name }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center p-4 bg-white rounded-lg shadow-sm">
                                        <div class="flex-shrink-0 hidden md:block">
                                            <img :src="`${assetsUrl}/categories.png`" alt="Category Icon" class="h-12 w-12 object-cover"/>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-lg"><strong>{{ t('fields.category') }}:</strong> {{ microsite.category.name }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="logo-section flex-1 flex items-center justify-center p-4">
                                <img v-if="microsite.logo" :src="getLogoUrl(microsite.logo)" alt="Logo" class="logo-image"/>
                            </div>
                        </div>

                        <!-- Details Section -->
                        <div class="details-section mt-8">
                            <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                                <div class="bg-white p-4 rounded-lg shadow-sm flex items-center flex-1">
                                    <div class="flex-shrink-0 hidden md:block">
                                        <img :src="`${assetsUrl}/expiration.png`" alt="Expiration Icon" class="h-12 w-12 object-cover"/>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-lg"><strong>{{ t('fields.expiration') }}:</strong> {{ microsite.expiration }}</p>
                                    </div>
                                </div>
                                <div class="bg-white p-4 rounded-lg shadow-sm flex items-center flex-1">
                                    <div class="flex-shrink-0 hidden md:block">
                                        <img :src="`${assetsUrl}/currency.png`" alt="Currency Icon" class="h-12 w-12 object-cover"/>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-lg"><strong>{{ t('fields.currency') }}:</strong> {{ microsite.currencies[0].code }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Section -->
                        <div class="payment-section mt-8">
                            <h2 class="text-xl font-bold mb-4">{{ t('titles.paymentHistory') }}</h2>
                            <div class="bg-white p-4 rounded-lg shadow-md">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ t('labels.paymentsLabel.reference') }}</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ t('labels.paymentsLabel.amount') }}</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ t('labels.paymentsLabel.paymentMethod') }}</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ t('labels.paymentsLabel.status') }}</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ t('labels.paymentsLabel.date') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="payment in payments"  :key="payment.id" >
                                        <td class="px-6 py-4 text-center whitespace-nowrap">{{ payment.reference }}</td>
                                        <td class="px-6 py-4 text-center whitespace-nowrap">{{ payment.amount }}</td>
                                        <td class="px-6 py-4 text-center whitespace-nowrap">{{ payment.payment_method }}</td>
                                        <td class="px-6 py-4 text-center whitespace-nowrap">{{ payment.status }}</td>
                                        <td class="px-6 py-4 text-center whitespace-nowrap">{{ payment.date }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
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

<style scoped>
.microsite-show {
    max-width: 1200px;
    margin: auto;
    padding: 20px;
    background: #f9f9f9;
}

.header-section, .details-section {
    margin-top: 20px;
}

.card, .logo-section {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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

.table {
    width: 100%;
    border-collapse: collapse;
}

.table th, .table td {
    padding: 12px;
    text-align: left;
    border: 1px solid #ddd;
}

.table th {
    background-color: #f0f0f0;
    font-weight: bold;
}

.table td {
    background-color: white;
}

.table tr:nth-child(even) {
    background-color: #f9f9f9;
}

.table tr:hover {
    background-color: #f1f1f1;
}
</style>
