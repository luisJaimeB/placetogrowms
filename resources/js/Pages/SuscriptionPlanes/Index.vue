<script>
export default {
    name: 'SuscriptionPlansIndex'
}
</script>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import {useI18n} from "vue-i18n";

const { t } = useI18n();

defineProps({
    plans: {
        type: Object,
        required: true
    }
})

const deletePlan = id =>{
    if (confirm('¿Estás seguro?')) {
        router.delete(route('planes.destroy', id))
    }
}
</script>

<template>
    <Head title="Suscriptions" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ t('titles.suscriptionPlanes') }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Botón de crear planes -->
                <div class="mt-4 py-6 rounded-lg overflow-hidden mx-4 md:mx-10">
                    <button class="bg-blue-500 text-white px-4 py-2 rounded">
                        <Link :href="route('planes.create')" v-if="$page.props.user.permissions.includes('planes.create')">
                            {{t('actions.suscriptions.create')}}
                        </Link>
                    </button>
                </div>

                <!-- Tarjetas -->
                <div class="mt-16 container lg:space-y-0 lg:grid lg:grid-cols-2 lg:gap-x-8">
                    <!-- Si no hay planes de suscripción, mostrar una tarjeta con el mensaje -->
                    <div v-if="plans.length === 0" class="relative rounded-2xl ml-4 flex flex-col">
                        <div class="flex-1 pl-8">
                            <h3 class="text-xl font-semibold text-gray-900">No tienes planes de suscripción creados</h3>
                            <p class="mt-4 text-gray-600">Parece que aún no has creado ningún plan de suscripción. Puedes crear uno usando el botón de arriba.</p>
                        </div>
                    </div>

                    <!-- Renderizar las tarjetas de los planes si existen -->
                    <div v-else v-for="plan in plans" :key="plan.id" class="relative bg-white p-8 border border-gray-200 rounded-2xl shadow-sm flex flex-col">
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold">{{ plan.name }}</h3>
                            <p class="mt-4 flex items-baseline">
                                <span class="text-5xl font-extrabold tracking-tight">${{ plan.amount }}</span><span class="ml-1 text-xl font-semibold">/{{ plan.periodicity }}</span>
                            </p>
                            <p class="mt-6">El cobro se realizará con una periodicidad {{ plan.periodicity }}</p>
                            <ul role="list" class="mt-6 space-y-6">
                                <li v-for="(item, index) in plan.items" :key="index" class="flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 w-6 h-6 text-emerald-500" aria-hidden="true">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    <span class="ml-3">{{ item }}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button class="bg-green-400 text-white px-2 py-1 rounded mr-2" v-if="$page.props.user.permissions.includes('planes.update')">
                                <Link :href="route('planes.edit', plan.id)">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="text-white text-center h-6 w-full mr-2" fill="currentColor" viewBox="0 0 512 512">
                                        <path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160L0 416c0 53 43 96 96 96l256 0c53 0 96-43 96-96l0-96c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 96c0 17.7-14.3 32-32 32L96 448c-17.7 0-32-14.3-32-32l0-256c0-17.7 14.3-32 32-32l96 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L96 64z"/>
                                    </svg>
                                </Link>
                            </button>
                            <button @click="deletePlan(plan.id)" class="bg-red-400 text-white px-2 py-1 rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" class="text-white text-center h-6 w-full mr-2" fill="currentColor" viewBox="0 0 448 512">
                                    <path d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0L284.2 0c12.1 0 23.2 6.8 28.6 17.7L320 32l96 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 96C14.3 96 0 81.7 0 64S14.3 32 32 32l96 0 7.2-14.3zM32 128l384 0 0 320c0 35.3-28.7 64-64 64L96 512c-35.3 0-64-28.7-64-64l0-320zm96 64c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
