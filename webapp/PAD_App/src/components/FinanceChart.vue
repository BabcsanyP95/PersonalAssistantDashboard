<template>
    <canvas ref="canvas"></canvas>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import {
    Chart,
    BarController,
    BarElement,
    CategoryScale,
    LinearScale,
    Tooltip,
    Legend,
} from 'chart.js'

import { useTransactionStore } from '../stores/transactions'

Chart.register(
    BarController,
    BarElement,
    CategoryScale,
    LinearScale,
    Tooltip,
    Legend
)

const txStore = useTransactionStore()
const canvas = ref<HTMLCanvasElement | null>(null)

let chart: Chart | null = null

const buildChart = () => {
    const income = txStore.transactions
        .filter(t => t.type === 'income')
        .reduce((sum, t) => sum + Number(t.amount), 0)

    const expenses = txStore.transactions
        .filter(t => t.type === 'expense')
        .reduce((sum, t) => sum + Number(t.amount), 0)

    if (chart) chart.destroy()

    chart = new Chart(canvas.value!, {
        type: 'bar',
        data: {
            labels: ['Income', 'Expenses'],
            datasets: [
                {
                    label: 'Amount',
                    data: [income, expenses],
                    backgroundColor: ['#22c55e', '#ef4444'],
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
            },
        },
    })
}

onMounted(() => {
    buildChart()
})

watch(
    () => txStore.transactions,
    () => {
        buildChart()
    },
    { deep: true }
)
</script>