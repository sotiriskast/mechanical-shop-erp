<script setup>
import { Line, Bar } from 'vue-chartjs'
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    BarElement,
    Title,
    Tooltip,
    Legend
} from 'chart.js'

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    BarElement,
    Title,
    Tooltip,
    Legend
)

const props = defineProps({
    type: {
        type: String,
        default: 'line'
    },
    data: {
        type: Object,
        required: true
    },
    options: {
        type: Object,
        default: () => ({})
    }
})

const chartComponent = computed(() =>
    props.type === 'line' ? Line : Bar
)
</script>

<template>
    <component
        :is="chartComponent"
        :data="data"
        :options="options"
    />
</template>
