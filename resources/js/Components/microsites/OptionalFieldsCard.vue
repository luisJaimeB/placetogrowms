<script setup>
import { useForm } from '@inertiajs/vue3'

defineProps({
    form: {
        type: Object,
        required: true
    }
})

defineEmits(['submit'])
</script>

<template>
    <div class="w-full p-6 bg-white rounded-lg shadow-md mt-6">
        <h2 class="text-lg font-bold mb-4">Campos Opcionales</h2>
        <form @submit.prevent="$emit('submit')">
            <div class="flex flex-row flex-wrap gap-6">
                <!-- Dirección -->
                <div class="flex items-center">
                    <input
                        type="checkbox"
                        id="address"
                        v-model="form.address"
                        class="mr-2 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                    />
                    <label for="address" class="text-gray-700">Dirección</label>
                </div>

                <!-- País -->
                <div class="flex items-center">
                    <input
                        type="checkbox"
                        id="country"
                        v-model="form.country"
                        class="mr-2 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                    />
                    <label for="country" class="text-gray-700">País</label>
                </div>

                <!-- Ciudad -->
                <div class="flex items-center">
                    <input
                        type="checkbox"
                        id="city"
                        v-model="form.city"
                        class="mr-2 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                    />
                    <label for="city" class="text-gray-700">Ciudad</label>
                </div>
            </div>

            <button
                type="submit"
                class="mt-4 px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                Añadir
            </button>
        </form>
    </div>
</template>

<style scoped>
.w-full {
    max-width: 100%;
}
</style>
