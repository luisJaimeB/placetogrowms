<script>
export default {
    name: 'AclsCreate'
}
</script>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import {useI18n} from "vue-i18n";
import AclsForm from "@/Components/acls/Form.vue";

const props = defineProps({
    models: {
        type: Array,
        required: true
    },
    users: {
        type: Object,
        required: true
    },
    actions: {
        type: Array,
        required: true
    }
})

const { t } = useI18n()

const form = useForm({
    user_id: '',
    model_type: '',
    model_id: '',
    status: ''
})
</script>

<template>
    <Head title="Acls" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ t('titles.acls')}}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-withe overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <AclsForm :form="form" :actions="actions" :users="users" :models="models" @submit="form.post(route('acls.store'))" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
