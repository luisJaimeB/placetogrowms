<script setup>
import InvoicePaymentForm from "@/Components/Payments/InvoicePaymentForm.vue";
import {router, useForm} from "@inertiajs/vue3";
import {useToast} from "vue-toastification";
import axios from 'axios';

const props = defineProps({
  microsite: {
    type: Object,
    required: true
  }
});

const form = useForm({
  order_number: '',
  errors: {},
});

const toast = useToast();

const submitForm = () => {
    axios.post(route('payment.invoice.search'), form)
        .then(response => {
            if (response.data.redirect) {
                console.log(response.data.invoice)
                router.visit(response.data.redirect,{
                  data: { invoice: response.data.invoice }
                });
            } else {
                toast.success('Operación exitosa', {
                  duration: 5000
                });
            }
        })
        .catch(error => {
            if (error.response && error.response.data.error) {
                toast.error(error.response.data.error, {
                  duration: 5000
                });
            }
        });
};

</script>

<template>
  <InvoicePaymentForm :form="form" :microsite="microsite" @submit="submitForm" :errors="form.errors"/>
</template>

<style scoped>

</style>
