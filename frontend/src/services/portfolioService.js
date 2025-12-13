import api from './api';

export const portfolioService = {
  getPublicData: async () => {
    try {
      const response = await api.get('/portfolio/public');
      return response.data;
    } catch (error) {
      console.error('Error fetching public data:', error);
      // Retornar datos por defecto si falla la API
      return {
        settings: {},
        projects: [],
        skills: {}
      };
    }
  },

  getSettings: async () => {
    const response = await api.get('/portfolio/settings');
    return response.data;
  },

  updateSettings: async (settings) => {
    const response = await api.put('/portfolio/settings', { settings });
    return response.data;
  },

  getSetting: async (key) => {
    const response = await api.get(`/portfolio/settings/${key}`);
    return response.data;
  },

  updateSetting: async (key, value, type = 'text') => {
    const response = await api.put(`/portfolio/settings/${key}`, { value, type });
    return response.data;
  },
};

