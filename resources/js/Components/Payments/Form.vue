<script>
export default {
    name: 'PaymentForm'
}
</script>

<script setup>

import PrimaryButton from "@/Components/PrimaryButton.vue";
import {useI18n} from "vue-i18n";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    microsite: {
        type: Object,
        required: true
    },
    currencies: {
        type: Array,
        required: true
    },
    form: {
        type: Object,
        required: true
    },
    errors: {
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
    }
});

const { t } = useI18n();
const getLogoUrl = (path) => {
    return path ? `/microsite/logo/${path}` : null;
};

console.log('form:', props.form);
console.log('Currencies:', props.currencies);
console.log('errors: ', props.errors)

const currencyOptions = props.currencies.filter(currency => currency.id === props.microsite.currencies[0].id);

const filterInput = (event) => {
    event.target.value = event.target.value.replace(/\D/g, '');
    props.form.buyer_id = event.target.value;
};

const handleInputChange = (field, value) => {
    const existingField = props.form.optional_fields.find(item => item.field === field);

    if (existingField) {
        existingField.value = value;
    } else {
        props.form.optional_fields.push({ field, value });
    }
};

defineEmits(['submit'])
</script>

<template>
    <div class="bg-gray-100 flex items-center justify-center min-h-screen bg-gradient-to-br from-purple-100 to-indigo-200 p-4">
        <div class="max-w-2xl w-full bg-white rounded-xl shadow-2xl overflow-hidden transform transition duration-500">
            <!-- Banner -->
            <div class="relative">
                <img v-if="microsite.logo" :src="getLogoUrl(microsite.logo)" alt="Logo" class="w-full h-64 object-cover"/>
            </div>
            <!-- Card Content -->
            <form @submit.prevent="$emit('submit')" class="p-6 space-y-6">
                <!-- Form Fields -->
                <div class="space-y-4">
                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">{{ t('fields.description') }}</label>
                        <input type="text" id="description" v-model="form.description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"/>
                        <InputError :message="errors.description" class="mt-2"/>
                    </div>
                    <!-- Currency and Amount -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="currency" class="block text-sm font-medium text-gray-700">{{ t('fields.currency') }}</label>
                            <select id="currency" v-model="form.currency" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="" disabled>{{ t('labels.micrositesLabel.selectCurrency') }}</option>
                                <option v-for="currency in currencyOptions" :key="currency.id" :value="currency.id">
                                    {{ currency.name }}
                                </option>
                            </select>
                            <InputError :message="errors.currency" class="mt-2"/>
                        </div>
                        <div>
                            <label for="total" class="block text-sm font-medium text-gray-700">{{ t('fields.amount') }}</label>
                            <input type="number" v-model="form.amount" id="total" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"/>
                            <InputError :message="errors.amount" class="mt-2"/>
                        </div>
                    </div>
                    <!-- Name, Last Name, Email, and Phone -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="buyerIdType" class="block text-sm font-medium text-gray-700">{{ t('fields.buyerIdType') }}</label>
                            <select v-model="form.buyer_id_type" id="buyer_id_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="" disabled>Selecciona un tipo de Documento</option>
                                <option v-for="buyer_id_type in buyer_id_types" :key="buyer_id_type.id" :value="buyer_id_type.id">{{ buyer_id_type.document_type }}</option>
                            </select>
                            <InputError :message="errors.buyer_id_type" class="mt-2"/>
                        </div>
                        <div>
                            <label for="lastName" class="block text-sm font-medium text-gray-700">{{ t('fields.buyerId') }}</label>
                            <input id="buyer_id" v-model="form.buyer_id" type="text" inputmode="numeric" autocomplete="buyer_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" @input="filterInput"/>
                            <InputError :message="errors.buyer_id" class="mt-2"/>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="firstName" class="block text-sm font-medium text-gray-700">{{ t('fields.buyerName') }}</label>
                            <input type="text" v-model="form.name" id="firstName" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"/>
                            <InputError :message="errors.name" class="mt-2"/>
                        </div>
                        <div>
                            <label for="lastName" class="block text-sm font-medium text-gray-700">{{ t('fields.buyerLastName') }}</label>
                            <input type="text" v-model="form.lastName" id="lastName" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"/>
                            <InputError :message="errors.lastName" class="mt-2"/>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">{{ t('fields.email') }}</label>
                            <input type="email" v-model="form.email" id="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"/>
                            <InputError :message="errors.email" class="mt-2"/>
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">{{ t('fields.phone') }}</label>
                            <input type="tel" v-model="form.phone" id="phone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"/>
                            <InputError :message="errors.phone" class="mt-2"/>
                        </div>
                    </div>

                    <!-- Campos opcionales -->
                    <div v-if="optionals.length > 0">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div v-for="(optional) in optionals" :key="optional.id" class="flex flex-col">
                                <!-- Verifica si el campo opcional está habilitado en 'microsite.optional_fields' -->
                                <div v-if="microsite.optional_fields && microsite.optional_fields[optional.field] === '1'">
                                    <label :for="optional.field" class="block text-sm font-medium text-gray-700">{{ optional.label }}</label>
                                    <input
                                        :id="optional.field"
                                        :name="optional.field"
                                        type="text"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        @input="handleInputChange(optional.field, $event.target.value)"
                                    />
                                    <span v-if="errors[optional.field]" class="text-red-500 text-sm">{{ errors[optional.field] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Payment Method -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ t('fields.paymentMethod') }}</label>
                    <div class="mt-2 flex flex-col space-y-4">
                        <!-- PlaceToPay Radio Button -->
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" v-model="form.paymentMethod" value="placetopay" class="mr-2"/>
                            <img src="https://static.placetopay.com/placetopay-logo.svg" alt="PlaceToPay" class="w-24 h-16 rounded-md"/>
                            <InputError :message="errors.paymentMethod" class="mt-2"/>
                        </label>
                        <!-- PayPal Radio Button -->
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" v-model="form.paymentMethod" value="paypal" class="mr-2"/>
                            <img src="https://www.paypalobjects.com/digitalassets/c/website/logo/full-text/pp_fc_hl.svg" alt="PayPal" class="w-24 h-16 rounded-md"/>
                            <InputError :message="errors.paymentMethod" class="mt-2"/>
                        </label>
                    </div>
                </div>
                <!-- Submit Button -->
                <div class="flex justify-end mt-4">
                    <PrimaryButton class="px-4 py-2 bg-indigo-600 text-white rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ t('buttons.payB') }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>

</style>
