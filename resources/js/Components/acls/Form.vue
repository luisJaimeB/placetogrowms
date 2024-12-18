<script>
export default {
    name: 'AclsForm'
}
</script>

<script setup>
import FormSection from '@/Components/FormSection.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import {useI18n} from "vue-i18n"
import axios from 'axios'
import {ref} from "vue";
import {SButton} from "@placetopay/spartan-vue";

const { t } = useI18n()

const props = defineProps({
    form: {
        type: Object,
        required: true
    },
    models: {
        type: Array,
        required: true
    },
    users: {
        type: Object,
        required: true
    },
    updating: {
        type: Boolean,
        required: false,
        default: false
    },
    actions: {
        type: Array,
        required: true
    }
})

let modelData = ref([]);

const fetchModelData = async () => {
    try {
        const selectedModel = props.form.model_type;
        console.log("Modelo seleccionado:", selectedModel)
        const response = await axios.post(route('index.aclmodel'), { model: selectedModel });
        modelData.value = response.data;
        console.log("Datos del modelo:", modelData.value);
    } catch (error) {
        console.error("Hubo un error:", error);
    }
}

const goBack = () => {
    window.history.back();
}

defineEmits(['submit'])
</script>

<template>
    <FormSection @submitted="$emit('submit')">
        <template #title>
            {{  updating ? t('strings.updateAcl') : t('strings.createAcl') }}
        </template>

        <template #description>
            {{ updating ? t('strings.updateAclDesc') : t('strings.createAclDesc') }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="users" :value="t('titles.users')" />
                <select id="users" v-model="form.user_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option v-for="user in users" :key="user.id" :value="user.id">
                        {{ user.name }}
                    </option>
                </select>
                <InputError :message="$page.props.errors.user_id" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="users" :value="t('fields.modules')" />
                <select id="models" v-model="form.model_type" @change="fetchModelData" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option v-for="model in models" :key="model.value" :value="model.value">
                        {{ model.text }}
                    </option>
                </select>
                <InputError :message="$page.props.errors.model_type" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="actionss" :value="t('fields.actions')" />
                <select id="actions" v-model="form.status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option v-for="action in actions" :key="action" :value="action">
                        {{ action }}
                    </option>
                </select>
                <InputError :message="$page.props.errors.action" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="actionss" :value="form.model_type" />
                <select id="model_id" v-model="form.model_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option v-for="item in modelData" :key="item.id" :value="item.id">
                        {{ item.text }}
                    </option>
                </select>
                <InputError :message="$page.props.errors.model_id" class="mt-2" />
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
