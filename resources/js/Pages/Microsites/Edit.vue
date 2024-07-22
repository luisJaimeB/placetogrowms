<script>
export default {
    name: 'MicrositesEdit'
}
</script>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import MicrositesEditForm from '@/Components/microsites/FormEdit.vue';
import { Head, useForm } from '@inertiajs/vue3';
import {ref} from 'vue';

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
    currencies: {
        type: Object,
        required: true
    },
})

const form = useForm({
    _method: 'patch',
    name: props.microsite.name,
    category: props.microsite.category_id,
    logo: props.microsite.logo,
    siteType: props.microsite.type_site_id,
    expiration: props.microsite.expiration,
    currency: props.microsite.currencies[0].id
})

const newLogoFile = ref(null);

const submitForm = () => {
    console.log('Nuevo archivo de logotipo:', newLogoFile.value);

    const formData = new FormData();

    // Recorremos todos los campos del formulario (incluyendo 'logo')
    for (const clave in form) {
        formData.append(clave, form[clave]);
    }

    // Adjuntar el nuevo archivo de logotipo si existe
    if (newLogoFile.value) {
        formData.append('logo', newLogoFile.value);
    }

    // Depuración de FormData (opcional)
    /*
    for (let [clave, valor] of formData.entries()) {
      console.log(clave, valor);
    }
    */

    form.post(route('microsites.update', props.microsite.id), {
        forceFormData: true,
        formData,
        onSuccess: () => console.log('Formulario enviado correctamente.'),
        onError: (error) => console.log('Error al enviar el formulario:', error),
    });
};
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
                        <MicrositesEditForm
                            :updating="true"
                            :currencies="currencies"
                            :categories="categories"
                            :types="types"
                            :form="form"
                            @update:newLogoFile="newLogoFile = $event"
                            @submit="submitForm"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
