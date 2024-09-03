<script setup>
import {useI18n} from "vue-i18n";
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    payment: {
        type: Object,
        required: true
    }
});

const { t } = useI18n();

const getLogoUrl = (path) => {
    return path ? `/microsite/logo/${path}` : null;
};

const getStatusClass = (status) => {
    switch (status) {
        case 'APPROVED':
            return 'status-approved';
        case 'PENDING':
            return 'status-pending';
        case 'REJECTED':
            return 'status-rejected';
        default:
            return '';
    }
};
</script>

<template>
    <div class="bg-gray-100 flex items-center justify-center min-h-screen bg-gradient-to-br from-purple-100 to-indigo-200 p-4">
        <div class="max-w-md w-full bg-white rounded-xl shadow-2xl overflow-hidden transform transition duration-500 hover:scale-105">
            <!-- Banner -->
            <div class="relative">
                <img v-if="payment.microsite.logo" :src="getLogoUrl(payment.microsite.logo)" alt="Logo" class="w-full h-64 object-cover">
            </div>

            <!-- Card -->
            <div class="p-6">
                <!-- Card Header -->
                <div :class="[getStatusClass(payment.status), 'text-white text-center px-2 py-1 m-2 rounded-md text-sm font-semibold']">
                    {{ t('labels.paymentsLabel.status') }}: {{ payment.status }}
                </div>

                <!-- Card Content -->
                <div class="space-y-4 m-2">
                    <div class="flex justify-between">
                        <div class="w-1/2">
                            <span class="font-bold">{{ t('labels.paymentsLabel.reference') }}: </span>
                            <span>{{ payment.reference }} </span>
                        </div>
                        <div class="w-1/2">
                            <span class="font-bold">{{ t('labels.paymentsLabel.cus') }}: </span>
                            <span>{{ payment.cus_code }}</span>
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <div class="w-1/2">
                            <span class="font-bold">{{ t('labels.paymentsLabel.amount') }}: </span>
                            <span>$ {{ payment.amount }} </span>
                        </div>
                        <div class="w-1/2">
                            <span class="font-bold">{{ t('labels.paymentsLabel.currency') }}: </span>
                            <span> {{ payment.currency.code }} </span>
                        </div>
                    </div>
                    <div>
                        <span class="font-bold">{{ t('labels.paymentsLabel.date') }}: </span>
                        <span> {{ payment.date }} </span>
                    </div>
                    <div class="flex justify-end">
                        <Link :href="route('payments.create', payment.microsite.id)" class="px-4 py-2 bg-indigo-600 text-white rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Volver al comercio
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.status-pending {
    background-color: #fbbf24; /* amarillo */
}
.status-approved {
    background-color: #34d399; /* verde */
}
.status-rejected {
    background-color: #f87171; /* rojo */
}
</style>
