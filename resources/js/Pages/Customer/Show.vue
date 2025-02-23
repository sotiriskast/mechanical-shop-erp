<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import {
    PencilIcon,
    TrashIcon,
    ArrowLeftIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
    customer: {
        type: Object,
        required: true
    },
    can: {
        type: Object,
        default: () => ({
            edit: false,
            delete: false
        })
    }
})

const fullName = computed(() => {
    if (!props.customer) return 'Unknown Customer'
    return `${props.customer.first_name || ''} ${props.customer.last_name || ''}`.trim()
})

const title = computed(() => {
    return `Customer: ${fullName.value}`
})

const confirmDelete = () => {
    if (!props.customer?.id) return

    if (confirm('Are you sure you want to delete this customer?')) {
        router.delete(route('customers.destroy', props.customer.id))
    }
}
</script>

<template>
    <AppLayout :title="`Customer: ${customer.first_name} ${customer.last_name}`">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-4 flex justify-between items-center">
                    <button
                        @click="$inertia.visit(route('customers.index'))"
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                    >
                        <ArrowLeftIcon class="h-4 w-4 mr-2"/>
                        Back to Customers
                    </button>

                    <div class="flex space-x-2">
                        <Link
                            v-if="can.edit && customer?.id"
                            :href="route('customers.edit', customer.id)"
                            class="text-green-500 hover:text-green-700"
                            title="Edit"
                        >
                            Edit
                        </Link>
                        <button
                            v-if="can.delete && customer?.id"
                            @click="confirmDelete"
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        >
                            <TrashIcon class="h-4 w-4 mr-2"/>
                            Delete
                        </button>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Personal Information</h3>
                            <div class="space-y-2">
                                <p><strong>Name:</strong> {{ customer.first_name }} {{ customer.last_name }}</p>
                                <p><strong>Email:</strong> {{ customer.email }}</p>
                                <p><strong>Phone:</strong> {{ customer.phone }}</p>
                                <p><strong>Mobile:</strong> {{ customer.mobile || 'N/A' }}</p>
                                <p>
                                    <strong>Status:</strong>
                                    <span
                                        :class="[
                                            customer.is_active
                                                ? 'bg-green-100 text-green-800'
                                                : 'bg-red-100 text-red-800',
                                            'px-2 py-1 rounded-full text-xs'
                                        ]"
                                    >
                                        {{ customer.is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Business Information</h3>
                            <div class="space-y-2">
                                <p><strong>Company:</strong> {{ customer.company_name || 'N/A' }}</p>
                                <p><strong>VAT Number:</strong> {{ customer.vat_number || 'N/A' }}</p>
                                <p><strong>Tax Office:</strong> {{ customer.tax_office || 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Address Information</h3>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <strong>Billing Address:</strong>
                                    <p>{{ customer.billing_address || 'N/A' }}</p>
                                    <p>{{ customer.city }} {{ customer.postal_code }}</p>
                                    <p>{{ customer.country }}</p>
                                </div>
                                <div>
                                    <strong>Shipping Address:</strong>
                                    <p>{{ customer.shipping_address || 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <div v-if="customer.notes" class="md:col-span-2">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Notes</h3>
                            <p class="text-gray-600">{{ customer.notes }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
