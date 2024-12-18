<script>
export default {
    name: 'PlansForm'
}
</script>

<script setup>
import FormSection from '@/Components/FormSection.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import {useI18n} from "vue-i18n"
import {toRefs} from "vue";
import {SButton} from "@placetopay/spartan-vue";

const { t } = useI18n()

const props = defineProps({
    form: {
        type: Object,
        required: true
    },
    updating: {
        type: Boolean,
        required: false,
        default: false
    },
    periodicities: {
        type: Array,
        required: true
    },
    subscriptionTerm: {
        type: Array,
        required: true
    },
    microsites: {
        type: Array,
        required: false
    }
})

const { form } = toRefs(props)

const addItem = () => {
    if (!Array.isArray(form.value.items)) {
        form.value.items = [];
    }
    form.value.items.push('');
}

const removeItem = (index) => {
    if (Array.isArray(form.value.items)) {
        form.value.items.splice(index, 1);
    }
}

const filterInput = (event) => {
    event.target.value = event.target.value.replace(/\D/g, '');
    props.form.buyer_id = event.target.value;
};

const goBack = () => {
    window.history.back();
}

defineEmits(['submit'])
</script>

<template>
    <FormSection @submitted="$emit('submit')">
        <template #title>
            {{ updating ? t('strings.updateSuscriptionPlan') : t('strings.createSuscriptionPlan') }}
        </template>

        <template #description>
            {{ updating ? t('strings.updateSuscriptionPlanDesc') : t('strings.createSuscriptionPlanDesc') }}
        </template>

        <template #form>
            <!-- Name -->
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="name" value="Name" />
                <TextInput id="name" v-model="form.name" type="text" autocomplete="name" class="mt-1 block w-full" />
                <InputError :message="$page.props.errors.name" class="mt-2" />
            </div>

            <!-- Microsite ID -->
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="microsite_id" :value="t('fields.microsite')" />
                <select id="microsite_id" v-model="form.microsite_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option v-for="microsite in microsites" :key="microsite.id" :value="microsite.id">
                        {{ microsite.name }}
                    </option>
                </select>
                <InputError :message="$page.props.errors.microsite_id" class="mt-2" />
            </div>

            <!-- Periodicity -->
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="periodicity" :value="t('fields.periodicity')" />
                <select id="periodicity" v-model="form.periodicity" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option v-for="option in periodicities" :key="option" :value="option">
                        {{ option }}
                    </option>
                </select>
                <InputError :message="$page.props.errors.periodicity" class="mt-2" />
            </div>

            <!-- Due Date -->
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="due_date" :value="t('fields.dueDate')" />
                <select id="subscriptionTerm" v-model="form.subscriptionTerm" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option v-for="option in subscriptionTerm" :key="option" :value="option">
                        {{ option }}
                    </option>
                </select>
                <InputError :message="$page.props.errors.subscriptionTerm" class="mt-2" />
            </div>

            <!-- amount -->
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="amount" :value="t('fields.amountSuscription')" />
                <TextInput id="amount" v-model="form.amount" type="text" class="mt-1 block w-full" @input="filterInput"/>
                <InputError :message="$page.props.errors.amount" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="lapse" value="Tiempo reintento (En caso de fallar el cobro)" />
                <TextInput id="lapse" v-model="form.lapse" placeholder="Tiempo de cada reintento en horas" type="text" class="mt-1 block w-full" @input="filterInput"/>
                <InputError :message="$page.props.errors.lapse" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="attempts" value="Número de intentos" />
                <TextInput id="attempts" v-model="form.attempts" placeholder="Número de intentos" type="text" class="mt-1 block w-full" @input="filterInput"/>
                <InputError :message="$page.props.errors.attempts" class="mt-2" />
            </div>

            <!-- Items -->
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="items" :value="t('fields.items')" />
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-1">
                    <div v-for="(index) in form.items" :key="index" class="flex items-center">
                        <TextInput v-model="form.items[index]" type="text" class="block w-full" />
                        <button type="button" @click="removeItem(index)" class="ml-2 text-red-600">
                            Remove
                        </button>
                    </div>
                </div>
                <button type="button" @click="addItem" class="mt-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out">
                    Add Item
                </button>
                <InputError :message="$page.props.errors.items" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <SButton variant="secondary" @click="goBack" class="mr-4">{{ t('buttons.cancel') }}</SButton>
            <SButton variant="primary" type="submit">
                {{ updating ? t('buttons.updateB') : t('buttons.createB') }}
            </SButton>
        </template>
    </FormSection>
</template>

