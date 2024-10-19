<script>
export default {
    name: 'SuscriptionPlansIndex'
}
</script>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import {useI18n} from "vue-i18n";
import {SButton, SCard, SDefinitionTerm, SLink, SSectionDescription, SSectionTitle} from "@placetopay/spartan-vue";

const { t } = useI18n();

defineProps({
    suscriptions: {
        type: Object,
        required: true
    },
    editing: {
        type: Boolean,
        required: false,
        default: false
    },
})

const deleteSuscription = id =>{
    if (confirm('¿Estás seguro, la cancelación de tu suscripción no se podrá reversar?')) {
        router.delete(route('subscriptions.destroy', id))
    }
}

function goToSuscriptionIndex(suscriptionId) {
    router.visit(route('subscriptions.show', { id: suscriptionId }));
}
</script>

<template>
    <Head title="Suscriptions" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ t('titles.suscriptions') }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="suscriptions.length === 0" class="text-center mt-16">
                    <p class="text-lg font-medium text-gray-500">No se tienen suscripciones activas.</p>
                </div>
                <!-- Tarjetas -->
                <!-- <ul v-for="suscription in suscriptions" :key="suscription.id" class="bg-white shadow overflow-hidden sm:rounded-md max-w-sm mx-auto mt-16">
                    <li>
                        <div class="px-4 py-5 sm:px-6">

                            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ suscription.suscription_plan.name }}</h3>

                            <div class="mt-4 flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-500">
                                    <strong>Micrositio:</strong> {{ suscription.microsite.name }}
                                </p>
                            </div>

                            <div class="mt-2">
                                <ul class="mt-1 max-w-2xl text-sm text-gray-500">
                                    <li v-for="(item, index) in suscription.suscription_plan.items" :key="index" class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round" class="flex-shrink-0 w-6 h-6 text-emerald-500" aria-hidden="true">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        <span class="ml-3">{{ item }}</span>
                                    </li>
                                </ul>
                            </div>


                            <div class="mt-4 flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-500">Status: <span class="text-green-600">{{ suscription.status }}</span></p>
                                <button @click="deleteSuscription(suscription.id)" class="bg-red-400 text-white px-2 py-1 rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="text-white text-center h-6 w-full mr-2" fill="currentColor" viewBox="0 0 448 512">
                                        <path d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0L284.2 0c12.1 0 23.2 6.8 28.6 17.7L320 32l96 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 96C14.3 96 0 81.7 0 64S14.3 32 32 32l96 0 7.2-14.3zM32 128l384 0 0 320c0 35.3-28.7 64-64 64L96 512c-35.3 0-64-28.7-64-64l0-320zm96 64c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </li>
                </ul> -->

                <div v-for="suscription in suscriptions" :key="suscription.id">
                    <SCard class="mx-auto w-full max-w-4xl mt-4">
                        <div class="flex justify-between gap-8">
                            <div>
                                <SSectionTitle>{{ suscription.suscription_plan.name }}</SSectionTitle>
                                <SSectionDescription>
                                    {{ suscription.microsite.name }}
                                </SSectionDescription>
                            </div>
                            <div class="space-x-3">
                                <SButton variant="danger" @click="deleteSuscription(suscription.id)">Delete</SButton>
                                <SButton v-if="editing" @click="goToSuscriptionIndex(suscription.id)">Show</SButton>
                            </div>
                        </div>
                        <div class="mt-6">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                                <SDefinitionTerm class="sm:col-span-1">
                                    Monto
                                    <template #description>{{ suscription.suscription_plan.amount }}</template>
                                </SDefinitionTerm>

                                <SDefinitionTerm class="sm:col-span-1">
                                    Status
                                    <template #description><span class="text-green-600">{{ suscription.status }}</span></template>
                                </SDefinitionTerm>

                                <SDefinitionTerm class="sm:col-span-1">
                                    Próximo pago
                                    <template #description>
                                        {{ suscription.next_billing_date }}
                                    </template>
                                </SDefinitionTerm>

                                <SDefinitionTerm class="sm:col-span-1">
                                    Finalización suscripción
                                    <template #description>
                                        {{ suscription.expiration_date }}
                                    </template>
                                </SDefinitionTerm>
                            </dl>
                        </div>
                    </SCard>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
