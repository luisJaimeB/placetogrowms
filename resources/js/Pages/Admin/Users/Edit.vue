<script>
export default {
    name: 'UsersEdit'
}
</script>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import UserForm from '@/Components/users/Form.vue';
import { Head, useForm } from '@inertiajs/vue3';
import {useI18n} from "vue-i18n";

const props = defineProps({
    userToEdit: {
        type: Object,
        required: true
    },
    userPermissions: {
        type: Array,
        required: true
    },
    roles: {
        type: Array,
        required: true
    },
    userRole: {
      type: Array,
      required: true
    },
})

const { t } = useI18n();

const form = useForm({
    name: props.userToEdit.name,
    email: props.userToEdit.email,
    roles: props.userToEdit.roles[0].id,
})
</script>

<template>
    <Head title="Edición de usuarios" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ t('titles.userediting') }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <UserForm :updating="true" :userRole="userRole" :userPermissions="userPermissions" :roles="roles" :form="form" @submit="form.patch(route('users.update', user.id))" />
                        </div>
                    </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
