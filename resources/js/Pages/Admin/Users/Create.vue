<script>
export default {
    name: 'UsersCreate'
}
</script>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import UserForm from '@/Components/users/Form.vue'
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useSSRContext } from 'vue';

const props = defineProps({
    roles: {
        type: Array,
        required: true
    },
})

const form = useForm({
    name: '',
    email: '',
    password: '',
    roles: []
})
</script>

<template>
    <Head title="Users" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $page.props.trans.common.titles.users }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-withe overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <UserForm :form="form" :roles="roles" @submit="form.post(route('users.store'))" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>