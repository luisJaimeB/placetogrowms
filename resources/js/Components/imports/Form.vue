<script>
export default {
    name: 'ImportsForm'
}
</script>

<script setup>
import FormSection from '@/Components/FormSection.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import {useI18n} from "vue-i18n"
import {SButton} from "@placetopay/spartan-vue";

const { t } = useI18n()

defineProps({
    form: {
        type: Object,
        required: true
    },
    updating: {
        type: Boolean,
        required: false,
        default: false
    }
})

const goBack = () => {
    window.history.back();
}

defineEmits(['submit'])
</script>

<template>
    <FormSection @submitted="$emit('submit')">
        <template #title>
            {{  updating ? t('strings.updateImport') : t('strings.createImport') }}
        </template>

        <template #description>
            {{ updating ? t('strings.updateImportDesc') : t('strings.createImportDesc') }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="import" class="my-2" :value="t('fields.import')" />
                <input id="import" type="file" @input="form.file = $event.target.files[0]" class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                <InputError :message="$page.props.errors.file" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <SButton variant="secondary" @click="goBack" class="mr-4">Cancelar</SButton>
            <SButton variant="primary">
                {{ updating ? t('buttons.updateB') : t('buttons.createB') }}
            </SButton>
        </template>
    </FormSection>
</template>
