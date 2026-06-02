export type TransactionType = 'income' | 'expense'

export interface Transaction {
  id: number
  amount: number
  type: TransactionType
  description: string | null
  transaction_date: string
}