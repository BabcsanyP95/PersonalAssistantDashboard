import api from './http'

export const getTransactions = async () => {
    const res = await api.get('/transactions')
    return res.data
}

export const createTransaction = async (data: {
    amount: number
    type: 'income' | 'expense'
    description: string
}) => {
    const res = await api.post('/transactions', data)
    return res.data
}

export const updateTransaction = async (id: number, data: any) => {
    const res = await api.put(`/transactions/${id}`, data)
    return res.data
}

export const deleteTransaction = async (id: number) => {
    const res = await api.delete(`/transactions/${id}`)
    return res.data
}