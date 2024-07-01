<script>
export default {
    name: 'RolesIndex'
}
</script>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';

defineProps({
    roles: {
        type: Object,
        required: true
    }
})

const deleteRole = id =>{
    if (confirm('¿Estás seguro?')) {
        router.delete(route('roles.destroy', id))
    }
}
</script>

<template>
    <Head title="Roles" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{$page.props.trans.common.titles.roles}}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 bg-white border-b border-gray-200">
                    <button class="bg-blue-500 text-white px-4 py-2 rounded">
                        <Link :href="route('roles.create')">
                            {{$page.props.trans.common.actions.roles.create}}
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
                        
                        <tr v-for="role in roles.data">
                            <td class="py-2 px-4 border-b text-center">{{ role.id }}</td>
                            <td class="py-2 px-4 border-b text-center">{{ role.name }}</td>
                            <td class="py-2 px-4 border-b text-center">
                            <button class="bg-green-500 text-white px-4 py-1 rounded mr-2">
                                <Link :href="route('roles.edit', role.id)">{{$page.props.trans.common.actions.roles.edit}}</Link>
                            </button>
                            <button @click="deleteRole(role.id)" class="bg-red-500 text-white px-4 py-1 rounded" v-if="$page.props.user.permissions.includes('roles.delete')">
                                {{$page.props.trans.common.actions.roles.delete}}
                            </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
