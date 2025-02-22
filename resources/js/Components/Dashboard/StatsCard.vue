<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import {
    UserIcon,
    TruckIcon,
    WrenchIcon,
    CurrencyEuroIcon,
    ArrowUpIcon,
    ArrowDownIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
    title: String,
    total: [Number, String],
    active: Number,
    new: Number,
    completed: Number,
    value: Number,
    previous: Number,
    percentage: Number,
    type: {
        type: String,
        default: 'number'
    },
    icon: String,
    trend: String,
    link: String
})

const icons = {
    UserIcon,
    TruckIcon,
    WrenchIcon,
    CurrencyEuroIcon
}

const IconComponent = computed(() => icons[props.icon])

const formattedValue = computed(() => {
    if (props.type === 'currency') {
        return new Intl.NumberFormat('en-CY', {
            style: 'currency',
            currency: 'EUR'
        }).format(props.value)
    }
    return props.total?.toLocaleString()
})
</script>

<template>
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
        <Link
            v-if="link"
            :href="link"
            class="block group"
        >
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <component
                        :is="IconComponent"
                        class="h-6 w-6 text-gray-400 group-hover:text-gray-600"
                    />
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        {{ title }}
                    </h3>
                    <p class="mt-2 text-3xl font-semibold text-gray-900">
                        {{ formattedValue }}
                    </p>

                    <!-- Show percentage change if available -->
                    <div v-if="percentage !== undefined" class="mt-2 flex items-center">
            <span
                :class="[
                'text-sm font-medium',
                percentage >= 0 ? 'text-green-600' : 'text-red-600'
              ]"
            >
              <component
                  :is="percentage >= 0 ? ArrowUpIcon : ArrowDownIcon"
                  class="h-4 w-4 inline"
              />
              {{ Math.abs(percentage) }}%
            </span>
                        <span class="text-sm text-gray-500 ml-2">
              from last month
            </span>
                    </div>

                    <!-- Show active count if available -->
                    <div v-if="active !== undefined" class="mt-2">
            <span class="text-sm text-gray-500">
              {{ active }} active
            </span>
                    </div>
                </div>
            </div>
        </Link>
    </div>
</template>
