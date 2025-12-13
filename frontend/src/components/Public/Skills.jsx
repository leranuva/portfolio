import React, { useEffect, useRef, useCallback, useMemo } from 'react';

// Mover constantes fuera del componente para evitar recreación
const categoryIcons = {
  frontend: 'fas fa-paint-brush',
  backend: 'fas fa-server',
  database: 'fas fa-database',
  devops: 'fas fa-cloud',
  design: 'fas fa-palette',
};

const categoryNames = {
  frontend: 'Frontend',
  backend: 'Backend',
  database: 'Base de Datos',
  devops: 'Infraestructura & DevOps',
  design: 'Diseño & UX',
};

const Skills = ({ skills }) => {
  const sectionRef = useRef(null);

  // Memoizar el observer callback
  const observerCallback = useCallback((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const skillBars = entry.target.querySelectorAll('.skill-bar');
        skillBars.forEach((bar) => {
          const width = bar.getAttribute('data-width') || bar.style.width;
          bar.style.width = '0';
          // Usar requestAnimationFrame para mejor rendimiento
          requestAnimationFrame(() => {
            setTimeout(() => {
              bar.style.width = width;
            }, 50);
          });
        });
        observer.unobserve(entry.target);
      }
    });
  }, []);

  useEffect(() => {
    const observer = new IntersectionObserver(observerCallback, {
      threshold: 0.3,
      rootMargin: '50px',
    });

    const categories = sectionRef.current?.querySelectorAll('.skill-category');
    categories?.forEach((category) => {
      observer.observe(category);
    });

    return () => {
      observer.disconnect();
    };
  }, [skills, observerCallback]);

  if (!skills || Object.keys(skills).length === 0) {
    return (
      <section id="skills" className="skills">
        <div className="container">
          <h2 className="section-title">Habilidades & Stack Tecnológico</h2>
          <p style={{ textAlign: 'center', color: 'var(--text-secondary)' }}>
            No hay habilidades disponibles en este momento.
          </p>
        </div>
      </section>
    );
  }

  return (
    <section id="skills" className="skills" ref={sectionRef}>
      <div className="container">
        <h2 className="section-title">Habilidades & Stack Tecnológico</h2>
        <div className="skills-grid">
          {Object.entries(skills).map(([category, categorySkills]) => (
            <div key={category} className="skill-category">
              <div className="skill-category-header">
                <i className={categoryIcons[category] || 'fas fa-code'}></i>
                <h3>{categoryNames[category] || category}</h3>
              </div>
              <div className="skill-items">
                {categorySkills.map((skill) => (
                  <div key={skill.id} className="skill-item">
                    <span className="skill-name">{skill.name}</span>
                    <div className="skill-level">
                      <div 
                        className="skill-bar" 
                        style={{ width: `${skill.level}%` }}
                        data-width={`${skill.level}%`}
                      ></div>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
};

export default React.memo(Skills);

