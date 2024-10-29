<script setup>

import {computed} from "vue";

const logoUrl = computed(() => {
    return '/microsite/logo.png';
});
</script>
<template>
    <div class="justify-center flex flex-col items-center py-10">
        <img :src="logoUrl" alt="logo" class="h-16 w-16 md:h-24 md:w-24 lg:h-32 lg:w-32 object-cover">
    </div>

</template>
