<script setup lang="ts">
import { ref, watch } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import debounce from 'lodash/debounce'
import AppLayout from '@/layouts/AppLayout.vue'
import SearchInput from '@/components/ui/searchInput/SearchInput.vue'
import Pagination from '@/components/ui/pagination/Pagination.vue'
import {
    TrashIcon,
    PencilIcon,
    EyeIcon,
    PlusIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
    vehicles: Object,
    filters: Object,
    can: Object
})

const search = ref(props.filters.search)

// Debounced search
watch(search, debounce((value) => {
    router.get(route('vehicles.index'), {
        search: value
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    });
}, 300));

const confirmDelete = (vehicleId) => {
    if (confirm('Are you sure you want to delete this vehicle?')) {
        router.delete(route('vehicles.destroy', vehicleId))
    }
}
</script>

<template>
    <AppLayout title="Vehicles">
        <Head title="Vehicles" />

        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Vehicles
                </h2>
                <Link
                    v-if="can.create"
                    :href="route('vehicles.create')"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    <PlusIcon class="h-4 w-4 mr-2" />
                    Add Vehicle
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="mb-6">
                            <SearchInput
                                v-model="search"
                                placeholder="Search by make, model, license plate..."
                            />
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        License Plate
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Vehicle
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Customer
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
                                <tr v-for="vehicle in vehicles.data" :key="vehicle.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ vehicle.license_plate }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ vehicle.full_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ vehicle.customer?.full_name || '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            :class="[
                                                vehicle.status === 'active' ? 'bg-green-100 text-green-800' :
                                                vehicle.status === 'inactive' ? 'bg-yellow-100 text-yellow-800' :
                                                'bg-red-100 text-red-800',
                                                'px-2 py-1 text-xs rounded-full'
                                            ]"
                                        >
                                            {{ vehicle.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right flex justify-end space-x-2">
                                        <Link
                                            :href="route('vehicles.show', vehicle.id)"
                                            class="text-blue-500 hover:text-blue-700"
                                            title="View"
                                        >
                                            <EyeIcon class="h-5 w-5" />
                                        </Link>
                                        <Link
                                            v-if="can.edit"
                                            :href="route('vehicles.edit', vehicle.id)"
                                            class="text-green-500 hover:text-green-700"
                                            title="Edit"
                                        >
                                            <PencilIcon class="h-5 w-5" />
                                        </Link>
                                        <button
                                            v-if="can.delete"
                                            @click="confirmDelete(vehicle.id)"
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

                        <div class="mt-4">
                            <Pagination :links="vehicles.links" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
