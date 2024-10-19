<script>
export default {
    name: 'PlanesEdit'
}
</script>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import UserForm from '@/Components/permissions/Form.vue';
import { Head, useForm } from '@inertiajs/vue3';
import {useI18n} from "vue-i18n";
import PlansForm from "@/Components/plans/Form.vue";

const { t } = useI18n()

const props = defineProps({
    plan: {
        type: Object,
        required: true
    },
    periodicities: {
    type: Array,
        required: true
    },
    subscriptionTerm: {
        type: Array,
        required: true
    },
    microsites: {
        type: Array,
        required: false
    }
})

const form = useForm({
    name: props.plan.name,
    amount: props.plan.amount,
    microsite_id: props.plan.microsite_id,
    periodicity: props.plan.periodicity,
    subscriptionTerm: props.plan.subscriptionTerm,
    items: props.plan.items
})
</script>

<template>
    <Head title="Edición de planes" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ t('actions.planes.edit')}}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <PlansForm :updating="true" :form="form" :subscriptionTerm="subscriptionTerm" :periodicities="periodicities" :microsites="microsites" @submit="form.patch(route('planes.update', plan.id))"/>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
