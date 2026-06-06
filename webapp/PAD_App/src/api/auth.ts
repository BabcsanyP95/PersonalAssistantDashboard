import api from './http'

export const login = async (payload: {
    email: string
    password: string
}) => {
    const res = await api.post('/login', payload)
    return res.data
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

export const getUser = async () => {
    const res = await api.get('/user')
    return res.data
}

export const logout = async () => {
    const res = await api.post('/logout')
    return res.data
}