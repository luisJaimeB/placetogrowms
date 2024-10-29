<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useI18n } from 'vue-i18n';
import { usePage } from '@inertiajs/vue3';

const { locale, setLocaleMessage, t } = useI18n();
const isChecked = ref(false);

const toggleLanguage = async (event) => {
    const isCheckedValue = event.target.checked;
    const newLocale = isCheckedValue ? 'en' : 'es';

    try {
        const response = await axios.get(`/lang/${newLocale}`);
        locale.value = response.data.locale;
        setLocaleMessage(response.data.locale, response.data.messages);
        isChecked.value = (newLocale === 'en');
    } catch (error) {
        console.error('Error loading translations:', error);
    }
};

onMounted(() => {
    const currentLocale = usePage().props.locale;
    isChecked.value = (currentLocale === 'en');
});
</script>

<template>
    <label class="inline-flex items-center cursor-pointer space-x-2 rtl:space-x-reverse">
        <span class="text-sm font-medium text-gray-900 dark:text-gray-300">{{ t('fields.lang.es') }}</span>
        <input
            type="checkbox"
            class="sr-only peer"
            v-model="isChecked"
            @change="toggleLanguage"
        >
        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
        <span class="text-sm font-medium text-gray-900 dark:text-gray-300">{{ t('fields.lang.en') }}</span>
    </label>
</template>

<style scoped>

</style>
