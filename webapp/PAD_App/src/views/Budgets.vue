<template>
    <div class="p-4 md:p-6 space-y-6">

        <div>
            <h1 class="text-2xl font-bold">
                Budgets
            </h1>

            <p class="text-gray-500">
                Set monthly spending limits
            </p>
        </div>

        <!-- Create Budget -->

        <div class="bg-white rounded-xl border shadow-sm p-4 space-y-3">

            <h2 class="font-semibold">
                Create Budget
            </h2>

            <select v-model="form.category_id" class="w-full border rounded p-2">
                <option disabled value="">
                    Select category
                </option>

                <option v-for="cat in categoryStore.categories" :key="cat.id" :value="cat.id">
                    {{ cat.name }}
                </option>

            </select>

            <input type="number" v-model.number="form.amount" placeholder="Budget Amount"
                class="w-full border rounded p-2" />

            <div class="grid grid-cols-2 gap-2">

                <input type="number" v-model.number="form.month" min="1" max="12" placeholder="Month"
                    class="border rounded p-2" />

                <input type="number" v-model.number="form.year" placeholder="Year" class="border rounded p-2" />

            </div>

            <button @click="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                Save Budget
            </button>

        </div>

        <!-- Budget List -->

        <div class="bg-white rounded-xl border shadow-sm p-4">

            <h2 class="font-semibold mb-4">
                Current Budgets
            </h2>

            <div v-for="budget in budgetStore.budgets" :key="budget.id" class="border rounded-lg p-4 mb-3">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-semibold">
                            {{ budget.category?.name }}
                        </p>

                        <p class="text-sm text-gray-500">
                            {{ budget.month }}/{{ budget.year }}
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <p class="font-bold">
                            ${{ budget.current_amount }} remaining
                        </p>

                        <p class="text-xs text-gray-500">
                            Budget: ${{ budget.original_amount }}
                        </p>

                        <button @click="removeBudget(budget.id)"
                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                            Delete
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </div>
</template>
<script setup lang="ts">
import { reactive, onMounted } from "vue"

import { useBudgetStore } from "@/stores/budgets"
import { useCategoryStore } from "@/stores/categories"

const budgetStore = useBudgetStore()
const categoryStore = useCategoryStore()

interface BudgetForm {
    category_id: number | null
    amount: number
    month: number
    year: number
}

const form = reactive<BudgetForm>({
    category_id: null,
    amount: 0,
    month: new Date().getMonth() + 1,
    year: new Date().getFullYear(),
})

const submit = async () => {
    if (form.category_id === null) {
        alert("Please select a category")
        return
    }

    await budgetStore.addBudget({
        category_id: form.category_id,
        amount: form.amount,
        month: form.month,
        year: form.year,
    })

    form.amount = 0
}

const removeBudget = async (id: number) => {
    const confirmed = confirm(
        "Are you sure you want to delete this budget?"
    )

    if (!confirmed) return

    await budgetStore.removeBudget(id)
}

onMounted(async () => {
    await categoryStore.fetchCategories()
    await budgetStore.fetchBudgets()
})
</script>