import React, { useMemo, useCallback } from 'react';

const Hero = ({ settings }) => {
  // Memoizar valores para evitar recálculos
  const name = useMemo(() => settings?.name || 'Ramiro Núñez Valverde', [settings?.name]);
  const role = useMemo(() => settings?.role || 'Full-Stack Web Developer', [settings?.role]);
  const description = useMemo(() => 
    settings?.description || 'Desarrollador apasionado por crear soluciones web modernas y eficientes. Me especializo en construir aplicaciones escalables que combinan diseño elegante con funcionalidad robusta.',
    [settings?.description]
  );

  const scrollToSection = useCallback((id) => {
    const element = document.getElementById(id);
    if (element) {
      element.scrollIntoView({ behavior: 'smooth' });
    }
  }, []);

  return (
    <section id="home" className="hero">
      <div className="container">
        <div className="hero-content">
          <h1 className="hero-title">
            <span className="greeting">Hola, soy</span>
            <span className="name">{name}</span>
          </h1>
          <p className="hero-role">{role}</p>
          <p className="hero-description">{description}</p>
          <div className="hero-cta">
            <a 
              href="#projects" 
              className="btn btn-primary" 
              onClick={(e) => { 
                e.preventDefault(); 
                scrollToSection('projects'); 
              }}
            >
              Ver Proyectos
            </a>
            <a 
              href="#contact" 
              className="btn btn-secondary" 
              onClick={(e) => { 
                e.preventDefault(); 
                scrollToSection('contact'); 
              }}
            >
              Contactar
            </a>
          </div>
        </div>
        <div className="hero-image">
          <div className="floating-shapes">
            <div className="shape shape-1"></div>
            <div className="shape shape-2"></div>
            <div className="shape shape-3"></div>
          </div>
        </div>
      </div>
    </section>
  );
};

export default React.memo(Hero);

