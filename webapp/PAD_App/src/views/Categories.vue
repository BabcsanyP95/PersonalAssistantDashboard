<template>
    <div class="p-4 md:p-6 space-y-6">

        <div>
            <h1 class="text-2xl font-bold">Categories</h1>
            <p class="text-gray-500">
                Manage your income and expense categories
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-6">

            <!-- Form -->

            <div class="bg-white rounded-xl border shadow-sm p-4 space-y-3">

                <h2 class="font-semibold">
                    {{ editingId ? 'Edit Category' : 'New Category' }}
                </h2>

                <input v-model="form.name" placeholder="Category name" class="w-full border rounded p-2" />

                <select v-model="form.type" class="w-full border rounded p-2">
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>

                <input type="color" v-model="form.color" class="w-full h-10" />

                <button @click="save" class="w-full bg-blue-600 text-white rounded p-2">
                    {{ editingId ? 'Save Changes' : 'Create Category' }}
                </button>

            </div>

            <!-- List -->

            <div class="md:col-span-2 bg-white rounded-xl border shadow-sm p-4">

                <div v-for="cat in categoryStore.categories" :key="cat.id"
                    class="flex justify-between items-center py-3 border-b">

                    <div class="flex items-center gap-3">

                        <div class="w-4 h-4 rounded-full" :style="{ background: cat.color }" />

                        <div>

                            <p class="font-medium">
                                {{ cat.name }}
                            </p>

                            <p class="text-xs text-gray-500">
                                {{ cat.type }}
                            </p>

                        </div>

                    </div>

                    <div class="flex gap-3">

                        <button class="text-blue-500" @click="startEdit(cat)">
                            Edit
                        </button>

                        <button class="text-red-500" @click="categoryStore.removeCategory(cat.id)">
                            Delete
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>
</template>

<script setup lang="ts">
import { reactive, ref, onMounted } from 'vue'
import { useCategoryStore } from '@/stores/categories'

const categoryStore = useCategoryStore()

const editingId = ref<number | null>(null)

const form = reactive({
    name: '',
    type: 'expense',
    color: '#3b82f6'
})

const save = async () => {
    if (editingId.value) {
        await categoryStore.updateCategory(editingId.value, form)
        editingId.value = null
    } else {
        await categoryStore.addCategory(form)
    }

    form.name = ''
    form.type = 'expense'
    form.color = '#3b82f6'
}

const startEdit = (cat: any) => {
    editingId.value = cat.id

    form.name = cat.name
    form.type = cat.type
    form.color = cat.color
}

onMounted(() => {
    categoryStore.fetchCategories()
})
</script>