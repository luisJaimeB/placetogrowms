<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useI18n } from "vue-i18n";
import DashboardInvoice from "@/Components/dashboards/DashboardInvoice.vue";
import DashboardSuscription from "@/Components/dashboards/DashboardSuscription.vue";
import DashboardPayments from "@/Components/dashboards/DashboardPayments.vue";

const { t } = useI18n();

const props = defineProps({
    payments: {
        type: Array,
        required: false
    },
    invoices: {
        type: Object,
        required: false
    },
    suscriptions: {
        type: Object,
        required: false
    },
    typeSiteIdFlag: {
        type: Number,
        required: false
    },
    message: {
        type: String,
        required: false
    }
});

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <!-- Payment Section -->
        <!-- <div class="payment-section mt-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-xl font-bold mb-4">{{ t('titles.payments') }}</h2>
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
                    <tbody class="bg-white divide-y divide-gray-200 cursor-pointer">
                    <tr v-for="payment in payments" :key="payment.id" @click="paymentDetail(payment.id)">
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
        </div> -->
        <!--<div class="container mx-auto p-4">

            <div class="payment-section mt-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-xl font-bold mb-4">Facturas</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow-md text-center">
                        <h3 class="text-gray-500 text-left uppercase text-xs font-medium">Facturas Totales</h3>
                        <p class="mt-4 text-3xl font-bold text-blue-600">{{ allInvoices }}</p>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-md text-center">
                        <h3 class="text-gray-500 text-left uppercase text-xs font-medium">Facturas Pagadas</h3>
                        <p class="mt-4 text-3xl font-bold text-green-600">{{ paid }}</p>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-md text-center">
                        <h3 class="text-gray-500 text-left uppercase text-xs font-medium">Facturas Pendientes</h3>
                        <p class="mt-4 text-3xl font-bold text-yellow-600">{{ pending }}</p>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-md text-center">
                        <h3 class="text-gray-500 text-left uppercase text-xs font-medium">Facturas Vencidas</h3>
                        <p class="mt-4 text-3xl font-bold text-red-600">{{ expired }}</p>
                    </div>
                </div>
            </div>


            <div class="payment-section mt-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-xl font-bold mb-4">Métricas</h2>

                <div class="card bg-white shadow-lg rounded-lg p-4 mb-4">
                    <h2 class="text-lg font-semibold mb-2">Facturas Activas vs Pagadas</h2>
                    <div class="relative" style="height: 300px;">
                        <Bar :data="dataBar" :options="options" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="card bg-white shadow-lg rounded-lg p-4 mb-4">
                        <h2 class="text-lg font-semibold mb-2">Facturas Pendientes vs Vencidas</h2>
                        <div class="relative" style="height: 300px;">
                            <Pie :data="dataPie" :options="options" />
                        </div>
                    </div>

                    <div class="card bg-white shadow-lg rounded-lg p-4 mb-4">
                        <h2 class="text-lg font-semibold mb-2">Detalles de Facturas vencidas</h2>

                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                <tr class="w-full bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Número de Factura</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Fecha de Emisión</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Fecha de Vencimiento</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Monto</th>
                                </tr>
                                </thead>
                                <tbody class="text-gray-600 text-sm font-light">
                                <tr v-for="invoice in expiredInvoices()" :key="invoice.id" class="border-b border-gray-200 hover:bg-gray-100">
                                    <td class="py-3 px-6 text-center"> {{ invoice.order_number }}</td>
                                    <td class="py-3 px-6 text-center">{{ invoice.debtor_name }}</td>
                                    <td class="py-3 px-6 text-center hidden sm:table-cell">{{ new Date(invoice.created_at).toLocaleDateString('es-ES') }}</td>
                                    <td class="py-3 px-6 text-center hidden md:table-cell">{{ new Date(invoice.expiration_date).toLocaleDateString('es-ES') }}</td>
                                    <td class="py-3 px-6 text-center">{{ invoice.amount }} {{ invoice.currency.code }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>-->
        <div>
            <h1>{{ props.message }}</h1>
        </div>
        <div v-if="typeSiteIdFlag === 1">
            <DashboardPayments :payments="payments"></DashboardPayments>
        </div>
        <div v-if="typeSiteIdFlag === 2">
            <DashboardInvoice :invoices="invoices"></DashboardInvoice>
        </div>
        <div v-if="typeSiteIdFlag === 3">
            <DashboardSuscription :suscriptions="suscriptions"></DashboardSuscription>
        </div>

    </AuthenticatedLayout>
</template>
