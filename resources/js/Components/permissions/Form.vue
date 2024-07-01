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
    }
}) 

defineEmits(['submit'])
</script>

<template>
    <FormSection @submitted="$emit('submit')">
        <template #title>
            {{  updating ? $page.props.trans.common.strings.updatePermission : $page.props.trans.common.strings.createPermission }}
        </template>

        <template #description>
            {{ updating ? $page.props.trans.common.strings.updatePermissionDesc : $page.props.trans.common.strings.createPermissionDesc }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="name" value="Name" />
                <TextInput id="name" v-model="form.name" type="text" autocomplete="name" class="mt-1 block w-full" />
                <InputError :message="$page.props.errors.name" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <PrimaryButton>
                {{ updating ? $page.props.trans.common.buttons.updateB : $page.props.trans.common.buttons.createB }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>