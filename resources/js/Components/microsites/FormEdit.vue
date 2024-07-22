<script setup>
import FormSection from '@/Components/FormSection.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { ref, defineProps, defineEmits } from 'vue';

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
});

const changeEventTriggered = ref(false);
const newLogoUrl = ref(null);

const emit = defineEmits(['submit']);

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        newLogoUrl.value = URL.createObjectURL(file);
        props.form.logo = file;
        changeEventTriggered.value = true;
    } else {
        newLogoUrl.value = null;
        changeEventTriggered.value = false;
    }
};

const getLogoUrl = (path) => {
    return path ? `/microsite/logo/${path}` : null;
};
</script>

<template>
    <FormSection @submitted="$emit('submit')">
        <template #title>
            {{ updating ? $page.props.trans.common.strings.updateMicrosite : $page.props.trans.common.strings.createMicrosite }}
        </template>

        <template #description>
            {{ updating ? $page.props.trans.common.strings.updateMicrositeDesc : $page.props.trans.common.strings.createMicrositeDesc }}
        </template>

        <template #form>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="type" value="Tipo de Sitio" />
                <select id="siteType" v-model="form.siteType" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="" disabled>Selecciona un tipo de sitio</option>
                    <option v-for="type in types" :key="type.id" :value="type.id">{{ type.name }}</option>
                </select>
                <InputError :message="$page.props.errors.siteType" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="category" value="Categoría" />
                <select id="category" v-model="form.category" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="" disabled>Selecciona una categoría</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                </select>
                <InputError :message="$page.props.errors.category" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="name" value="Name" />
                <TextInput id="name" v-model="form.name" type="text" autocomplete="name" class="mt-1 block w-full" />
                <InputError :message="$page.props.errors.name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="currency" value="Moneda" />
                <select id="currency" v-model="form.currency" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="" disabled>Selecciona una Moneda</option>
                    <option v-for="currency in currencies" :key="currency.id" :value="currency.id">{{ currency.code }}</option>
                </select>
                <InputError :message="$page.props.errors.currency" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="expiration" value="Expiración" />
                <select
                    id="expiration"
                    v-model="form.expiration"
                    class="mt-1 block w-full p-2.5 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="10">10 minutos</option>
                    <option value="20">20 minutos</option>
                    <option value="30">30 minutos</option>
                </select>
                <InputError :message="$page.props.errors.expiration" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="logo" value="logo" />
                <div class="mt-2 flex items-center">
                    <img v-if="form.logo && !changeEventTriggered" :src="getLogoUrl(form.logo)" alt="Logo actual" class="h-16 w-16 rounded-full object-cover mr-4" />
                    <img v-else-if="newLogoUrl" :src="newLogoUrl" alt="Nuevo logo" class="h-16 w-16 rounded-full object-cover mr-4" />
                    <input id="logo" type="file" @change="handleFileChange" class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                </div>
                <InputError :message="$page.props.errors.logo" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <PrimaryButton>
                {{ updating ? 'Actualizar' : 'Crear' }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>
