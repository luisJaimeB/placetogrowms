<script setup>
import {useI18n} from "vue-i18n";
import {ArcElement, BarElement, CategoryScale, Chart as ChartJS, Legend, LinearScale, Title, Tooltip} from "chart.js";
import {Bar, Pie} from "vue-chartjs";

const { t } = useI18n();

const props = defineProps({
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
</script>

<template>
    <div class="container mx-auto p-4">

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

    </div>
</template>

<style scoped>

</style>
