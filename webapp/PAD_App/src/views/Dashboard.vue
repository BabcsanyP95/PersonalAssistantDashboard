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
                <p class="text-2xl font-bold">$2,450</p>
            </div>

            <div class="bg-white p-4 rounded-xl border shadow-sm">
                <p class="text-gray-500 text-sm">Income</p>
                <p class="text-2xl font-bold text-green-600">$3,200</p>
            </div>

            <div class="bg-white p-4 rounded-xl border shadow-sm">
                <p class="text-gray-500 text-sm">Expenses</p>
                <p class="text-2xl font-bold text-red-500">$750</p>
            </div>

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

const auth = useAuthStore()
const txStore = useTransactionStore()

// SAFE fallback (prevents null crashes)
const safeTransactions = computed(() => {
    return Array.isArray(txStore.transactions)
        ? txStore.transactions.filter(t => t && t.id)
        : []
})


onMounted(async () => {
    await auth.fetchUser()

    await txStore.fetchTransactions()
})
</script>