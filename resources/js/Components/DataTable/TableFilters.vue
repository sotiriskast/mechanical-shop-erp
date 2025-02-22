<script setup>
import { ref, watch } from 'vue'
import { debounce } from 'lodash'
import {
    Listbox,
    ListboxButton,
    ListboxOptions,
    ListboxOption
} from '@headlessui/vue'
import { FunnelIcon, XMarkIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    filters: {
        type: Array,
        required: true
    },
    initialValues: {
        type: Object,
        default: () => ({})
    }
})

const emit = defineEmits(['filter'])

const activeFilters = ref({...props.initialValues})

// Debounced filter emission
const emitFilters = debounce(() => {
    emit('filter', activeFilters.value)
}, 300)

// Watch for filter changes
watch(activeFilters, () => {
    emitFilters()
}, { deep: true })

const resetFilters = () => {
    activeFilters.value = {}
    emit('filter', {})
}
</script>

<template>
    <div class="bg-white p-4 rounded-lg shadow mb-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <FunnelIcon class="h-5 w-5 mr-2" />
                Filters
            </h3>
            <button
                v-if="Object.keys(activeFilters).length > 0"
                @click="resetFilters"
                class="text-sm text-gray-500 hover:text-gray-700 flex items-center"
            >
                <XMarkIcon class="h-4 w-4 mr-1" />
                Clear all
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Text Filter -->
            <template v-for="filter in filters" :key="filter.key">
                <div v-if="filter.type === 'text'" class="flex flex-col">
                    <label :for="filter.key" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ filter.label }}
                    </label>
                    <input
                        :id="filter.key"
                        type="text"
                        v-model="activeFilters[filter.key]"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                        :placeholder="filter.placeholder"
                    />
                </div>

                <!-- Select Filter -->
                <div v-else-if="filter.type === 'select'" class="flex flex-col">
                    <Listbox v-model="activeFilters[filter.key]">
                        <ListboxLabel class="block text-sm font-medium text-gray-700 mb-1">
                            {{ filter.label }}
                        </ListboxLabel>
                        <div class="relative mt-1">
                            <ListboxButton
                                class="relative w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            >
                <span class="block truncate">
                  {{ filter.options.find(opt => opt.value === activeFilters[filter.key])?.label || 'Select...' }}
                </span>
                            </ListboxButton>

                            <ListboxOptions
                                class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
                            >
                                <ListboxOption
                                    v-for="option in filter.options"
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
                    </Listbox>
                </div>

                <!-- Date Range Filter -->
                <div v-else-if="filter.type === 'dateRange'" class="flex flex-col">
                    <label :for="filter.key + '_start'" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ filter.label }}
                    </label>
                    <div class="flex space-x-2">
                        <input
                            :id="filter.key + '_start'"
                            type="date"
                            v-model="activeFilters[filter.key + '_start']"
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                        />
                        <input
                            :id="filter.key + '_end'"
                            type="date"
                            v-model="activeFilters[filter.key + '_end']"
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                        />
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>
