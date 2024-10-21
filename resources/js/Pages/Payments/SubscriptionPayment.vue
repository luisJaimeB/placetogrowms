<script setup>

import {useForm} from "@inertiajs/vue3";
import SuscriptionForm from "@/Components/Payments/SuscriptionForm.vue";

const props = defineProps({
    microsite: {
        type: Object,
        required: true
    },
    currencies: {
        type: Object,
        required: true
    },
    buyer_id_types: {
        type: Object,
        required: true
    },
    optionals: {
        type: Object,
        required: true
    },
    plans: {
        type: Object,
        required: true
    }
});

const micrositeId = props.microsite.id
const type = props.microsite.type_site_id
const expiration = props.microsite.expiration


const form = useForm({
    description: '',
    amount: '',
    currency: '',
    buyer_id_type: '',
    buyer_id: '',
    name: '',
    lastName: '',
    email: '',
    phone: '',
    paymentMethod: '',
    micrositeId: micrositeId,
    type: type,
    expiration: expiration,
    errors: {},
    optional_fields: [],
    plan:[],
});

const submitForm = () => {
    axios.post(route('payments.process'), form)
        .then(response => {
            if (response.data.redirect_url) {
                // Redirige al usuario a la URL de redirección
                window.location.href = response.data.redirect_url;
            } else if (response.data.error) {
                // Maneja errores del servidor
                console.error(response.data.error);
                form.setError({ general: response.data.error });
            }
        })
        .catch(error => {
            console.error(error);
            if (error.response && error.response.data.errors) {
                const formattedErrors = {};
                Object.keys(error.response.data.errors).forEach(key => {
                    formattedErrors[key] = error.response.data.errors[key][0];
                });
                form.setError(formattedErrors);
            } else {
                form.setError({ general: 'Ha ocurrido un error inesperado.' });
            }
        });
};
</script>

<template>
    <SuscriptionForm :form="form" :plans="plans" :optionals="optionals" :buyer_id_types="buyer_id_types"  :microsite="microsite" :currencies="currencies" @submit="submitForm" :errors="form.errors"/>
</template>

<style scoped>

</style>
