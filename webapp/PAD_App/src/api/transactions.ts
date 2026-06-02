import api from './http'

export const getTransactions = async () => {
  const res = await api.get('/transactions')
  return res.data
}