import { defineStore } from 'pinia'
import { getTransactions } from '../api/transactions'
import { createTransaction } from '../api/transactions'
import {
    updateTransaction,
    deleteTransaction
} from '../api/transactions'

export interface Transaction {
    id: number
    amount: number
    type: 'income' | 'expense'
    description: string
    category_id: number | null
    transaction_date: string
    created_at: string

    category?: {
        id: number
        name: string
    } | null
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
        async addTransaction(payload: {
            description: string
            amount: number
            type: 'income' | 'expense'
            category_id: number | null
            transaction_date: string
        }) {
            const newTx = await createTransaction(payload)

            this.transactions.unshift(newTx)
        },
        async removeTransaction(id: number) {
            await deleteTransaction(id)

            this.transactions = this.transactions.filter(t => t.id !== id)
        },
        async updateTransaction(id: number, payload: any) {
            this.loading = true

            try {
                const res = await updateTransaction(id, payload)

                // replace in local state
                const index = this.transactions.findIndex(t => t.id === id)

                if (index !== -1) {
                    this.transactions[index] = res
                }

            } finally {
                this.loading = false
            }
        },
        reset() {
            this.transactions = []
            this.loading = false
        }
    },
})