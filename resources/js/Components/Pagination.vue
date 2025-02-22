<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    links: {
        type: [Array, Object],
        required: true
    }
})

// Convert object to array if needed
const linkArray = computed(() => {
    return Array.isArray(props.links)
        ? props.links
        : props.links.links || []
})
</script>

<template>
    <div v-if="linkArray.length > 0" class="flex items-center justify-between bg-white px-4 py-3 sm:px-6">
        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
            <Link
                v-for="link in linkArray"
                v-show="link.url"
                :key="link.label"
                :href="link.url"
                :class="[
          link.active
            ? 'bg-indigo-600 text-white hover:bg-indigo-500'
            : 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50',
          'relative inline-flex items-center px-4 py-2 text-sm font-semibold focus:z-20'
        ]"
            >
                <span v-html="link.label"></span>
            </Link>
        </nav>
    </div>
</template>
