<script>
export default {
    name: 'MicrositesEdit'
}
</script>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import MicrositesEditForm from '@/Components/microsites/FormEdit.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useSSRContext } from 'vue';

const props = defineProps({
    microsite: {
        type: Object,
        required: true
    },
    types: {
        type: Object,
        required: true
    },
    categories: {
        type: Object,
        required: true
    },
})

const form = useForm({
    name: props.microsite.name,
    category: props.microsite.category_id,
    siteType: props.microsite.type_site_id,
    logo: props.microsite.logo,
    expiration: props.microsite.expiration,
})
</script>

<template>
    <Head title="Edición de micrositios" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{$page.props.trans.common.actions.microsites.edit}}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <MicrositesEditForm :updating="true" :categories="categories" :types="types" :form="form" @submit="form.patch(route('microsites.update', microsite.id))" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>