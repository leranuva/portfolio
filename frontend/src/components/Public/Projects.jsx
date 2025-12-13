import React, { useEffect, useRef, useMemo, useCallback } from 'react';

const Projects = ({ projects }) => {
  const sectionRef = useRef(null);

  // Memoizar el observer callback
  const observerCallback = useCallback((entries) => {
    entries.forEach((entry, index) => {
      if (entry.isIntersecting) {
        // Usar requestAnimationFrame para mejor rendimiento
        requestAnimationFrame(() => {
          setTimeout(() => {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
          }, index * 100);
        });
      }
    });
  }, []);

  useEffect(() => {
    const observer = new IntersectionObserver(observerCallback, {
      threshold: 0.1,
      rootMargin: '50px', // Cargar antes de que sea visible
    });

    const cards = sectionRef.current?.querySelectorAll('.project-card');
    cards?.forEach((card) => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(30px)';
      card.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
      card.style.willChange = 'opacity, transform';
      observer.observe(card);
    });

    return () => {
      observer.disconnect();
    };
  }, [projects, observerCallback]);

  if (!projects || projects.length === 0) {
    return (
      <section id="projects" className="projects">
        <div className="container">
          <h2 className="section-title">Proyectos Destacados</h2>
          <p style={{ textAlign: 'center', color: 'var(--text-secondary)' }}>
            No hay proyectos disponibles en este momento.
          </p>
        </div>
      </section>
    );
  }

  return (
    <section id="projects" className="projects" ref={sectionRef}>
      <div className="container">
        <h2 className="section-title">Proyectos Destacados</h2>
        <div className="projects-grid">
          {projects.map((project) => (
            <div key={project.id} className="project-card">
              <div className="project-image">
                <div className="project-placeholder">
                  <i className="fas fa-code"></i>
                </div>
              </div>
              <div className="project-content">
                <h3 className="project-title">{project.title}</h3>
                <p className="project-description">{project.description}</p>
                {project.problem_resolution && (
                  <div className="project-details">
                    <p><strong>Problema/Resolución:</strong> {project.problem_resolution}</p>
                    {project.my_role && (
                      <p><strong>Mi rol:</strong> {project.my_role}</p>
                    )}
                  </div>
                )}
                {project.technologies && (
                  <ProjectTechnologies technologies={project.technologies} />
                )}
                <div className="project-links">
                  {project.demo_link && (
                    <a href={project.demo_link} className="project-link" target="_blank" rel="noopener noreferrer">
                      <i className="fas fa-external-link-alt"></i> Demo
                    </a>
                  )}
                  {project.repository_link && (
                    <a href={project.repository_link} className="project-link" target="_blank" rel="noopener noreferrer">
                      <i className="fab fa-github"></i> Repositorio
                    </a>
                  )}
                </div>
                {project.result && (
                  <p className="project-result">
                    <strong>Resultado:</strong> {project.result}
                  </p>
                )}
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
};

// Componente memoizado para tecnologías
const ProjectTechnologies = React.memo(({ technologies }) => {
  const techs = useMemo(() => {
    let techsArray = technologies;
    if (typeof techsArray === 'string') {
      try {
        techsArray = JSON.parse(techsArray);
      } catch (e) {
        techsArray = techsArray.split(',').map(t => t.trim());
      }
    }
    return Array.isArray(techsArray) ? techsArray : [];
  }, [technologies]);

  if (techs.length === 0) return null;

  return (
    <div className="project-tech">
      {techs.map((tech, index) => (
        <span key={`${tech}-${index}`} className="tech-tag">{tech}</span>
      ))}
    </div>
  );
});

ProjectTechnologies.displayName = 'ProjectTechnologies';

export default React.memo(Projects);

