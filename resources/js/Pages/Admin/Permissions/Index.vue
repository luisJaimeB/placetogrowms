<script>
export default {
    name: 'PermissionsIndex'
}
</script>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';

defineProps({
    permissions: {
        type: Object,
        required: true
    }
})

const deletePermission = id =>{
    if (confirm('¿Estás seguro?')) {
        router.delete(route('permissions.destroy', id))
    }
}
</script>

<template>
    <Head title="Permissions" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{$page.props.trans.common.titles.permissions}}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 bg-white border-b border-gray-200">
                    <button class="bg-blue-500 text-white px-4 py-2 rounded">
                        <Link :href="route('permissions.create')" v-if="$page.props.user.permissions.includes('permissions.create')">
                            {{$page.props.trans.common.actions.permissions.create}}
                        </Link>
                    </button>
                </div>
                <div class="mt-4">
                    <table class="min-w-full bg-white border rounded-lg">
                        <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">{{$page.props.trans.common.fields.id}}</th>
                            <th class="py-2 px-4 border-b">{{$page.props.trans.common.fields.name}}</th>
                            <th class="py-2 px-4 border-b">{{$page.props.trans.common.actionsLabel}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        
                        <tr v-for="permission in permissions.data">
                            <td class="py-2 px-4 border-b text-center">{{ permission.id }}</td>
                            <td class="py-2 px-4 border-b text-center">{{ permission.name }}</td>
                            <td class="py-2 px-4 border-b text-center">
                                <button class="bg-green-500 text-white px-4 py-1 rounded mr-2">
                                    <Link :href="route('permissions.edit', permission.id)">{{$page.props.trans.common.actions.permissions.edit}}</Link>
                                </button>
                                <button @click="deletePermission(permission.id)" class="bg-red-500 text-white px-4 py-1 rounded">
                                    {{$page.props.trans.common.actions.permissions.delete}}
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="flex justify-center items-center space-x-2 mt-4">
            <Link v-if="permissions.current_page > 1" :href="permissions.prev_page_url" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 disabled:opacity-50">
                <<
            </Link>

            <Link v-if="permissions.current_page < permissions.last_page" :href="permissions.next_page_url" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                >>
            </Link>
        </div>
    </AuthenticatedLayout>
</template>
