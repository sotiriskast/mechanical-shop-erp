<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import {
    PencilIcon,
    TrashIcon,
    ArrowLeftIcon,
    PlusIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
    vehicle: {
        type: Object,
        required: true
    },
    serviceHistory: {
        type: Array,
        default: () => []
    },
    can: {
        type: Object,
        default: () => ({
            edit: false,
            delete: false
        })
    }
})

const title = computed(() => {
    return `Vehicle: ${props.vehicle.license_plate} - ${props.vehicle.full_name}`
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
                            v-if="can.edit && vehicle?.id"
                            :href="route('vehicles.edit', vehicle.id)"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        >
                            <PencilIcon class="h-4 w-4 mr-2"/>
                            Edit
                        </Link>
                        <button
                            v-if="can.delete && vehicle?.id"
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
                        <!-- Vehicle Information -->
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
                                            vehicle.status === 'active' ? 'bg-green-100 text-green-800' :
                                            vehicle.status === 'inactive' ? 'bg-yellow-100 text-yellow-800' :
                                            'bg-red-100 text-red-800',
                                            'px-2 py-1 rounded-full text-xs'
                                        ]"
                                    >
                                        {{ vehicle.status }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <!-- Technical Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Technical Information</h3>
                            <div class="space-y-2">
                                <p><strong>Engine Number:</strong> {{ vehicle.engine_number || 'N/A' }}</p>
                                <p><strong>Engine Type:</strong> {{ vehicle.engine_type || 'N/A' }}</p>
                                <p><strong>Transmission:</strong> {{ vehicle.transmission || 'N/A' }}</p>
                                <p><strong>Mileage:</strong> {{ vehicle.mileage }} {{ vehicle.mileage_unit }}</p>
                            </div>
                        </div>

                        <!-- Customer Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Customer Information</h3>
                            <div class="space-y-2" v-if="vehicle.customer">
                                <p><strong>Name:</strong> {{ vehicle.customer.full_name }}</p>
                                <p><strong>Company:</strong> {{ vehicle.customer.company_name || 'N/A' }}</p>
                                <p><strong>Email:</strong> {{ vehicle.customer.email || 'N/A' }}</p>
                                <p><strong>Phone:</strong> {{ vehicle.customer.phone || 'N/A' }}</p>
                                <Link
                                    :href="route('customers.show', vehicle.customer.id)"
                                    class="inline-flex items-center mt-2 text-sm text-indigo-600 hover:text-indigo-900"
                                >
                                    View Customer Details
                                </Link>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div v-if="vehicle.notes">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Notes</h3>
                            <p class="text-gray-600">{{ vehicle.notes }}</p>
                        </div>
                    </div>

                    <!-- Service History -->
                    <div class="px-6 py-4 border-t border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Service History</h3>
                            <Link
                                :href="route('vehicles.service-history.create', {id: vehicle.id} )"
                                class="inline-flex items-center px-3 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                <PlusIcon class="h-4 w-4 mr-1"/>
                                Add Service Record
                            </Link>
                        </div>

                        <div v-if="serviceHistory && serviceHistory.length > 0">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service Type</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mileage</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Technician</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="record in serviceHistory" :key="record.id">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        {{ new Date(record.service_date).toLocaleDateString() }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        {{ record.service_type }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        {{ record.mileage }} {{ record.mileage_unit }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        {{ record.technician_name || 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span
                                            :class="[
                                                record.status === 'completed' ? 'bg-green-100 text-green-800' :
                                                record.status === 'in_progress' ? 'bg-blue-100 text-blue-800' :
                                                record.status === 'scheduled' ? 'bg-yellow-100 text-yellow-800' :
                                                'bg-red-100 text-red-800',
                                                'px-2 py-1 rounded-full text-xs'
                                            ]"
                                        >
                                            {{ record.status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right flex space-x-2">
                                        <Link
                                            :href="route('vehicles.service-history.show', [vehicle.id, record.id])"
                                            class="text-blue-500 hover:text-blue-700"
                                            title="View"
                                        >
                                            <EyeIcon class="h-5 w-5" />
                                        </Link>
                                        <Link
                                            v-if="can.edit"
                                            :href="route('vehicles.service-history.edit', [vehicle.id, record.id])"
                                            class="text-green-500 hover:text-green-700"
                                            title="Edit"
                                        >
                                            <PencilIcon class="h-5 w-5" />
                                        </Link>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else class="bg-white p-4 text-center text-gray-500">
                            No service records found.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
