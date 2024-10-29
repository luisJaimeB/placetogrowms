<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed } from "vue";
import { useForm } from '@inertiajs/vue3';
import PrimaryButton from "@/Components/PrimaryButton.vue";

defineProps({
    categories: {
        type: Array,
        required: true
    },
    microsites: {
        type: Array,
        required: true
    },
    filters: {
        type: Object,
        required: true
    },
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

const logoUrl = computed(() => {
    return '/microsite/logo.png';
});

const form = useForm({
    search: '',
    category_id: null,
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}

function updateSearch() {
    form.get(route('dashboard.index'), { preserveState: true });
}

function filterByCategory(category_id) {
    form.category_id = category_id;
    form.get(route('dashboard.index'), { preserveState: true });
}

const getLogoUrl = (path) => {
    return path ? `/microsite/logo/${path}` : null;
};

console.log(form.microsites)
</script>

<template>
    <Head title="Welcome" />
    <div>
        <div
            class="flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white"
        >
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                    <div class="flex lg:justify-center lg:col-start-2">
                        <img :src="logoUrl" alt="logo" class="h-32 w-32 object-cover">
                    </div>
                    <nav v-if="canLogin" class="-mx-3 flex flex-1 justify-end">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]"
                        >
                            Dashboard
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]"
                            >
                                Log in
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]"
                            >
                                Register
                            </Link>
                        </template>
                    </nav>
                </header>

                <main class="mt-6">
                    <div class="container mx-auto p-4">
                        <div class="text-center mb-8">
                            <h1 class="text-4xl font-bold">Bienvenido a Nuestro Servicio</h1>
                            <div class="mt-4">
                                <input
                                    type="text"
                                    v-model="form.search"
                                    @input="updateSearch"
                                    class="border border-gray-300 rounded-md p-2 w-1/2"
                                    placeholder="Buscar por nombre..."
                                >
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div
                                v-for="category in categories"
                                :key="category.id"
                                @click="filterByCategory(category.id)"
                                class="bg-white rounded-lg shadow-md p-6 cursor-pointer"
                            >
                                <h2 class="text-xl font-bold mb-2">{{ category.name }}</h2>
                            </div>
                        </div>

                        <div v-for="microsite in microsites" :key="microsite.id">
                            <a class="p-8 mt-4 shadow-lg max-w-lg border border-gray-200 rounded-2xl hover:shadow-xl hover:shadow-indigo-50 flex flex-col items-center"
                               href="#">
                                <img v-if="microsite.logo" :src="getLogoUrl(microsite.logo)" alt="Logo" class="w-full h-64 object-cover"/>
                                <div class="mt-8">
                                    <h4 class="font-bold text-xl">{{ microsite.name }}</h4>
                                    <p class="mt-2 text-gray-600">
                                        {{ microsite.description }}
                                    </p>
                                    <div class="mt-5">
                                        <PrimaryButton class="text-white px-2 py-1 mx-2 rounded">
                                            <Link :href="route('payments.create', microsite.id)">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="text-white text-center h-6 w-full mr-2" fill="currentColor" viewBox="0 0 576 512">
                                                    <path d="M64 32C28.7 32 0 60.7 0 96l0 32 576 0 0-32c0-35.3-28.7-64-64-64L64 32zM576 224L0 224 0 416c0 35.3 28.7 64 64 64l448 0c35.3 0 64-28.7 64-64l0-192zM112 352l64 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-64 0c-8.8 0-16-7.2-16-16s7.2-16 16-16zm112 16c0-8.8 7.2-16 16-16l128 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-128 0c-8.8 0-16-7.2-16-16z"/>
                                                </svg>
                                            </Link>
                                        </PrimaryButton>
                                    </div>
                                </div>
                            </a>
                        </div>

                    </div>
                </main>

                <footer class="py-16 text-center text-sm text-black dark:text-white/70">
                    Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
                </footer>
            </div>
        </div>
    </div>
</template>
