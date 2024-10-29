<script>
export default {
    name: 'MicrositesIndex'
}
</script>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {Inertia} from "@inertiajs/inertia";

defineProps({
    microsites: {
        type: Object,
        required: true
    }
})

const { t } = useI18n();

const deleteMicrosite = id =>{
    if (confirm('¿Estás seguro?')) {
        router.delete(route('microsites.destroy', id))
    }
}

</script>

<template>
    <Head title="Microsites" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ t('titles.microsites') }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mt-4 rounded-lg overflow-hidden mx-4 md:mx-10">
                    <button class="bg-blue-500 text-white px-4 py-2 rounded" v-if="$page.props.user.permissions.includes('microsites.create')">
                        <Link :href="route('microsites.create')">
                            {{t('actions.microsites.create')}}
                        </Link>
                    </button>
                </div>
                <div class="mt-4 shadow-lg rounded-lg overflow-hidden mx-4 md:mx-10">
                    <table class="w-full table-fixed">
                        <thead>
                        <tr class="bg-gray-100">
                            <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">{{t('fields.id')}}</th>
                            <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">{{t('fields.name')}}</th>
                            <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">{{t('fields.type')}}</th>
                            <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">{{t('fields.category')}}</th>
                            <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">{{t('actionsLabel')}}</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white">

                        <tr v-for="microsite in microsites" :key="microsite.id"
                            class="transition-transform duration-300 ease-in-out transform hover:scale-95 cursor-pointer"
                        >
                            <td class="py-4 px-6 border-b border-gray-200">{{ microsite.id }}</td>
                            <td class="py-4 px-6 border-b border-gray-200 truncate">
                                <button class="shadow-lg font-bold bg-indigo-300 rounded-lg overflow-hidden px-2 oy-2">
                                    <Link :href="route('microsites.show', microsite.id)">{{ microsite.name }}</Link>
                                </button>
                            </td>
                            <td class="py-4 px-6 border-b text border-gray-200">{{ microsite.type_site.name }}</td>
                            <td class="py-4 px-6 border-b border-gray-200">{{ microsite.category.name }}</td>
                            <td class="py-4 px-6 border-b border-gray-200">
                                <button class="bg-green-400 text-white px-2 py-1 rounded mr-2" v-if="$page.props.user.permissions.includes('microsites.update')">
                                    <Link :href="route('microsites.edit', microsite.id)">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-white text-center h-6 w-full mr-2" fill="currentColor" viewBox="0 0 512 512">
                                            <path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160L0 416c0 53 43 96 96 96l256 0c53 0 96-43 96-96l0-96c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 96c0 17.7-14.3 32-32 32L96 448c-17.7 0-32-14.3-32-32l0-256c0-17.7 14.3-32 32-32l96 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L96 64z"/>
                                        </svg>

                                    </Link>
                                </button>
                                <button @click="deleteMicrosite(microsite.id)" class="bg-red-400 text-white px-2 py-1 rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="text-white text-center h-6 w-full mr-2" fill="currentColor" viewBox="0 0 448 512">
                                        <path d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0L284.2 0c12.1 0 23.2 6.8 28.6 17.7L320 32l96 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 96C14.3 96 0 81.7 0 64S14.3 32 32 32l96 0 7.2-14.3zM32 128l384 0 0 320c0 35.3-28.7 64-64 64L96 512c-35.3 0-64-28.7-64-64l0-320zm96 64c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16z"/>
                                    </svg>
                                </button>
                                <PrimaryButton class="text-white px-2 py-1 mx-2 rounded">
                                    <Link :href="route('payments.create', microsite.id)">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-white text-center h-6 w-full mr-2" fill="currentColor" viewBox="0 0 576 512">
                                            <path d="M64 32C28.7 32 0 60.7 0 96l0 32 576 0 0-32c0-35.3-28.7-64-64-64L64 32zM576 224L0 224 0 416c0 35.3 28.7 64 64 64l448 0c35.3 0 64-28.7 64-64l0-192zM112 352l64 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-64 0c-8.8 0-16-7.2-16-16s7.2-16 16-16zm112 16c0-8.8 7.2-16 16-16l128 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-128 0c-8.8 0-16-7.2-16-16z"/>
                                        </svg>
                                    </Link>
                                </PrimaryButton>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="flex justify-center items-center space-x-2 mt-4">
            <Link v-if="microsites.current_page > 1" :href="microsites.prev_page_url" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 disabled:opacity-50">
                <<
            </Link>

            <Link v-if="microsites.current_page < microsites.last_page" :href="microsites.next_page_url" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                >>
            </Link>
        </div>
    </AuthenticatedLayout>
</template>
