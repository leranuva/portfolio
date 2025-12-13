import api from './api';

export const skillService = {
  getAll: async (category = null) => {
    const params = category ? { category } : {};
    const response = await api.get('/skills', { params });
    return response.data;
  },

  getById: async (id) => {
    const response = await api.get(`/skills/${id}`);
    return response.data;
  },

  create: async (data) => {
    const response = await api.post('/skills', data);
    return response.data;
  },

  update: async (id, data) => {
    const response = await api.put(`/skills/${id}`, data);
    return response.data;
  },

  delete: async (id) => {
    const response = await api.delete(`/skills/${id}`);
    return response.data;
  },
};

