import api from './api';

export const profileService = {
  update: async (data) => {
    const response = await api.put('/profile', data);
    return response.data;
  },

  updatePassword: async (currentPassword, newPassword, confirmPassword) => {
    const response = await api.put('/profile/password', {
      current_password: currentPassword,
      password: newPassword,
      password_confirmation: confirmPassword,
    });
    return response.data;
  },
};

