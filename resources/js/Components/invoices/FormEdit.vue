<script>
export default {
    name: 'InvoicesEditForm'
}
</script>

<script setup>
import FormSection from '@/Components/FormSection.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import {useI18n} from "vue-i18n"

const { t } = useI18n()

const props = defineProps({
    form: {
        type: Object,
        required: true
    },
    invoice: {
      type: Object,
      required: true
    },
    updating: {
        type: Boolean,
        required: false,
        default: false
    },
    identification_types: {
        type: Object,
        required: true
    }
})
const filterInput = (event) => {
    event.target.value = event.target.value.replace(/\D/g, '');
    form.buyer_id = event.target.value;
};
defineEmits(['submit'])
</script>

<template>
    <FormSection @submitted="$emit('submit')">
        <template #title>
            {{  updating ? t('strings.updatePermission') : t('strings.createPermission') }}
        </template>

        <template #description>
            {{ updating ? t('strings.updatePermissionDesc') : t('strings.createPermissionDesc') }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="order" value="Order number" />
                <TextInput id="order_number" v-model="form.order_number" type="text" autocomplete="name" class="mt-1 block w-full" />
                <InputError :message="$page.props.errors.order_number" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="identification_type" value="Tipo de documento" />
                <select id="identification_types" v-model="form.identification_type_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option v-for="identification_type in identification_types" :key="identification_type.id" :value="identification_type.id">
                        {{ identification_type.code }}
                    </option>
                </select>
                <InputError :message="$page.props.errors.identification_type_id" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="name" value="Número documento" />
                <TextInput id="name" v-model="form.identification_number" type="text" autocomplete="name" class="mt-1 block w-full" />
                <InputError :message="$page.props.errors.name" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="name" value="Name" />
                <TextInput id="name" v-model="form.debtor_name" type="text" autocomplete="name" class="mt-1 block w-full" />
                <InputError :message="$page.props.errors.debtor_name" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="email" value="Correo" />
                <TextInput id="email" v-model="form.email" type="email" autocomplete="email" class="mt-1 block w-full" />
                <InputError :message="$page.props.errors.email" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="description" value="Descripción" />
                <TextInput id="description" v-model="form.description" type="text" autocomplete="description" class="mt-1 block w-full" />
                <InputError :message="$page.props.errors.description" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="next_payment" value="Fecha vencimiento" />
                <TextInput id="next_payment" v-model="form.expiration_date" type="date" class="border-2 border-gray-300 rounded px-3 py-2 w-full" />
                <InputError :message="$page.props.errors.expiration_date" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="amount" value="Amount" />
                <TextInput id="amount" v-model="form.amount" type="number" class="mt-1 block w-full" @input="filterInput"/>
                <InputError :message="$page.props.errors.amount" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <PrimaryButton>
                {{ updating ? t('buttons.updateB') : t('buttons.createB') }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>
