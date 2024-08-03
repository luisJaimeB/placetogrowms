<script setup>
import PaymentForm from "@/Components/Payments/Form.vue";
import {useForm} from "@inertiajs/vue3";

const props = defineProps({
    microsite: {
        type: Object,
        required: true
    },
    currencies: {
        type: Object,
        required: true
    },
});

const micrositeId = props.microsite.id
const type = props.microsite.type_site_id
const expiration = props.microsite.expiration


const form = useForm({
    description: '',
    amount: '',
    currency: '',
    name: '',
    lastName: '',
    email: '',
    phone: '',
    paymentMethod: '',
    micrositeId: micrositeId,
    type: type,
    expiration: expiration,
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
                form.setErrors({ general: response.data.error });
            }
        })
        .catch(error => {
            console.error(error);
            // Maneja errores de la solicitud
            if (error.response && error.response.data.errors) {
                form.setErrors(error.response.data.errors);
            } else {
                form.setErrors({ general: 'Ha ocurrido un error inesperado.' });
            }
        });
};
</script>

<template>
    <PaymentForm :form="form" :microsite="microsite" :currencies="currencies" @submit="submitForm"/>
</template>

<style scoped>

</style>
