<script setup>
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import InputError from '@/Components/InputError.vue'

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
    is_active: true
})

const submit = () => {
    form.post(route('customers.store'), {
        onFinish: () => form.reset()
    })
}
</script>

<template>
    <AppLayout title="Create Customer">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="grid grid-cols-2 gap-6">
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

                        <!-- Add more fields similarly -->
                        <div class="col-span-2">
                            <PrimaryButton
                                :disabled="form.processing"
                                type="submit"
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
