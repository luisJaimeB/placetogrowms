<script>
    export default {
        name: 'PermissionsForm'
    }
</script>

<script setup>
import FormSection from '@/Components/FormSection.vue'
import TextInput from '@/Components/TextInput.vue'
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
            {{  updating ? t('strings.updatePermission') : t('strings.createPermission') }}
        </template>

        <template #description>
            {{ updating ? t('strings.updatePermissionDesc') : t('strings.createPermissionDesc') }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="name" value="Name" />
                <TextInput id="name" v-model="form.name" type="text" autocomplete="name" class="mt-1 block w-full" />
                <InputError :message="$page.props.errors.name" class="mt-2" />
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
