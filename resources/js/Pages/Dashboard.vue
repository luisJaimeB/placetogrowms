<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useI18n } from "vue-i18n";

const { t } = useI18n();

const props = defineProps({
    payments: {
        type: Array,
        required: true
    }
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <!-- Payment Section -->
        <div class="payment-section mt-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ t('fields.microsite') }}</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="payment in payments" :key="payment.id">
                        <td class="px-6 py-4 text-center whitespace-nowrap">{{ payment.reference }}</td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">{{ payment.amount }}</td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">{{ payment.payment_method }}</td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">{{ payment.status }}</td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">{{ payment.date }}</td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">{{ payment.microsite.name }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
