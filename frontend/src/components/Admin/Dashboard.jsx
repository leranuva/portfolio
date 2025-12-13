import React, { useState, useEffect } from 'react';
import { clientService } from '../../services/clientService';
import { projectService } from '../../services/projectService';
import { skillService } from '../../services/skillService';

const Dashboard = () => {
  const [stats, setStats] = useState({
    clients: 0,
    projects: 0,
    skills: 0,
    newClients: 0,
  });
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const loadStats = async () => {
      try {
        const [clients, projects, skills] = await Promise.all([
          clientService.getAll(),
          projectService.getAll(),
          skillService.getAll(),
        ]);

        const newClients = clients.filter((c) => c.status === 'new').length;

        setStats({
          clients: clients.length,
          projects: projects.length,
          skills: skills.length,
          newClients,
        });
      } catch (error) {
        console.error('Error loading stats:', error);
      } finally {
        setLoading(false);
      }
    };

    loadStats();
  }, []);

  if (loading) {
    return <div>Cargando estadísticas...</div>;
  }

  const statCards = [
    { title: 'Total Clientes', value: stats.clients, icon: 'fas fa-users', color: '#007bff' },
    { title: 'Nuevos Clientes', value: stats.newClients, icon: 'fas fa-user-plus', color: '#28a745' },
    { title: 'Proyectos', value: stats.projects, icon: 'fas fa-folder', color: '#ffc107' },
    { title: 'Habilidades', value: stats.skills, icon: 'fas fa-code', color: '#dc3545' },
  ];

  return (
    <div>
      <h1 style={{ marginBottom: '2rem', color: 'var(--text-primary)' }}>Dashboard</h1>
      <div
        style={{
          display: 'grid',
          gridTemplateColumns: 'repeat(auto-fit, minmax(250px, 1fr))',
          gap: '1.5rem',
          marginBottom: '2rem',
        }}
      >
        {statCards.map((card, index) => (
          <div
            key={index}
            style={{
              backgroundColor: 'var(--card-bg)',
              padding: '1.5rem',
              borderRadius: '12px',
              boxShadow: '0 4px 6px var(--shadow)',
              border: '1px solid var(--border-color)',
            }}
          >
            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
              <div>
                <div style={{ fontSize: '0.9rem', color: 'var(--text-secondary)', marginBottom: '0.5rem' }}>
                  {card.title}
                </div>
                <div style={{ fontSize: '2rem', fontWeight: 'bold', color: 'var(--text-primary)' }}>
                  {card.value}
                </div>
              </div>
              <div
                style={{
                  width: '60px',
                  height: '60px',
                  borderRadius: '50%',
                  backgroundColor: `${card.color}20`,
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                }}
              >
                <i className={card.icon} style={{ fontSize: '1.5rem', color: card.color }}></i>
              </div>
            </div>
          </div>
        ))}
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
        <h2 style={{ marginBottom: '1rem', color: 'var(--text-primary)' }}>Bienvenido al Panel de Administración</h2>
        <p style={{ color: 'var(--text-secondary)', lineHeight: '1.8' }}>
          Desde aquí puedes gestionar todos los aspectos de tu portfolio:
        </p>
        <ul style={{ marginTop: '1rem', color: 'var(--text-secondary)', lineHeight: '2' }}>
          <li><strong>Clientes:</strong> Gestiona los mensajes y contactos de clientes potenciales</li>
          <li><strong>Proyectos:</strong> Añade, edita y elimina proyectos destacados</li>
          <li><strong>Habilidades:</strong> Administra tu stack tecnológico y niveles de dominio</li>
          <li><strong>Configuración:</strong> Personaliza la información de tu portfolio</li>
        </ul>
      </div>
    </div>
  );
};

export default Dashboard;

