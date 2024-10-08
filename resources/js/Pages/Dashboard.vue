<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Chart as ChartJS, ArcElement, Tooltip, Legend, BarElement, CategoryScale, LinearScale, Title} from 'chart.js';
import { Pie, Bar } from 'vue-chartjs'
import {Head, router} from '@inertiajs/vue3';
import { useI18n } from "vue-i18n";

const { t } = useI18n();

const props = defineProps({
    payments: {
        type: Array,
        required: true
    },
    invoices: {
        type: Object,
        required: true
    }
});

ChartJS.register(ArcElement, Tooltip, Legend, CategoryScale, LinearScale, BarElement, Title)
const rawPieLabels = new Set()
const rawBarLabels = new Set()
const statusColors = {
    ACTIVE: '#41B883',
    PAY: '#00D8FF',
    EXPIRED: '#E46651'
}
let paid = 0
let pending = 0
let expired = 0
let paidData =  new Array(12).fill(0)
let activeData =  new Array(12).fill(0)
const allInvoices = props.invoices.length
const months = [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December'
]

Object.values(props.invoices).forEach(invoice => {

    const createdDate = new Date(invoice.created_at)
    const monthIndex = createdDate.getMonth()

    switch (invoice.status) {
        case 'ACTIVE':
            pending++
            rawBarLabels.add(invoice.status)
            rawPieLabels.add(invoice.status)
            activeData[monthIndex]++;
            break
        case 'PAY':
            paid++
            rawBarLabels.add(invoice.status)
            paidData[monthIndex]++;
            break
        case 'EXPIRED':
            expired++
            rawPieLabels.add(invoice.status)
            break
    }
})
const pieLabels = Array.from(rawPieLabels).sort()
const barLabels = Array.from(rawBarLabels).sort()
const pieColors = pieLabels.map(label => statusColors[label])

const dataBar = {
    labels: months,
    datasets: [
        {
            label: barLabels.filter(label => label === 'ACTIVE'),
            backgroundColor: statusColors['ACTIVE'],
            data: activeData.filter(value => value !== null),
            borderColor: '#ddd',
            borderWidth: 1,
        },
        {
            label: barLabels.filter(label => label === 'PAY'),
            backgroundColor: statusColors['PAY'],
            data: paidData.filter(value => value !== null),
            borderColor: '#ddd',
            borderWidth: 1,
        }
    ]
}

const dataPie = {
    labels: pieLabels,
    datasets: [
        {
            backgroundColor: pieColors,
            data: [pending > 0 ? pending : null,
                expired > 0 ? expired : null].filter(value => value !== null),
            borderColor: '#ddd',
            borderWidth: 1,
        }
    ]
}

const options = {
    responsive: true,
    maintainAspectRatio: false,
}

const expiredInvoices = () => {
    return props.invoices.filter(invoice => invoice.status === 'EXPIRED')
}

function paymentDetail(paymentId) {
    router.visit(route('payment.details', { id: paymentId }));
}

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
        <div class="container mx-auto p-4"> <!-- Contenedor principal -->

            <div class="payment-section mt-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-xl font-bold mb-4">Facturas</h2>
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                        <tr>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Facturas totales</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Facturas pagadas</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Facturas Pendientes</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Facturas vencidas</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 cursor-pointer">
                        <tr>
                            <td class="px-6 py-4 text-center whitespace-nowrap">{{ allInvoices }}</td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">{{ paid }}</td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">{{ pending }}</td>
                            <td class="px-6 py-4 text-center whitespace-nowrap"> {{ expired }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="payment-section mt-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-xl font-bold mb-4">Métricas</h2>

                <div class="card bg-white shadow-lg rounded-lg p-4 mb-4"> <!-- Tarjeta para el primer gráfico -->
                    <h2 class="text-lg font-semibold mb-2">Facturas Activas vs Pagadas</h2>
                    <div class="relative" style="height: 300px;"> <!-- Establecer altura -->
                        <Bar :data="dataBar" :options="options" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4"> <!-- Usar grid para dos columnas -->
                    <div class="card bg-white shadow-lg rounded-lg p-4 mb-4"> <!-- Tarjeta para el gráfico de pie -->
                        <h2 class="text-lg font-semibold mb-2">Facturas Pendientes vs Vencidas</h2>
                        <div class="relative" style="height: 300px;"> <!-- Establecer altura -->
                            <Pie :data="dataPie" :options="options" />
                        </div>
                    </div>

                    <div class="card bg-white shadow-lg rounded-lg p-4 mb-4">
                        <!-- Tarjeta para la tabla -->
                        <h2 class="text-lg font-semibold mb-2">Detalles de Facturas vencidas</h2>

                        <!-- Hacemos la tabla desplazable horizontalmente -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                <tr class="w-full bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Número de Factura</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                    <!-- Ocultamos en pantallas pequeñas -->
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Fecha de Emisión</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Fecha de Vencimiento</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Monto</th>
                                </tr>
                                </thead>
                                <tbody class="text-gray-600 text-sm font-light">
                                <tr v-for="invoice in expiredInvoices()" :key="invoice.id" class="border-b border-gray-200 hover:bg-gray-100">
                                    <td class="py-3 px-6 text-center"> {{ invoice.order_number }}</td>
                                    <td class="py-3 px-6 text-center">{{ invoice.debtor_name }}</td>
                                    <!-- Ocultamos en pantallas pequeñas -->
                                    <td class="py-3 px-6 text-center hidden sm:table-cell">{{ new Date(invoice.created_at).toLocaleDateString('es-ES') }}</td>
                                    <!-- Ocultamos en pantallas medianas y pequeñas -->
                                    <td class="py-3 px-6 text-center hidden md:table-cell">{{ new Date(invoice.expiration_date).toLocaleDateString('es-ES') }}</td>
                                    <td class="py-3 px-6 text-center">{{ invoice.amount }} {{ invoice.currency.code }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </AuthenticatedLayout>
</template>
