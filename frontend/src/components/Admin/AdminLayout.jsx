import React, { useState } from 'react';
import { Outlet, Link, useNavigate, useLocation } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';

const AdminLayout = () => {
  const { user, logout } = useAuth();
  const navigate = useNavigate();
  const location = useLocation();
  const [sidebarOpen, setSidebarOpen] = useState(true);

  const handleLogout = async () => {
    await logout();
    navigate('/admin/login');
  };

  const menuItems = [
    { path: '/admin/dashboard', icon: 'fas fa-chart-line', label: 'Dashboard' },
    { path: '/admin/clients', icon: 'fas fa-users', label: 'Clientes' },
    { path: '/admin/projects', icon: 'fas fa-folder', label: 'Proyectos' },
    { path: '/admin/skills', icon: 'fas fa-code', label: 'Habilidades' },
    { path: '/admin/settings', icon: 'fas fa-cog', label: 'Configuración' },
    { path: '/admin/profile', icon: 'fas fa-user-circle', label: 'Mi Perfil' },
  ];

  return (
    <div style={{ display: 'flex', minHeight: '100vh', backgroundColor: 'var(--bg-secondary)' }}>
      {/* Sidebar */}
      <aside
        style={{
          width: sidebarOpen ? '250px' : '80px',
          backgroundColor: 'var(--card-bg)',
          borderRight: '1px solid var(--border-color)',
          transition: 'width 0.3s ease',
          padding: '1rem',
          position: 'fixed',
          height: '100vh',
          overflowY: 'auto',
        }}
      >
        <div style={{ marginBottom: '2rem', display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
          <h2 style={{ fontSize: '1.5rem', color: 'var(--accent-primary)', display: sidebarOpen ? 'block' : 'none' }}>
            Admin
          </h2>
          <button
            onClick={() => setSidebarOpen(!sidebarOpen)}
            style={{
              background: 'none',
              border: 'none',
              color: 'var(--text-primary)',
              cursor: 'pointer',
              fontSize: '1.2rem',
            }}
          >
            <i className={sidebarOpen ? 'fas fa-chevron-left' : 'fas fa-chevron-right'}></i>
          </button>
        </div>

        <nav style={{ display: 'flex', flexDirection: 'column', gap: '0.5rem' }}>
          {menuItems.map((item) => (
            <Link
              key={item.path}
              to={item.path}
              style={{
                display: 'flex',
                alignItems: 'center',
                gap: '1rem',
                padding: '0.75rem 1rem',
                borderRadius: '8px',
                textDecoration: 'none',
                color: location.pathname === item.path ? 'var(--accent-primary)' : 'var(--text-primary)',
                backgroundColor: location.pathname === item.path ? 'var(--bg-secondary)' : 'transparent',
                transition: 'var(--transition)',
              }}
            >
              <i className={item.icon} style={{ width: '20px', textAlign: 'center' }}></i>
              {sidebarOpen && <span>{item.label}</span>}
            </Link>
          ))}
        </nav>

        <div style={{ marginTop: 'auto', paddingTop: '2rem' }}>
          <div style={{ padding: '1rem', borderTop: '1px solid var(--border-color)', marginBottom: '1rem' }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem', marginBottom: '0.5rem' }}>
              <i className="fas fa-user-circle" style={{ fontSize: '2rem', color: 'var(--accent-primary)' }}></i>
              {sidebarOpen && (
                <div>
                  <div style={{ fontWeight: 'bold', color: 'var(--text-primary)' }}>{user?.name}</div>
                  <div style={{ fontSize: '0.85rem', color: 'var(--text-secondary)' }}>{user?.email}</div>
                </div>
              )}
            </div>
          </div>
          <button
            onClick={handleLogout}
            style={{
              width: '100%',
              padding: '0.75rem',
              backgroundColor: 'var(--accent-primary)',
              color: 'white',
              border: 'none',
              borderRadius: '8px',
              cursor: 'pointer',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              gap: '0.5rem',
            }}
          >
            <i className="fas fa-sign-out-alt"></i>
            {sidebarOpen && <span>Cerrar Sesión</span>}
          </button>
        </div>
      </aside>

      {/* Main Content */}
      <main
        style={{
          marginLeft: sidebarOpen ? '250px' : '80px',
          flex: 1,
          padding: '2rem',
          transition: 'margin-left 0.3s ease',
        }}
      >
        <Outlet />
      </main>
    </div>
  );
};

export default AdminLayout;

