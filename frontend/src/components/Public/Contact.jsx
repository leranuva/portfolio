import React, { useState, useCallback, useMemo } from 'react';
import { useToast } from '../UI/ToastContainer';
import Input from '../UI/Input';
import Textarea from '../UI/Textarea';

const Contact = ({ settings, onSubmit }) => {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    phone: '',
    company: '',
    message: '',
  });
  const [formErrors, setFormErrors] = useState({});
  const [submitting, setSubmitting] = useState(false);
  const toast = useToast();

  // Memoizar valores de settings
  const email = useMemo(() => settings?.email || 'tu.email@ejemplo.com', [settings?.email]);
  const linkedin = useMemo(() => settings?.linkedin || 'https://linkedin.com/in/tu-perfil', [settings?.linkedin]);
  const github = useMemo(() => settings?.github || 'https://github.com/tu-usuario', [settings?.github]);

  const validateForm = useCallback(() => {
    const errors = {};
    if (!formData.name.trim()) {
      errors.name = 'El nombre es requerido';
    }
    if (!formData.email.trim()) {
      errors.email = 'El email es requerido';
    } else if (!/\S+@\S+\.\S+/.test(formData.email)) {
      errors.email = 'Email inválido';
    }
    if (!formData.message.trim()) {
      errors.message = 'El mensaje es requerido';
    }
    setFormErrors(errors);
    return Object.keys(errors).length === 0;
  }, [formData.name, formData.email, formData.message]);

  const handleChange = useCallback((e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({
      ...prev,
      [name]: value,
    }));
    // Limpiar error del campo cuando el usuario empiece a escribir
    setFormErrors((prev) => {
      if (prev[name]) {
        return { ...prev, [name]: null };
      }
      return prev;
    });
  }, []);

  const handleSubmit = useCallback(async (e) => {
    e.preventDefault();
    if (!validateForm()) {
      toast.warning('Por favor, completa todos los campos requeridos');
      return;
    }

    setSubmitting(true);

    try {
      const result = await onSubmit(formData);
      if (result.success) {
        toast.success(result.message || '¡Gracias por tu mensaje! Te responderé pronto.');
        setFormData({
          name: '',
          email: '',
          phone: '',
          company: '',
          message: '',
        });
        setFormErrors({});
      } else {
        toast.error(result.message || 'Error al enviar el mensaje');
      }
    } catch (error) {
      console.error('Error sending message:', error);
      toast.error('Error al enviar el mensaje. Por favor, intenta de nuevo.');
    } finally {
      setSubmitting(false);
    }
  }, [formData, validateForm, onSubmit, toast]);

  return (
    <section id="contact" className="contact">
      <div className="container">
        <h2 className="section-title">Contacto</h2>
        <p className="contact-subtitle">
          ¿Tienes un proyecto en mente? Me encantaría escucharlo.
          Estoy siempre abierto a nuevas oportunidades y colaboraciones.
        </p>
        <div className="contact-content">
          <div className="contact-info">
            <div className="contact-item">
              <div className="contact-icon">
                <i className="fas fa-envelope"></i>
              </div>
              <div className="contact-details">
                <h3>Email</h3>
                <a href={`mailto:${email}`}>{email}</a>
              </div>
            </div>
            <div className="contact-item">
              <div className="contact-icon">
                <i className="fab fa-linkedin"></i>
              </div>
              <div className="contact-details">
                <h3>LinkedIn</h3>
                <a href={linkedin} target="_blank" rel="noopener noreferrer">
                  {linkedin.replace('https://', '')}
                </a>
              </div>
            </div>
            <div className="contact-item">
              <div className="contact-icon">
                <i className="fab fa-github"></i>
              </div>
              <div className="contact-details">
                <h3>GitHub</h3>
                <a href={github} target="_blank" rel="noopener noreferrer">
                  {github.replace('https://', '')}
                </a>
              </div>
            </div>
          </div>
          <div className="contact-form-container">
            <form className="contact-form" onSubmit={handleSubmit}>
              <Input
                label="Nombre"
                name="name"
                value={formData.name}
                onChange={handleChange}
                error={formErrors.name}
                required
                icon="fas fa-user"
                placeholder="Tu nombre completo"
              />
              <Input
                label="Email"
                type="email"
                name="email"
                value={formData.email}
                onChange={handleChange}
                error={formErrors.email}
                required
                icon="fas fa-envelope"
                placeholder="tu@email.com"
              />
              <Input
                label="Teléfono (opcional)"
                type="tel"
                name="phone"
                value={formData.phone}
                onChange={handleChange}
                icon="fas fa-phone"
                placeholder="+34 123 456 789"
              />
              <Input
                label="Empresa (opcional)"
                name="company"
                value={formData.company}
                onChange={handleChange}
                icon="fas fa-building"
                placeholder="Nombre de tu empresa"
              />
              <Textarea
                label="Mensaje"
                name="message"
                value={formData.message}
                onChange={handleChange}
                error={formErrors.message}
                required
                rows={5}
                placeholder="Cuéntame sobre tu proyecto..."
              />
              <button
                type="submit"
                className="btn btn-primary btn-block"
                disabled={submitting}
                style={{
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  gap: '0.5rem',
                }}
              >
                {submitting ? (
                  <>
                    <i className="fas fa-spinner fa-spin"></i>
                    Enviando...
                  </>
                ) : (
                  <>
                    <i className="fas fa-paper-plane"></i>
                    Enviar Mensaje
                  </>
                )}
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>
  );
};

export default React.memo(Contact);
