<script setup>
import { onMounted, onUnmounted, ref } from 'vue'
import { Line } from 'vue-chartjs'
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
} from 'chart.js'

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
)

const props = defineProps({
    data: {
        type: Object,
        required: true
    },
    options: {
        type: Object,
        default: () => ({})
    }
})

const chartContainer = ref(null)
let resizeObserver = null

onMounted(() => {
    resizeObserver = new ResizeObserver(() => {
        if (chartContainer.value) {
            ChartJS.instances[0]?.resize()
        }
    })

    if (chartContainer.value) {
        resizeObserver.observe(chartContainer.value)
    }
})

onUnmounted(() => {
    if (resizeObserver) {
        resizeObserver.disconnect()
    }
})
</script>

<template>
    <div ref="chartContainer" class="w-full h-full">
        <Line
            :data="data"
            :options="{
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 10,
                            usePointStyle: true
                        }
                    }
                },
                ...options
            }"
        />
    </div>
</template>
