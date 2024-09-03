<script>
export default {
    name: 'PlanesCreate'
}
</script>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PlansForm from '@/Components/plans/Form.vue'
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps({
    periodicities: {
        type: Array,
        required: true
    },
    microsites: {
        type: Array,
        required: false
    }
})

const form = useForm({
    name: '',
    items: [],
    periodicity: '',
    amount: '',
    interval: '',
    next_payment: '',
    due_date: '',
    microsite_id: '',
});

const submitForm = () => {
    form.post(route('planes.store'), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Plans" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight"> {{ t('titles.microsites') }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-withe overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <PlansForm :form="form" :microsites="microsites" :periodicities="periodicities" @submit="submitForm" v-if="$page.props.user.permissions.includes('planes.create')"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
