import api from './http'

export const getSavingsGoals = async () => {
    const res = await api.get('/goals')
    return res.data
}

export const createSavingsGoal = async (data: any) => {
    const res = await api.post('/goals', data)
    return res.data
}

export const updateSavingsGoal = async (id: number, data: any) => {
    const res = await api.put(`/goals/${id}`, data)
    return res.data
}

export const depositSavingsGoal = async (id: number, amount: number) => {
    const { data } = await api.post(
        `/goals/${id}/deposit`,
        { amount }
    )

    return data
}

export const deleteSavingsGoal = async (id: number) => {
    const res = await api.delete(`/goals/${id}`)
    return res.data
}