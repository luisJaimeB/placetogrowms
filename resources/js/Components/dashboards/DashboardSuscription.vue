<script setup>
import {Bar, Pie} from "vue-chartjs";
import {useI18n} from "vue-i18n";
import {ArcElement, BarElement, CategoryScale, Chart as ChartJS, Legend, LinearScale, Title, Tooltip} from "chart.js";

const { t } = useI18n();

const props = defineProps({
    suscriptions: {
        type: Object,
        required: true
    }
});
ChartJS.register(ArcElement, Tooltip, Legend, CategoryScale, LinearScale, BarElement, Title)
const rawPieLabels = new Set()
const rawBarLabels = new Set()
const statusColors = {
    Active: '#41B883',
    Freeze: '#00D8FF',
    Suspended: '#E46651',
    Cancelled: '#E43215'
}
const allSuscriptions = props.suscriptions.length
let active = 0
let cancelled = 0
let freeze = 0
let suspended = 0
let activeData =  new Array(12).fill(0)
let suspendedData =  new Array(12).fill(0)
let freezeData =  new Array(12).fill(0)
let cancelledData = new Array(12).fill(0)

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

Object.values(props.suscriptions).forEach(suscription => {

    const createdDate = new Date(suscription.created_at)
    const monthIndex = createdDate.getMonth()

    switch (suscription.status) {
        case 'Active':
            active++
            rawBarLabels.add(suscription.status)
            rawPieLabels.add(suscription.status)
            activeData[monthIndex]++;
            break
        case 'Freeze':
            freeze++
            rawBarLabels.add(suscription.status)
            freezeData[monthIndex]++;
            break
        case 'Suspended':
            suspended++
            rawPieLabels.add(suscription.status)
            rawBarLabels.add(suscription.status)
            suspendedData[monthIndex]++;
            break
        case 'Cancelled':
            cancelled++
            cancelledData[monthIndex]++;
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
            label: barLabels.filter(label => label === 'Active'),
            backgroundColor: statusColors['Active'],
            data: activeData.filter(value => value !== null),
            borderColor: '#ddd',
            borderWidth: 1,
        },
        {
            label: barLabels.filter(label => label === 'Freeze'),
            backgroundColor: statusColors['Freeze'],
            data: freezeData.filter(value => value !== null),
            borderColor: '#ddd',
            borderWidth: 1,
        },
        {
            label: barLabels.filter(label => label === 'Suspended'),
            backgroundColor: statusColors['Suspended'],
            data: suspendedData.filter(value => value !== null),
            borderColor: '#ddd',
            borderWidth: 1,
        },

    ]
}

const dataPie = {
    labels: pieLabels,
    datasets: [
        {
            backgroundColor: pieColors,
            data: [freeze > 0 ? freeze : null,
                suspended > 0 ? suspended : null].filter(value => value !== null),
            borderColor: '#ddd',
            borderWidth: 1,
        }
    ]
}

const options = {
    responsive: true,
    maintainAspectRatio: false,
}

const expiredSuscriptions = () => {
    return props.suscriptions.filter(suscription => suscription.status === 'Suspended')
}
</script>

<template>
    <div class="container mx-auto p-4">

        <div class="payment-section mt-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-xl font-bold mb-4">Suscripciones</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <h3 class="text-gray-500 text-left uppercase text-xs font-medium">Suscripciones Totales</h3>
                    <p class="mt-4 text-3xl font-bold text-blue-600">{{ allSuscriptions }}</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <h3 class="text-gray-500 text-left uppercase text-xs font-medium">Suscripciones Activas</h3>
                    <p class="mt-4 text-3xl font-bold text-green-600">{{ active }}</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <h3 class="text-gray-500 text-left uppercase text-xs font-medium">Suscripciones Conjeladas</h3>
                    <p class="mt-4 text-3xl font-bold text-yellow-600">{{ freeze }}</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <h3 class="text-gray-500 text-left uppercase text-xs font-medium">Suscripciones Suspendidas</h3>
                    <p class="mt-4 text-3xl font-bold text-red-600">{{ suspended }}</p>
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
                    <h2 class="text-lg font-semibold mb-2">Suscripciones Freeze vs Suspendidas</h2>
                    <div class="relative" style="height: 300px;">
                        <Pie :data="dataPie" :options="options" />
                    </div>
                </div>

                <div class="card bg-white shadow-lg rounded-lg p-4 mb-4">
                    <h2 class="text-lg font-semibold mb-2">Detalles de Suscripciones vencidas</h2>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                            <tr class="w-full bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Número de Suscripción</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Fecha de Emisión</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Fecha de Vencimiento</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                            </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm font-light">
                            <tr v-for="suscription in expiredSuscriptions()" :key="suscription.id" class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6 text-center"> {{ suscription.initial_payment.reference }}</td>
                                <td class="py-3 px-6 text-center">{{ suscription.user.name }}</td>
                                <td class="py-3 px-6 text-center hidden sm:table-cell">{{ new Date(suscription.created_at).toLocaleDateString('es-ES') }}</td>
                                <td class="py-3 px-6 text-center hidden md:table-cell">{{ new Date(suscription.expiration_date).toLocaleDateString('es-ES') }}</td>
                                <td class="py-3 px-6 text-center">{{ suscription.suscription_plan.name }}</td>
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
