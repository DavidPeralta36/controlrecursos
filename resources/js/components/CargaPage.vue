<template lang="html">
    <div>
        Bienvenido {{ props.user.name }}
    </div>
</template>

<script setup>
import { ref, defineProps } from 'vue';

const props = defineProps({
    user: Object,
});

</script>


<style >
    
</style>