<script>
export default {
    name: 'RolesEdit'
}
</script>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import UserForm from '@/Components/roles/Form.vue'
import { Head, useForm } from '@inertiajs/vue3';
import {useI18n} from "vue-i18n";

const { t } = useI18n();

const props = defineProps(['role', 'permissions', 'rolePermissions']);

const form = useForm({
    name: props.role.name,
    permissions: props.rolePermissions,
})
</script>

<template>
    <Head title="Edición de roles" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ t('actions.roles.edit') }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <UserForm :updating="true" :permissions="permissions" :form="form" @submit="form.patch(route('roles.update', role.id))" />
                        </div>
                    </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
