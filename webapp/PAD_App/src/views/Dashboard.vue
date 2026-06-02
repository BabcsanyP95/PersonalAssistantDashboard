<template>
    <div class="p-6 space-y-6">

        <!-- Header -->
        <div>
            <h1 class="text-2xl font-bold">Dashboard</h1>

            <p v-if="auth.user" class="text-gray-500">
                Welcome back, {{ auth.user.name }}
            </p>
        </div>

        <!-- Cards -->
        <div class="grid grid-cols-3 gap-4">

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

        <div class="bg-white p-4 rounded-xl border shadow-sm space-y-3">

            <h2 class="text-lg font-semibold">Add Transaction</h2>

            <input v-model="form.description" placeholder="Description" class="w-full border p-2 rounded" />

            <input v-model.number="form.amount" type="number" placeholder="Amount" class="w-full border p-2 rounded" />

            <select v-model="form.type" class="w-full border p-2 rounded">
                <option value="income">Income</option>
                <option value="expense">Expense</option>
            </select>

            <button @click="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                Add
            </button>

        </div>

        <!-- Transactions -->
        <div class="bg-white p-4 rounded-xl border shadow-sm">

            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Transactions</h2>

                <button @click="txStore.fetchTransactions" class="text-sm text-blue-500">
                    Refresh
                </button>
            </div>

            <!-- Loading -->
            <p v-if="txStore.loading" class="text-gray-500">
                Loading transactions...
            </p>

            <!-- Empty state -->
            <p v-else-if="safeTransactions.length === 0" class="text-gray-500">
                No transactions found
            </p>

            <!-- List -->
            <div v-else class="space-y-3">

                <div v-for="tx in safeTransactions" :key="tx.id" class="flex justify-between border-b pb-2">

                    <div>
                        <p class="font-medium">
                            {{ tx.description }}
                        </p>

                        <p class="text-sm text-gray-400">
                            {{ tx.created_at }}
                        </p>
                    </div>

                    <div :class="tx.type === 'income'
                        ? 'text-green-600'
                        : 'text-red-500'">
                        {{ tx.type === 'income' ? '+' : '-' }}${{ tx.amount }}
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
import { reactive } from 'vue'

const auth = useAuthStore()
const txStore = useTransactionStore()

// SAFE fallback (prevents null crashes)
const safeTransactions = computed(() => {
    return Array.isArray(txStore.transactions)
        ? txStore.transactions.filter(t => t && t.id)
        : []
})

const totalIncome = computed(() => {
    return txStore.transactions
        .filter(t => t.type === 'income')
        .reduce((sum, t) => sum + Number(t.amount), 0)
})

const totalExpenses = computed(() => {
    return txStore.transactions
        .filter(t => t.type === 'expense')
        .reduce((sum, t) => sum + Number(t.amount), 0)
})

const balance = computed(() => {
    return totalIncome.value - totalExpenses.value
})

const format = (n: number) => {
    return n.toLocaleString('en-US')
}

const form = reactive({
    description: '',
    amount: 0,
    type: 'income' as 'income' | 'expense',
})

const submit = async () => {
    if (!form.description || !form.amount) return

    await txStore.addTransaction({
        description: form.description,
        amount: form.amount,
        type: form.type,
    })

    // reset form
    form.description = ''
    form.amount = 0
    form.type = 'income'
}

onMounted(async () => {
    await auth.fetchUser()

    await txStore.fetchTransactions()
})
</script>