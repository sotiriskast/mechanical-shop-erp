<script setup>
const props = defineProps({
    meta: {
        type: Object,
        required: true
    }
})

const emit = defineEmits(['page'])

const pageRange = computed(() => {
    const range = []
    const start = Math.max(1, props.meta.current_page - 2)
    const end = Math.min(props.meta.last_page, props.meta.current_page + 2)

    for (let i = start; i <= end; i++) {
        range.push(i)
    }

    return range
})
</script>

<template>
    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
        <div class="flex-1 flex justify-between sm:hidden">
            <button
                :disabled="!meta.prev_page_url"
                @click="$emit('page', meta.current_page - 1)"
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                :class="{ 'opacity-50 cursor-not-allowed': !meta.prev_page_url }"
            >
                Previous
            </button>
            <button
                :disabled="!meta.next_page_url"
                @click="$emit('page', meta.current_page + 1)"
                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                :class="{ 'opacity-50 cursor-not-allowed': !meta.next_page_url }"
            >
                Next
            </button>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Showing
                    <span class="font-medium">{{ meta.from }}</span>
                    to
                    <span class="font-medium">{{ meta.to }}</span>
                    of
                    <span class="font-medium">{{ meta.total }}</span>
                    results
                </p>
            </div>
        </div>
    </div>
</template>
