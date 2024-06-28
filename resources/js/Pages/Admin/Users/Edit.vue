<script>
export default {
    name: 'UsersEdit'
}
</script>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import UserForm from '@/Components/users/Form.vue'
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useSSRContext } from 'vue';

const props = defineProps({
    user: {
        type: Object,
        required: true
    },
    userPermissions: {
        type: Array,
        required: true
    }
})

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: ''
})
</script>

<template>
    <Head title="Edición de usuarios" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Usuario</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <UserForm :updating="true" :form="form" @submit="form.patch(route('users.update', user.id))" />
                        </div>
                    </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>