import React, { useState, useEffect } from 'react';
import { skillService } from '../../services/skillService';
import { useToast } from '../UI/ToastContainer';
import Modal from '../UI/Modal';
import ConfirmModal from '../UI/ConfirmModal';
import Input from '../UI/Input';

const Skills = () => {
  const [skills, setSkills] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [showDeleteModal, setShowDeleteModal] = useState(false);
  const [skillToDelete, setSkillToDelete] = useState(null);
  const [editingSkill, setEditingSkill] = useState(null);
  const [formErrors, setFormErrors] = useState({});
  const toast = useToast();
  const [formData, setFormData] = useState({
    name: '',
    category: 'frontend',
    level: 50,
    icon: '',
    order: 0,
  });

  const categories = [
    { value: 'frontend', label: 'Frontend', icon: 'fas fa-paint-brush' },
    { value: 'backend', label: 'Backend', icon: 'fas fa-server' },
    { value: 'database', label: 'Base de Datos', icon: 'fas fa-database' },
    { value: 'devops', label: 'DevOps', icon: 'fas fa-cloud' },
    { value: 'design', label: 'Diseño', icon: 'fas fa-palette' },
  ];

  useEffect(() => {
    loadSkills();
  }, []);

  const loadSkills = async () => {
    try {
      const data = await skillService.getAll();
      setSkills(data);
    } catch (error) {
      console.error('Error loading skills:', error);
      toast.error('Error al cargar las habilidades');
    } finally {
      setLoading(false);
    }
  };

  const validateForm = () => {
    const errors = {};
    if (!formData.name.trim()) {
      errors.name = 'El nombre es requerido';
    }
    if (formData.level < 0 || formData.level > 100) {
      errors.level = 'El nivel debe estar entre 0 y 100';
    }
    setFormErrors(errors);
    return Object.keys(errors).length === 0;
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!validateForm()) {
      toast.warning('Por favor, corrige los errores en el formulario');
      return;
    }

    try {
      if (editingSkill) {
        await skillService.update(editingSkill.id, formData);
        toast.success('Habilidad actualizada exitosamente');
      } else {
        await skillService.create(formData);
        toast.success('Habilidad creada exitosamente');
      }
      setShowModal(false);
      setEditingSkill(null);
      resetForm();
      loadSkills();
    } catch (error) {
      console.error('Error saving skill:', error);
      toast.error('Error al guardar la habilidad');
    }
  };

  const handleEdit = (skill) => {
    setEditingSkill(skill);
    setFormData({
      name: skill.name || '',
      category: skill.category || 'frontend',
      level: skill.level || 50,
      icon: skill.icon || '',
      order: skill.order || 0,
    });
    setFormErrors({});
    setShowModal(true);
  };

  const handleDeleteClick = (id) => {
    setSkillToDelete(id);
    setShowDeleteModal(true);
  };

  const handleDeleteConfirm = async () => {
    try {
      await skillService.delete(skillToDelete);
      toast.success('Habilidad eliminada exitosamente');
      loadSkills();
    } catch (error) {
      console.error('Error deleting skill:', error);
      toast.error('Error al eliminar la habilidad');
    }
    setShowDeleteModal(false);
    setSkillToDelete(null);
  };

  const resetForm = () => {
    setFormData({
      name: '',
      category: 'frontend',
      level: 50,
      icon: '',
      order: 0,
    });
    setFormErrors({});
    setEditingSkill(null);
  };

  const skillsByCategory = skills.reduce((acc, skill) => {
    if (!acc[skill.category]) {
      acc[skill.category] = [];
    }
    acc[skill.category].push(skill);
    return acc;
  }, {});

  if (loading) {
    return (
      <div style={{ display: 'flex', justifyContent: 'center', alignItems: 'center', height: '50vh' }}>
        <div style={{ textAlign: 'center' }}>
          <div style={{ fontSize: '2rem', marginBottom: '1rem' }}>⏳</div>
          <div style={{ color: 'var(--text-secondary)' }}>Cargando habilidades...</div>
        </div>
      </div>
    );
  }

  return (
    <div>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '2rem' }}>
        <h1 style={{ color: 'var(--text-primary)' }}>Habilidades</h1>
        <button
          className="btn btn-primary"
          onClick={() => {
            resetForm();
            setShowModal(true);
          }}
          style={{
            display: 'flex',
            alignItems: 'center',
            gap: '0.5rem',
          }}
        >
          <i className="fas fa-plus"></i>
          Nueva Habilidad
        </button>
      </div>

      {Object.entries(skillsByCategory).map(([category, categorySkills]) => {
        const categoryInfo = categories.find((c) => c.value === category);
        return (
          <div key={category} style={{ marginBottom: '2rem' }}>
            <div
              style={{
                display: 'flex',
                alignItems: 'center',
                gap: '1rem',
                marginBottom: '1rem',
                paddingBottom: '1rem',
                borderBottom: '2px solid var(--border-color)',
              }}
            >
              <i
                className={categoryInfo?.icon || 'fas fa-code'}
                style={{ fontSize: '1.5rem', color: 'var(--accent-primary)' }}
              ></i>
              <h2 style={{ color: 'var(--text-primary)', margin: 0 }}>
                {categoryInfo?.label || category}
              </h2>
            </div>
            <div style={{ display: 'grid', gap: '1rem' }}>
              {categorySkills.map((skill) => (
                <div
                  key={skill.id}
                  style={{
                    backgroundColor: 'var(--card-bg)',
                    padding: '1.5rem',
                    borderRadius: '12px',
                    boxShadow: '0 4px 6px var(--shadow)',
                    border: '1px solid var(--border-color)',
                    display: 'flex',
                    justifyContent: 'space-between',
                    alignItems: 'center',
                    transition: 'all 0.3s ease',
                  }}
                  onMouseEnter={(e) => {
                    e.currentTarget.style.transform = 'translateY(-2px)';
                    e.currentTarget.style.boxShadow = '0 8px 15px var(--shadow-hover)';
                  }}
                  onMouseLeave={(e) => {
                    e.currentTarget.style.transform = 'translateY(0)';
                    e.currentTarget.style.boxShadow = '0 4px 6px var(--shadow)';
                  }}
                >
                  <div style={{ flex: 1 }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: '1rem', marginBottom: '0.5rem' }}>
                      <h3 style={{ color: 'var(--text-primary)', margin: 0 }}>{skill.name}</h3>
                      <span
                        style={{
                          color: 'var(--text-secondary)',
                          fontSize: '0.9rem',
                          fontWeight: '600',
                        }}
                      >
                        {skill.level}%
                      </span>
                    </div>
                    <div className="skill-level" style={{ width: '100%', maxWidth: '400px' }}>
                      <div
                        className="skill-bar"
                        style={{ width: `${skill.level}%` }}
                      ></div>
                    </div>
                  </div>
                  <div style={{ display: 'flex', gap: '0.5rem' }}>
                    <button
                      className="btn btn-secondary"
                      onClick={() => handleEdit(skill)}
                      style={{
                        padding: '0.5rem 1rem',
                        fontSize: '0.9rem',
                      }}
                    >
                      <i className="fas fa-edit"></i>
                    </button>
                    <button
                      onClick={() => handleDeleteClick(skill.id)}
                      style={{
                        padding: '0.5rem 1rem',
                        backgroundColor: '#ef4444',
                        color: 'white',
                        border: 'none',
                        borderRadius: '8px',
                        cursor: 'pointer',
                        fontSize: '0.9rem',
                        transition: 'all 0.2s ease',
                      }}
                      onMouseEnter={(e) => {
                        e.target.style.backgroundColor = '#dc2626';
                        e.target.style.transform = 'translateY(-1px)';
                      }}
                      onMouseLeave={(e) => {
                        e.target.style.backgroundColor = '#ef4444';
                        e.target.style.transform = 'translateY(0)';
                      }}
                    >
                      <i className="fas fa-trash"></i>
                    </button>
                  </div>
                </div>
              ))}
            </div>
          </div>
        );
      })}

      {skills.length === 0 && (
        <div
          style={{
            textAlign: 'center',
            padding: '4rem 2rem',
            color: 'var(--text-secondary)',
            backgroundColor: 'var(--card-bg)',
            borderRadius: '12px',
            border: '1px solid var(--border-color)',
          }}
        >
          <div style={{ fontSize: '3rem', marginBottom: '1rem' }}>💻</div>
          <h3 style={{ color: 'var(--text-primary)', marginBottom: '0.5rem' }}>No hay habilidades</h3>
          <p>Crea tu primera habilidad para comenzar</p>
        </div>
      )}

      {/* Modal de Edición/Creación */}
      <Modal
        isOpen={showModal}
        onClose={() => {
          setShowModal(false);
          resetForm();
        }}
        title={editingSkill ? 'Editar Habilidad' : 'Nueva Habilidad'}
        size="medium"
      >
        <form onSubmit={handleSubmit}>
          <Input
            label="Nombre"
            value={formData.name}
            onChange={(e) => {
              setFormData({ ...formData, name: e.target.value });
              if (formErrors.name) {
                setFormErrors({ ...formErrors, name: null });
              }
            }}
            error={formErrors.name}
            required
            icon="fas fa-tag"
            placeholder="Ej: React, Node.js, MongoDB"
          />

          <div style={{ marginBottom: '1.5rem' }}>
            <label
              style={{
                display: 'block',
                marginBottom: '0.5rem',
                color: 'var(--text-primary)',
                fontWeight: '500',
                fontSize: '0.95rem',
              }}
            >
              Categoría <span style={{ color: '#ef4444' }}>*</span>
            </label>
            <div
              style={{
                display: 'grid',
                gridTemplateColumns: 'repeat(auto-fit, minmax(150px, 1fr))',
                gap: '0.75rem',
              }}
            >
              {categories.map((cat) => (
                <label
                  key={cat.value}
                  style={{
                    display: 'flex',
                    alignItems: 'center',
                    gap: '0.75rem',
                    padding: '0.75rem',
                    borderRadius: '8px',
                    border: `2px solid ${
                      formData.category === cat.value ? 'var(--accent-primary)' : 'var(--border-color)'
                    }`,
                    cursor: 'pointer',
                    transition: 'all 0.2s ease',
                    backgroundColor:
                      formData.category === cat.value ? 'var(--bg-secondary)' : 'transparent',
                  }}
                  onMouseEnter={(e) => {
                    if (formData.category !== cat.value) {
                      e.currentTarget.style.borderColor = 'var(--accent-primary)';
                      e.currentTarget.style.backgroundColor = 'var(--bg-secondary)';
                    }
                  }}
                  onMouseLeave={(e) => {
                    if (formData.category !== cat.value) {
                      e.currentTarget.style.borderColor = 'var(--border-color)';
                      e.currentTarget.style.backgroundColor = 'transparent';
                    }
                  }}
                >
                  <input
                    type="radio"
                    name="category"
                    value={cat.value}
                    checked={formData.category === cat.value}
                    onChange={(e) => setFormData({ ...formData, category: e.target.value })}
                    style={{ cursor: 'pointer' }}
                  />
                  <i className={cat.icon} style={{ color: 'var(--accent-primary)' }}></i>
                  <span style={{ color: 'var(--text-primary)', fontWeight: '500' }}>{cat.label}</span>
                </label>
              ))}
            </div>
          </div>

          <div style={{ marginBottom: '1.5rem' }}>
            <label
              style={{
                display: 'block',
                marginBottom: '0.5rem',
                color: 'var(--text-primary)',
                fontWeight: '500',
                fontSize: '0.95rem',
              }}
            >
              Nivel: <strong style={{ color: 'var(--accent-primary)' }}>{formData.level}%</strong>
            </label>
            <input
              type="range"
              min="0"
              max="100"
              value={formData.level}
              onChange={(e) => {
                setFormData({ ...formData, level: parseInt(e.target.value) });
                if (formErrors.level) {
                  setFormErrors({ ...formErrors, level: null });
                }
              }}
              style={{
                width: '100%',
                height: '8px',
                borderRadius: '4px',
                background: 'var(--bg-secondary)',
                outline: 'none',
                cursor: 'pointer',
              }}
            />
            {formErrors.level && (
              <div style={{ marginTop: '0.5rem', color: '#ef4444', fontSize: '0.875rem' }}>
                {formErrors.level}
              </div>
            )}
            <div
              style={{
                display: 'flex',
                justifyContent: 'space-between',
                marginTop: '0.5rem',
                fontSize: '0.75rem',
                color: 'var(--text-secondary)',
              }}
            >
              <span>0%</span>
              <span>50%</span>
              <span>100%</span>
            </div>
          </div>

          <Input
            label="Icono (opcional)"
            value={formData.icon}
            onChange={(e) => setFormData({ ...formData, icon: e.target.value })}
            icon="fas fa-icons"
            placeholder="fas fa-code"
          />

          <Input
            label="Orden"
            type="number"
            value={formData.order}
            onChange={(e) => setFormData({ ...formData, order: parseInt(e.target.value) || 0 })}
            min="0"
            icon="fas fa-sort-numeric-down"
          />

          <div style={{ display: 'flex', gap: '1rem', marginTop: '2rem' }}>
            <button
              type="submit"
              className="btn btn-primary"
              style={{
                flex: 1,
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                gap: '0.5rem',
              }}
            >
              <i className={editingSkill ? 'fas fa-save' : 'fas fa-plus'}></i>
              {editingSkill ? 'Actualizar Habilidad' : 'Crear Habilidad'}
            </button>
            <button
              type="button"
              className="btn btn-secondary"
              onClick={() => {
                setShowModal(false);
                resetForm();
              }}
              style={{ flex: 1 }}
            >
              Cancelar
            </button>
          </div>
        </form>
      </Modal>

      {/* Modal de Confirmación de Eliminación */}
      <ConfirmModal
        isOpen={showDeleteModal}
        onClose={() => {
          setShowDeleteModal(false);
          setSkillToDelete(null);
        }}
        onConfirm={handleDeleteConfirm}
        title="Eliminar Habilidad"
        message="¿Estás seguro de que deseas eliminar esta habilidad? Esta acción no se puede deshacer."
        confirmText="Eliminar"
        cancelText="Cancelar"
        type="danger"
      />
    </div>
  );
};

export default Skills;
