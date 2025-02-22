<script setup>
import { ref, computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import StatCard from '@/Components/Dashboard/StatsCard.vue'
import LineChart from '@/Components/Dashboard/LineChart.vue'
import DateRangeFilter from '@/Components/Dashboard/DateRangeFilter.vue'

const props = defineProps({
    statistics: {
        type: Object,
        default: () => ({
            trends: {
                labels: [],
                datasets: {
                    revenue: [],
                    work_orders: [],
                    customers: []
                }
            },
            counts: {
                customers: { total: 0, active: 0, new: 0 }
            }
        })
    },
    filters: {
        type: Object,
        default: () => ({
            date_range: 'month'
        })
    }
})

const dateRange = ref(props.filters.date_range)

const chartData = computed(() => ({
    labels: props.statistics.trends.labels,
    datasets: [
        {
            label: 'Customers',
            data: props.statistics.trends.datasets.customers,
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.5)',
            tension: 0.4,
            pointRadius: 2,
            pointHoverRadius: 4
        }
    ]
}))
</script>

<template>
    <AppLayout title="Dashboard">
        <Head title="Dashboard" />

        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Dashboard
                </h2>
                <DateRangeFilter v-model="dateRange" />
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Customers Stats -->
                    <StatCard
                        title="Total Customers"
                        :total="statistics.counts.customers.total"
                        icon="UserIcon"
                        link="/customers"
                    />

                    <StatCard
                        title="Active Customers"
                        :total="statistics.counts.customers.active"
                        icon="UserIcon"
                    />

                    <StatCard
                        title="New Customers"
                        :total="statistics.counts.customers.new"
                        icon="UserIcon"
                    />
                </div>

                <!-- Customer Trends Chart -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Customer Growth
                        </h3>
                        <div class="h-[400px]">
                            <LineChart
                                :data="chartData"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
