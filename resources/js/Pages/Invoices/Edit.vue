<script>
export default {
    name: 'InvoicesEdit'
}
</script>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import {ref} from 'vue';
import {useI18n} from "vue-i18n";
import InvoicesEditForm from "@/Components/invoices/FormEdit.vue";

const { t } = useI18n();

const props = defineProps({
    invoice: {
        type: Object,
        required: true
    },
    identification_types: {
        type: Object,
        required: true
    }
})

const form = useForm({
    order_number: props.invoice.order_number,
    debtor_name: props.invoice.debtor_name,
    identification_type_id: props.invoice.identification_type_id,
    identification_number: props.invoice.identification_number,
    email: props.invoice.email,
    description: props.invoice.description,
    amount: props.invoice.amount,
    expiration_date: props.invoice.expiration_date,
});

</script>

<template>
    <Head title="Edición de facturas" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{t('actions.invoices.edit')}}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <InvoicesEditForm
                            :updating="true"
                            :identification_types="identification_types"
                            :invoice="invoice"
                            :form="form"
                            @submit="form.patch(route('invoices.update', invoice.id))"
                         />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
