import { defineStore } from 'pinia'
import { getBudgets, createBudget, updateBudget, deleteBudget } from '@/api/budgets'

export interface Budget {
    id: number
    user_id: number
    category_id: number
    month: number
    year: number

    original_amount: number
    current_amount: number

    category?: {
        id: number
        name: string
        color: string
        type: 'income' | 'expense'
    }
}

export const useBudgetStore = defineStore('budgets', {
    state: () => ({
        budgets: [] as Budget[],
        loading: false,
    }),

    actions: {
        async fetchBudgets() {
            this.loading = true

            try {
                const res = await getBudgets()

                this.budgets = Array.isArray(res)
                    ? res
                    : Array.isArray(res.data)
                        ? res.data
                        : []
            } finally {
                this.loading = false
            }
        },

        async addBudget(payload: {
            category_id: number
            month: number
            year: number
            amount: number
        }) {
            const budget = await createBudget(payload)

            this.budgets.unshift(budget)
        },

        async editBudget(id: number, payload: any) {
            const updated = await updateBudget(id, payload)

            const index = this.budgets.findIndex(
                b => b.id === id
            )

            if (index !== -1) {
                this.budgets[index] = updated
            }
        },

        async removeBudget(id: number) {
            await deleteBudget(id)

            this.budgets = this.budgets.filter(
                b => b.id !== id
            )
        },
    },
})