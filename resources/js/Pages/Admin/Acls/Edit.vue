<script>
export default {
    name: 'AclsEdit'
}
</script>
<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, useForm} from "@inertiajs/vue3";
import {useI18n} from "vue-i18n";

const props = defineProps({
    acl: {
        type: Object,
        required: true
    }
})

const { t } = useI18n();

const form = useForm({
    user_id: props.acl.user_id,
    model_type: props.acl.model_type,
    model_id: props.acl.model_id,
    status: props.acl.status
})
</script>

<template>
    <Head title="Acls" />
    <authenticated-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ t('titles.acls') }}
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-withe overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h2>Próximamente...</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </authenticated-layout>
</template>

<style scoped>

</style>
