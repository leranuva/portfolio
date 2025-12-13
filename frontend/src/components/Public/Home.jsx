import React, { useState, useEffect, useCallback, useMemo } from 'react';
import { portfolioService } from '../../services/portfolioService';
import { clientService } from '../../services/clientService';
import { useToast } from '../UI/ToastContainer';
import Hero from './Hero';
import Projects from './Projects';
import Skills from './Skills';
import Contact from './Contact';

const Home = () => {
  const [portfolioData, setPortfolioData] = useState(null);
  const [loading, setLoading] = useState(true);
  const toast = useToast();

  useEffect(() => {
    const loadData = async () => {
      try {
        const data = await portfolioService.getPublicData();
        setPortfolioData(data);
      } catch (error) {
        console.error('Error loading portfolio data:', error);
        toast.error('Error al cargar los datos del portfolio');
      } finally {
        setLoading(false);
      }
    };

    loadData();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []); // Solo ejecutar una vez al montar

  const handleContactSubmit = useCallback(async (formData) => {
    try {
      await clientService.create({
        name: formData.name,
        email: formData.email,
        phone: formData.phone || '',
        company: formData.company || '',
        message: formData.message,
        status: 'new',
      });
      return { success: true, message: '¡Gracias por tu mensaje! Te responderé pronto.' };
    } catch (error) {
      console.error('Error sending message:', error);
      return { success: false, message: 'Error al enviar el mensaje. Por favor, intenta de nuevo.' };
    }
  }, []);

  // Memoizar datos para evitar re-renders innecesarios
  const projects = useMemo(() => portfolioData?.projects || [], [portfolioData?.projects]);
  const skills = useMemo(() => portfolioData?.skills || {}, [portfolioData?.skills]);
  const settings = useMemo(() => portfolioData?.settings || null, [portfolioData?.settings]);

  if (loading) {
    return (
      <div style={{ display: 'flex', justifyContent: 'center', alignItems: 'center', height: '100vh', color: 'var(--text-primary)' }}>
        <div style={{ textAlign: 'center' }}>
          <div style={{ fontSize: '2rem', marginBottom: '1rem' }}>⏳</div>
          <div>Cargando portfolio...</div>
        </div>
      </div>
    );
  }

  // Si hay error, mostrar contenido por defecto
  if (!portfolioData) {
    return (
      <>
        <Hero settings={null} />
        <Projects projects={[]} />
        <Skills skills={{}} />
        <Contact 
          settings={null} 
          onSubmit={handleContactSubmit}
        />
      </>
    );
  }

  return (
    <>
      <Hero settings={settings} />
      <Projects projects={projects} />
      <Skills skills={skills} />
      <Contact 
        settings={settings} 
        onSubmit={handleContactSubmit}
      />
    </>
  );
};

export default React.memo(Home);

