import api from './http'

export const getBudgets = async () => {
    const res = await api.get('/budgets')
    return res.data
}

export const createBudget = async (data: any) => {
    const res = await api.post('/budgets', data)
    return res.data
}

export const updateBudget = async (
    id: number,
    data: any
) => {
    const res = await api.put(`/budgets/${id}`, data)
    return res.data
}

export const deleteBudget = async (id: number) => {
    const res = await api.delete(`/budgets/${id}`)
    return res.data
}