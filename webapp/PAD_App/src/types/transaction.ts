export type TransactionType = 'income' | 'expense'

export interface Transaction {
    id: number
    user_id?: number
    category_id: number | null
    type: 'income' | 'expense'
    amount: number | string
    description: string | null
    transaction_date: string
    created_at: string
    updated_at?: string
    category?: {
        id: number
        name: string
        type: 'income' | 'expense'
        color: string
    } | null
}