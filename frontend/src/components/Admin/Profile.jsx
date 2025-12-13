import React, { useState, useEffect, useMemo, useCallback } from 'react';
import { useAuth } from '../../context/AuthContext';
import { profileService } from '../../services/profileService';
import { useToast } from '../UI/ToastContainer';
import Input from '../UI/Input';

const Profile = () => {
  const { user, updateUser } = useAuth();
  const toast = useToast();
  const [activeTab, setActiveTab] = useState('profile');
  const [loading, setLoading] = useState(false);

  // Datos del perfil
  const [profileData, setProfileData] = useState({
    name: '',
    email: '',
  });
  const [profileErrors, setProfileErrors] = useState({});

  // Datos de contraseña
  const [passwordData, setPasswordData] = useState({
    current_password: '',
    password: '',
    password_confirmation: '',
  });
  const [passwordErrors, setPasswordErrors] = useState({});

  useEffect(() => {
    if (user) {
      setProfileData({
        name: user.name || '',
        email: user.email || '',
      });
    }
  }, [user]);

  const validateProfile = () => {
    const errors = {};
    if (!profileData.name.trim()) {
      errors.name = 'El nombre es requerido';
    }
    if (!profileData.email.trim()) {
      errors.email = 'El email es requerido';
    } else if (!/\S+@\S+\.\S+/.test(profileData.email)) {
      errors.email = 'Email inválido';
    }
    setProfileErrors(errors);
    return Object.keys(errors).length === 0;
  };

  const validatePassword = () => {
    const errors = {};
    if (!passwordData.current_password) {
      errors.current_password = 'La contraseña actual es requerida';
    }
    if (!passwordData.password) {
      errors.password = 'La nueva contraseña es requerida';
    } else if (passwordData.password.length < 8) {
      errors.password = 'La contraseña debe tener al menos 8 caracteres';
    }
    if (!passwordData.password_confirmation) {
      errors.password_confirmation = 'Confirma la nueva contraseña';
    } else if (passwordData.password !== passwordData.password_confirmation) {
      errors.password_confirmation = 'Las contraseñas no coinciden';
    }
    setPasswordErrors(errors);
    return Object.keys(errors).length === 0;
  };

  const handleProfileSubmit = async (e) => {
    e.preventDefault();
    if (!validateProfile()) {
      toast.warning('Por favor, corrige los errores en el formulario');
      return;
    }

    setLoading(true);
    try {
      const response = await profileService.update(profileData);
      toast.success('Perfil actualizado exitosamente');
      // Actualizar el usuario en el contexto
      if (response.user) {
        updateUser(response.user);
      }
    } catch (error) {
      console.error('Error updating profile:', error);
      if (error.response?.data?.message) {
        toast.error(error.response.data.message);
      } else {
        toast.error('Error al actualizar el perfil');
      }
    } finally {
      setLoading(false);
    }
  };

  const handlePasswordSubmit = async (e) => {
    e.preventDefault();
    if (!validatePassword()) {
      toast.warning('Por favor, corrige los errores en el formulario');
      return;
    }

    setLoading(true);
    try {
      await profileService.updatePassword(
        passwordData.current_password,
        passwordData.password,
        passwordData.password_confirmation
      );
      toast.success('Contraseña actualizada exitosamente');
      // Limpiar todos los datos y errores completamente
      setPasswordData({
        current_password: '',
        password: '',
        password_confirmation: '',
      });
      // Limpiar todos los errores completamente usando un objeto vacío
      setPasswordErrors({});
    } catch (error) {
      console.error('Error updating password:', error);
      // Limpiar todos los errores primero
      const newErrors = {};
      
      if (error.response?.data) {
        const data = error.response.data;
        
        // Manejar errores de validación
        if (data.errors) {
          Object.keys(data.errors).forEach((key) => {
            if (key === 'password') {
              newErrors.password = Array.isArray(data.errors[key]) ? data.errors[key][0] : data.errors[key];
            } else if (key === 'password_confirmation') {
              newErrors.password_confirmation = Array.isArray(data.errors[key]) ? data.errors[key][0] : data.errors[key];
            } else if (key === 'current_password') {
              newErrors.current_password = Array.isArray(data.errors[key]) ? data.errors[key][0] : data.errors[key];
            }
          });
          setPasswordErrors(newErrors);
          toast.error('Por favor, corrige los errores en el formulario');
        } else if (data.message) {
          const message = data.message;
          toast.error(message);
          if (message.includes('contraseña actual') || message.includes('incorrecta')) {
            newErrors.current_password = message;
          }
          setPasswordErrors(newErrors);
        } else {
          toast.error('Error al actualizar la contraseña');
          setPasswordErrors(newErrors);
        }
      } else {
        toast.error('Error al actualizar la contraseña');
        setPasswordErrors(newErrors);
      }
    } finally {
      setLoading(false);
    }
  };

  const tabs = useMemo(() => [
    { id: 'profile', label: 'Perfil', icon: 'fas fa-user' },
    { id: 'password', label: 'Contraseña', icon: 'fas fa-lock' },
  ], []);

  // Limpiar errores cuando se cambia de pestaña
  const handleTabChange = useCallback((tabId) => {
    setActiveTab(tabId);
    if (tabId === 'password') {
      // Limpiar errores al entrar a la pestaña de contraseña
      setPasswordErrors({});
    }
  }, []);

  // Memoizar el cálculo de la barra de progreso de contraseña
  const passwordStrength = useMemo(() => {
    const length = passwordData.password.length;
    return {
      width: `${Math.min((length / 8) * 100, 100)}%`,
      color: length >= 8 ? '#10b981' : length >= 4 ? '#f59e0b' : '#ef4444',
      isStrong: length >= 8,
    };
  }, [passwordData.password.length]);

  // Memoizar fechas formateadas
  const formattedDates = useMemo(() => {
    if (!user) return { created: 'N/A', updated: 'N/A' };
    
    const formatDate = (dateString) => {
      if (!dateString) return 'N/A';
      return new Date(dateString).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
      });
    };

    return {
      created: formatDate(user.created_at),
      updated: formatDate(user.updated_at),
    };
  }, [user?.created_at, user?.updated_at]);

  // Handlers optimizados con useCallback
  const handleNameChange = useCallback((e) => {
    setProfileData((prev) => ({ ...prev, name: e.target.value }));
    if (profileErrors.name) {
      setProfileErrors((prev) => ({ ...prev, name: null }));
    }
  }, [profileErrors.name]);

  const handleEmailChange = useCallback((e) => {
    setProfileData((prev) => ({ ...prev, email: e.target.value }));
    if (profileErrors.email) {
      setProfileErrors((prev) => ({ ...prev, email: null }));
    }
  }, [profileErrors.email]);

  const handleCurrentPasswordChange = useCallback((e) => {
    setPasswordData((prev) => ({ ...prev, current_password: e.target.value }));
    if (passwordErrors.current_password) {
      setPasswordErrors((prev) => ({ ...prev, current_password: null }));
    }
  }, [passwordErrors.current_password]);

  const handlePasswordChange = useCallback((e) => {
    const newPassword = e.target.value;
    setPasswordData((prev) => ({ ...prev, password: newPassword }));
    if (passwordErrors.password) {
      setPasswordErrors((prev) => ({ ...prev, password: null }));
    }
    // Validar confirmación si ya tiene valor
    if (passwordData.password_confirmation && newPassword !== passwordData.password_confirmation) {
      setPasswordErrors((prev) => ({
        ...prev,
        password_confirmation: 'Las contraseñas no coinciden',
      }));
    } else if (passwordData.password_confirmation) {
      setPasswordErrors((prev) => ({ ...prev, password_confirmation: null }));
    }
  }, [passwordErrors.password, passwordData.password_confirmation]);

  const handlePasswordConfirmationChange = useCallback((e) => {
    const newConfirmation = e.target.value;
    setPasswordData((prev) => ({ ...prev, password_confirmation: newConfirmation }));
    if (passwordErrors.password_confirmation) {
      if (newConfirmation === passwordData.password) {
        setPasswordErrors((prev) => ({ ...prev, password_confirmation: null }));
      } else {
        setPasswordErrors((prev) => ({
          ...prev,
          password_confirmation: 'Las contraseñas no coinciden',
        }));
      }
    }
  }, [passwordErrors.password_confirmation, passwordData.password]);

  return (
    <div>
      <h1 style={{ color: 'var(--text-primary)', marginBottom: '2rem' }}>Mi Perfil</h1>

      {/* Tabs */}
      <div
        style={{
          display: 'flex',
          gap: '0.5rem',
          marginBottom: '2rem',
          borderBottom: '2px solid var(--border-color)',
        }}
      >
        {tabs.map((tab) => (
          <button
            key={tab.id}
            onClick={() => handleTabChange(tab.id)}
            className={activeTab === tab.id ? 'tab-active' : 'tab-inactive'}
            style={{
              padding: '1rem 1.5rem',
              background: 'none',
              border: 'none',
              borderBottom: `3px solid ${activeTab === tab.id ? 'var(--accent-primary)' : 'transparent'}`,
              color: activeTab === tab.id ? 'var(--accent-primary)' : 'var(--text-secondary)',
              fontWeight: activeTab === tab.id ? '600' : '500',
              cursor: 'pointer',
              display: 'flex',
              alignItems: 'center',
              gap: '0.5rem',
              marginBottom: '-2px',
            }}
          >
            <i className={tab.icon}></i>
            {tab.label}
          </button>
        ))}
      </div>

      {/* Tab: Perfil */}
      {activeTab === 'profile' && (
        <div
          style={{
            backgroundColor: 'var(--card-bg)',
            padding: '2rem',
            borderRadius: '12px',
            boxShadow: '0 4px 6px var(--shadow)',
            border: '1px solid var(--border-color)',
          }}
        >
          <div style={{ marginBottom: '2rem' }}>
            <div
              style={{
                width: '100px',
                height: '100px',
                borderRadius: '50%',
                backgroundColor: 'var(--accent-primary)',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                margin: '0 auto 1rem',
                fontSize: '2.5rem',
                color: 'white',
                fontWeight: 'bold',
              }}
            >
              {user?.name?.charAt(0).toUpperCase() || 'A'}
            </div>
            <h2 style={{ textAlign: 'center', color: 'var(--text-primary)', marginBottom: '0.5rem' }}>
              {user?.name || 'Administrador'}
            </h2>
            <p style={{ textAlign: 'center', color: 'var(--text-secondary)' }}>{user?.email || ''}</p>
          </div>

          <form onSubmit={handleProfileSubmit}>
            <Input
              label="Nombre"
              value={profileData.name}
              onChange={handleNameChange}
              error={profileErrors.name}
              required
              icon="fas fa-user"
              placeholder="Tu nombre completo"
            />

            <Input
              label="Email"
              type="email"
              value={profileData.email}
              onChange={handleEmailChange}
              error={profileErrors.email}
              required
              icon="fas fa-envelope"
              placeholder="tu@email.com"
            />

            <div style={{ display: 'flex', gap: '1rem', marginTop: '2rem' }}>
              <button
                type="submit"
                className="btn btn-primary"
                disabled={loading}
                style={{
                  flex: 1,
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  gap: '0.5rem',
                }}
              >
                {loading ? (
                  <>
                    <i className="fas fa-spinner fa-spin"></i>
                    Guardando...
                  </>
                ) : (
                  <>
                    <i className="fas fa-save"></i>
                    Guardar Cambios
                  </>
                )}
              </button>
            </div>
          </form>
        </div>
      )}

      {/* Tab: Contraseña */}
      {activeTab === 'password' && (
        <div
          style={{
            backgroundColor: 'var(--card-bg)',
            padding: '2rem',
            borderRadius: '12px',
            boxShadow: '0 4px 6px var(--shadow)',
            border: '1px solid var(--border-color)',
          }}
        >
          <div
            style={{
              display: 'flex',
              alignItems: 'start',
              gap: '1rem',
              marginBottom: '2rem',
              padding: '1rem',
              backgroundColor: 'var(--bg-secondary)',
              borderRadius: '8px',
            }}
          >
            <div
              style={{
                width: '40px',
                height: '40px',
                borderRadius: '50%',
                backgroundColor: 'var(--accent-primary)',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                flexShrink: 0,
              }}
            >
              <i className="fas fa-shield-alt" style={{ color: 'white' }}></i>
            </div>
            <div>
              <h3 style={{ color: 'var(--text-primary)', marginBottom: '0.5rem' }}>Seguridad de la cuenta</h3>
              <p style={{ color: 'var(--text-secondary)', margin: 0, lineHeight: '1.6' }}>
                Cambia tu contraseña regularmente para mantener tu cuenta segura. Asegúrate de usar una contraseña
                fuerte con al menos 8 caracteres.
              </p>
            </div>
          </div>

          <form onSubmit={handlePasswordSubmit}>
            <Input
              label="Contraseña Actual"
              type="password"
              value={passwordData.current_password}
              onChange={handleCurrentPasswordChange}
              error={passwordErrors.current_password}
              required
              icon="fas fa-lock"
              placeholder="Ingresa tu contraseña actual"
            />

            <Input
              label="Nueva Contraseña"
              type="password"
              value={passwordData.password}
              onChange={handlePasswordChange}
              error={passwordErrors.password}
              required
              icon="fas fa-key"
              placeholder="Mínimo 8 caracteres"
            />

            <div style={{ marginBottom: '1.5rem' }}>
              <div
                style={{
                  height: '4px',
                  backgroundColor: 'var(--bg-secondary)',
                  borderRadius: '2px',
                  marginBottom: '0.5rem',
                  overflow: 'hidden',
                }}
              >
                <div
                  style={{
                    height: '100%',
                    backgroundColor: passwordStrength.color,
                    width: passwordStrength.width,
                    transition: 'width 0.2s ease, background-color 0.2s ease',
                    willChange: 'width, background-color',
                  }}
                ></div>
              </div>
              <div style={{ fontSize: '0.85rem', color: 'var(--text-secondary)' }}>
                {passwordStrength.isStrong ? (
                  <span style={{ color: '#10b981' }}>
                    <i className="fas fa-check-circle"></i> Contraseña segura
                  </span>
                ) : (
                  <span style={{ color: '#ef4444' }}>
                    <i className="fas fa-exclamation-circle"></i> La contraseña debe tener al menos 8 caracteres
                  </span>
                )}
              </div>
            </div>

            <Input
              label="Confirmar Nueva Contraseña"
              type="password"
              value={passwordData.password_confirmation}
              onChange={handlePasswordConfirmationChange}
              error={passwordErrors.password_confirmation}
              required
              icon="fas fa-check-double"
              placeholder="Confirma tu nueva contraseña"
            />

            <div style={{ display: 'flex', gap: '1rem', marginTop: '2rem' }}>
              <button
                type="submit"
                className="btn btn-primary"
                disabled={loading}
                style={{
                  flex: 1,
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  gap: '0.5rem',
                }}
              >
                {loading ? (
                  <>
                    <i className="fas fa-spinner fa-spin"></i>
                    Actualizando...
                  </>
                ) : (
                  <>
                    <i className="fas fa-save"></i>
                    Cambiar Contraseña
                  </>
                )}
              </button>
            </div>
          </form>
        </div>
      )}

      {/* Información adicional */}
      <div
        style={{
          marginTop: '2rem',
          padding: '1.5rem',
          backgroundColor: 'var(--bg-secondary)',
          borderRadius: '12px',
          border: '1px solid var(--border-color)',
        }}
      >
        <h3 style={{ color: 'var(--text-primary)', marginBottom: '1rem' }}>Información de la cuenta</h3>
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))', gap: '1rem' }}>
          <div>
            <div style={{ fontSize: '0.85rem', color: 'var(--text-secondary)', marginBottom: '0.25rem' }}>
              Miembro desde
            </div>
            <div style={{ color: 'var(--text-primary)', fontWeight: '500' }}>
              {formattedDates.created}
            </div>
          </div>
          <div>
            <div style={{ fontSize: '0.85rem', color: 'var(--text-secondary)', marginBottom: '0.25rem' }}>
              Última actualización
            </div>
            <div style={{ color: 'var(--text-primary)', fontWeight: '500' }}>
              {formattedDates.updated}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default React.memo(Profile);

