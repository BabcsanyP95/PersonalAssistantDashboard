<template>

    <div class="p-4 md:p-6 space-y-6">

        <div>

            <h1 class="text-2xl font-bold">
                Savings Goals
            </h1>

            <p class="text-gray-500">
                Save money towards your goals.
            </p>

        </div>

        <!-- Create -->

        <div class="bg-white rounded-xl border shadow-sm p-4 space-y-3">

            <h2 class="font-semibold">
                New Goal
            </h2>

            <input v-model="form.name" placeholder="Goal name" class="w-full border rounded p-2">

            <input v-model.number="form.target_amount" type="number" placeholder="Target amount"
                class="w-full border rounded p-2">

            <input v-model="form.target_date" type="date" class="w-full border rounded p-2">

            <button @click="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                Create Goal
            </button>

        </div>

        <!-- List -->

        <div v-for="goal in savingsStore.goals" :key="goal.id" class="bg-white rounded-xl border shadow-sm p-5">

            <div class="flex justify-between items-center">

                <div>

                    <h2 class="font-semibold text-lg">

                        {{ goal.name }}

                    </h2>

                    <p class="text-gray-500 text-sm">

                        Target:
                        ${{ goal.target_amount }}

                    </p>

                    <p class="text-gray-500 text-sm">

                        Saved:
                        ${{ goal.current_amount }}

                    </p>

                </div>

                <button @click="savingsStore.removeGoal(goal.id)" class="text-red-500">
                    Delete
                </button>

            </div>

            <div class="mt-4 h-3 bg-gray-200 rounded-full overflow-hidden">

                <div class="bg-green-500 h-full" :style="{

                    width: Math.min(
                        Number(goal.current_amount) /
                        Number(goal.target_amount) * 100,
                        100
                    ) + '%'

                }" />

            </div>

            <p class="mt-2 text-sm text-gray-500">

                {{ Math.round(Number(goal.current_amount) / Number(goal.target_amount) * 100) }}%

            </p>

            <div class="mt-4 flex gap-2">
                <input v-model.number="deposits[goal.id]" type="number" min="1" placeholder="Deposit amount"
                    class="flex-1 border rounded p-2">

                <button @click="deposit(goal)" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Deposit
                </button>
            </div>

        </div>

    </div>

</template>

<script setup lang="ts">

import { reactive, onMounted } from "vue"

import { useSavingsStore } from "../stores/savings"
import { useTransactionStore } from "@/stores/transactions"
import { useCategoryStore } from "@/stores/categories"

const txStore = useTransactionStore()
const categoryStore = useCategoryStore()

const savingsStore = useSavingsStore()

const deposits = reactive<Record<number, number>>({})


const form = reactive({

    name: "",

    target_amount: 0,

    target_date: "",

})

const submit = async () => {

    await savingsStore.addGoal({

        ...form,

        current_amount: 0,

    })

    form.name = ""

    form.target_amount = 0

    form.target_date = ""

}

const deposit = async (goal: any) => {
    const amount = Number(deposits[goal.id] || 0)

    if (amount <= 0) return

    const categoryName = `Savings: ${goal.name}`

    let category = categoryStore.categories.find(
        c => c.name === categoryName
    )

    if (!category) {
        category = await categoryStore.addCategory({
            name: categoryName,
            type: "expense",
            color: "#22c55e",
        })
    }

    await txStore.addTransaction({
        description: `Savings deposit: ${goal.name}`,
        amount,
        type: "expense",
        category_id: category!.id,
        transaction_date: new Date().toISOString().slice(0, 10),
    })

    await savingsStore.editGoal(goal.id, {
        ...goal,
        current_amount: Number(goal.current_amount) + amount,
    })

    deposits[goal.id] = 0
}

onMounted(async () => {
    await Promise.all([
        savingsStore.fetchGoals(),
        categoryStore.fetchCategories(),
    ])
})

</script>