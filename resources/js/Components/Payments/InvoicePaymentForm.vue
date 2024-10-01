<script>
export default {
    name: 'InvoicePaymentForm'
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
    form: {
        type: Object,
        required: true
    },
    errors: {
        type: Object,
        required: true
    },
});

const { t } = useI18n();
const getLogoUrl = (path) => {
    return path ? `/microsite/logo/${path}` : null;
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
                    <!-- Information Section -->
                    <div class="bg-gray-100 p-4 rounded-md shadow-sm">
                        <p class="text-gray-700 text-sm">
                            {{ t('info.description_intro') }}
                        </p>
                        <p class="text-gray-500 text-xs mt-2">
                            {{ t('info.description_details') }}
                        </p>
                    </div>

                    <!-- Description -->
                    <div class="flex justify-end">
                        <div class="w-1/2">
                            <label for="order_number" class="block text-sm font-medium text-gray-700">{{ t('fields.orderNumber') }}</label>
                            <input type="text" id="order_number" v-model="form.order_number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"/>
                            <InputError :message="errors.order_number" class="mt-2"/>
                        </div>
                    </div>
                </div>

              <!-- Submit Button -->
                <div class="flex justify-end mt-4">
                    <PrimaryButton class="px-4 py-2 bg-indigo-600 text-white rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ t('buttons.search') }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>

</style>
