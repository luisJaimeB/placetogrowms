<script>
export default {
    name: "PaymentDetails",
};
</script>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    payment: {
        type: Object,
        required: true
    }
});

//const buyer = JSON.parse(payment.buyer);
</script>

<template>
    <authenticated-layout>
        <div class="flex space-x-4">
            <div class="bg-white overflow-hidden shadow rounded-lg border mx-4 box">
                <div class="px-4 py-5 sm:px-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Detalles del pago
                        </h3>
                    </div>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        La información de contacto se proporciona a continuación.
                    </p>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                    <dl class="sm:divide-y sm:divide-gray-200">

                        <!-- Sección: Datos del comprador -->
                        <div class="px-4 py-5 sm:px-6">
                            <h4 class="text-lg leading-6 font-medium text-gray-900">
                                Datos del comprador
                            </h4>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Tipo de documento</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ JSON.parse(payment.buyer).buyer_id_type }}
                            </dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Número de documento</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ JSON.parse(payment.buyer).buyer_id }}
                            </dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ JSON.parse(payment.buyer).name }}
                            </dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Apellido</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ JSON.parse(payment.buyer).lastName }}
                            </dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Correo electrónico</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ JSON.parse(payment.buyer).email }}
                            </dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Número de teléfono</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ JSON.parse(payment.buyer).phone }}
                            </dd>
                        </div>

                        <!-- Sección: Datos del pagador -->
                        <div class="px-4 py-5 sm:px-6">
                            <h4 class="text-lg leading-6 font-medium text-gray-900">
                                Datos del pagador
                            </h4>
                        </div>
                        <!-- Ejemplo de datos del pagador, ajusta según sea necesario -->
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Tipo de identificación</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ JSON.parse(payment.payer).document_type }}
                            </dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Apellidos</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ JSON.parse(payment.payer).document }}
                            </dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ JSON.parse(payment.payer).name }}
                            </dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Apellidos</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ JSON.parse(payment.payer).surname }}
                            </dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Correo eléctronico</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ JSON.parse(payment.payer).email }}
                            </dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Celular</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ JSON.parse(payment.payer).mobile }}
                            </dd>
                        </div>

                    </dl>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg border box">
                <div class="px-4 py-5 sm:px-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Detalles transacción
                        </h3>
                    </div>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        You can find the project sites assigned to this contact below.
                    </p>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                    <dl class="sm:divide-y sm:divide-gray-200">
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Estado de la transacción</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ payment.status }}
                            </dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Reference</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ payment.reference }}
                            </dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ payment.description }}
                            </dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Monto</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ payment.amount }}
                            </dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Moneda</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ payment.currency.code }}
                            </dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Fecha</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ payment.date }}
                            </dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Número de autorización</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ payment.cus_code }}
                            </dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Micrositio</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ payment.microsite.name }}
                            </dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Dirección IP</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ payment.ip_address }}
                            </dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Agente navegador</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ payment.user_agent }}
                            </dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Pasarela</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ payment.payment_method }}
                            </dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Vaucher de pago</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <a :href="payment.return_url" class="py-1 px-2.5 border-none rounded bg-blue-100 text-xl text-blue-800 font-medium">
                                    {{ payment.return_url }}
                                </a>
                            </dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Número de sesión</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ payment.request_id }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </authenticated-layout>
</template>

<style scoped>

</style>
