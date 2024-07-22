<script>
export default {
    name: 'MicrositesIndex'
}
</script>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    microsites: {
        type: Object,
        required: true
    }
})

const goToMicrositeShow = (micrositeId) => {
    // Redirige al usuario al detalle (show) del micrositio
    router.push(route('microsites.show', micrositeId));
}

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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{$page.props.trans.common.titles.microsites}}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 bg-white border-b border-gray-200">
                    <button class="bg-blue-500 text-white px-4 py-2 rounded" v-if="$page.props.user.permissions.includes('microsites.create')">
                        <Link :href="route('microsites.create')">
                            {{$page.props.trans.common.actions.microsites.create}}
                        </Link>
                    </button>
                </div>
                <div class="mt-4">
                    <table class="min-w-full bg-white border rounded-lg">
                        <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">{{$page.props.trans.common.fields.id}}</th>
                            <th class="py-2 px-4 border-b">{{$page.props.trans.common.fields.name}}</th>
                            <th class="py-2 px-4 border-b">{{$page.props.trans.common.fields.type}}</th>
                            <th class="py-2 px-4 border-b">{{$page.props.trans.common.fields.category}}</th>
                            <th class="py-2 px-4 border-b">{{$page.props.trans.common.actionsLabel}}</th>
                        </tr>
                        </thead>
                        <tbody>

                        <tr v-for="microsite in microsites"  :key="microsite.id" >
                            <td class="py-2 px-4 border-b text-center">{{ microsite.id }}</td>
                            <td class="py-2 px-4 border-b text-center">
                                <button class="text-blue-500 underline cursor-pointer">
                                    <Link :href="route('microsites.show', microsite.id)">{{ microsite.name }}</Link>
                                </button>
                            </td>
                            <td class="py-2 px-4 border-b text-center">{{ microsite.type_site.name }}</td>
                            <td class="py-2 px-4 border-b text-center">{{ microsite.category.name }}</td>
                            <td class="py-2 px-4 border-b text-center">
                            <button class="bg-green-500 text-white px-4 py-1 rounded mr-2" v-if="$page.props.user.permissions.includes('microsites.update')">
                                <Link :href="route('microsites.edit', microsite.id)">{{$page.props.trans.common.actions.microsites.edit}}</Link>
                            </button>
                            <button @click="deleteMicrosite(microsite.id)" class="bg-red-500 text-white px-4 py-1 rounded">
                                {{$page.props.trans.common.actions.microsites.delete}}
                            </button>
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
