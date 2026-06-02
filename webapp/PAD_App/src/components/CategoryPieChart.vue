<template>
    <canvas ref="canvas"></canvas>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import {
    Chart,
    PieController,
    ArcElement,
    Tooltip,
    Legend,
} from 'chart.js'

Chart.register(PieController, ArcElement, Tooltip, Legend)

const props = defineProps<{
    transactions: any[]
}>()

const canvas = ref<HTMLCanvasElement | null>(null)

let chart: Chart | null = null

const buildChart = () => {
    if (!props.transactions?.length) return

    // Only expenses
    const expenses = props.transactions.filter(
        t => t.type === 'expense'
    )

    // Group by category
    const grouped: Record<string, number> = {}

    expenses.forEach(t => {
        const name = t.category?.name ?? 'Unknown'
        grouped[name] = (grouped[name] || 0) + Number(t.amount)
    })

    const labels = Object.keys(grouped)
    const data = Object.values(grouped)

    if (chart) chart.destroy()

    chart = new Chart(canvas.value!, {
        type: 'pie',
        data: {
            labels,
            datasets: [
                {
                    data,
                    backgroundColor: [
                        '#f87171',
                        '#60a5fa',
                        '#34d399',
                        '#fbbf24',
                        '#a78bfa',
                        '#fb7185',
                        '#22c55e',
                    ],
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
            },
        },
    })
}

onMounted(buildChart)

watch(
    () => props.transactions,
    buildChart,
    { deep: true }
)
</script>