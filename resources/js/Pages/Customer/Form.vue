<script setup>
import {ref} from 'vue'
import {useForm} from '@inertiajs/vue3'
import FormInput from '@/Components/Forms/FormInput.vue'
import FormSelect from '@/Components/Forms/FormSelect.vue'
import FormTextarea from '@/Components/Forms/FormTextarea.vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

const props = defineProps({
    customer: {
        type: Object,
        default: () => ({})
    },
    isEdit: {
        type: Boolean,
        default: false
    }
})

const form = useForm({
    first_name: props.customer?.first_name ?? '',
    last_name: props.customer?.last_name ?? '',
    email: props.customer?.email ?? '',
    phone: props.customer?.phone ?? '',
    mobile: props.customer?.mobile ?? '',
    company_name: props.customer?.company_name ?? '',
    vat_number: props.customer?.vat_number ?? '',
    tax_office: props.customer?.tax_office ?? '',
    billing_address: props.customer?.billing_address ?? '',
    shipping_address: props.customer?.shipping_address ?? '',
    city: props.customer?.city ?? '',
    postal_code: props.customer?.postal_code ?? '',
    country: props.customer?.country ?? 'Cyprus',
    notes: props.customer?.notes ?? '',
    is_active: props.customer?.is_active ?? true,
    documents: []
})

const documentInput = ref(null)

const submit = () => {
    if (props.isEdit) {
        form.put(route('customers.update', props.customer.id), {
            onSuccess: () => {
                // Clear uploaded files
                if (documentInput.value) {
                    documentInput.value.value = ''
                }
            }
        })
    } else {
        form.post(route('customers.store'), {
            onSuccess: () => {
                // Clear uploaded files
                if (documentInput.value) {
                    documentInput.value.value = ''
                }
            }
        })
    }
}

const handleFileUpload = (e) => {
    form.documents = [...e.target.files]
}

const statusOptions = [
    {value: true, label: 'Active'},
    {value: false, label: 'Inactive'}
]
</script>

<template>
    <DashboardLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ isEdit ? 'Edit Customer' : 'Create Customer' }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Personal Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Personal Information</h3>
                            <div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                <FormInput
                                    v-model="form.first_name"
                                    label="First Name"
                                    required
                                    :error="form.errors.first_name"
                                />

                                <FormInput
                                    v-model="form.last_name"
                                    label="Last Name"
                                    required
                                    :error="form.errors.last_name"
                                />

                                <FormInput
                                    v-model="form.email"
                                    type="email"
                                    label="Email"
                                    required
                                    :error="form.errors.email"
                                />

                                <FormInput
                                    v-model="form.phone"
                                    label="Phone"
                                    help="Cyprus format: +35712345678"
                                    :error="form.errors.phone"
                                />

                                <FormInput
                                    v-model="form.mobile"
                                    label="Mobile"
                                    help="Cyprus format: +35712345678"
                                    :error="form.errors.mobile"
                                />
                            </div>
                        </div>

                        <!-- Business Information -->
                        <div class="pt-6">
                            <h3 class="text-lg font-medium text-gray-900">Business Information</h3>
                            <div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                <FormInput
                                    v-model="form.company_name"
                                    label="Company Name"
                                    :error="form.errors.company_name"
                                />

                                <FormInput
                                    v-model="form.vat_number"
                                    label="VAT Number"
                                    help="Cyprus format: CY12345678X"
                                    :error="form.errors.vat_number"
                                />

                                <FormInput
                                    v-model="form.tax_office"
                                    label="Tax Office"
                                    :error="form.errors.tax_office"
                                />
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="pt-6">
                            <h3 class="text-lg font-medium text-gray-900">Address Information</h3>
                            <div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                <div class="sm:col-span-2">
                                    <FormTextarea
                                        v-model="form.billing_address"
                                        label="Billing Address"
                                        :error="form.errors.billing_address"
                                    />
                                </div>

                                <div class="sm:col-span-2">
                                    <FormTextarea
                                        v-model="form.shipping_address"
                                        label="Shipping Address"
                                        :error="form.errors.shipping_address"
                                    />
                                </div>

                                <FormInput
                                    v-model="form.city"
                                    label="City"
                                    :error="form.errors.city"
                                />

                                <FormInput
                                    v-model="form.postal_code"
                                    label="Postal Code"
                                    help="Cyprus format: 4 digits"
                                    :error="form.errors.postal_code"
                                />

                                <FormInput
                                    v-model="form.country"
                                    label="Country"
                                    :error="form.errors.country"
                                />
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="pt-6">
                            <h3 class="text-lg font-medium text-gray-900">Additional Information</h3>
                            <div class="mt-4 grid grid-cols-1 gap-y-6">
                                <FormSelect
                                    v-model="form.is_active"
                                    label="Status"
                                    :options="statusOptions"
                                    :error="form.errors.is_active"
                                />

                                <FormTextarea
                                    v-model="form.notes"
                                    label="Notes"
                                    :error="form.errors.notes"
                                />

                                <!-- Document Upload -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        Documents
                                    </label>
                                    <div class="mt-1">
                                        <input
                                            ref="documentInput"
                                            type="file"
                                            multiple
                                            @change="handleFileUpload"
                                            class="block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-indigo-50 file:text-indigo-700
                        hover:file:bg-indigo-100"
                                        />
                                    </div>
                                    <p v-if="form.errors.documents" class="mt-2 text-sm text-red-600">
                                        {{ form.errors.documents }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-3 pt-6">
                            <Link
                                :href="route('customers.index')"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Cancel
                            </Link>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                {{ isEdit ? 'Update' : 'Create' }} Customer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
