<script setup>
defineProps({
    text: {
        type: String,
        default: 'Software Engineering Consortium Journals',
    },
    as: {
        type: String,
        default: 'span',
    },
});
</script>

<template>
    <component :is="as" class="italic">{{ text }}</component>
</template>
