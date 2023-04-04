<template>
    <div>
        teste
    </div>
</template>

<script setup>
const props = defineProps({
    board: Object,
});

const columns = computed(() => props.board?.data?.columns);
</script>
