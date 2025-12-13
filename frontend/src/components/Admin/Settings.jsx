import React, { useState, useEffect } from 'react';
import { portfolioService } from '../../services/portfolioService';
import { useToast } from '../UI/ToastContainer';
import Input from '../UI/Input';
import Textarea from '../UI/Textarea';

const Settings = () => {
  const [settings, setSettings] = useState([]);
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const toast = useToast();

  useEffect(() => {
    loadSettings();
  }, []);

  const loadSettings = async () => {
    try {
      const data = await portfolioService.getSettings();
      setSettings(data);
    } catch (error) {
      console.error('Error loading settings:', error);
      toast.error('Error al cargar la configuración');
    } finally {
      setLoading(false);
    }
  };

  const handleChange = (key, value) => {
    setSettings(settings.map((s) => (s.key === key ? { ...s, value } : s)));
  };

  const handleSave = async () => {
    setSaving(true);
    try {
      await portfolioService.updateSettings(settings);
      toast.success('Configuración guardada exitosamente');
    } catch (error) {
      console.error('Error saving settings:', error);
      toast.error('Error al guardar la configuración');
    } finally {
      setSaving(false);
    }
  };

  const defaultSettings = [
    { key: 'name', label: 'Nombre', type: 'text', value: '', icon: 'fas fa-user' },
    { key: 'role', label: 'Rol', type: 'text', value: '', icon: 'fas fa-briefcase' },
    { key: 'description', label: 'Descripción', type: 'textarea', value: '', icon: 'fas fa-align-left' },
    { key: 'email', label: 'Email', type: 'email', value: '', icon: 'fas fa-envelope' },
    { key: 'linkedin', label: 'LinkedIn URL', type: 'url', value: '', icon: 'fab fa-linkedin' },
    { key: 'github', label: 'GitHub URL', type: 'url', value: '', icon: 'fab fa-github' },
  ];

  const currentSettings = defaultSettings.map((def) => {
    const existing = settings.find((s) => s.key === def.key);
    return existing || { ...def, value: def.value };
  });

  if (loading) {
    return (
      <div style={{ display: 'flex', justifyContent: 'center', alignItems: 'center', height: '50vh' }}>
        <div style={{ textAlign: 'center' }}>
          <div style={{ fontSize: '2rem', marginBottom: '1rem' }}>⏳</div>
          <div style={{ color: 'var(--text-secondary)' }}>Cargando configuración...</div>
        </div>
      </div>
    );
  }

  return (
    <div>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '2rem' }}>
        <h1 style={{ color: 'var(--text-primary)' }}>Configuración del Portfolio</h1>
        <button
          className="btn btn-primary"
          onClick={handleSave}
          disabled={saving}
          style={{
            display: 'flex',
            alignItems: 'center',
            gap: '0.5rem',
          }}
        >
          {saving ? (
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

      <div
        style={{
          backgroundColor: 'var(--card-bg)',
          padding: '2rem',
          borderRadius: '12px',
          boxShadow: '0 4px 6px var(--shadow)',
          border: '1px solid var(--border-color)',
        }}
      >
        {currentSettings.map((setting) => {
          if (setting.type === 'textarea') {
            return (
              <Textarea
                key={setting.key}
                label={setting.label}
                value={setting.value}
                onChange={(e) => handleChange(setting.key, e.target.value)}
                rows={4}
                icon={setting.icon}
              />
            );
          }
          return (
            <Input
              key={setting.key}
              label={setting.label}
              type={setting.type}
              value={setting.value}
              onChange={(e) => handleChange(setting.key, e.target.value)}
              icon={setting.icon}
              placeholder={`Ingresa tu ${setting.label.toLowerCase()}`}
            />
          );
        })}
      </div>

      <div
        style={{
          marginTop: '2rem',
          padding: '1.5rem',
          backgroundColor: 'var(--bg-secondary)',
          borderRadius: '12px',
          border: '1px solid var(--border-color)',
        }}
      >
        <div style={{ display: 'flex', alignItems: 'start', gap: '1rem' }}>
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
            <i className="fas fa-info-circle" style={{ color: 'white' }}></i>
          </div>
          <div>
            <h3 style={{ color: 'var(--text-primary)', marginBottom: '0.5rem' }}>Información</h3>
            <p style={{ color: 'var(--text-secondary)', lineHeight: '1.8', margin: 0 }}>
              Estos valores se mostrarán en el portfolio público. Asegúrate de mantener la información actualizada.
              Los cambios se reflejarán inmediatamente después de guardar.
            </p>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Settings;
