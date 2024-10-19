<script>
export default {
    name: "SuscriptionShow",
};
</script>

<script setup>

import {Head, Link} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {useI18n} from "vue-i18n";

defineProps({
    suscription: {
        type: Object,
        required: true
    },
    payments: {
        type: Object,
        required: true
    },
})

const { t } = useI18n();
</script>

<template>
    <Head title="Microsites" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ t('titles.microsites') }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mt-4 shadow-lg rounded-lg overflow-hidden mx-4 md:mx-10">
                    <h2 class="text-xl font-bold mb-4">Suscripción</h2>
                    <table class="w-full table-fixed">
                        <thead>
                        <tr class="bg-gray-100">
                            <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">Nombre</th>
                            <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">Micrositio</th>
                            <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">Estado</th>
                            <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">Monto</th>
                            <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">Próximo pago</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white">
                        <tr class="transition-transform duration-300 ease-in-out transform hover:scale-95 cursor-pointer">
                            <td class="py-4 px-6 border-b text border-gray-200">{{ suscription.suscription_plan.name }}</td>
                            <td class="py-4 px-6 border-b text border-gray-200">{{ suscription.microsite.name }}</td>
                            <td class="py-4 px-6 border-b border-gray-200">{{ suscription.status }}</td>
                            <td class="py-4 px-6 border-b border-gray-200">{{ suscription.suscription_plan.amount }}</td>
                            <td class="py-4 px-6 border-b border-gray-200">{{ suscription.next_billing_date }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 shadow-lg rounded-lg overflow-hidden mx-4 md:mx-10">
                    <h2 class="text-xl font-bold mb-4">Pago inicial</h2>
                    <table class="w-full table-fixed">
                        <thead>
                        <tr class="bg-gray-100">
                            <th class="w-1/4 py-4 px-6 text-center text-gray-600 font-bold uppercase">Estado</th>
                            <th class="w-1/4 py-4 px-6 text-center text-gray-600 font-bold uppercase">Referencia</th>
                            <th class="w-1/4 py-4 px-6 text-center text-gray-600 font-bold uppercase">Fecha</th>
                            <th class="w-1/4 py-4 px-6 text-center text-gray-600 font-bold uppercase">Monto</th>
                            <th class="w-1/4 py-4 px-6 text-center text-gray-600 font-bold uppercase">Sesión ID</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white">
                        <tr class="transition-transform duration-300 ease-in-out transform hover:scale-95 cursor-pointer">
                            <td class="py-4 px-6 border-b text-center border-gray-200">{{ suscription.initial_payment.status }}</td>
                            <td class="py-4 px-6 border-b text-center border-gray-200">{{ suscription.initial_payment.reference }}</td>
                            <td class="py-4 px-6 border-b text-center border-gray-200">{{ suscription.initial_payment.date }}</td>
                            <td class="py-4 px-6 border-b text-center border-gray-200">{{ suscription.initial_payment.amount }}</td>
                            <td class="py-4 px-6 border-b text-center border-gray-200">{{ suscription.initial_payment.request_id }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 shadow-lg rounded-lg overflow-hidden mx-4 md:mx-10">
                    <h2 class="text-xl font-bold mb-4">Cobros realizados</h2>
                    <table class="w-full table-fixed">
                        <thead>
                        <tr class="bg-gray-100">
                            <th class="w-1/4 py-4 px-6 text-center text-gray-600 font-bold uppercase">Estado</th>
                            <th class="w-1/4 py-4 px-6 text-center text-gray-600 font-bold uppercase">Referencia</th>
                            <th class="w-1/4 py-4 px-6 text-center text-gray-600 font-bold uppercase">Fecha</th>
                            <th class="w-1/4 py-4 px-6 text-center text-gray-600 font-bold uppercase">Monto</th>
                            <th class="w-1/4 py-4 px-6 text-center text-gray-600 font-bold uppercase">Sesión ID</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white">
                        <tr v-for="payment in payments" :key="payment.id" class="transition-transform duration-300 ease-in-out transform hover:scale-95 cursor-pointer">
                            <td class="py-4 px-6 border-b text-center border-gray-200">{{ payment.status }}</td>
                            <td class="py-4 px-6 border-b text-center border-gray-200">{{ payment.reference }}</td>
                            <td class="py-4 px-6 border-b text-center border-gray-200">{{ payment.date }}</td>
                            <td class="py-4 px-6 border-b text-center border-gray-200">{{ payment.amount }}</td>
                            <td class="py-4 px-6 border-b text-center border-gray-200">{{ payment.request_id }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


<style scoped>

</style>
