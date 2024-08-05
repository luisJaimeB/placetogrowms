<script>
export default {
    name: 'MicrositesCreate'
}
</script>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import MicrositesForm from '@/Components/microsites/Form.vue'
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps({
    sites_type: {
        type: Object,
        required: true
    },
    categories: {
        type: Object,
        required: true
    },
    currencies: {
        type: Object,
        required: true
    },
})

const form = useForm({
    name: '',
    category_id: '',
    type_site_id: '',
    logo: '',
    expiration: '',
    currency: '',
});

const submitForm = () => {
    form.post(route('microsites.store'), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Microsites" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight"> {{ t('titles.microsites') }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-withe overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <MicrositesForm :form="form" :currencies="currencies" :categories="categories" :types="sites_type" @submit="submitForm" v-if="$page.props.user.permissions.includes('microsites.create')"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
