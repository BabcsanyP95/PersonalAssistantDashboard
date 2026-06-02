import { defineStore } from 'pinia'
import { getTransactions } from '../api/transactions'

export interface Transaction {
    id: number
    amount: number
    type: 'income' | 'expense'
    description: string
    created_at: string
}

export const useTransactionStore = defineStore('transactions', {
    state: () => ({
        transactions: [] as Transaction[],
        loading: false,
    }),

    actions: {
        async fetchTransactions() {
            this.loading = true

            try {
                const res = await getTransactions()

                console.log('RAW:', res)

                // ALWAYS force array
                this.transactions = Array.isArray(res)
                    ? res
                    : Array.isArray(res?.data)
                        ? res.data
                        : []

            } finally {
                this.loading = false
            }
        },
    },
})