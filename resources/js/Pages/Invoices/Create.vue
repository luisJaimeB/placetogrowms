<script>
export default {
    name: 'InvoicesCreate'
}
</script>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import InvoicesForm from "@/Components/invoices/Form.vue";

const { t } = useI18n();

const props = defineProps({
    microsites: {
        type: Object,
        required: true
    },
    identification_types: {
        type: Object,
        required: true
    }
})

const form = useForm({
    order_number: '',
    debtor_name: '',
    microsite_id: '',
    identification_type_id: '',
    identification_number: '',
    email: '',
    description: '',
    currency_id: '',
    amount: '',
    expiration_date: '',
});

const submitForm = () => {
    form.post(route('invoices.store'), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Microsites" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight"> {{ t('titles.invoices') }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-withe overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <InvoicesForm :form="form" :identification_types="identification_types" :microsites="microsites" @submit="submitForm" v-if="$page.props.user.permissions.includes('invoices.create')"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
