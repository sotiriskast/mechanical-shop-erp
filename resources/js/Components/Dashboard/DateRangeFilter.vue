<script setup>
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    modelValue: {
        type: String,
        required: true
    }
})

const emit = defineEmits(['update:modelValue'])

const ranges = [
    { value: 'today', label: 'Today' },
    { value: 'week', label: 'This Week' },
    { value: 'month', label: 'This Month' },
    { value: 'quarter', label: 'This Quarter' },
    { value: 'year', label: 'This Year' }
]

const selectedRange = ref(props.modelValue)

watch(selectedRange, (newValue) => {
    emit('update:modelValue', newValue)
    router.get(route('dashboard'), { date_range: newValue }, {
        preserveState: true,
        preserveScroll: true
    })
})
</script>

<template>
    <div class="flex items-center space-x-2">
        <label class="text-sm font-medium text-gray-700">Time Range:</label>
        <select
            v-model="selectedRange"
            class="rounded-md border-gray-300 py-2 pl-3 pr-10 text-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500"
        >
            <option
                v-for="range in ranges"
                :key="range.value"
                :value="range.value"
            >
                {{ range.label }}
            </option>
        </select>
    </div>
</template>
