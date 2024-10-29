<script>
export default {
    name: 'InvoicesForm'
}
</script>

<script setup>
import FormSection from '@/Components/FormSection.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import {useI18n} from "vue-i18n"
import {ref, toRefs, watch} from "vue";
import {SInputDateBlock, SButton, SSelect, SInput} from "@placetopay/spartan-vue";

const { t } = useI18n()

const props = defineProps({
    form: {
        type: Object,
        required: true
    },
    updating: {
        type: Boolean,
        required: false,
        default: false
    },
    microsites: {
        type: Object,
        required: true
    },
    identification_types: {
        type: Object,
        required: true
    },
    surchargeRates: {
        type: Array,
        required: true
    }
})

const { form, microsites } = toRefs(props);
const currencies = ref([]);

const updateCurrencies = (micrositeId) => {
    const selectedMicrosite = microsites.value.find(microsite => microsite.id === micrositeId);
    if (selectedMicrosite) {
        currencies.value = selectedMicrosite.currencies;
    } else {
        currencies.value = [];
    }
}

watch(() => form.value.microsite_id, (newMicrositeId) => {
    updateCurrencies(newMicrositeId);
});

const filterInput = (event) => {
    event.target.value = event.target.value.replace(/\D/g, '');
    form.buyer_id = event.target.value;
};

const goBack = () => {
    window.history.back();
}

defineEmits(['submit'])
</script>

<template>
    <FormSection @submitted="$emit('submit')">
        <template #title>
            {{  updating ? t('strings.updateInvoice') : t('strings.createInvoice') }}
        </template>

        <template #description>
            {{ updating ? t('strings.updateInvoiceDesc') : t('strings.createInvoiceDesc') }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="microsite_id" :value="t('labels.microsite')" />
                <SSelect id="microsite_id" placeholder="Select an option" v-model="form.microsite_id" class="mt-1 block w-full pl-3 pr-10 py-2">
                    <option v-for="microsite in microsites" :key="microsite.id" :value="microsite.id">
                        {{ microsite.name }}
                    </option>
                </SSelect>
                <InputError :message="$page.props.errors.microsite_id" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <SInputDateBlock :label="t('labels.expirationDate')" id="expiration_date" v-model="form.expiration_date" :errorText="$page.props.errors.expiration_date"/>
            </div>
            <div class="col-span-6 sm:col-span-3">
                <SInputDateBlock :label="t('labels.surchargeDate')" id="surcharge_date" v-model="form.surcharge_date" :errorText="$page.props.errors.surcharge_date"/>
            </div>
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="surcharge_rate" :value="t('labels.surchargeRate')"/>
                <select id="surcharge_rate" v-model="form.surcharge_rate" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option v-for="surchargeRate in surchargeRates" :key="surchargeRate" :value="surchargeRate">
                        {{ surchargeRate }}
                    </option>
                </select>
                <InputError :message="$page.props.errors.surcharge_rate" class="mt-2" />
            </div>
            <div v-if="form.surcharge_rate === 'additional amount'" class="col-span-6 sm:col-span-3">
                <InputLabel for="additional_amount" :value="t('labels.additionalAmount')" />
                <TextInput id="additional_amount" placeholder="Añada el monto adicional." v-model="form.additional_amount" type="text" class="mt-1 block w-full" @input="filterInput"/>
                <InputError :message="$page.props.errors.additional_amount" class="mt-2" />
            </div>
            <div v-if="form.surcharge_rate === 'percent'" class="col-span-6 sm:col-span-3">
                <InputLabel for="percent" :value="t('labels.percent')" />
                <TextInput id="percent" placeholder="Añada el porcentaje" v-model="form.percent" type="text" class="mt-1 block w-full" @input="filterInput" />
                <InputError :message="$page.props.errors.percent" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="identification_type" :value="t('labels.buyerType')"/>
                <select id="identification_types" v-model="form.identification_type_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option v-for="identification_type in identification_types" :key="identification_type.id" :value="identification_type.id">
                        {{ identification_type.code }}
                    </option>
                </select>
                <InputError :message="$page.props.errors.identification_type_id" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="buyerId" :value="t('labels.buyerNumber')" />
                <SInput id="buyerId" v-model="form.identification_number" :placeholder="t('fields.buyerId')" />
                <InputError :message="$page.props.errors.identification_number" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="name" :value="t('labels.invoiceDebtorName')" />
                <TextInput id="name" v-model="form.debtor_name" type="text" autocomplete="name" class="mt-1 block w-full" />
                <InputError :message="$page.props.errors.debtor_name" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="email" :value="t('labels.email')" />
                <TextInput id="email" v-model="form.email" type="email" autocomplete="email" class="mt-1 block w-full" />
                <InputError :message="$page.props.errors.email" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="description" :value="t('labels.description')" />
                <TextInput id="description" v-model="form.description" type="text" autocomplete="description" class="mt-1 block w-full" />
                <InputError :message="$page.props.errors.description" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="order" :value="t('labels.orderNumber')"  />
                <TextInput id="order_number" v-model="form.order_number" type="text" autocomplete="name" class="mt-1 block w-full" />
                <InputError :message="$page.props.errors.order_number" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="currency" :value="t('labels.currency')" />
                <select id="currency" v-model="form.currency_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="" disabled>Selecciona una Moneda</option>
                    <option v-for="currency in currencies" :key="currency.id" :value="currency.id">
                        {{ currency.code }}
                    </option>
                </select>
                <InputError :message="$page.props.errors.currency" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="amount" :value="t('labels.amount')" />
                <TextInput id="amount" v-model="form.amount" type="text" class="mt-1 block w-full" @input="filterInput"/>
                <InputError :message="$page.props.errors.amount" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <SButton variant="secondary" @click="goBack" class="mr-4">{{ t('buttons.cancel') }}</SButton>
            <SButton variant="primary" type="submit">
                {{ updating ? t('buttons.updateB') : t('buttons.createB') }}
            </SButton>
        </template>
    </FormSection>
</template>
