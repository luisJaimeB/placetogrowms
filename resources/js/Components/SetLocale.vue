<script setup>
import { ref, onMounted } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

const isChecked = ref(false);

const toggleLanguage = (event) => {
    const isCheckedValue = event.target.checked;
    const locale = isCheckedValue ? 'en' : 'es';

    router.visit(route('setLang', { locale }), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            isChecked.value = (locale === 'en');
            window.location.reload();
        }
    });
};

onMounted(() => {
    const locale = usePage().props.locale;
    isChecked.value = (locale === 'en');
});
</script>

<template>
    <label class="inline-flex items-center cursor-pointer space-x-2 rtl:space-x-reverse">
        <span class="text-sm font-medium text-gray-900 dark:text-gray-300">{{ $page.props.trans.common.fields.lang.es }}</span>
        <input
            type="checkbox"
            class="sr-only peer"
            v-model="isChecked"
            @change="toggleLanguage"
        >
        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
        <span class="text-sm font-medium text-gray-900 dark:text-gray-300">{{ $page.props.trans.common.fields.lang.en }}</span>
    </label>
</template>

<style scoped>

</style>
