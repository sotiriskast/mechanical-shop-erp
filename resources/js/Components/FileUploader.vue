<script setup>
import { ref, computed } from 'vue';
import { XMarkIcon } from '@heroicons/vue/24/outline';
import { DocumentIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    modelValue: {
        type: [FileList, Array],
        default: () => []
    },
    existingFiles: {
        type: Array,
        default: () => []
    },
    accept: {
        type: String,
        default: '.pdf,.doc,.docx,.jpg,.jpeg,.png'
    },
    maxSize: {
        type: Number,
        default: 10 * 1024 * 1024 // 10MB
    }
});

const emit = defineEmits(['update:modelValue', 'remove-existing']);

const fileInputRef = ref(null);
const isDragging = ref(false);
const errors = ref([]);

const previewFiles = computed(() => {
    if (!props.modelValue) return [];
    return Array.from(props.modelValue).map(file => ({
        name: file.name,
        size: formatFileSize(file.size),
        type: file.type,
        preview: file.type.startsWith('image/') ? URL.createObjectURL(file) : null
    }));
});

const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

const validateFiles = (files) => {
    errors.value = [];

    const validFiles = Array.from(files).filter(file => {
        // Check file size
        if (file.size > props.maxSize) {
            errors.value.push(`${file.name} is too large. Maximum size is ${formatFileSize(props.maxSize)}`);
            return false;
        }

        // Check file type
        const acceptedTypes = props.accept.split(',').map(type => type.trim());
        const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
        if (!acceptedTypes.includes(fileExtension)) {
            errors.value.push(`${file.name} has an invalid file type`);
            return false;
        }

        return true;
    });

    return validFiles;
};

const handleFileDrop = (e) => {
    e.preventDefault();
    isDragging.value = false;

    const validFiles = validateFiles(e.dataTransfer.files);
    if (validFiles.length > 0) {
        emit('update:modelValue', validFiles);
    }
};

const handleFileInput = (e) => {
    const validFiles = validateFiles(e.target.files);
    if (validFiles.length > 0) {
        emit('update:modelValue', validFiles);
    }
};

const removeFile = (index) => {
    const newFiles = Array.from(props.modelValue);
    newFiles.splice(index, 1);
    emit('update:modelValue', newFiles.length > 0 ? newFiles : []);
};

const removeExistingFile = (fileId) => {
    emit('remove-existing', fileId);
};
</script>

<template>
    <div>
        <!-- Upload Area -->
        <div
            @dragover.prevent="isDragging = true"
            @dragleave.prevent="isDragging = false"
            @drop.prevent="handleFileDrop"
            class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10"
            :class="{ 'border-indigo-500 bg-indigo-50': isDragging }"
        >
            <div class="text-center">
                <DocumentIcon class="mx-auto h-12 w-12 text-gray-300" />
                <div class="mt-4 flex text-sm leading-6 text-gray-600 justify-center">
                    <label
                        class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500"
                    >
                        <span>Upload files</span>
                        <input
                            ref="fileInputRef"
                            type="file"
                            multiple
                            class="sr-only"
                            :accept="accept"
                            @input="handleFileInput"
                        />
                    </label>
                    <p class="pl-1">or drag and drop</p>
                </div>
                <p class="text-xs leading-5 text-gray-600">
                    Accepted types: {{ accept }}. Max size: {{ formatFileSize(maxSize) }}
                </p>
            </div>
        </div>

        <!-- Error Messages -->
        <div v-if="errors.length > 0" class="mt-2">
            <p v-for="(error, index) in errors" :key="index" class="text-sm text-red-600">
                {{ error }}
            </p>
        </div>

        <!-- New Files Preview -->
        <div v-if="previewFiles.length > 0" class="mt-4">
            <h4 class="text-sm font-medium text-gray-900">Selected Files</h4>
            <ul class="mt-2 divide-y divide-gray-200 rounded-md border border-gray-200">
                <li v-for="(file, index) in previewFiles" :key="index" class="flex items-center justify-between py-3 pl-3 pr-4 text-sm">
                    <div class="flex w-0 flex-1 items-center">
                        <img v-if="file.preview" :src="file.preview" class="h-10 w-10 object-cover rounded" />
                        <span v-else class="h-10 w-10 flex items-center justify-center bg-gray-100 rounded">
                            {{ file.name.split('.').pop().toUpperCase() }}
                        </span>
                        <div class="ml-4 flex min-w-0 flex-1 gap-2">
                            <span class="truncate font-medium">{{ file.name }}</span>
                            <span class="flex-shrink-0 text-gray-400">{{ file.size }}</span>
                        </div>
                    </div>
                    <button
                        type="button"
                        @click="removeFile(index)"
                        class="ml-4 flex-shrink-0 text-gray-400 hover:text-gray-500"
                    >
                        <XMarkIcon class="h-5 w-5" />
                    </button>
                </li>
            </ul>
        </div>

        <!-- Existing Files -->
        <div v-if="existingFiles.length > 0" class="mt-4">
            <h4 class="text-sm font-medium text-gray-900">Current Files</h4>
            <ul class="mt-2 divide-y divide-gray-200 rounded-md border border-gray-200">
                <li v-for="file in existingFiles" :key="file.id" class="flex items-center justify-between py-3 pl-3 pr-4 text-sm">
                    <div class="flex w-0 flex-1 items-center">
                        <img v-if="file.thumbnail_url" :src="file.thumbnail_url" class="h-10 w-10 object-cover rounded" />
                        <span v-else class="h-10 w-10 flex items-center justify-center bg-gray-100 rounded">
                            {{ file.extension?.toUpperCase() }}
                        </span>
                        <div class="ml-4 flex min-w-0 flex-1 gap-2">
                            <span class="truncate font-medium">{{ file.file_name }}</span>
                            <span class="flex-shrink-0 text-gray-400">{{ formatFileSize(file.size) }}</span>
                        </div>
                    </div>
                    <button
                        type="button"
                        @click="removeExistingFile(file.id)"
                        class="ml-4 flex-shrink-0 text-gray-400 hover:text-gray-500"
                    >
                        <XMarkIcon class="h-5 w-5" />
                    </button>
                </li>
            </ul>
        </div>
    </div>
</template>
