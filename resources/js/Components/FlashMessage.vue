<script setup>
import { usePage } from '@inertiajs/vue3'
import { computed, onMounted, ref } from 'vue'

const show = ref(false)
const flash = computed(() => usePage().props.flash)

onMounted(() => {
    if (flash.value) {
        show.value = true
        setTimeout(() => {
            show.value = false
        }, 3000) // Hide after 3 seconds
    }
})
</script>

<template>
    <div
        v-if="show && flash"
        :class="{
            'fixed top-4 right-4 z-50 rounded-lg p-4 shadow-lg transition-all duration-500': true,
            'bg-green-100 text-green-800': flash.type === 'success',
            'bg-red-100 text-red-800': flash.type === 'error'
        }"
    >
        <div class="flex items-center">
            <!-- Success Icon -->
            <svg
                v-if="flash.type === 'success'"
                class="h-5 w-5 mr-2"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 13l4 4L19 7"
                />
            </svg>
            <!-- Error Icon -->
            <svg
                v-if="flash.type === 'error'"
                class="h-5 w-5 mr-2"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                />
            </svg>
            {{ flash.message }}
        </div>
    </div>
</template>
