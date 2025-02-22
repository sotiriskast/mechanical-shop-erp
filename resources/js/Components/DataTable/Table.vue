<script setup>
import { computed } from 'vue'
import TablePagination from './TablePagination.vue'
import TableHeader from './TableHeader.vue'
import { ChevronUpIcon, ChevronDownIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    columns: {
        type: Array,
        required: true
    },
    data: {
        type: Object,
        required: true
    },
    sortBy: {
        type: String,
        default: null
    },
    sortDirection: {
        type: String,
        default: 'asc'
    }
})

const emit = defineEmits(['sort', 'page'])

const handleSort = (column) => {
    if (!column.sortable) return

    emit('sort', {
        column: column.key,
        direction: props.sortBy === column.key && props.sortDirection === 'asc' ? 'desc' : 'asc'
    })
}

const handlePage = (page) => {
    emit('page', page)
}
</script>

<template>
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th v-for="column in columns"
                                :key="column.key"
                                :class="[
                      'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider',
                      column.sortable ? 'cursor-pointer hover:text-gray-700' : ''
                    ]"
                                @click="handleSort(column)"
                            >
                                <div class="flex items-center">
                                    {{ column.label }}
                                    <span v-if="column.sortable" class="ml-2">
                      <ChevronUpIcon
                          v-if="sortBy === column.key && sortDirection === 'asc'"
                          class="h-4 w-4"
                      />
                      <ChevronDownIcon
                          v-else-if="sortBy === column.key && sortDirection === 'desc'"
                          class="h-4 w-4"
                      />
                    </span>
                                </div>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="item in data.data" :key="item.id">
                            <td v-for="column in columns"
                                :key="column.key"
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                            >
                                <slot :name="column.key" :item="item">
                                    {{ item[column.key] }}
                                </slot>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <TablePagination
                        v-if="data.meta"
                        :meta="data.meta"
                        @page="handlePage"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
