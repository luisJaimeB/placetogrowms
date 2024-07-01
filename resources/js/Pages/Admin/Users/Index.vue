<script>
export default {
    name: 'UsersIndex'
}
</script>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';

defineProps({
    users: {
        type: Object,
        required: true
    }
})

const deleteUser = id =>{
    if (confirm('¿Estás seguro?')) {
        router.delete(route('users.destroy', id))
    }
}
</script>

<template>
    <Head title="Users"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $page.props.trans.common.titles.users }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 bg-white border-b border-gray-200">
                    <button class="bg-blue-500 text-white px-4 py-2 rounded" v-if="$page.props.user.permissions.includes('users.create')">
                        <Link :href="route('users.create')">
                            {{$page.props.trans.common.actions.users.create}}
                        </Link>
                    </button>
                </div>
                <div class="mt-4">
                    <table class="min-w-full bg-white border rounded-lg">
                        <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">{{$page.props.trans.common.fields.id}}</th>
                            <th class="py-2 px-4 border-b">{{$page.props.trans.common.fields.name}}</th>
                            <th class="py-2 px-4 border-b">{{$page.props.trans.common.fields.users.email}}</th>
                            <th class="py-2 px-4 border-b">{{$page.props.trans.common.actionsLabel}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        
                        <tr v-for="user in users.data">
                            <td class="py-2 px-4 border-b text-center">{{ user.id }}</td>
                            <td class="py-2 px-4 border-b text-center">{{ user.name }}</td>
                            <td class="py-2 px-4 border-b text-center">{{ user.email }}</td>
                            <td class="py-2 px-4 border-b text-center">
                            <button class="bg-green-500 text-white px-4 py-1 rounded mr-2" v-if="$page.props.user.permissions.includes('users.update')">
                                <Link :href="route('users.edit', user.id)">{{$page.props.trans.common.actions.users.edit}}</Link>
                            </button>
                            <button @click="deleteUser(user.id)" class="bg-red-500 text-white px-4 py-1 rounded">
                                {{$page.props.trans.common.actions.users.delete}}
                            </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="flex justify-center items-center space-x-2 mt-4">
            <Link v-if="users.current_page > 1" :href="users.prev_page_url" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 disabled:opacity-50">
                <<
            </Link>

            <Link v-if="users.current_page < users.last_page" :href="users.next_page_url" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                >>
            </Link>
        </div>
    </AuthenticatedLayout>
</template>
