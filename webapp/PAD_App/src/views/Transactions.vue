<template>
    <div class="p-4 md:p-6 space-y-6">

        <div>
            <h1 class="text-2xl font-bold">
                Transactions
            </h1>

            <p class="text-gray-500">
                Manage your income and expenses
            </p>
        </div>

        <div class="bg-white p-4 rounded-xl border shadow-sm">

            <label class="block text-sm font-medium mb-2">
                Filter by Month
            </label>

            <input type="month" v-model="selectedMonth" class="border rounded p-2" />

        </div>


        <div class="bg-white p-4 rounded-xl border shadow-sm space-y-3">

            <h2 class="text-lg font-semibold">Add Transaction</h2>

            <input v-model="form.description" placeholder="Description" class="w-full border p-2 rounded" />

            <input v-model.number="form.amount" type="number" placeholder="Amount" class="w-full border p-2 rounded" />

            <p v-if="selectedCategoryObject" class="text-sm text-gray-500">
                Type:
                <span :class="selectedCategoryObject.type === 'income'
                    ? 'text-green-600'
                    : 'text-red-500'">
                    {{ selectedCategoryObject.type }}
                </span>
            </p>

            <select v-model="form.category_id" class="w-full border p-2 rounded">
                <option disabled value="">Select category</option>

                <option v-for="cat in categoryStore.categories" :key="cat.id" :value="cat.id">
                    {{ cat.name }}
                </option>
            </select>


            <button @click="submit" class="w-full md:w-auto bg-blue-600 text-white px-4 py-2 rounded">
                Add
            </button>

        </div>

        <div class="mb-4">

            <select v-model="selectedCategory" class="w-full border p-2 rounded">
                <option :value="null">All categories</option>

                <option v-for="cat in categoryStore.categories" :key="cat.id" :value="cat.id">
                    {{ cat.name }}
                </option>

            </select>

        </div>

        <p v-if="selectedCategory" class="text-sm text-gray-500 mb-2">
            Filtering by:
            {{
                categoryStore.categories.find(c => c.id === selectedCategory)?.name
            }}
        </p>
        <!-- Transactions -->
        <div class="bg-white p-4 rounded-xl border shadow-sm">

            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2">
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
            <p v-else-if="filteredTransactions.length === 0" class="text-gray-500">
                No transactions found
            </p>


            <div v-if="editingId" class="bg-gray-100 p-4 rounded-xl mb-4 space-y-2">

                <h2 class="font-semibold">Edit Transaction</h2>

                <input v-model="editForm.description" class="border p-2 w-full rounded" />
                <input v-model.number="editForm.amount" class="border p-2 w-full rounded" />

                <p v-if="selectedEditCategory" class="text-sm text-gray-500">
                    Type:
                    <span :class="selectedEditCategory.type === 'income'
                        ? 'text-green-600'
                        : 'text-red-500'">
                        {{ selectedEditCategory.type }}
                    </span>
                </p>

                <select v-model="editForm.category_id" class="w-full border p-2 rounded">
                    <option disabled value="">Select category</option>

                    <option v-for="cat in categoryStore.categories" :key="cat.id" :value="cat.id">
                        {{ cat.name }}
                    </option>
                </select>

                <div class="flex flex-col md:flex-row gap-2">
                    <button @click="saveEdit" class="bg-blue-600 text-white px-3 py-1 rounded">
                        Save
                    </button>

                    <button @click="editingId = null" class="text-gray-600">
                        Cancel
                    </button>
                </div>

            </div>
            <!-- List -->
            <div v-else class="space-y-3">

                <div v-for="tx in filteredTransactions" :key="tx.id"
                    class="bg-white border rounded-xl p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 hover:shadow-sm transition">

                    <div class="flex flex-col">

                        <p class="font-medium text-gray-900">
                            {{ tx.description }}
                        </p>

                        <span v-if="tx.category"
                            class="inline-block mt-1 text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600 w-fit">
                            {{ tx.category.name }}
                        </span>

                        <p class="text-xs text-gray-400 mt-1">
                            {{ tx.category?.name ?? 'Uncategorized' }} • {{ formatDate(tx.transaction_date) }}
                        </p>

                    </div>

                    <div class="flex items-center justify-between md:justify-end gap-4">


                        <!-- Amount -->
                        <div :class="tx.type === 'income'
                            ? 'text-green-600 font-semibold'
                            : 'text-red-500 font-semibold'" class="text-right">
                            {{ tx.type === 'income' ? '+' : '-' }}${{ tx.amount }}
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2">

                            <button @click="startEdit(tx)" class="text-blue-500 text-sm hover:underline">
                                Edit
                            </button>

                            <button @click="txStore.removeTransaction(tx.id)"
                                class="text-red-500 text-sm hover:underline">
                                Delete
                            </button>

                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>
</template>

<script setup lang="ts">
import { computed, onMounted, reactive, ref } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useTransactionStore } from '../stores/transactions'
import { useCategoryStore } from '../stores/categories'

const auth = useAuthStore()
const txStore = useTransactionStore()
const categoryStore = useCategoryStore()

const selectedMonth = ref('')
const selectedCategory = ref<number | null>(null)
const editingId = ref<number | null>(null)

const filteredTransactions = computed(() => {
    let txs = Array.isArray(txStore.transactions)
        ? txStore.transactions.filter(t => t && t.id)
        : []

    if (selectedCategory.value) {
        txs = txs.filter(
            t => t.category_id === selectedCategory.value
        )
    }

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

const selectedCategoryObject = computed(() =>
    categoryStore.categories.find(
        c => c.id === form.category_id
    )
)

const selectedEditCategory = computed(() =>
    categoryStore.categories.find(
        c => c.id === editForm.category_id
    )
)

const format = (n: number) => {
    return n.toLocaleString('en-US')
}

const formatDate = (dateString: string) => {
    if (!dateString) return ''

    return new Intl.DateTimeFormat('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    }).format(new Date(dateString))
}

const form = reactive({
    description: '',
    amount: 0,
    category_id: null as number | null,
    transaction_date: new Date().toISOString().slice(0, 10),
})

const submit = async () => {
    if (
        !form.description ||
        !form.amount ||
        !form.category_id
    ) {
        return
    }

    const category = categoryStore.categories.find(
        c => c.id === form.category_id
    )

    if (!category) return

    await txStore.addTransaction({
        description: form.description,
        amount: Number(form.amount),
        type: category.type,
        category_id: form.category_id,
        transaction_date: form.transaction_date,
    })

    form.description = ''
    form.amount = 0
    form.category_id = null
    form.transaction_date = new Date()
        .toISOString()
        .slice(0, 10)
}

const editForm = reactive({
    description: '',
    amount: 0,
    category_id: null as number | null,
    transaction_date: new Date().toISOString().slice(0, 10),
})

const startEdit = (tx: any) => {
    editingId.value = tx.id

    editForm.description = tx.description
    editForm.amount = Number(tx.amount)
    editForm.category_id = tx.category_id
    editForm.transaction_date =
        tx.transaction_date.split('T')[0]
}

const saveEdit = async () => {
    if (!editingId.value) return

    const category = categoryStore.categories.find(
        c => c.id === editForm.category_id
    )

    if (!category) return

    await txStore.updateTransaction(editingId.value, {
        description: editForm.description,
        amount: Number(editForm.amount),
        type: category.type,
        category_id: editForm.category_id,
        transaction_date: editForm.transaction_date,
    })

    editingId.value = null
}

onMounted(async () => {
    await Promise.all([
        auth.fetchUser(),
        categoryStore.fetchCategories(),
        txStore.fetchTransactions(),
    ])
})
</script>