<script setup>
import { computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import {
    PencilIcon,
    TrashIcon,
    ArrowLeftIcon,
    ClockIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
    vehicle: {
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
    if (!props.vehicle) return 'Unknown Vehicle'
    return `${props.vehicle.make} ${props.vehicle.model} (${props.vehicle.year})`
})

const title = computed(() => {
    return `Vehicle: ${fullName.value}`
})

const confirmDelete = () => {
    if (!props.vehicle?.id) return

    if (confirm('Are you sure you want to delete this vehicle?')) {
        router.delete(route('vehicles.destroy', props.vehicle.id))
    }
}
</script>

<template>
    <AppLayout :title="title">
        <Head :title="title" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-4 flex justify-between items-center">
                    <button
                        @click="$inertia.visit(route('vehicles.index'))"
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                    >
                        <ArrowLeftIcon class="h-4 w-4 mr-2"/>
                        Back to Vehicles
                    </button>

                    <div class="flex space-x-2">
                        <Link
                            :href="route('vehicles.service-history.index', vehicle.id)"
                            class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        >
                            <ClockIcon class="h-4 w-4 mr-2"/>
                            Service History
                        </Link>
                        <Link
                            v-if="can.edit"
                            :href="route('vehicles.edit', vehicle.id)"
                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        >
                            <PencilIcon class="h-4 w-4 mr-2"/>
                            Edit
                        </Link>
                        <button
                            v-if="can.delete"
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
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Vehicle Information</h3>
                            <div class="space-y-2">
                                <p><strong>License Plate:</strong> {{ vehicle.license_plate }}</p>
                                <p><strong>Make:</strong> {{ vehicle.make }}</p>
                                <p><strong>Model:</strong> {{ vehicle.model }}</p>
                                <p><strong>Year:</strong> {{ vehicle.year }}</p>
                                <p><strong>Color:</strong> {{ vehicle.color || 'N/A' }}</p>
                                <p><strong>VIN:</strong> {{ vehicle.vin || 'N/A' }}</p>
                                <p>
                                    <strong>Status:</strong>
                                    <span
                                        :class="[
                                            'px-2 py-1 rounded-full text-xs',
                                            vehicle.status === 'active' ? 'bg-green-100 text-green-800' :
                                            vehicle.status === 'inactive' ? 'bg-yellow-100 text-yellow-800' :
                                            'bg-red-100 text-red-800'
                                        ]"
                                    >
                                        {{ vehicle.status }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Technical Information</h3>
                            <div class="space-y-2">
                                <p><strong>Engine Number:</strong> {{ vehicle.engine_number || 'N/A' }}</p>
                                <p><strong>Engine Type:</strong> {{ vehicle.engine_type || 'N/A' }}</p>
                                <p><strong>Transmission:</strong> {{ vehicle.transmission || 'N/A' }}</p>
                                <p><strong>Mileage:</strong> {{ vehicle.mileage }} {{ vehicle.mileage_unit }}</p>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Owner Information</h3>
                            <div class="space-y-2" v-if="vehicle.customer">
                                <p><strong>Customer:</strong> {{ vehicle.customer.full_name }}</p>
                                <p><strong>Company:</strong> {{ vehicle.customer.company_name || 'N/A' }}</p>
                                <p><strong>Email:</strong> {{ vehicle.customer.email || 'N/A' }}</p>
                                <p><strong>Phone:</strong> {{ vehicle.customer.phone || 'N/A' }}</p>
                                <Link
                                    :href="route('customers.show', vehicle.customer_id)"
                                    class="text-indigo-600 hover:text-indigo-900"
                                >
                                    View Customer
                                </Link>
                            </div>
                            <p v-else class="text-gray-500">No customer associated</p>
                        </div>

                        <div v-if="vehicle.notes">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Notes</h3>
                            <p class="text-gray-600">{{ vehicle.notes }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
