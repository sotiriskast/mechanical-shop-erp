<script setup lang="ts">
import { computed, reactive, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { PencilIcon, TrashIcon, ArrowLeftIcon, PlusIcon, EyeIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    vehicle: {
        type: Object,
        required: true,
    },
    serviceHistory: {
        type: Array,
        default: () => [],
    },
    can: {
        type: Object,
        default: () => ({
            edit: false,
            delete: false,
        }),
    },
});
const vehicle = reactive({ ...props.vehicle.data });

watch(
    () => props.vehicle,
    (newVehicle) => {
        if (newVehicle) {
            Object.assign(vehicle, newVehicle);
        }
    },
    { immediate: true, deep: true },
);
const title = computed(() => {
    return `Vehicle: ${props.vehicle.license_plate} - ${props.vehicle.full_name}`;
});
const goBackToVehicles = () => {
    router.visit(route('vehicles.index'))
}
const confirmDelete = () => {
    if (!props.vehicle?.id) return;

    if (confirm('Are you sure you want to delete this vehicle?')) {
        router.delete(route('vehicles.destroy', props.vehicle.id));
    }
};
</script>

<template>
    <AppLayout :title="title">
        <Head title="Vehicles" />
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-4 flex items-center justify-between">
                    <button
                        @click="goBackToVehicles"
                        class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 shadow-sm transition duration-150 ease-in-out hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25"
                    >
                        <ArrowLeftIcon class="mr-2 h-4 w-4" />
                        Back to Vehicles
                    </button>

                    <div class="flex space-x-2">
                        <!-- Add New Vehicle Button -->
                        <Link
                            v-if="can.edit"
                            :href="route('vehicles.create')"
                            class="inline-flex items-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                        >
                            <PlusIcon class="mr-2 h-4 w-4" />
                            New Vehicle
                        </Link>

                        <!-- Edit Button -->
                        <Link
                            v-if="can.edit && vehicle?.id"
                            :href="route('vehicles.edit', vehicle.id)"
                            class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            <PencilIcon class="mr-2 h-4 w-4" />
                            Edit
                        </Link>

                        <!-- Delete Button -->
                        <button
                            v-if="can.delete && vehicle?.id"
                            @click="confirmDelete"
                            class="inline-flex items-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-red-700 focus:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 active:bg-red-900"
                        >
                            <TrashIcon class="mr-2 h-4 w-4" />
                            Delete
                        </button>
                    </div>
                </div>

                <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                    <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-2">
                        <!-- Vehicle Information -->
                        <div>
                            <h3 class="mb-2 text-lg font-semibold text-gray-900">Vehicle Information</h3>
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
                                            vehicle.status === 'active'
                                                ? 'bg-green-100 text-green-800'
                                                : vehicle.status === 'inactive'
                                                  ? 'bg-yellow-100 text-yellow-800'
                                                  : 'bg-red-100 text-red-800',
                                            'rounded-full px-2 py-1 text-xs',
                                        ]"
                                    >
                                        {{ vehicle.status }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <!-- Technical Information -->
                        <div>
                            <h3 class="mb-2 text-lg font-semibold text-gray-900">Technical Information</h3>
                            <div class="space-y-2">
                                <p><strong>Engine Number:</strong> {{ vehicle.engine_number || 'N/A' }}</p>
                                <p><strong>Engine Type:</strong> {{ vehicle.engine_type || 'N/A' }}</p>
                                <p><strong>Transmission:</strong> {{ vehicle.transmission || 'N/A' }}</p>
                                <p><strong>Mileage:</strong> {{ vehicle.mileage }} {{ vehicle.mileage_unit }}</p>
                            </div>
                        </div>

                        <!-- Customer Information -->
                        <div>
                            <h3 class="mb-2 text-lg font-semibold text-gray-900">Customer Information</h3>
                            <div class="space-y-2" v-if="vehicle.customer">
                                <p><strong>Name:</strong> {{ vehicle.customer.full_name }}</p>
                                <p><strong>Company:</strong> {{ vehicle.customer.company_name || 'N/A' }}</p>
                                <p><strong>Email:</strong> {{ vehicle.customer.email || 'N/A' }}</p>
                                <p><strong>Phone:</strong> {{ vehicle.customer.phone || 'N/A' }}</p>
                                <Link
                                    :href="route('customers.show', vehicle.customer.id)"
                                    class="mt-2 inline-flex items-center text-sm text-indigo-600 hover:text-indigo-900"
                                >
                                    View Customer Details
                                </Link>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div v-if="vehicle.notes">
                            <h3 class="mb-2 text-lg font-semibold text-gray-900">Notes</h3>
                            <p class="text-gray-600">{{ vehicle.notes }}</p>
                        </div>
                    </div>

                    <!-- Service History -->
                    <div class="border-t border-gray-200 px-6 py-4">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Service History</h3>
                            <Link
                                :href="route('vehicles.service-history.create', vehicle.id)"
                                class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-3 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                <PlusIcon class="mr-1 h-4 w-4" />
                                Add Service Record
                            </Link>
                        </div>

                        <div v-if="serviceHistory && serviceHistory.length > 0">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Service Type</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Mileage</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Technician</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="record in serviceHistory" :key="record.id">
                                        <td class="whitespace-nowrap px-4 py-3">
                                            {{ new Date(record.service_date).toLocaleDateString() }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3">
                                            {{ record.service_type }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3">{{ record.mileage }} {{ record.mileage_unit }}</td>
                                        <td class="whitespace-nowrap px-4 py-3">
                                            {{ record.technician_name || 'N/A' }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3">
                                            <span
                                                :class="[
                                                    record.status === 'completed'
                                                        ? 'bg-green-100 text-green-800'
                                                        : record.status === 'in_progress'
                                                          ? 'bg-blue-100 text-blue-800'
                                                          : record.status === 'scheduled'
                                                            ? 'bg-yellow-100 text-yellow-800'
                                                            : 'bg-red-100 text-red-800',
                                                    'rounded-full px-2 py-1 text-xs',
                                                ]"
                                            >
                                                {{ record.status }}
                                            </span>
                                        </td>
                                        <td class="flex space-x-2 whitespace-nowrap px-4 py-3 text-right">
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
                                            <button
                                                v-if="can.delete"
                                                @click="confirmDelete(record.id)"
                                                class="text-red-500 hover:text-red-700"
                                                title="Delete"
                                            >
                                                <TrashIcon class="h-5 w-5" />
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else class="bg-white p-4 text-center text-gray-500">No service records found.</div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
