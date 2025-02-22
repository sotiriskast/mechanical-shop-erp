<script setup>
import { computed } from 'vue'

const props = defineProps({
    modelValue: {
        type: [String, Number],
        default: ''
    },
    label: {
        type: String,
        required: true
    },
    type: {
        type: String,
        default: 'text'
    },
    error: {
        type: String,
        default: ''
    },
    required: {
        type: Boolean,
        default: false
    },
    help: {
        type: String,
        default: ''
    }
})

const emit = defineEmits(['update:modelValue'])

const inputId = computed(() => `input_${Math.random().toString(36).substr(2, 9)}`)
</script>

<template>
    <div>
        <label :for="inputId" class="block text-sm font-medium text-gray-700">
            {{ label }}
            <span v-if="required" class="text-red-500">*</span>
        </label>

        <div class="mt-1">
            <input
                :id="inputId"
                :type="type"
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
                :class="[
          'shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md',
          error ? 'border-red-300' : ''
        ]"
            />
        </div>

        <p v-if="help" class="mt-2 text-sm text-gray-500">{{ help }}</p>
        <p v-if="error" class="mt-2 text-sm text-red-600">{{ error }}</p>
    </div>
</template>
