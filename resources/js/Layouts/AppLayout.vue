<script setup>
import { ref, computed } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import NavLink from '@/Components/NavLink.vue'
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue'
import { BellIcon, BellAlertIcon } from '@heroicons/vue/24/outline'
import FlashMessage from "@/Components/FlashMessage.vue";


defineProps({
    title: {
        type: String,
        required: true
    }
})

const showingNavigationDropdown = ref(false)
const showNotifications = ref(false)

const notifications = computed(() => usePage().props.auth.user.notifications)
const unreadNotificationsCount = computed(() => usePage().props.auth.user.unread_notifications_count)

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}
</script>

<template>
    <div>
        <Head :title="title"/>
        <FlashMessage/>
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')">
                                    <ApplicationLogo class="block h-9 w-auto"/>
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Dashboard
                                </NavLink>
                                <NavLink :href="route('customers.index')" :active="route().current('customers.*')">
                                    Customers
                                </NavLink>
                            </div>
                        </div>

                        <!-- Settings Dropdown -->
                        <div class="hidden sm:flex sm:items-center sm:ml-6 space-x-4">
                            <!-- Notifications Dropdown -->
                            <div class="relative">
                                <button
                                    @click="showNotifications = !showNotifications"
                                    class="relative p-1 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                >
                                    <BellAlertIcon v-if="unreadNotificationsCount" class="h-6 w-6 text-gray-600"/>
                                    <BellIcon v-else class="h-6 w-6 text-gray-600"/>

                                    <!-- Notification Badge -->
                                    <div
                                        v-if="unreadNotificationsCount"
                                        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full min-w-[20px] h-5 flex items-center justify-center px-1"
                                    >
                                        {{ unreadNotificationsCount }}
                                    </div>
                                </button>

                                <!-- Notifications Panel -->
                                <div
                                    v-show="showNotifications"
                                    class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg overflow-hidden z-50"
                                >
                                    <div class="py-2">
                                        <div class="px-4 py-2 bg-gray-50 border-b border-gray-200">
                                            <div class="flex justify-between items-center">
                                                <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                                                <button
                                                    v-if="unreadNotificationsCount"
                                                    class="text-xs text-blue-600 hover:text-blue-800"
                                                    @click="markAllAsRead"
                                                >
                                                    Mark all as read
                                                </button>
                                            </div>
                                        </div>

                                        <div v-if="notifications.length" class="max-h-64 overflow-y-auto">
                                            <div
                                                v-for="notification in notifications"
                                                :key="notification.id"
                                                class="px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-0"
                                                :class="{ 'bg-blue-50': !notification.read_at }"
                                            >
                                                <div class="flex items-start">
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-medium text-gray-900">
                                                            {{ notification.data.title }}
                                                        </p>
                                                        <p class="text-sm text-gray-500">
                                                            {{ notification.data.message }}
                                                        </p>
                                                        <p class="mt-1 text-xs text-gray-400">
                                                            {{ formatDate(notification.created_at) }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            v-else
                                            class="px-4 py-6 text-center text-gray-500"
                                        >
                                            No notifications
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Settings Dropdown -->
                            <Dropdown align="right" width="48">
                                <!-- Rest of your existing settings dropdown -->
                            </Dropdown>
                        </div>
                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"/>
                                    <path
                                        :class="{'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown}"
                     class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('customers.index')" :active="route().current('customers.*')">
                            Customers
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="flex items-center px-4">
                            <div>
                                <div class="font-medium text-base text-gray-800">
                                    {{ $page.props.auth.user.name }}
                                </div>
                                <div class="font-medium text-sm text-gray-500">
                                    {{ $page.props.auth.user.email }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.show')">
                                Profile
                            </ResponsiveNavLink>

                            <form method="POST" @submit.prevent="logout">
                                <ResponsiveNavLink as="button">
                                    Log Out
                                </ResponsiveNavLink>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header"/>
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot/>
            </main>
        </div>
    </div>
</template>
