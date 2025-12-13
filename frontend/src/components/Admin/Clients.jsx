import React, { useState, useEffect } from 'react';
import { clientService } from '../../services/clientService';
import { useToast } from '../UI/ToastContainer';
import ConfirmModal from '../UI/ConfirmModal';

const Clients = () => {
  const [clients, setClients] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showDeleteModal, setShowDeleteModal] = useState(false);
  const [clientToDelete, setClientToDelete] = useState(null);
  const [filter, setFilter] = useState('all');
  const toast = useToast();

  useEffect(() => {
    loadClients();
  }, []);

  const loadClients = async () => {
    try {
      const data = await clientService.getAll();
      setClients(data);
    } catch (error) {
      console.error('Error loading clients:', error);
      toast.error('Error al cargar los clientes');
    } finally {
      setLoading(false);
    }
  };

  const handleStatusChange = async (client, newStatus) => {
    try {
      await clientService.update(client.id, { ...client, status: newStatus });
      toast.success('Estado actualizado exitosamente');
      loadClients();
    } catch (error) {
      console.error('Error updating status:', error);
      toast.error('Error al actualizar el estado');
    }
  };

  const handleDeleteClick = (id) => {
    setClientToDelete(id);
    setShowDeleteModal(true);
  };

  const handleDeleteConfirm = async () => {
    try {
      await clientService.delete(clientToDelete);
      toast.success('Cliente eliminado exitosamente');
      loadClients();
    } catch (error) {
      console.error('Error deleting client:', error);
      toast.error('Error al eliminar el cliente');
    }
    setShowDeleteModal(false);
    setClientToDelete(null);
  };

  const filteredClients = filter === 'all' ? clients : clients.filter((c) => c.status === filter);

  const statusColors = {
    new: { bg: '#3b82f6', text: 'white', label: 'Nuevo' },
    contacted: { bg: '#f59e0b', text: 'white', label: 'Contactado' },
    in_progress: { bg: '#06b6d4', text: 'white', label: 'En Progreso' },
    completed: { bg: '#10b981', text: 'white', label: 'Completado' },
  };

  if (loading) {
    return (
      <div style={{ display: 'flex', justifyContent: 'center', alignItems: 'center', height: '50vh' }}>
        <div style={{ textAlign: 'center' }}>
          <div style={{ fontSize: '2rem', marginBottom: '1rem' }}>⏳</div>
          <div style={{ color: 'var(--text-secondary)' }}>Cargando clientes...</div>
        </div>
      </div>
    );
  }

  return (
    <div>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '2rem' }}>
        <h1 style={{ color: 'var(--text-primary)' }}>Clientes</h1>
        <div style={{ display: 'flex', gap: '0.5rem' }}>
          <button
            className={`btn ${filter === 'all' ? 'btn-primary' : 'btn-secondary'}`}
            onClick={() => setFilter('all')}
            style={{ padding: '0.5rem 1rem', fontSize: '0.9rem' }}
          >
            Todos ({clients.length})
          </button>
          <button
            className={`btn ${filter === 'new' ? 'btn-primary' : 'btn-secondary'}`}
            onClick={() => setFilter('new')}
            style={{ padding: '0.5rem 1rem', fontSize: '0.9rem' }}
          >
            Nuevos ({clients.filter((c) => c.status === 'new').length})
          </button>
        </div>
      </div>

      <div style={{ display: 'grid', gap: '1rem' }}>
        {filteredClients.map((client) => {
          const statusInfo = statusColors[client.status] || statusColors.new;
          return (
            <div
              key={client.id}
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
              <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'start', marginBottom: '1rem' }}>
                <div style={{ flex: 1 }}>
                  <div style={{ display: 'flex', alignItems: 'center', gap: '1rem', marginBottom: '0.75rem' }}>
                    <h3 style={{ color: 'var(--text-primary)', margin: 0 }}>{client.name}</h3>
                    <span
                      style={{
                        padding: '0.25rem 0.75rem',
                        backgroundColor: statusInfo.bg,
                        color: statusInfo.text,
                        borderRadius: '20px',
                        fontSize: '0.85rem',
                        fontWeight: '500',
                      }}
                    >
                      {statusInfo.label}
                    </span>
                  </div>
                  <div style={{ display: 'flex', flexDirection: 'column', gap: '0.5rem' }}>
                    <p style={{ color: 'var(--text-secondary)', margin: 0, display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
                      <i className="fas fa-envelope" style={{ width: '16px' }}></i>
                      {client.email}
                    </p>
                    {client.phone && (
                      <p style={{ color: 'var(--text-secondary)', margin: 0, display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
                        <i className="fas fa-phone" style={{ width: '16px' }}></i>
                        {client.phone}
                      </p>
                    )}
                    {client.company && (
                      <p style={{ color: 'var(--text-secondary)', margin: 0, display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
                        <i className="fas fa-building" style={{ width: '16px' }}></i>
                        {client.company}
                      </p>
                    )}
                  </div>
                </div>
                <div style={{ display: 'flex', flexDirection: 'column', gap: '0.5rem', alignItems: 'flex-end' }}>
                  <select
                    value={client.status}
                    onChange={(e) => handleStatusChange(client, e.target.value)}
                    style={{
                      padding: '0.5rem 1rem',
                      borderRadius: '8px',
                      border: '1px solid var(--border-color)',
                      backgroundColor: 'var(--bg-primary)',
                      color: 'var(--text-primary)',
                      cursor: 'pointer',
                      fontSize: '0.9rem',
                      fontWeight: '500',
                      transition: 'all 0.2s ease',
                    }}
                    onMouseEnter={(e) => {
                      e.target.style.borderColor = 'var(--accent-primary)';
                    }}
                    onMouseLeave={(e) => {
                      e.target.style.borderColor = 'var(--border-color)';
                    }}
                  >
                    {Object.entries(statusColors).map(([value, info]) => (
                      <option key={value} value={value}>
                        {info.label}
                      </option>
                    ))}
                  </select>
                  <button
                    onClick={() => handleDeleteClick(client.id)}
                    style={{
                      padding: '0.5rem 1rem',
                      backgroundColor: '#ef4444',
                      color: 'white',
                      border: 'none',
                      borderRadius: '8px',
                      cursor: 'pointer',
                      fontSize: '0.85rem',
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
              {client.message && (
                <div
                  style={{
                    padding: '1rem',
                    backgroundColor: 'var(--bg-secondary)',
                    borderRadius: '8px',
                    marginTop: '1rem',
                  }}
                >
                  <p style={{ color: 'var(--text-primary)', margin: 0, lineHeight: '1.6' }}>{client.message}</p>
                </div>
              )}
              <div style={{ marginTop: '1rem', fontSize: '0.85rem', color: 'var(--text-secondary)' }}>
                <i className="fas fa-clock" style={{ marginRight: '0.5rem' }}></i>
                Recibido: {new Date(client.created_at).toLocaleDateString('es-ES', {
                  year: 'numeric',
                  month: 'long',
                  day: 'numeric',
                  hour: '2-digit',
                  minute: '2-digit',
                })}
              </div>
            </div>
          );
        })}
      </div>

      {filteredClients.length === 0 && (
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
          <div style={{ fontSize: '3rem', marginBottom: '1rem' }}>👥</div>
          <h3 style={{ color: 'var(--text-primary)', marginBottom: '0.5rem' }}>
            No hay clientes {filter !== 'all' ? `con estado "${statusColors[filter]?.label}"` : ''}
          </h3>
          <p>Los mensajes del formulario de contacto aparecerán aquí</p>
        </div>
      )}

      {/* Modal de Confirmación de Eliminación */}
      <ConfirmModal
        isOpen={showDeleteModal}
        onClose={() => {
          setShowDeleteModal(false);
          setClientToDelete(null);
        }}
        onConfirm={handleDeleteConfirm}
        title="Eliminar Cliente"
        message="¿Estás seguro de que deseas eliminar este cliente? Esta acción no se puede deshacer."
        confirmText="Eliminar"
        cancelText="Cancelar"
        type="danger"
      />
    </div>
  );
};

export default Clients;
