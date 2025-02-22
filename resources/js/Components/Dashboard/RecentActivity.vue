<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import {
    UserIcon,
    WrenchIcon,
    DocumentTextIcon,
    ChevronRightIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
    workOrders: {
        type: Array,
        default: () => []
    },
    customers: {
        type: Array,
        default: () => []
    },
    invoices: {
        type: Array,
        default: () => []
    }
})

const activeTab = ref('work-orders')

const tabs = [
    { id: 'work-orders', name: 'Work Orders', icon: WrenchIcon },
    { id: 'customers', name: 'Customers', icon: UserIcon },
    { id: 'invoices', name: 'Invoices', icon: DocumentTextIcon }
]

const getStatusColor = (status) => {
    const colors = {
        pending: 'bg-yellow-100 text-yellow-800',
        in_progress: 'bg-blue-100 text-blue-800',
        completed: 'bg-green-100 text-green-800',
        cancelled: 'bg-red-100 text-red-800',
        unpaid: 'bg-red-100 text-red-800',
        paid: 'bg-green-100 text-green-800'
    }
    return colors[status] || 'bg-gray-100 text-gray-800'
}
</script>

<template>
    <div>
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button
                    v-for="tab in tabs"
                    :key="tab.id"
                    @click="activeTab = tab.id"
                    :class="[
            activeTab === tab.id
              ? 'border-indigo-500 text-indigo-600'
              : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
            'group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm'
          ]"
                >
                    <component
                        :is="tab.icon"
                        :class="[
              activeTab === tab.id ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500',
              '-ml-0.5 mr-2 h-5 w-5'
            ]"
                    />
                    {{ tab.name }}
                </button>
            </nav>
        </div>

        <!-- Work Orders Tab -->
        <div v-if="activeTab === 'work-orders'" class="mt-4 space-y-4">
            <div
                v-for="order in workOrders"
                :key="order.id"
                class="bg-white rounded-lg border p-4 hover:bg-gray-50"
            >
                <Link :href="route('workshop.orders.show', order.id)" class="group">
                    <div class="flex items-center justify-between">
                        <div>
              <span class="text-sm font-medium text-gray-900">
                {{ order.number }}
              </span>
                            <span
                                :class="[
                  getStatusColor(order.status),
                  'ml-2 px-2 py-1 text-xs rounded-full'
                ]"
                            >
                {{ order.status }}
              </span>
                        </div>
                        <ChevronRightIcon class="h-5 w-5 text-gray-400 group-hover:text-gray-500" />
                    </div>
                    <p class="mt-2 text-sm text-gray-500">
                        {{ order.customer.full_name }} - {{ order.vehicle.make }} {{ order.vehicle.model }}
                    </p>
                    <div class="mt-1 text-xs text-gray-400">
                        Created {{ new Date(order.created_at).toLocaleDateString() }}
                    </div>
                </Link>
            </div>

            <div v-if="!workOrders.length" class="text-center py-4 text-gray-500">
                No recent work orders
            </div>
        </div>

        <!-- Customers Tab -->
        <div v-if="activeTab === 'customers'" class="mt-4 space-y-4">
            <div
                v-for="customer in customers"
                :key="customer.id"
                class="bg-white rounded-lg border p-4 hover:bg-gray-50"
            >
                <Link :href="route('customers.show', customer.id)" class="group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
              <span class="text-sm font-medium text-gray-900">
                {{ customer.full_name }}
              </span>
                            <span
                                v-if="customer.is_active"
                                class="ml-2 px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full"
                            >
                Active
              </span>
                        </div>
                        <ChevronRightIcon class="h-5 w-5 text-gray-400 group-hover:text-gray-500" />
                    </div>
                    <p class="mt-2 text-sm text-gray-500">
                        {{ customer.email }}
                    </p>
                    <div class="mt-1 flex items-center text-xs text-gray-400">
                        <span>{{ customer.vehicles_count }} vehicles</span>
                        <span class="mx-2">•</span>
                        <span>Joined {{ new Date(customer.created_at).toLocaleDateString() }}</span>
                    </div>
                </Link>
            </div>

            <div v-if="!customers.length" class="text-center py-4 text-gray-500">
                No recent customers
            </div>
        </div>

        <!-- Invoices Tab -->
        <div v-if="activeTab === 'invoices'" class="mt-4 space-y-4">
            <div
                v-for="invoice in invoices"
                :key="invoice.id"
                class="bg-white rounded-lg border p-4 hover:bg-gray-50"
            >
                <Link :href="route('finance.invoices.show', invoice.id)" class="group">
                    <div class="flex items-center justify-between">
                        <div>
              <span class="text-sm font-medium text-gray-900">
                {{ invoice.number }}
              </span>
                            <span
                                :class="[
                  getStatusColor(invoice.status),
                  'ml-2 px-2 py-1 text-xs rounded-full'
                ]"
                            >
                {{ invoice.status }}
              </span>
                        </div>
                        <div class="text-sm font-medium text-gray-900">
                            € {{ invoice.total_amount.toFixed(2) }}
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">
                        {{ invoice.customer.full_name }}
                    </p>
                    <div class="mt-1 flex items-center text-xs text-gray-400">
                        <span>Due {{ new Date(invoice.due_date).toLocaleDateString() }}</span>
                        <span class="mx-2">•</span>
                        <span>Created {{ new Date(invoice.created_at).toLocaleDateString() }}</span>
                    </div>
                </Link>
            </div>

            <div v-if="!invoices.length" class="text-center py-4 text-gray-500">
                No recent invoices
            </div>
        </div>
    </div>
</template>
