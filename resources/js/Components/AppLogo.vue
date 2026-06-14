<script setup lang="ts">
defineProps({
    color: {
        type: String,
        default: 'currentColor'
    }
});
</script>

<template>
    <svg
        viewBox="0 0 250 250"
        xmlns="http://www.w3.org/2000/svg"
        :fill="color"
        class="rpgate-logo"
    >
        <path
            d="M125 10 L230 70 L230 180 L125 240 L20 180 L20 70 Z"
            fill-opacity="0.1"
            :stroke="color"
            stroke-width="12"
            stroke-linejoin="round"
        />

        <g :fill="color" transform="translate(55, 55) scale(0.56)">
            <path d="M125,5 240,65 125,245 10,65 Z" fill-opacity="0.3"/>
            <path d="M125,5 L10,65 125,100 240,65 Z"/>
            <path d="M10,65 L10,185 125,100 Z"/>
            <path d="M240,65 L240,185 125,100 Z"/>
            <path d="M10,185 L125,245 125,100 Z"/>
            <path d="M240,185 L125,245 125,100 Z"/>

            <path
                d="M125,5 L10,65 L10,185 L125,245 L240,185 L240,65 Z M125,5 L125,100 M10,65 L125,100 M240,65 L125,100 M10,185 L125,100 M240,185 L125,100 M125,245 L125,100"
                fill="none"
                :stroke="color"
                stroke-width="6"
                stroke-linejoin="round"
            />
        </g>
    </svg>
</template>

<style scoped>
.rpgate-logo {
    display: inline-block;
    max-width: 100%;
    max-height: 100%;
    vertical-align: middle;
}
</style>
