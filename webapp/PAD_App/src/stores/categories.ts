import { defineStore } from 'pinia'
import {
  getCategories,
  createCategory,
  updateCategory,
  deleteCategory,
} from '../api/categories'

export interface Category {
  id: number
  name: string
  type: 'income' | 'expense'
  color: string
}

export const useCategoryStore = defineStore('categories', {
  state: () => ({
    categories: [] as Category[],
    loading: false,
  }),

  actions: {
    async fetchCategories() {
      this.loading = true

      try {
        const res = await getCategories()

        this.categories = Array.isArray(res)
          ? res
          : res.data ?? []
      } finally {
        this.loading = false
      }
    },

    async addCategory(payload: {
      name: string
      type: 'income' | 'expense'
      color: string
    }) {
      const category = await createCategory(payload)

      this.categories.unshift(category)
      return category;
    },

    async updateCategory(
      id: number,
      payload: {
        name: string
        type: 'income' | 'expense'
        color: string
      }
    ) {
      const updated = await updateCategory(id, payload)

      const index = this.categories.findIndex(c => c.id === id)

      if (index !== -1) {
        this.categories[index] = updated
      }
    },

    async removeCategory(id: number) {
      await deleteCategory(id)

      this.categories = this.categories.filter(
        c => c.id !== id
      )
    },
  },
})