<script setup>
import { ref, onMounted } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import { BellIcon, CheckCircleIcon } from '@heroicons/vue/24/outline'

const notifications = ref([])
const unreadCount = ref(0)
const showDropdown = ref(false)

// Get notifications from props passed down from Inertia
const props = defineProps({
    initialNotifications: {
        type: Array,
        default: () => []
    }
})

onMounted(() => {
    notifications.value = props.initialNotifications
    updateUnreadCount()

    // Listen for real-time notifications
    window.Echo.private(`App.Models.User.${usePage().props.auth.user.id}`)
        .notification((notification) => {
            notifications.value.unshift(notification)
            updateUnreadCount()
        })
})

const updateUnreadCount = () => {
    unreadCount.value = notifications.value.filter(n => !n.read_at).length
}

const markAsRead = (notification) => {
    router.patch(route('notifications.mark-as-read', notification.id), {}, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            notification.read_at = new Date()
            updateUnreadCount()
        }
    })
}

const markAllAsRead = () => {
    router.post(route('notifications.mark-all-as-read'), {}, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            notifications.value.forEach(notification => {
                notification.read_at = new Date()
            })
            updateUnreadCount()
        }
    })
}

const handleNotificationClick = (notification) => {
    // Mark as read and navigate to the target URL
    markAsRead(notification)
    if (notification.data.action_url) {
        router.visit(notification.data.action_url)
        showDropdown.value = false
    }
}

const closeDropdown = (e) => {
    if (!e.target.closest('.notification-center')) {
        showDropdown.value = false
    }
}

onMounted(() => {
    document.addEventListener('click', closeDropdown)
})

onBeforeUnmount(() => {
    document.removeEventListener('click', closeDropdown)
})
</script>

<template>
    <div class="notification-center relative">
        <!-- Notification Bell -->
        <button
            @click="showDropdown = !showDropdown"
            class="relative p-1 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
            <BellIcon class="h-6 w-6 text-gray-500" />
            <span
                v-if="unreadCount > 0"
                class="absolute top-0 right-0 -mt-1 -mr-1 px-2 py-1 text-xs leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full"
            >
        {{ unreadCount }}
      </span>
        </button>

        <!-- Notification Dropdown -->
        <div
            v-if="showDropdown"
            class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg overflow-hidden z-50"
        >
            <div class="py-2">
                <!-- Header -->
                <div class="px-4 py-2 bg-gray-50 flex justify-between items-center border-b">
                    <h3 class="text-sm font-medium text-gray-900">Notifications</h3>
                    <button
                        v-if="unreadCount > 0"
                        @click="markAllAsRead"
                        class="text-sm text-indigo-600 hover:text-indigo-900"
                    >
                        Mark all as read
                    </button>
                </div>

                <!-- Notification List -->
                <div class="max-h-96 overflow-y-auto">
                    <template v-if="notifications.length">
                        <div
                            v-for="notification in notifications"
                            :key="notification.id"
                            @click="handleNotificationClick(notification)"
                            class="px-4 py-3 hover:bg-gray-50 cursor-pointer transition duration-150 ease-in-out"
                            :class="{ 'bg-blue-50': !notification.read_at }"
                        >
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <CheckCircleIcon
                                        v-if="notification.read_at"
                                        class="h-6 w-6 text-green-400"
                                    />
                                    <div
                                        v-else
                                        class="h-2 w-2 mt-2 bg-blue-600 rounded-full"
                                    />
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-900 line-clamp-1">
                                        {{ notification.data.title }}
                                    </p>
                                    <p class="mt-1 text-sm text-gray-500 line-clamp-2">
                                        {{ notification.data.body }}
                                    </p>
                                    <div class="mt-2 text-xs text-gray-400">
                                        {{ new Date(notification.created_at).toLocaleDateString() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <div
                        v-else
                        class="px-4 py-6 text-sm text-gray-500 text-center"
                    >
                        No notifications
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
