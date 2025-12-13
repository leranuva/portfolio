import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider, useAuth } from './context/AuthContext';
import ToastContainer from './components/UI/ToastContainer';
import PublicLayout from './components/Public/PublicLayout';
import AdminLayout from './components/Admin/AdminLayout';
import Login from './components/Admin/Login';
import Dashboard from './components/Admin/Dashboard';
import Clients from './components/Admin/Clients';
import Projects from './components/Admin/Projects';
import Skills from './components/Admin/Skills';
import Settings from './components/Admin/Settings';
import Profile from './components/Admin/Profile';

const ProtectedRoute = ({ children }) => {
  const { user, loading } = useAuth();
  
  if (loading) {
    return <div style={{ display: 'flex', justifyContent: 'center', alignItems: 'center', height: '100vh' }}>Cargando...</div>;
  }
  
  return user ? children : <Navigate to="/admin/login" />;
};

function App() {
  return (
    <AuthProvider>
      <Router
        future={{
          v7_startTransition: true,
          v7_relativeSplatPath: true,
        }}
      >
        <Routes>
          {/* Rutas públicas */}
          <Route path="/*" element={<PublicLayout />} />
          
          {/* Rutas de administración */}
          <Route path="/admin/login" element={<Login />} />
          <Route
            path="/admin/*"
            element={
              <ProtectedRoute>
                <AdminLayout />
              </ProtectedRoute>
            }
          >
            <Route path="dashboard" element={<Dashboard />} />
            <Route path="clients" element={<Clients />} />
            <Route path="projects" element={<Projects />} />
            <Route path="skills" element={<Skills />} />
            <Route path="settings" element={<Settings />} />
            <Route path="profile" element={<Profile />} />
          </Route>
        </Routes>
        <ToastContainer />
      </Router>
    </AuthProvider>
  );
}

export default App;

