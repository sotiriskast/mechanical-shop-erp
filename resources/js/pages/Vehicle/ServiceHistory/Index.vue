<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import Pagination from '@/components/ui/pagination/Pagination.vue'
import {
    TrashIcon,
    PencilIcon,
    EyeIcon,
    PlusIcon,
    ArrowLeftIcon
} from '@heroicons/vue/24/outline'
import { reactive, watch } from 'vue';

const props = defineProps({
    vehicle: Object,
    serviceHistory: Object,
    filters: Object,
    can: Object
})

const confirmDelete = (serviceHistoryId) => {
    if (confirm('Are you sure you want to delete this service record?')) {
        router.delete(route('vehicles.service-history.destroy', [props.vehicle.id, serviceHistoryId]))
    }
}
const vehicle = reactive({ ...props.vehicle.data });

watch(() => props.vehicle, (newVehicle) => {
    if (newVehicle) {
        Object.assign(vehicle, newVehicle);
    }
}, { immediate: true, deep: true });
const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString();
}

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-CY', {
        style: 'currency',
        currency: 'EUR'
    }).format(amount);
}
</script>

<template>
    <AppLayout :title="`Service History - ${vehicle.make} ${vehicle.model} (${vehicle.license_plate})`">
        <Head :title="`Service History - ${vehicle.make} ${vehicle.model}`" />

        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Service History - {{ vehicle.make }} {{ vehicle.model }} ({{ vehicle.license_plate }})
                </h2>
                <Link
                    v-if="can.create"
                    :href="route('vehicles.service-history.create', vehicle.id)"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    <PlusIcon class="h-4 w-4 mr-2" />
                    Add Service Record
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-4">
                    <button
                        @click="$inertia.visit(route('vehicles.show', vehicle.id))"
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                    >
                        <ArrowLeftIcon class="h-4 w-4 mr-2"/>
                        Back to Vehicle
                    </button>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Service Type
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Mileage
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cost
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="record in serviceHistory.data" :key="record.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ formatDate(record.service_date) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ record.service_type }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ record.mileage }} {{ record.mileage_unit }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ formatCurrency(record.cost) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                            :class="{
                                                'bg-green-100 text-green-800': record.status === 'completed',
                                                'bg-blue-100 text-blue-800': record.status === 'in_progress',
                                                'bg-yellow-100 text-yellow-800': record.status === 'scheduled',
                                                'bg-red-100 text-red-800': record.status === 'cancelled'
                                            }"
                                        >
                                            {{ record.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right flex justify-end space-x-2">
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
                                <tr v-if="serviceHistory.data.length === 0">
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No service records found
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            <Pagination :links="serviceHistory.links" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
