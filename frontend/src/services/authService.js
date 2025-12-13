import api from './api';

export const authService = {
  login: async (email, password) => {
    const response = await api.post('/login', { email, password });
    if (response.data.token) {
      localStorage.setItem('auth_token', response.data.token);
    }
    return response.data;
  },

  logout: async () => {
    await api.post('/logout');
    localStorage.removeItem('auth_token');
  },

  getUser: async () => {
    const response = await api.get('/user');
    return response.data;
  },
};

