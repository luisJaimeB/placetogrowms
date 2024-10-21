<script>
export default {
    name: 'ImportsCreate'
}
</script>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm, usePage} from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import ImportsForm from "@/Components/imports/Form.vue";
import {useToast} from "vue-toastification";

const { t } = useI18n();

const toast = useToast();
const { props } = usePage();

const form = useForm({
    file: '',
});

const submitForm = () => {
    form.post(route('import.invoices'), {
        forceFormData: true,
    });
};

// Log the flash messages to the console
console.log('Flash messages:', props.flash);
console.log('Errors:', props.errors);

if (props.flash && props.flash.success) {
    toast.success(props.flash.success);
}

if (props.errors && props.errors.file) {
    toast.error(props.errors.file);
}
</script>

<template>
    <Head title="Imports" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight"> {{ t('titles.invoices') }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-withe overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <ImportsForm :form="form" @submit="submitForm" v-if="$page.props.user.permissions.includes('imports.create')"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
