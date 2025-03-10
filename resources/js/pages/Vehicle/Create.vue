<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import InputLabel from '@/components/ui/label/Label.vue'
import TextInput from '@/components/ui/input/Input.vue'
import PrimaryButton from '@/components/ui/button/Button.vue'
import InputError from '@/components/ui/error/InputError.vue'
import Textarea from '@/components/ui/textarea/Textarea.vue'
import { ArrowLeftIcon, DocumentTextIcon } from '@heroicons/vue/24/outline'
import { ref, computed, onMounted, onUnmounted } from 'vue'

const props = defineProps({
    customers: {
        type: Array,
        required: true
    }
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

const fileCount = ref(0)
const isSubmitting = ref(false)

// Custom dropdowns state management
const customerSearch = ref('')
const showCustomerDropdown = ref(false)
const selectedCustomer = ref<Customer | null>(null)

const showStatusDropdown = ref(false)
const statusOptions = [
    { value: 'active', color: 'bg-green-400' },
    { value: 'inactive', color: 'bg-yellow-400' },
    { value: 'sold', color: 'bg-blue-400' }
]

const showTransmissionDropdown = ref(false)
const transmissionOptions = ['Manual', 'Automatic', 'Semi-Automatic', 'CVT']

// Filter customers based on search input
const filteredCustomers = computed(() => {
    if (!props.customers || !Array.isArray(props.customers)) {
        return []
    }

    if (!customerSearch.value) {
        return props.customers
    }

    const searchLower = customerSearch.value.toLowerCase()
    return props.customers.filter(customer => {
        const fullName = customer.full_name ? customer.full_name.toLowerCase() : ''
        const companyName = customer.company_name ? customer.company_name.toLowerCase() : ''
        const email = customer.email ? customer.email.toLowerCase() : ''

        return fullName.includes(searchLower) ||
            companyName.includes(searchLower) ||
            email.includes(searchLower)
    })
})

// Customer dropdown functions
const toggleCustomerDropdown = () => {
    showCustomerDropdown.value = !showCustomerDropdown.value;
    if (showCustomerDropdown.value) {
        showStatusDropdown.value = false;
        showTransmissionDropdown.value = false;
    }
}

// Customer selection functions
const selectCustomer = (customer: Customer) => {
    selectedCustomer.value = customer;
    form.customer_id = customer.id.toString();
    showCustomerDropdown.value = false;
}

// Status dropdown functions
const toggleStatusDropdown = () => {
    showStatusDropdown.value = !showStatusDropdown.value;
    if (showStatusDropdown.value) {
        showCustomerDropdown.value = false;
        showTransmissionDropdown.value = false;
    }
}

const selectStatus = (status: string) => {
    form.status = status;
    showStatusDropdown.value = false;
}

// Transmission dropdown functions
const toggleTransmissionDropdown = () => {
    showTransmissionDropdown.value = !showTransmissionDropdown.value;
    if (showTransmissionDropdown.value) {
        showCustomerDropdown.value = false;
        showStatusDropdown.value = false;
    }
}

const selectTransmission = (option: string) => {
    form.transmission = option;
    showTransmissionDropdown.value = false;
}

// Close dropdowns when clicking outside
const closeDropdowns = (event: MouseEvent) => {
    const target = event.target as HTMLElement;
    const isClickInsideCustomerDropdown = target.closest('#customer-button') || target.closest('#customer_search');
    const isClickInsideStatusDropdown = target.closest('#status-button');
    const isClickInsideTransmissionDropdown = target.closest('#transmission-button');

    if (!isClickInsideCustomerDropdown && showCustomerDropdown.value) {
        showCustomerDropdown.value = false;
    }

    if (!isClickInsideStatusDropdown && showStatusDropdown.value) {
        showStatusDropdown.value = false;
    }

    if (!isClickInsideTransmissionDropdown && showTransmissionDropdown.value) {
        showTransmissionDropdown.value = false;
    }
}

// Set up event listeners
onMounted(() => {
    document.addEventListener('mousedown', closeDropdowns);

    // Set selected customer if form has customer_id already
    if (form.customer_id && props.customers && Array.isArray(props.customers)) {
        const customer = props.customers.find(c => c.id.toString() === form.customer_id.toString());
        if (customer) {
            selectedCustomer.value = customer;
        }
    }
});

onUnmounted(() => {
    document.removeEventListener('mousedown', closeDropdowns);
});

const handleFileInput = (e) => {
    form.documents = e.target.files
    fileCount.value = e.target.files ? e.target.files.length : 0
}

const clearFileInput = () => {
    const fileInput = document.getElementById('documents') as HTMLInputElement;
    if (fileInput) {
        fileInput.value = '';
        form.documents = [];
        fileCount.value = 0;
    }
}

const submit = () => {
    isSubmitting.value = true
    form.post(route('vehicles.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
            clearFileInput()
            isSubmitting.value = false
        },
        onError: () => {
            isSubmitting.value = false
        }
    })
}
</script>

<template>
    <AppLayout title="Add Vehicle">
        <div class="py-6">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-6 flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-900">Add New Vehicle</h1>
                    <button
                        @click="() => { location.href = route('vehicles.index'); }"
                        type="button"
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                    >
                        <ArrowLeftIcon class="h-4 w-4 mr-2" />
                        Back to Vehicles
                    </button>
                </div>

                <form @submit.prevent="submit" class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <!-- Form Sections -->
                    <div class="border-b border-gray-200">
                        <div class="px-6 py-5 bg-gray-50">
                            <h2 class="text-lg font-medium text-gray-900">Vehicle Information</h2>
                            <p class="mt-1 text-sm text-gray-500">Enter the basic information about the vehicle.</p>
                        </div>

                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- License Plate -->
                            <div>
                                <InputLabel for="license_plate" value="License Plate *" />
                                <TextInput
                                    id="license_plate"
                                    v-model="form.license_plate"
                                    type="text"
                                    class="mt-1 block w-full"
                                    placeholder="Enter license plate"
                                    required
                                />
                                <InputError :message="form.errors.license_plate" class="mt-2" />
                            </div>

                            <!-- VIN -->
                            <div>
                                <InputLabel for="vin" value="VIN (Vehicle Identification Number)" />
                                <TextInput
                                    id="vin"
                                    v-model="form.vin"
                                    type="text"
                                    class="mt-1 block w-full"
                                    placeholder="Enter VIN"
                                />
                                <InputError :message="form.errors.vin" class="mt-2" />
                            </div>

                            <!-- Make -->
                            <div>
                                <InputLabel for="make" value="Make *" />
                                <TextInput
                                    id="make"
                                    v-model="form.make"
                                    type="text"
                                    class="mt-1 block w-full"
                                    placeholder="e.g. Toyota, BMW, Ford"
                                    required
                                />
                                <InputError :message="form.errors.make" class="mt-2" />
                            </div>

                            <!-- Model -->
                            <div>
                                <InputLabel for="model" value="Model *" />
                                <TextInput
                                    id="model"
                                    v-model="form.model"
                                    type="text"
                                    class="mt-1 block w-full"
                                    placeholder="e.g. Corolla, 3 Series, Focus"
                                    required
                                />
                                <InputError :message="form.errors.model" class="mt-2" />
                            </div>

                            <!-- Year -->
                            <div>
                                <InputLabel for="year" value="Year *" />
                                <TextInput
                                    id="year"
                                    v-model="form.year"
                                    type="number"
                                    min="1900"
                                    :max="new Date().getFullYear() + 1"
                                    class="mt-1 block w-full"
                                    required
                                />
                                <InputError :message="form.errors.year" class="mt-2" />
                            </div>

                            <!-- Color -->
                            <div>
                                <InputLabel for="color" value="Color" />
                                <TextInput
                                    id="color"
                                    v-model="form.color"
                                    type="text"
                                    class="mt-1 block w-full"
                                    placeholder="e.g. Black, White, Silver"
                                />
                                <InputError :message="form.errors.color" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Technical Information -->
                    <div class="border-b border-gray-200">
                        <div class="px-6 py-5 bg-gray-50">
                            <h2 class="text-lg font-medium text-gray-900">Technical Information</h2>
                            <p class="mt-1 text-sm text-gray-500">Enter technical details of the vehicle.</p>
                        </div>

                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Engine Number -->
                            <div>
                                <InputLabel for="engine_number" value="Engine Number" />
                                <TextInput
                                    id="engine_number"
                                    v-model="form.engine_number"
                                    type="text"
                                    class="mt-1 block w-full"
                                    placeholder="Enter engine number"
                                />
                                <InputError :message="form.errors.engine_number" class="mt-2" />
                            </div>

                            <!-- Engine Type -->
                            <div>
                                <InputLabel for="engine_type" value="Engine Type" />
                                <TextInput
                                    id="engine_type"
                                    v-model="form.engine_type"
                                    type="text"
                                    class="mt-1 block w-full"
                                    placeholder="e.g. V6, 4-cylinder, Diesel"
                                />
                                <InputError :message="form.errors.engine_type" class="mt-2" />
                            </div>

                            <!-- Transmission with Enhanced Dropdown -->
                            <div>
                                <InputLabel for="transmission" value="Transmission" />
                                <div class="relative mt-1">
                                    <button
                                        type="button"
                                        id="transmission-button"
                                        @click="toggleTransmissionDropdown"
                                        class="relative w-full cursor-default rounded-md border border-gray-300 bg-white py-2 pl-3 pr-10 text-left shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm"
                                    >
                                        <span class="block truncate">{{ form.transmission || 'Select Transmission' }}</span>
                                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </button>

                                    <div
                                        v-if="showTransmissionDropdown"
                                        class="absolute z-10 mt-1 w-full rounded-md bg-white shadow-lg"
                                    >
                                        <ul class="max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
                                            <li
                                                v-for="option in transmissionOptions"
                                                :key="option"
                                                class="relative cursor-pointer select-none py-2 pl-3 pr-9 hover:bg-indigo-50"
                                                @click="selectTransmission(option)"
                                            >
                                                <span class="block truncate">{{ option }}</span>
                                                <span
                                                    v-if="form.transmission === option"
                                                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-indigo-600"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <InputError :message="form.errors.transmission" class="mt-2" />
                            </div>

                            <!-- Mileage -->
                            <div>
                                <InputLabel for="mileage" value="Mileage" />
                                <div class="flex items-center mt-1">
                                    <TextInput
                                        id="mileage"
                                        v-model="form.mileage"
                                        type="number"
                                        min="0"
                                        class="block w-full rounded-r-none"
                                        placeholder="Current odometer reading"
                                    />
                                    <select
                                        v-model="form.mileage_unit"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm rounded-l-none rounded-r-md border-l-0 px-3 py-2"
                                    >
                                        <option value="km">km</option>
                                        <option value="mi">mi</option>
                                    </select>
                                </div>
                                <InputError :message="form.errors.mileage" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="border-b border-gray-200">
                        <div class="px-6 py-5 bg-gray-50">
                            <h2 class="text-lg font-medium text-gray-900">Customer Information</h2>
                            <p class="mt-1 text-sm text-gray-500">Select the customer who owns this vehicle.</p>
                        </div>

                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Customer Dropdown (Consistent Style) -->
                            <div>
                                <InputLabel for="customer_id" value="Customer *" />
                                <div class="relative mt-1">
                                    <button
                                        type="button"
                                        id="customer-button"
                                        @click="toggleCustomerDropdown"
                                        class="relative w-full cursor-default rounded-md border border-gray-300 bg-white py-2 pl-3 pr-10 text-left shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm"
                                    >
                                        <span class="block truncate">
                                            {{ selectedCustomer ? selectedCustomer.full_name : 'Select Customer' }}
                                        </span>
                                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </button>

                                    <div
                                        v-if="showCustomerDropdown"
                                        class="absolute z-10 mt-1 w-full rounded-md bg-white shadow-lg"
                                    >
                                        <div class="p-2 border-b border-gray-200">
                                            <div class="relative">
                                                <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <input
                                                    type="text"
                                                    id="customer_search"
                                                    v-model="customerSearch"
                                                    placeholder="Search customers..."
                                                    autocomplete="off"
                                                    class="block w-full rounded-md border-gray-300 pl-10 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                />
                                            </div>
                                        </div>

                                        <ul class="max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
                                            <li v-if="filteredCustomers.length === 0" class="relative cursor-default select-none py-2 px-4 text-gray-500">
                                                No customers found
                                            </li>
                                            <li
                                                v-for="customer in filteredCustomers"
                                                :key="customer.id"
                                                class="relative cursor-pointer select-none hover:bg-indigo-50"
                                                @click="selectCustomer(customer)"
                                            >
                                                <div class="flex flex-col px-4 py-2">
                                                    <div class="flex items-center justify-between">
                                                        <span class="font-medium text-gray-900">{{ customer.full_name }}</span>
                                                        <span
                                                            v-if="form.customer_id === customer.id.toString()"
                                                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-indigo-600"
                                                        >
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div v-if="customer.company_name" class="text-sm text-gray-600">{{ customer.company_name }}</div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <input
                                    type="hidden"
                                    name="customer_id"
                                    :value="form.customer_id || ''"
                                />
                                <InputError :message="form.errors.customer_id" class="mt-2" />
                            </div>

                            <!-- Status Dropdown with Better UX -->
                            <div>
                                <InputLabel for="status" value="Status" />
                                <div class="relative mt-1">
                                    <button
                                        type="button"
                                        id="status-button"
                                        @click="toggleStatusDropdown"
                                        class="relative w-full cursor-default rounded-md border border-gray-300 bg-white py-2 pl-3 pr-10 text-left shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm"
                                    >
                                        <span class="flex items-center">
                                            <span
                                                class="inline-block h-2 w-2 rounded-full mr-2"
                                                :class="{
                                                    'bg-green-400': form.status === 'active',
                                                    'bg-yellow-400': form.status === 'inactive',
                                                    'bg-blue-400': form.status === 'sold'
                                                }"
                                            ></span>
                                            <span class="block truncate capitalize">{{ form.status }}</span>
                                        </span>
                                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </button>

                                    <div
                                        v-if="showStatusDropdown"
                                        class="absolute z-10 mt-1 w-full rounded-md bg-white shadow-lg"
                                    >
                                        <ul class="max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
                                            <li
                                                v-for="status in statusOptions"
                                                :key="status.value"
                                                class="relative cursor-pointer select-none py-2 pl-3 pr-9 hover:bg-indigo-50"
                                                @click="selectStatus(status.value)"
                                            >
                                                <div class="flex items-center">
                                                    <span
                                                        class="inline-block h-2 w-2 rounded-full mr-2"
                                                        :class="status.color"
                                                    ></span>
                                                    <span class="block truncate capitalize">{{ status.value }}</span>
                                                </div>
                                                <span
                                                    v-if="form.status === status.value"
                                                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-indigo-600"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <InputError :message="form.errors.status" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="border-b border-gray-200">
                        <div class="px-6 py-5 bg-gray-50">
                            <h2 class="text-lg font-medium text-gray-900">Additional Information</h2>
                            <p class="mt-1 text-sm text-gray-500">Add any other relevant details or notes.</p>
                        </div>

                        <div class="p-6">
                            <InputLabel for="notes" value="Notes" />
                            <Textarea
                                id="notes"
                                v-model="form.notes"
                                class="mt-1 block w-full"
                                rows="3"
                                placeholder="Add any additional notes about this vehicle..."
                            />
                            <InputError :message="form.errors.notes" class="mt-2" />
                        </div>
                    </div>

                    <!-- File Upload -->
                    <div class="border-b border-gray-200">
                        <div class="px-6 py-5 bg-gray-50">
                            <h2 class="text-lg font-medium text-gray-900">Documents</h2>
                            <p class="mt-1 text-sm text-gray-500">Attach registration, insurance or other relevant documents.</p>
                        </div>

                        <div class="p-6">
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                                <div class="flex flex-col items-center">
                                    <DocumentTextIcon class="h-10 w-10 text-gray-400" />
                                    <p class="mt-2 text-sm text-gray-600">
                                        Drag and drop documents here, or
                                        <label for="documents" class="text-indigo-600 font-medium cursor-pointer hover:text-indigo-500">
                                            browse files
                                        </label>
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500">PDF, JPG, PNG or other document files</p>

                                    <div v-if="fileCount > 0" class="mt-3 text-sm font-medium text-indigo-600">
                                        {{ fileCount }} {{ fileCount === 1 ? 'file' : 'files' }} selected
                                        <button
                                            type="button"
                                            @click="clearFileInput"
                                            class="ml-2 text-gray-500 hover:text-gray-700"
                                        >
                                            Clear
                                        </button>
                                    </div>
                                </div>

                                <input
                                    type="file"
                                    id="documents"
                                    multiple
                                    @input="handleFileInput"
                                    class="hidden"
                                />
                            </div>
                            <InputError :message="form.errors.documents" class="mt-2" />
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="px-6 py-4 bg-gray-50 flex items-center justify-between">
                        <p class="text-xs text-gray-500">* Required fields</p>
                        <PrimaryButton
                            :class="{ 'opacity-25': isSubmitting }"
                            :disabled="isSubmitting || form.processing"
                        >
                            <span v-if="isSubmitting">Creating...</span>
                            <span v-else>Create Vehicle</span>
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
