<script>
export default {
  name: 'SelectedInvoicePayment'
}
</script>

<script setup>

import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useI18n } from "vue-i18n";
import { useForm } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";

const props = defineProps({
    invoice: {
        type: Object,
        required: true
    },
    errors: {
        type: Object,
        required: true
    },
});

const form = useForm({
    optional_fields: [
      { field: 'order_number', value: props.invoice.order_number }
    ],
    name: props.invoice.debtor_name,
    buyer_id_type: props.invoice.identification_type_id,
    buyer_id: props.invoice.identification_number,
    email: props.invoice.email,
    description: props.invoice.description,
    amount: props.invoice.amount,
    expiration: props.invoice.microsite.expiration,
    currency: props.invoice.currency.id,
    micrositeId: props.invoice.microsite.id,
    type: props.invoice.microsite.type_site_id,
    paymentMethod: 'placetopay',
    lastName: '',
    phone: '',
    errors: {},
});

const { t } = useI18n();
const toast = useToast();
const getLogoUrl = (path) => {
  return path ? `/microsite/logo/${path}` : null;
};

const submitForm = () => {
  axios.post(route('payments.process'), form)
      .then(response => {
        if (response.data.redirect_url) {
          window.location.href = response.data.redirect_url;
        } else if (response.data.error) {
          console.error(response.data.error);
          form.setError({ general: response.data.error });
          toast.error(response.data.error, {
            duration: 5000
          });
        }
      })
      .catch(error => {
        console.error(error);
        if (error.response && error.response.status === 422) {
          const errors = error.response.data.errors;
          for (const key in errors) {
            if (errors.hasOwnProperty(key)) {
              toast.error(errors[key][0], {
                duration: 5000
              });
            }
          }
        } else if (error.response && error.response.data.error) {
          toast.error(error.response.data.error, {
            duration: 5000
          });
        } else {
          form.setError({ general: 'Ha ocurrido un error inesperado.' });
          toast.error('Ha ocurrido un error inesperado.', {
            duration: 5000
          });
        }
      });
};


</script>

<template>
    <div class="bg-gray-100 flex items-center justify-center min-h-screen bg-gradient-to-br from-purple-100 to-indigo-200 p-8">
        <div class="max-w-6xl w-full bg-white rounded-2xl shadow-2xl overflow-hidden transform transition duration-500">
            <!-- Banner -->
            <div class="relative">
                <img v-if="invoice.microsite.logo" :src="getLogoUrl(invoice.microsite.logo)" alt="Logo" class="w-full h-80 object-cover"/>
            </div>
            <!-- Card Content -->
            <form @submit.prevent="submitForm" class="p-12 space-y-12">
                <!-- Form Fields -->
                <div class="space-y-6">
                    <!-- Information Section -->
                    <div class="bg-gray-100 p-4 rounded-md shadow-sm">
                        <p class="text-gray-700 text-sm">
                            {{ t('info.description_intro') }}
                        </p>
                        <p class="text-gray-500 text-xs mt-2">
                            {{ t('info.description_details') }}
                        </p>
                    </div>

                    <div class=" shadow-lg rounded-lg overflow-hidden">
                        <table class="w-full table-fixed">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">{{t('fields.order_number')}}</th>
                                    <th class="w-1/2 py-4 px-6 text-center text-gray-600 font-bold uppercase">{{t('fields.description')}}</th>
                                    <th class="w-1/4 py-4 px-6 text-center text-gray-600 font-bold uppercase">{{t('fields.amount')}}</th>
                                    <th class="w-1/4 py-4 px-6 text-center text-gray-600 font-bold uppercase">{{t('fields.status')}}</th>
                                    <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">{{t('fields.due_date')}}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                <tr class="transition-transform duration-300 ease-in-out transform hover:scale-95 cursor-pointer">
                                    <td class="py-4 px-6 border-b border-gray-200">{{ invoice.order_number }}</td>
                                    <td class="py-4 px-6 border-b border-gray-200 truncate">{{ invoice.description }}</td>
                                    <td class="py-4 px-6 border-b text-center border-gray-200">{{ invoice.amount }}</td>
                                    <td class="py-4 px-6 border-b text-center border-gray-200">{{ invoice.status }}</td>
                                    <td class="py-4 px-6 border-b text-center border-gray-200">{{ invoice.expiration_date }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <PrimaryButton class="text-white px-2 py-1 mx-2 rounded" @submit.prevent="submitForm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="text-white text-center h-6 w-full mr-2" fill="currentColor" viewBox="0 0 576 512">
                        <path d="M64 32C28.7 32 0 60.7 0 96l0 32 576 0 0-32c0-35.3-28.7-64-64-64L64 32zM576 224L0 224 0 416c0 35.3 28.7 64 64 64l448 0c35.3 0 64-28.7 64-64l0-192zM112 352l64 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-64 0c-8.8 0-16-7.2-16-16s7.2-16 16-16zm112 16c0-8.8 7.2-16 16-16l128 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-128 0c-8.8 0-16-7.2-16-16z"/>
                    </svg>
                </PrimaryButton>
          </form>
        </div>
  </div>
</template>

<style scoped>

</style>
