<script>
    export default {
        name: 'MicrositesForm'
    }
</script>

<script setup>
import FormSection from '@/Components/FormSection.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import {useI18n} from "vue-i18n";

defineProps({
    form: {
        type: Object,
        required: true
    },
    updating: {
        type: Boolean,
        required: false,
        default: false
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
    optionals: {
        type: Object,
        required: true
    }
})

const { t } = useI18n();

const filterInput = (event) => {
    event.target.value = event.target.value.replace(/\D/g, '');
    form.buyer_id = event.target.value;
};

defineEmits(['submit'])
</script>

<template>
    <FormSection @submitted="$emit('submit')">
        <template #title>
            {{  updating ? t('strings.updateMicrosite') : t('strings.createMicrosite') }}
        </template>

        <template #description>
            {{ updating ? t('strings.updateMicrositeDesc') : t('strings.createMicrositeDesc') }}
        </template>

        <template #form>

            <!-- Site Type -->
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="siteType" :value="t('fields.type')" />
                <select id="siteType" v-model="form.type_site_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="" disabled>Selecciona un tipo de sitio</option>
                    <option v-for="type in types" :key="type.id" :value="type.id">{{ type.name }}</option>
                </select>
                <InputError :message="$page.props.errors.siteType" class="mt-2" />
            </div>

            <!-- Category -->
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="category" :value="t('fields.category')" />
                <select id="category" v-model="form.category_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="" disabled>Selecciona una categoría</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                </select>
                <InputError :message="$page.props.errors.category" class="mt-2" />
            </div>

            <!-- Name -->
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="name" :value="t('fields.name')" />
                <TextInput id="name" v-model="form.name" type="text" autocomplete="name" class="mt-1 block w-full" />
                <InputError :message="$page.props.errors.name" class="mt-2" />
            </div>

            <!-- Currency -->
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="currency" :value="t('fields.currency')" />
                <select id="currency" v-model="form.currency" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="" disabled>Selecciona una Moneda</option>
                    <option v-for="currency in currencies" :key="currency.id" :value="currency.id">{{ currency.code }}</option>
                </select>
                <InputError :message="$page.props.errors.currency" class="mt-2" />
            </div>

            <!-- Expiration -->
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="expiration" :value="t('fields.expiration')" />
                <select id="expiration" v-model="form.expiration" class="mt-1 block w-full p-2.5 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="" disabled>{{ t('labels.micrositesLabel.selectExpiration') }}</option>
                    <option value="10">{{ t('labels.micrositesLabel.teenMicrositeExp') }}</option>
                    <option value="15">{{ t('labels.micrositesLabel.fifteenMicrositeExp') }}</option>
                    <option value="20">{{ t('labels.micrositesLabel.twentyMicrositeExp') }}</option>
                    <option value="30">{{ t('labels.micrositesLabel.thirtyMicrositeExp') }}</option>
                </select>
                <InputError :message="$page.props.errors.expiration" class="mt-2" />
            </div>

            <!-- Logo -->
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="logo" :value="t('labels.micrositesLabel.logoMicrosite')" />
                <input id="logo" type="file" @input="form.logo = $event.target.files[0]" class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                <InputError :message="$page.props.errors.logo" class="mt-2" />
            </div>

            <!-- Campos opcionales -->
            <div class="col-span-6 sm:col-span-6">
                <div class="w-full p-6 bg-white rounded-lg shadow-md mt-6">
                    <h2 class="text-lg font-bold mb-4">Campos Opcionales</h2>
                    <div class="flex flex-row flex-wrap gap-6">

                        <div v-for="optional in optionals" :key="optional.id" class="flex items-center">
                            <input
                                type="checkbox"
                                :id="optional.field"
                                v-model="form.optional_fields[optional.field]"
                            class="mr-2 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            />
                            <label :for="optional.field" class="text-gray-700">{{ optional.label }}</label>
                        </div>
                    </div>
                </div>
            </div>

        </template>

        <template #actions>
            <PrimaryButton>
                {{ updating ? t('buttons.updateB') : t('buttons.createB') }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>
