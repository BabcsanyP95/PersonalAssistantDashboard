<template>
    <div class="p-4 md:p-6 space-y-6">

        <!-- Header -->
        <div>
            <h1 class="text-2xl font-bold">Dashboard</h1>

            <p v-if="auth.user" class="text-gray-500">
                Welcome back, {{ auth.user.name }}
            </p>
        </div>

        <!-- Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <div class="bg-white p-4 rounded-xl border shadow-sm">
                <p class="text-gray-500 text-sm">Balance</p>
                <p class="text-2xl font-bold">
                    ${{ format(balance) }}
                </p>
            </div>

            <div class="bg-white p-4 rounded-xl border shadow-sm">
                <p class="text-gray-500 text-sm">Income</p>
                <p class="text-2xl font-bold text-green-600">
                    ${{ format(totalIncome) }}
                </p>
            </div>

            <div class="bg-white p-4 rounded-xl border shadow-sm">
                <p class="text-gray-500 text-sm">Expenses</p>
                <p class="text-2xl font-bold text-red-500">
                    ${{ format(totalExpenses) }}
                </p>
            </div>

        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <!-- Income vs Expenses -->
            <div class="bg-white p-4 rounded-xl border shadow-sm">
                <h2 class="text-lg font-semibold mb-4">Overview</h2>
                <div class="h-64">
                    <FinanceChart :transactions="filteredTransactions" />
                </div>
            </div>

            <!-- Category breakdown -->
            <div class="bg-white p-4 rounded-xl border shadow-sm">
                <h2 class="text-lg font-semibold mb-4 text-center">
                    Spending by Category
                </h2>

                <div class="flex justify-center">
                    <div class="h-64 w-64">
                        <CategoryPieChart :transactions="filteredTransactions" />
                    </div>
                </div>
            </div>

        </div>

    </div>
</template>

<script setup lang="ts">
import { computed, onMounted } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useTransactionStore } from '../stores/transactions'
import { reactive, ref } from 'vue'
import { useCategoryStore } from '../stores/categories'
import FinanceChart from '../components/FinanceChart.vue'
import CategoryPieChart from '../components/CategoryPieChart.vue'

const auth = useAuthStore()
const txStore = useTransactionStore()
const categoryStore = useCategoryStore()
// SAFE fallback (prevents null crashes)
const filteredTransactions = computed(() => {
    let txs = Array.isArray(txStore.transactions)
        ? txStore.transactions.filter(t => t && t.id)
        : []

    // CATEGORY FILTER
    if (selectedCategory.value) {
        txs = txs.filter(
            t => t.category_id === selectedCategory.value
        )
    }

    // MONTH FILTER
    if (selectedMonth.value) {
        txs = txs.filter(tx => {
            const date = new Date(tx.transaction_date)

            const year = date.getFullYear()
            const month = String(date.getMonth() + 1).padStart(2, '0')

            return `${year}-${month}` === selectedMonth.value
        })
    }

    return txs
})

const clearFilters = () => {
    selectedCategory.value = null
    selectedMonth.value = ''
}

const selectedMonth = ref('')

const selectedCategory = ref<number | null>(null)

const totalIncome = computed(() => {
    return filteredTransactions.value
        .filter(t => t.type === 'income')
        .reduce((sum, t) => sum + Number(t.amount), 0)
})

const totalExpenses = computed(() => {
    return filteredTransactions.value
        .filter(t => t.type === 'expense')
        .reduce((sum, t) => sum + Number(t.amount), 0)
})

const balance = computed(() => {
    return totalIncome.value - totalExpenses.value
})

const format = (n: number) => {
    return n.toLocaleString('en-US')
}

const formatDate = (dateString: string) => {
    if (!dateString) return ''

    const date = new Date(dateString)

    return new Intl.DateTimeFormat('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    }).format(date)
}

const form = reactive({
    description: '',
    amount: 0,
    type: 'income' as 'income' | 'expense',
    category_id: null as number | null,
    transaction_date: new Date().toISOString().split('T')[0],
})

const submit = async () => {
    if (!form.description || !form.amount) return

    await txStore.addTransaction({
        description: form.description,
        amount: Number(form.amount),
        type: form.type,
        category_id: form.category_id,
        transaction_date: form.transaction_date,
    })

    // reset form
    form.description = ''
    form.amount = 0
    form.type = 'income'
}

const editingId = ref<number | null>(null)

const editForm = reactive({
    description: '',
    amount: 0,
    type: 'income' as 'income' | 'expense',
    category_id: null as number | null,
    transaction_date: new Date().toISOString().split('T')[0],
})

const startEdit = (tx: any) => {
    editingId.value = tx.id

    editForm.description = tx.description
    editForm.amount = tx.amount
    editForm.type = tx.type
}

const saveEdit = async () => {
    if (!editingId.value) return

    await txStore.addTransaction({
        description: editForm.description,
        amount: Number(editForm.amount),
        type: editForm.type,
        category_id: editForm.category_id,
        transaction_date: editForm.transaction_date,
    })

    editingId.value = null
}
onMounted(() => {
    categoryStore.fetchCategories()
})

onMounted(async () => {
    await auth.fetchUser()

    await txStore.fetchTransactions()
})
</script>