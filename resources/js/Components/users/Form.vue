<script>
    export default {
        name: 'UsersForm'
    }
</script>

<script setup>
import FormSection from '@/Components/FormSection.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import {useI18n} from "vue-i18n"

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
    roles: {
        type: Array,
        required: true
    },
    userRole: {
        type: Array,
        required: false
    },
    userPermissions: {
        type: Array,
        required: false
    },
})
const { t } = useI18n();

defineEmits(['submit'])
</script>

<template>
    <FormSection @submitted="$emit('submit')">
        <template #title>
            {{  updating ? t('strings.updateUser') : t('strings.createUser') }}
        </template>

        <template #description>
            {{ updating ? t('strings.updateUserDesc') : t('strings.createUserDesc') }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="name" :value="t('fields.name')" />
                <TextInput id="name" v-model="form.name" type="text" autocomplete="name" class="mt-1 block w-full" />
                <InputError :message="$page.props.errors.name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="email" :value="t('fields.email')" />
                <TextInput id="email" v-model="form.email" type="email" autocomplete="email" class="mt-1 block w-full" />
                <InputError :message="$page.props.errors.email" class="mt-2" />
            </div>

            <div v-if="!updating" class="col-span-6 sm:col-span-6">
                <InputLabel for="password" :value="t('fields.password')" />
                <TextInput id="password" v-model="form.password" type="password" autocomplete="password" class="mt-1 block w-full" />
                <InputError :message="$page.props.errors.password" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="roles" :value="t('fields.roles')" />
                <div v-for="role in roles" :key="role.id" class="flex items-center">
                    <input type="radio" :value="role.id" v-model="form.rol" :id="'role_' + role.id" class="mr-2 leading-tight">
                    <label :for="'role_' + role.id" class="text-gray-700">{{ role.name }}</label>
                </div>
                <InputError :message="$page.props.errors.rol" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <PrimaryButton>
                {{ updating ? t('buttons.updateB') : t('buttons.createB') }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>
