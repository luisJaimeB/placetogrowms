<script>
export default {
    name: 'PermissionsEdit'
}
</script>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import UserForm from '@/Components/permissions/Form.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useSSRContext } from 'vue';

const props = defineProps({
    permission: {
        type: Object,
        required: true
    },
})

const form = useForm({
    name: props.permission.name,
})
</script>

<template>
    <Head title="Edición de permisos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Permiso</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <UserForm :updating="true" :form="form" @submit="form.patch(route('permissions.update', permission.id))" />
                        </div>
                    </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>