import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';
import { useToast } from '../UI/ToastContainer';
import Input from '../UI/Input';

const Login = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [errors, setErrors] = useState({});
  const [loading, setLoading] = useState(false);
  const { login, user } = useAuth();
  const navigate = useNavigate();
  const toast = useToast();

  useEffect(() => {
    if (user) {
      navigate('/admin/dashboard');
    }
  }, [user, navigate]);

  const validateForm = () => {
    const newErrors = {};
    if (!email.trim()) {
      newErrors.email = 'El email es requerido';
    } else if (!/\S+@\S+\.\S+/.test(email)) {
      newErrors.email = 'Email inválido';
    }
    if (!password) {
      newErrors.password = 'La contraseña es requerida';
    }
    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!validateForm()) {
      toast.warning('Por favor, corrige los errores en el formulario');
      return;
    }

    setLoading(true);
    setErrors({});

    try {
      await login(email, password);
      toast.success('¡Bienvenido!');
      navigate('/admin/dashboard');
    } catch (err) {
      setErrors({ general: 'Credenciales incorrectas. Por favor, intenta de nuevo.' });
      toast.error('Credenciales incorrectas');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div
      style={{
        minHeight: '100vh',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        backgroundColor: 'var(--bg-secondary)',
        padding: '2rem',
      }}
    >
      <div
        style={{
          backgroundColor: 'var(--card-bg)',
          padding: '3rem',
          borderRadius: '16px',
          boxShadow: '0 10px 25px var(--shadow)',
          width: '100%',
          maxWidth: '450px',
          border: '1px solid var(--border-color)',
        }}
      >
        <div style={{ textAlign: 'center', marginBottom: '2rem' }}>
          <div
            style={{
              width: '64px',
              height: '64px',
              borderRadius: '50%',
              backgroundColor: 'var(--accent-primary)',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              margin: '0 auto 1rem',
            }}
          >
            <i className="fas fa-lock" style={{ fontSize: '1.5rem', color: 'white' }}></i>
          </div>
          <h1 style={{ textAlign: 'center', marginBottom: '0.5rem', color: 'var(--text-primary)' }}>
            Iniciar Sesión
          </h1>
          <p style={{ color: 'var(--text-secondary)', fontSize: '0.95rem' }}>
            Accede al panel de administración
          </p>
        </div>

        <form onSubmit={handleSubmit}>
          {errors.general && (
            <div
              style={{
                padding: '1rem',
                marginBottom: '1.5rem',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                color: '#ef4444',
                borderRadius: '8px',
                display: 'flex',
                alignItems: 'center',
                gap: '0.5rem',
                border: '1px solid rgba(239, 68, 68, 0.2)',
              }}
            >
              <i className="fas fa-exclamation-circle"></i>
              <span>{errors.general}</span>
            </div>
          )}

          <Input
            label="Email"
            type="email"
            value={email}
            onChange={(e) => {
              setEmail(e.target.value);
              if (errors.email) {
                setErrors({ ...errors, email: null });
              }
            }}
            error={errors.email}
            required
            icon="fas fa-envelope"
            placeholder="tu@email.com"
            autoFocus
          />

          <Input
            label="Contraseña"
            type="password"
            value={password}
            onChange={(e) => {
              setPassword(e.target.value);
              if (errors.password) {
                setErrors({ ...errors, password: null });
              }
            }}
            error={errors.password}
            required
            icon="fas fa-lock"
            placeholder="••••••••"
          />

          <button
            type="submit"
            className="btn btn-primary btn-block"
            disabled={loading}
            style={{
              marginTop: '1rem',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              gap: '0.5rem',
            }}
          >
            {loading ? (
              <>
                <i className="fas fa-spinner fa-spin"></i>
                Iniciando sesión...
              </>
            ) : (
              <>
                <i className="fas fa-sign-in-alt"></i>
                Iniciar Sesión
              </>
            )}
          </button>
        </form>

        <div style={{ marginTop: '1.5rem', textAlign: 'center' }}>
          <a
            href="/"
            style={{
              color: 'var(--accent-primary)',
              textDecoration: 'none',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              gap: '0.5rem',
              transition: 'color 0.2s ease',
            }}
            onMouseEnter={(e) => {
              e.target.style.color = 'var(--accent-hover)';
            }}
            onMouseLeave={(e) => {
              e.target.style.color = 'var(--accent-primary)';
            }}
          >
            <i className="fas fa-arrow-left"></i>
            Volver al portfolio
          </a>
        </div>
      </div>
    </div>
  );
};

export default Login;
