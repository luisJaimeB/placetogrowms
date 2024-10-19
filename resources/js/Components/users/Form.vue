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
import {useI18n} from "vue-i18n"
import {SButton} from "@placetopay/spartan-vue";

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

const goBack = () => {
    window.history.back();
}

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
                <!-- Campo Nombre -->
                <div class="col-span-6 sm:col-span-3">
                    <InputLabel for="name" :value="t('fields.name')" />
                    <TextInput id="name" v-model="form.name" autocomplete="name" class="mt-1 block w-full"/>
                    <InputError :message="$page.props.errors.name" class="mt-2" />
                </div>

                <!-- Campo Email -->
                <div class="col-span-6 sm:col-span-3">
                    <InputLabel for="email" :value="t('fields.email')" />
                    <TextInput id="email" v-model="form.email" type="email" autocomplete="email" class="mt-1 block w-full"/>
                    <InputError :message="$page.props.errors.email" class="mt-2" />
                </div>

                <!-- Campo Contraseña -->
                <div v-if="!updating" class="col-span-6 sm:col-span-3">
                    <InputLabel for="password" :value="t('fields.password')" />
                    <TextInput id="password" v-model="form.password" type="password" autocomplete="password" class="mt-1 block w-full"/>
                    <InputError :message="$page.props.errors.password" class="mt-2" />
                </div>

                <!-- Radios -->
                <div class="col-span-2">
                    <InputLabel for="roles" :value="t('fields.roles')" />
                    <div class="flex items-center space-x-4">
                        <div v-for="role in roles" :key="role.id" class="flex items-center">
                            <input type="radio" :value="role.id" v-model="form.rol" :id="'role_' + role.id" class="mr-2 leading-tight">
                            <label :for="'role_' + role.id" class="text-gray-700">{{ role.name }}</label>
                        </div>
                    </div>
                    <InputError :message="$page.props.errors.rol" class="mt-2" />
                </div>
        </template>


        <template #actions>
            <section class="flex justify-end gap-2">
                <SButton variant="secondary" @click="goBack" class="mr-4">Cancelar</SButton>
                <SButton variant="primary">
                    {{ updating ? t('buttons.updateB') : t('buttons.createB') }}
                </SButton>
            </section>
        </template>
    </FormSection>
</template>
