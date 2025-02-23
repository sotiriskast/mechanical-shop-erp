<script setup>
import { ref, watch } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import debounce from 'lodash/debounce'
import AppLayout from '@/Layouts/AppLayout.vue'
import SearchInput from '@/Components/SearchInput.vue'
import Pagination from '@/Components/Pagination.vue'
import {
    TrashIcon,
    PencilIcon,
    EyeIcon,
    PlusIcon
} from '@heroicons/vue/24/outline'
const columns = [
    { key: 'full_name', label: 'Name', sortable: true },
    { key: 'company_name', label: 'Company', sortable: true },
    { key: 'vat_number', label: 'VAT Number', sortable: true },
    { key: 'phone', label: 'Phone', sortable: false },
    { key: 'mobile', label: 'Mobile', sortable: false },
    { key: 'actions', label: 'Actions', sortable: false }
];
const props = defineProps({
    customers: Object,
    filters: Object,
    can: Object
})

const search = ref(props.filters.search)

// Debounced search
watch(search, debounce((value) => {
    router.get(route('customers.index'), {
        search: value
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    });
}, 300));

const confirmDelete = (customerId) => {
    if (confirm('Are you sure you want to delete this customer?')) {
        router.delete(route('customers.destroy', customerId))
    }
}
</script>

<template>
    <AppLayout title="Customers">
        <Head title="Customers" />

        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Customers
                </h2>
                <Link
                    v-if="can.create"
                    :href="route('customers.create')"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    <PlusIcon class="h-4 w-4 mr-2" />
                    Add Customer
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
                                placeholder="Search by name, company, VAT number..."
                            />
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th v-for="column in columns" :key="column.key"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        {{ column.label }}
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="customer in customers.data" :key="customer.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ customer.full_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ customer.company_name || '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ customer.vat_number || '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ customer.phone || '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ customer.mobile || '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right flex justify-end space-x-2">
                                        <Link
                                            :href="route('customers.show', customer.id)"
                                            class="text-blue-500 hover:text-blue-700"
                                            title="View"
                                        >
                                            <EyeIcon class="h-5 w-5" />
                                        </Link>
                                        <Link
                                            v-if="can.edit"
                                            :href="route('customers.edit', customer.id)"
                                            class="text-green-500 hover:text-green-700"
                                            title="Edit"
                                        >
                                            <PencilIcon class="h-5 w-5" />
                                        </Link>
                                        <button
                                            v-if="can.delete"
                                            @click="confirmDelete(customer.id)"
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
                            <Pagination :links="customers.links" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
