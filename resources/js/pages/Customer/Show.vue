<script setup lang="ts">
import { computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { TrashIcon, ArrowLeftIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    customer: {
        type: Object,
        required: true,
    },
    can: {
        type: Object,
        default: () => ({
            edit: false,
            delete: false,
        }),
    },
});

// Access customer properties from customer.data
const customerData = computed(() => props.customer.data || {});

const fullName = computed(() => {
    if (!customerData.value) return 'Unknown Customer';
    return `${customerData.value.first_name || ''} ${customerData.value.last_name || ''}`.trim();
});

const title = computed(() => {
    return `Customer: ${fullName.value}`;
});

// Use computed for breadcrumbItems
const breadcrumbItems = computed(() => [
    {
        title: title.value,
        href: '/customers',
    },
]);

const confirmDelete = () => {
    if (!customerData.value?.id) return;

    if (confirm('Are you sure you want to delete this customer?')) {
        router.delete(route('customers.destroy', customerData.value.id));
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems" :title="title">
        <Head :title="fullName" />
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-4 flex items-center justify-between">
                    <button
                        @click="$inertia.visit(route('customers.index'))"
                        class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 shadow-sm transition duration-150 ease-in-out hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25"
                    >
                        <ArrowLeftIcon class="mr-2 h-4 w-4" />
                        Back to Customers
                    </button>

                    <div class="flex space-x-2">
                        <Link
                            v-if="can.edit && customerData?.id"
                            :href="route('customers.edit', customerData.id)"
                            class="text-green-500 hover:text-green-700"
                            title="Edit"
                        >
                            Edit
                        </Link>
                        <button
                            v-if="can.delete && customerData?.id"
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
                        <div>
                            <h3 class="mb-2 text-lg font-semibold text-gray-900">Personal Information</h3>
                            <div class="space-y-2">
                                <p><strong>Name:</strong> {{ customerData.first_name }} {{ customerData.last_name }}</p>
                                <p><strong>Email:</strong> {{ customerData.email }}</p>
                                <p><strong>Phone:</strong> {{ customerData.phone }}</p>
                                <p><strong>Mobile:</strong> {{ customerData.mobile || 'N/A' }}</p>
                                <p>
                                    <strong>Status:</strong>
                                    <span
                                        :class="[
                                            customerData.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800',
                                            'rounded-full px-2 py-1 text-xs',
                                        ]"
                                    >
                                        {{ customerData.is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div>
                            <h3 class="mb-2 text-lg font-semibold text-gray-900">Business Information</h3>
                            <div class="space-y-2">
                                <p><strong>Company:</strong> {{ customerData.company_name || 'N/A' }}</p>
                                <p><strong>VAT Number:</strong> {{ customerData.vat_number || 'N/A' }}</p>
                                <p><strong>Tax Office:</strong> {{ customerData.tax_office || 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <h3 class="mb-2 text-lg font-semibold text-gray-900">Address Information</h3>
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <strong>Billing Address:</strong>
                                    <p>{{ customerData.billing_address || 'N/A' }}</p>
                                    <p>{{ customerData.city }} {{ customerData.postal_code }}</p>
                                    <p>{{ customerData.country }}</p>
                                </div>
                                <div>
                                    <strong>Shipping Address:</strong>
                                    <p>{{ customerData.shipping_address || 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <div v-if="customerData.notes" class="md:col-span-2">
                            <h3 class="mb-2 text-lg font-semibold text-gray-900">Notes</h3>
                            <p class="text-gray-600">{{ customerData.notes }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
