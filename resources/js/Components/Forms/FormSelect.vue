<script setup>
import { computed } from 'vue'
import { Listbox, ListboxButton, ListboxOptions, ListboxOption } from '@headlessui/vue'
import { ChevronDownIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    modelValue: {
        type: [String, Number],
        default: ''
    },
    label: {
        type: String,
        required: true
    },
    options: {
        type: Array,
        required: true
    },
    error: {
        type: String,
        default: ''
    },
    required: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['update:modelValue'])

const selectedOption = computed(() => {
    return props.options.find(option => option.value === props.modelValue)
})
</script>

<template>
    <Listbox v-bind="modelValue" @update:modelValue="$emit('update:modelValue', $event)">
        <div class="relative">
            <ListboxLabel class="block text-sm font-medium text-gray-700">
                {{ label }}
                <span v-if="required" class="text-red-500">*</span>
            </ListboxLabel>

            <div class="mt-1">
                <ListboxButton
                    class="relative w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    :class="{ 'border-red-300': error }"
                >
          <span class="block truncate">
            {{ selectedOption?.label || 'Select...' }}
          </span>
                    <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
            <ChevronDownIcon class="h-5 w-5 text-gray-400" />
          </span>
                </ListboxButton>

                <ListboxOptions
                    class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
                >
                    <ListboxOption
                        v-for="option in options"
                        :key="option.value"
                        :value="option.value"
                        v-slot="{ active, selected }"
                    >
                        <div
                            :class="[
                active ? 'text-white bg-indigo-600' : 'text-gray-900',
                'cursor-default select-none relative py-2 pl-3 pr-9'
              ]"
                        >
              <span :class="[selected ? 'font-semibold' : 'font-normal', 'block truncate']">
                {{ option.label }}
              </span>
                        </div>
                    </ListboxOption>
                </ListboxOptions>
            </div>

            <p v-if="error" class="mt-2 text-sm text-red-600">{{ error }}</p>
        </div>
    </Listbox>
</template>
