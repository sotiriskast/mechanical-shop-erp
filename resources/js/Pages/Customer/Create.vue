<script setup>
import {useForm} from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import InputError from '@/Components/InputError.vue'
import Textarea from '@/Components/Textarea.vue'
import Checkbox from "@/Components/Checkbox.vue";

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    mobile: '',
    company_name: '',
    vat_number: '',
    tax_office: '',
    billing_address: '',
    shipping_address: '',
    city: '',
    postal_code: '',
    country: 'Cyprus',
    notes: '',
    is_active: true,
})

const submit = () => {
    form.post(route('customers.store'), {
        onSuccess: () => {
            // Only clear form on success
            form.reset()
        },
        preserveScroll: true // Keeps the scroll position
    })
}
</script>

<template>
    <AppLayout title="Create Customer">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="grid grid-cols-2 gap-6">
                        <!-- Personal Information -->
                        <div class="col-span-2">
                            <h2 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h2>
                        </div>

                        <div>
                            <InputLabel for="first_name" value="First Name"/>
                            <TextInput
                                id="first_name"
                                v-model="form.first_name"
                                type="text"
                                class="mt-1 block w-full"
                                required
                            />
                            <InputError :message="form.errors.first_name" class="mt-2"/>
                        </div>

                        <div>
                            <InputLabel for="last_name" value="Last Name"/>
                            <TextInput
                                id="last_name"
                                v-model="form.last_name"
                                type="text"
                                class="mt-1 block w-full"
                                required
                            />
                            <InputError :message="form.errors.last_name" class="mt-2"/>
                        </div>

                        <div>
                            <InputLabel for="email" value="Email"/>
                            <TextInput
                                id="email"
                                v-model="form.email"
                                type="email"
                                class="mt-1 block w-full"

                            />
                            <InputError :message="form.errors.email" class="mt-2"/>
                        </div>

                        <div>
                            <InputLabel for="phone" value="Phone"/>
                            <TextInput
                                id="phone"
                                v-model="form.phone"
                                type="tel"
                                class="mt-1 block w-full"
                                placeholder="+35712345678"
                                required
                            />
                            <InputError :message="form.errors.phone" class="mt-2"/>
                        </div>

                        <div>
                            <InputLabel for="mobile" value="Mobile"/>
                            <TextInput
                                id="mobile"
                                v-model="form.mobile"
                                type="tel"
                                class="mt-1 block w-full"
                                placeholder="+35712345678"
                            />
                            <InputError :message="form.errors.mobile" class="mt-2"/>
                        </div>

                        <!-- Business Information -->
                        <div class="col-span-2">
                            <h2 class="text-lg font-medium text-gray-900 mb-4">Business Information</h2>
                        </div>

                        <div>
                            <InputLabel for="company_name" value="Company Name"/>
                            <TextInput
                                id="company_name"
                                v-model="form.company_name"
                                type="text"
                                class="mt-1 block w-full"
                            />
                            <InputError :message="form.errors.company_name" class="mt-2"/>
                        </div>

                        <div>
                            <InputLabel for="vat_number" value="VAT Number"/>
                            <TextInput
                                id="vat_number"
                                v-model="form.vat_number"
                                type="text"
                                class="mt-1 block w-full"
                                placeholder="CY123456789X"
                            />
                            <InputError :message="form.errors.vat_number" class="mt-2"/>
                        </div>

                        <div>
                            <InputLabel for="tax_office" value="Tax Office"/>
                            <TextInput
                                id="tax_office"
                                v-model="form.tax_office"
                                type="text"
                                class="mt-1 block w-full"
                            />
                            <InputError :message="form.errors.tax_office" class="mt-2"/>
                        </div>

                        <!-- Address Information -->
                        <div class="col-span-2">
                            <h2 class="text-lg font-medium text-gray-900 mb-4">Address Information</h2>
                        </div>

                        <div class="col-span-2">
                            <InputLabel for="billing_address" value="Billing Address"/>
                            <Textarea
                                id="billing_address"
                                v-model="form.billing_address"
                                class="mt-1 block w-full"
                                rows="3"
                            />
                            <InputError :message="form.errors.billing_address" class="mt-2"/>
                        </div>

                        <div class="col-span-2">
                            <InputLabel for="shipping_address" value="Shipping Address"/>
                            <Textarea
                                id="shipping_address"
                                v-model="form.shipping_address"
                                class="mt-1 block w-full"
                                rows="3"
                            />
                            <InputError :message="form.errors.shipping_address" class="mt-2"/>
                        </div>

                        <div>
                            <InputLabel for="city" value="City"/>
                            <TextInput
                                id="city"
                                v-model="form.city"
                                type="text"
                                class="mt-1 block w-full"
                            />
                            <InputError :message="form.errors.city" class="mt-2"/>
                        </div>

                        <div>
                            <InputLabel for="postal_code" value="Postal Code"/>
                            <TextInput
                                id="postal_code"
                                v-model="form.postal_code"
                                type="text"
                                class="mt-1 block w-full"
                                placeholder="1234"
                            />
                            <InputError :message="form.errors.postal_code" class="mt-2"/>
                        </div>

                        <div>
                            <InputLabel for="country" value="Country"/>
                            <TextInput
                                id="country"
                                v-model="form.country"
                                type="text"
                                class="mt-1 block w-full"
                            />
                            <InputError :message="form.errors.country" class="mt-2"/>
                        </div>

                        <!-- Additional Information -->
                        <div class="col-span-2">
                            <h2 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h2>
                        </div>

                        <div class="col-span-2">
                            <InputLabel for="notes" value="Notes"/>
                            <Textarea
                                id="notes"
                                v-model="form.notes"
                                class="mt-1 block w-full"
                                rows="3"
                            />
                            <InputError :message="form.errors.notes" class="mt-2"/>
                        </div>

                        <div class="col-span-2">
                            <label class="flex items-center">
                                <Checkbox v-model:checked="form.is_active"/>
                                <span class="ml-2 text-sm text-gray-600">Active Customer</span>
                            </label>
                            <InputError :message="form.errors.is_active" class="mt-2"/>
                        </div>


                        <!-- Submit Button -->
                        <div class="col-span-2 mt-6">
                            <PrimaryButton
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                Create Customer
                            </PrimaryButton>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
