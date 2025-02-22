<script setup>
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import InputError from '@/Components/InputError.vue'
import { ArrowLeftIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    customer: Object
})

const form = useForm({
    first_name: props.customer.first_name,
    last_name: props.customer.last_name,
    email: props.customer.email,
    phone: props.customer.phone,
    mobile: props.customer.mobile,
    company_name: props.customer.company_name,
    vat_number: props.customer.vat_number,
    tax_office: props.customer.tax_office,
    billing_address: props.customer.billing_address,
    shipping_address: props.customer.shipping_address,
    city: props.customer.city,
    postal_code: props.customer.postal_code,
    country: props.customer.country || 'Cyprus',
    notes: props.customer.notes,
    is_active: props.customer.is_active
})

const submit = () => {
    form.put(route('customers.update', props.customer.id), {
        preserveScroll: true
    })
}
</script>

<template>
    <AppLayout title="Edit Customer">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-4">
                    <button
                        @click="$inertia.visit(route('customers.index'))"
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                    >
                        <ArrowLeftIcon class="h-4 w-4 mr-2" />
                        Back to Customers
                    </button>
                </div>

                <form @submit.prevent="submit" class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <InputLabel for="first_name" value="First Name" />
                            <TextInput
                                id="first_name"
                                v-model="form.first_name"
                                type="text"
                                class="mt-1 block w-full"
                                required
                            />
                            <InputError :message="form.errors.first_name" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="last_name" value="Last Name" />
                            <TextInput
                                id="last_name"
                                v-model="form.last_name"
                                type="text"
                                class="mt-1 block w-full"
                                required
                            />
                            <InputError :message="form.errors.last_name" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <InputLabel for="email" value="Email" />
                            <TextInput
                                id="email"
                                v-model="form.email"
                                type="email"
                                class="mt-1 block w-full"
                                required
                            />
                            <InputError :message="form.errors.email" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="phone" value="Phone" />
                            <TextInput
                                id="phone"
                                v-model="form.phone"
                                type="tel"
                                class="mt-1 block w-full"
                            />
                            <InputError :message="form.errors.phone" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="mobile" value="Mobile" />
                            <TextInput
                                id="mobile"
                                v-model="form.mobile"
                                type="tel"
                                class="mt-1 block w-full"
                            />
                            <InputError :message="form.errors.mobile" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <InputLabel for="notes" value="Notes" />
                            <textarea
                                id="notes"
                                v-model="form.notes"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                rows="4"
                            ></textarea>
                            <InputError :message="form.errors.notes" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    v-model="form.is_active"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                />
                                <span class="ml-2 text-sm text-gray-600">Active Customer</span>
                            </label>
                        </div>

                        <div class="md:col-span-2">
                            <PrimaryButton
                                :disabled="form.processing"
                                type="submit"
                            >
                                Update Customer
                            </PrimaryButton>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
