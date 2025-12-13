import React, { useState, useEffect } from 'react';
import { projectService } from '../../services/projectService';
import { useToast } from '../UI/ToastContainer';
import Modal from '../UI/Modal';
import ConfirmModal from '../UI/ConfirmModal';
import Input from '../UI/Input';
import Textarea from '../UI/Textarea';

const Projects = () => {
  const [projects, setProjects] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [showDeleteModal, setShowDeleteModal] = useState(false);
  const [projectToDelete, setProjectToDelete] = useState(null);
  const [editingProject, setEditingProject] = useState(null);
  const [formErrors, setFormErrors] = useState({});
  const toast = useToast();
  const [formData, setFormData] = useState({
    title: '',
    description: '',
    problem_resolution: '',
    my_role: '',
    technologies: [],
    demo_link: '',
    repository_link: '',
    result: '',
    is_featured: false,
    order: 0,
  });

  useEffect(() => {
    loadProjects();
  }, []);

  const loadProjects = async () => {
    try {
      const data = await projectService.getAll();
      setProjects(data);
    } catch (error) {
      console.error('Error loading projects:', error);
      toast.error('Error al cargar los proyectos');
    } finally {
      setLoading(false);
    }
  };

  const validateForm = () => {
    const errors = {};
    if (!formData.title.trim()) {
      errors.title = 'El título es requerido';
    }
    if (!formData.description.trim()) {
      errors.description = 'La descripción es requerida';
    }
    if (formData.demo_link && !isValidUrl(formData.demo_link)) {
      errors.demo_link = 'URL inválida';
    }
    if (formData.repository_link && !isValidUrl(formData.repository_link)) {
      errors.repository_link = 'URL inválida';
    }
    setFormErrors(errors);
    return Object.keys(errors).length === 0;
  };

  const isValidUrl = (string) => {
    try {
      new URL(string);
      return true;
    } catch (_) {
      return false;
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!validateForm()) {
      toast.warning('Por favor, corrige los errores en el formulario');
      return;
    }

    try {
      if (editingProject) {
        await projectService.update(editingProject.id, formData);
        toast.success('Proyecto actualizado exitosamente');
      } else {
        await projectService.create(formData);
        toast.success('Proyecto creado exitosamente');
      }
      setShowModal(false);
      setEditingProject(null);
      resetForm();
      loadProjects();
    } catch (error) {
      console.error('Error saving project:', error);
      toast.error('Error al guardar el proyecto');
    }
  };

  const handleEdit = (project) => {
    setEditingProject(project);
    setFormData({
      title: project.title || '',
      description: project.description || '',
      problem_resolution: project.problem_resolution || '',
      my_role: project.my_role || '',
      technologies: Array.isArray(project.technologies) ? project.technologies : [],
      demo_link: project.demo_link || '',
      repository_link: project.repository_link || '',
      result: project.result || '',
      is_featured: project.is_featured || false,
      order: project.order || 0,
    });
    setFormErrors({});
    setShowModal(true);
  };

  const handleDeleteClick = (id) => {
    setProjectToDelete(id);
    setShowDeleteModal(true);
  };

  const handleDeleteConfirm = async () => {
    try {
      await projectService.delete(projectToDelete);
      toast.success('Proyecto eliminado exitosamente');
      loadProjects();
    } catch (error) {
      console.error('Error deleting project:', error);
      toast.error('Error al eliminar el proyecto');
    }
    setShowDeleteModal(false);
    setProjectToDelete(null);
  };

  const resetForm = () => {
    setFormData({
      title: '',
      description: '',
      problem_resolution: '',
      my_role: '',
      technologies: [],
      demo_link: '',
      repository_link: '',
      result: '',
      is_featured: false,
      order: 0,
    });
    setFormErrors({});
    setEditingProject(null);
  };

  const handleTechChange = (e) => {
    const techs = e.target.value.split(',').map(t => t.trim()).filter(t => t);
    setFormData({ ...formData, technologies: techs });
    if (formErrors.technologies) {
      setFormErrors({ ...formErrors, technologies: null });
    }
  };

  if (loading) {
    return (
      <div style={{ display: 'flex', justifyContent: 'center', alignItems: 'center', height: '50vh' }}>
        <div style={{ textAlign: 'center' }}>
          <div style={{ fontSize: '2rem', marginBottom: '1rem' }}>⏳</div>
          <div style={{ color: 'var(--text-secondary)' }}>Cargando proyectos...</div>
        </div>
      </div>
    );
  }

  return (
    <div>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '2rem' }}>
        <h1 style={{ color: 'var(--text-primary)' }}>Proyectos</h1>
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
          Nuevo Proyecto
        </button>
      </div>

      <div style={{ display: 'grid', gap: '1.5rem' }}>
        {projects.map((project) => (
          <div
            key={project.id}
            style={{
              backgroundColor: 'var(--card-bg)',
              padding: '1.5rem',
              borderRadius: '12px',
              boxShadow: '0 4px 6px var(--shadow)',
              border: '1px solid var(--border-color)',
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
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'start' }}>
              <div style={{ flex: 1 }}>
                <div style={{ display: 'flex', alignItems: 'center', gap: '1rem', marginBottom: '1rem' }}>
                  <h3 style={{ color: 'var(--text-primary)', margin: 0 }}>{project.title}</h3>
                  {project.is_featured && (
                    <span
                      style={{
                        padding: '0.25rem 0.75rem',
                        backgroundColor: 'var(--accent-primary)',
                        color: 'white',
                        borderRadius: '20px',
                        fontSize: '0.85rem',
                        fontWeight: '500',
                      }}
                    >
                      ⭐ Destacado
                    </span>
                  )}
                </div>
                <p style={{ color: 'var(--text-secondary)', marginBottom: '1rem', lineHeight: '1.6' }}>
                  {project.description}
                </p>
                {project.technologies && Array.isArray(project.technologies) && project.technologies.length > 0 && (
                  <div style={{ display: 'flex', flexWrap: 'wrap', gap: '0.5rem', marginBottom: '1rem' }}>
                    {project.technologies.map((tech, index) => (
                      <span key={index} className="tech-tag">
                        {tech}
                      </span>
                    ))}
                  </div>
                )}
              </div>
              <div style={{ display: 'flex', flexDirection: 'column', gap: '0.5rem' }}>
                <button
                  className="btn btn-secondary"
                  onClick={() => handleEdit(project)}
                  style={{
                    padding: '0.5rem 1rem',
                    fontSize: '0.9rem',
                    display: 'flex',
                    alignItems: 'center',
                    gap: '0.5rem',
                  }}
                >
                  <i className="fas fa-edit"></i>
                  Editar
                </button>
                <button
                  onClick={() => handleDeleteClick(project.id)}
                  style={{
                    padding: '0.5rem 1rem',
                    backgroundColor: '#ef4444',
                    color: 'white',
                    border: 'none',
                    borderRadius: '8px',
                    cursor: 'pointer',
                    fontSize: '0.9rem',
                    display: 'flex',
                    alignItems: 'center',
                    gap: '0.5rem',
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
                  Eliminar
                </button>
              </div>
            </div>
          </div>
        ))}
      </div>

      {projects.length === 0 && (
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
          <div style={{ fontSize: '3rem', marginBottom: '1rem' }}>📁</div>
          <h3 style={{ color: 'var(--text-primary)', marginBottom: '0.5rem' }}>No hay proyectos</h3>
          <p>Crea tu primer proyecto para comenzar</p>
        </div>
      )}

      {/* Modal de Edición/Creación */}
      <Modal
        isOpen={showModal}
        onClose={() => {
          setShowModal(false);
          resetForm();
        }}
        title={editingProject ? 'Editar Proyecto' : 'Nuevo Proyecto'}
        size="large"
      >
        <form onSubmit={handleSubmit}>
          <Input
            label="Título"
            value={formData.title}
            onChange={(e) => {
              setFormData({ ...formData, title: e.target.value });
              if (formErrors.title) {
                setFormErrors({ ...formErrors, title: null });
              }
            }}
            error={formErrors.title}
            required
            icon="fas fa-heading"
            placeholder="Nombre del proyecto"
          />

          <Textarea
            label="Descripción"
            value={formData.description}
            onChange={(e) => {
              setFormData({ ...formData, description: e.target.value });
              if (formErrors.description) {
                setFormErrors({ ...formErrors, description: null });
              }
            }}
            error={formErrors.description}
            required
            rows={3}
            placeholder="Describe el proyecto..."
          />

          <Textarea
            label="Problema/Resolución"
            value={formData.problem_resolution}
            onChange={(e) => setFormData({ ...formData, problem_resolution: e.target.value })}
            rows={2}
            placeholder="¿Qué problema resuelve este proyecto?"
          />

          <Textarea
            label="Mi Rol"
            value={formData.my_role}
            onChange={(e) => setFormData({ ...formData, my_role: e.target.value })}
            rows={2}
            placeholder="Tu contribución en el proyecto..."
          />

          <Input
            label="Tecnologías"
            value={formData.technologies.join(', ')}
            onChange={handleTechChange}
            placeholder="React, Node.js, MongoDB (separadas por comas)"
            icon="fas fa-code"
          />

          <Input
            label="Link Demo"
            type="url"
            value={formData.demo_link}
            onChange={(e) => {
              setFormData({ ...formData, demo_link: e.target.value });
              if (formErrors.demo_link) {
                setFormErrors({ ...formErrors, demo_link: null });
              }
            }}
            error={formErrors.demo_link}
            icon="fas fa-external-link-alt"
            placeholder="https://demo.ejemplo.com"
          />

          <Input
            label="Link Repositorio"
            type="url"
            value={formData.repository_link}
            onChange={(e) => {
              setFormData({ ...formData, repository_link: e.target.value });
              if (formErrors.repository_link) {
                setFormErrors({ ...formErrors, repository_link: null });
              }
            }}
            error={formErrors.repository_link}
            icon="fab fa-github"
            placeholder="https://github.com/usuario/proyecto"
          />

          <Textarea
            label="Resultado"
            value={formData.result}
            onChange={(e) => setFormData({ ...formData, result: e.target.value })}
            rows={2}
            placeholder="Resultados o logros del proyecto..."
          />

          <div style={{ marginBottom: '1.5rem' }}>
            <label
              style={{
                display: 'flex',
                alignItems: 'center',
                gap: '0.75rem',
                cursor: 'pointer',
                padding: '0.75rem',
                borderRadius: '8px',
                transition: 'background 0.2s ease',
              }}
              onMouseEnter={(e) => {
                e.currentTarget.style.backgroundColor = 'var(--bg-secondary)';
              }}
              onMouseLeave={(e) => {
                e.currentTarget.style.backgroundColor = 'transparent';
              }}
            >
              <input
                type="checkbox"
                checked={formData.is_featured}
                onChange={(e) => setFormData({ ...formData, is_featured: e.target.checked })}
                style={{
                  width: '20px',
                  height: '20px',
                  cursor: 'pointer',
                }}
              />
              <span style={{ color: 'var(--text-primary)', fontWeight: '500' }}>
                <i className="fas fa-star" style={{ marginRight: '0.5rem', color: '#fbbf24' }}></i>
                Proyecto Destacado
              </span>
            </label>
          </div>

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
              <i className={editingProject ? 'fas fa-save' : 'fas fa-plus'}></i>
              {editingProject ? 'Actualizar Proyecto' : 'Crear Proyecto'}
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
          setProjectToDelete(null);
        }}
        onConfirm={handleDeleteConfirm}
        title="Eliminar Proyecto"
        message="¿Estás seguro de que deseas eliminar este proyecto? Esta acción no se puede deshacer."
        confirmText="Eliminar"
        cancelText="Cancelar"
        type="danger"
      />
    </div>
  );
};

export default Projects;
