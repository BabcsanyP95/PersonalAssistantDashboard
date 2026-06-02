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