<script setup>
import { ref } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import InputLabel from '@/components/ui/label/Label.vue'
import TextInput from '@/components/ui/input/Input.vue'
import PrimaryButton from '@/components/ui/button/Button.vue'
import InputError from '@/components/ui/error/InputError.vue'
import Textarea from '@/components/ui/textarea/Textarea.vue'
import { ArrowLeftIcon } from '@heroicons/vue/24/outline'
// import FileUploader from '@/components/ui/fileUploader/FileUploader.vue'

const props = defineProps({
    customers: Object
})

const form = useForm({
    license_plate: '',
    vin: '',
    make: '',
    model: '',
    year: new Date().getFullYear(),
    color: '',
    engine_number: '',
    engine_type: '',
    transmission: '',
    mileage: 0,
    mileage_unit: 'km',
    notes: '',
    status: 'active',
    customer_id: '',
    documents: []
})

const engineTypes = [
    'Gasoline',
    'Diesel',
    'Hybrid',
    'Electric',
    'Other'
]

const transmissionTypes = [
    'Manual',
    'Automatic',
    'Semi-Automatic',
    'CVT',
    'Other'
]

const submit = () => {
    form.post(route('vehicles.store'), {
        preserveScroll: true,
        forceFormData: true
    })
}
</script>

<template>
    <AppLayout title="Add Vehicle">
        <Head title="Add Vehicle" />

        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Add New Vehicle
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-4">
                    <button
                        @click="$inertia.visit(route('vehicles.index'))"
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                    >
                        <ArrowLeftIcon class="h-4 w-4 mr-2" />
                        Back to Vehicles
                    </button>
                </div>

                <form @submit.prevent="submit" class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div class="col-span-1 md:col-span-2">
                            <h2 class="text-lg font-medium text-gray-900 mb-4">Vehicle Information</h2>
                        </div>

                        <div>
                            <InputLabel for="license_plate" value="License Plate" />
                            <TextInput
                                id="license_plate"
                                v-model="form.license_plate"
                                type="text"
                                class="mt-1 block w-full"
                                required
                            />
                            <InputError :message="form.errors.license_plate" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="vin" value="VIN" />
                            <TextInput
                                id="vin"
                                v-model="form.vin"
                                type="text"
                                class="mt-1 block w-full"
                            />
                            <InputError :message="form.errors.vin" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="make" value="Make" />
                            <TextInput
                                id="make"
                                v-model="form.make"
                                type="text"
                                class="mt-1 block w-full"
                                required
                            />
                            <InputError :message="form.errors.make" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="model" value="Model" />
                            <TextInput
                                id="model"
                                v-model="form.model"
                                type="text"
                                class="mt-1 block w-full"
                                required
                            />
                            <InputError :message="form.errors.model" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="year" value="Year" />
                            <TextInput
                                id="year"
                                v-model="form.year"
                                type="number"
                                class="mt-1 block w-full"
                                required
                            />
                            <InputError :message="form.errors.year" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="color" value="Color" />
                            <TextInput
                                id="color"
                                v-model="form.color"
                                type="text"
                                class="mt-1 block w-full"
                            />
                            <InputError :message="form.errors.color" class="mt-2" />
                        </div>

                        <!-- Technical Information -->
                        <div class="col-span-1 md:col-span-2">
                            <h2 class="text-lg font-medium text-gray-900 mb-4">Technical Information</h2>
                        </div>

                        <div>
                            <InputLabel for="engine_number" value="Engine Number" />
                            <TextInput
                                id="engine_number"
                                v-model="form.engine_number"
                                type="text"
                                class="mt-1 block w-full"
                            />
                            <InputError :message="form.errors.engine_number" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="engine_type" value="Engine Type" />
                            <select
                                id="engine_type"
                                v-model="form.engine_type"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            >
                                <option value="">Select Engine Type</option>
                                <option v-for="type in engineTypes" :key="type" :value="type">
                                    {{ type }}
                                </option>
                            </select>
                            <InputError :message="form.errors.engine_type" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="transmission" value="Transmission" />
                            <select
                                id="transmission"
                                v-model="form.transmission"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            >
                                <option value="">Select Transmission</option>
                                <option v-for="type in transmissionTypes" :key="type" :value="type">
                                    {{ type }}
                                </option>
                            </select>
                            <InputError :message="form.errors.transmission" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="mileage" value="Mileage" />
                            <div class="flex">
                                <TextInput
                                    id="mileage"
                                    v-model="form.mileage"
                                    type="number"
                                    class="mt-1 block w-full rounded-r-none"
                                    min="0"
                                />
                                <select
                                    v-model="form.mileage_unit"
                                    class="mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-r-md shadow-sm border-l-0 w-20"
                                >
                                    <option value="km">km</option>
                                    <option value="mi">mi</option>
                                </select>
                            </div>
                            <InputError :message="form.errors.mileage" class="mt-2" />
                        </div>

                        <!-- Owner Information -->
                        <div class="col-span-1 md:col-span-2">
                            <h2 class="text-lg font-medium text-gray-900 mb-4">Owner Information</h2>
                        </div>

                        <div>
                            <InputLabel for="customer_id" value="Customer" />
                            <select
                                id="customer_id"
                                v-model="form.customer_id"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required
                            >
                                <option value="">Select Customer</option>
                                <option v-for="customer in customers.data" :key="customer.id" :value="customer.id">
                                    {{ customer.full_name }} {{ customer.company_name ? `(${customer.company_name})` : '' }}
                                </option>
                            </select>
                            <InputError :message="form.errors.customer_id" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="status" value="Status" />
                            <select
                                id="status"
                                v-model="form.status"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            >
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="sold">Sold</option>
                            </select>
                            <InputError :message="form.errors.status" class="mt-2" />
                        </div>

                        <!-- Additional Information -->
                        <div class="col-span-1 md:col-span-2">
                            <h2 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h2>
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <InputLabel for="notes" value="Notes" />
                            <Textarea
                                id="notes"
                                v-model="form.notes"
                                class="mt-1 block w-full"
                                rows="3"
                            />
                            <InputError :message="form.errors.notes" class="mt-2" />
                        </div>

                        <!-- Document Upload -->
                        <div class="col-span-1 md:col-span-2">
                            <InputLabel for="documents" value="Documents" />
<!--                            <FileUploader-->
<!--                                v-model="form.documents"-->
<!--                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"-->
<!--                            />-->
                            <InputError :message="form.errors.documents" class="mt-2" />
                        </div>

                        <!-- Submit Button -->
                        <div class="col-span-1 md:col-span-2 mt-6">
                            <PrimaryButton
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                Add Vehicle
                            </PrimaryButton>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
