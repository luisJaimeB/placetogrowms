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
}) 

defineEmits(['submit'])
</script>

<template>
    <FormSection @submitted="$emit('submit')">
        <template #title>
            {{  updating ? $page.props.trans.common.strings.updateUser : $page.props.trans.common.strings.createUser }}
        </template>

        <template #description>
            {{ updating ? $page.props.trans.common.strings.updateUserDesc : $page.props.trans.common.strings.createUserDesc }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="name" value="Name" />
                <TextInput id="name" v-model="form.name" type="text" autocomplete="name" class="mt-1 block w-full" />
                <InputError :message="$page.props.errors.name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="email" value="Email" />
                <TextInput id="email" v-model="form.email" type="email" autocomplete="email" class="mt-1 block w-full" />
                <InputError :message="$page.props.errors.email" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="password" value="password" />
                <TextInput id="password" v-model="form.password" type="password" autocomplete="password" class="mt-1 block w-full" />
                <InputError :message="$page.props.errors.password" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="roles" value="Roles" />
                <div v-for="role in roles" :key="role.id" class="flex items-center">
                    <input type="radio" :value="role.id" v-model="form.roles" :id="'role_' + role.id" class="mr-2 leading-tight">
                    <label :for="'role_' + role.id" class="text-gray-700">{{ role.name }}</label>
                </div>
                <InputError :message="$page.props.errors.roles" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <PrimaryButton>
                {{ updating ? $page.props.trans.common.buttons.updateB : $page.props.trans.common.buttons.createB }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>