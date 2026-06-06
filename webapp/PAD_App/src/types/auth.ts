import api from "@/api/http"

export interface User {
  id: number
  name: string
  email: string
}

export const register = async (payload: {
    name: string
    email: string
    password: string
    password_confirmation: string
}) => {
    const res = await api.post('/register', payload)

    return res.data
}