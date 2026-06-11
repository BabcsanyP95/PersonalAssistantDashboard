import { defineStore } from 'pinia'

import {
    getSavingsGoals,
    createSavingsGoal,
    updateSavingsGoal,
    deleteSavingsGoal,
    depositSavingsGoal
} from '../api/savings'

export interface SavingsGoal {
    id: number

    name: string

    target_amount: number

    current_amount: number

    target_date: string

    is_completed: boolean
}

export const useSavingsStore = defineStore('savings', {

    state: () => ({
        goals: [] as SavingsGoal[],
        loading: false,
    }),

    actions: {

        async fetchGoals() {

            this.loading = true

            try {

                this.goals = await getSavingsGoals()

            } finally {

                this.loading = false

            }

        },

        async addGoal(payload: any) {

            const goal = await createSavingsGoal(payload)

            this.goals.unshift(goal)

        },

        async editGoal(id: number, payload: any) {

            const updated = await updateSavingsGoal(id, payload)

            const index = this.goals.findIndex(g => g.id === id)

            if (index !== -1) {

                this.goals[index] = updated

            }

        },

        async removeGoal(id: number) {

            await deleteSavingsGoal(id)

            this.goals = this.goals.filter(g => g.id !== id)

        },

        async deposit(id: number, amount: number) {
            const updated = await depositSavingsGoal(id, amount)

            const index = this.goals.findIndex(g => g.id === id)

            if (index !== -1) {
                this.goals[index] = updated
            }
        }

    },

})