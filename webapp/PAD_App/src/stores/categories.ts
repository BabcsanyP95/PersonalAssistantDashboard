import { defineStore } from 'pinia'
import { getCategories } from '../api/categories'

export const useCategoryStore = defineStore('categories', {
  state: () => ({
    categories: [] as { id: number; name: string }[],
  }),

  actions: {
    async fetchCategories() {
      this.categories = await getCategories()
    },
  },
})