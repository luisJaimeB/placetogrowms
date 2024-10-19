<script>
    export default {
        name: 'RolesForm'
    }
</script>

<script setup>
import FormSection from '@/Components/FormSection.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
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
    },
    permissions:
    {
        type: Array,
        required: true
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
            {{  updating ? t('strings.updateRol') : t('strings.createRol') }}
        </template>

        <template #description>
            {{ updating ? t('strings.updateRolDesc') : t('strings.createRolDesc') }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="name" :value="t('fields.name')" />
                <TextInput id="name" v-model="form.name" type="text" autocomplete="name" class="mt-1 block w-full" />
                <InputError :message="$page.props.errors.name" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-6" v-for="(groupPermissions, groupName) in permissions" :key="groupName">
                <h3 class="text-lg font-medium text-gray-900">{{ groupName }}</h3>
                <div class="mt-4 grid grid-cols-2 gap-4">
                    <div v-for="permission in groupPermissions" :key="permission.id" class="flex items-center mb-2">
                        <input type="checkbox" :value="permission.id" v-model="form.permissions" :id="'permission_' + permission.id"
                            class="mr-2 leading-tight">
                        <label :for="'permission_' + permission.id" class="text-gray-700">{{ permission.name }}</label>
                    </div>
                </div>
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
