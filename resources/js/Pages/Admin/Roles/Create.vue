<script>
export default {
    name: 'RolesCreate'
}
</script>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import RoleForm from '@/Components/roles/Form.vue'
import { Head, useForm } from '@inertiajs/vue3';
import {useI18n} from "vue-i18n";

const { t } = useI18n();

const props = defineProps(['permissions']);

const form = useForm({
    name: '',
    permissions: [],
})
</script>

<template>
    <Head title="Roles" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ t('titles.roles') }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-withe overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <RoleForm :form="form" :permissions="permissions" @submit="form.post(route('roles.store'))" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
