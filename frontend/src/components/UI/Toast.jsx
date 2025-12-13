import React, { useEffect, useState } from 'react';

const Toast = ({ message, type = 'info', duration = 3000, onClose }) => {
  const [isVisible, setIsVisible] = useState(true);

  useEffect(() => {
    const timer = setTimeout(() => {
      setIsVisible(false);
      setTimeout(() => {
        if (onClose) onClose();
      }, 300);
    }, duration);

    return () => clearTimeout(timer);
  }, [duration, onClose]);

  const typeStyles = {
    success: {
      backgroundColor: '#10b981',
      icon: 'fas fa-check-circle',
    },
    error: {
      backgroundColor: '#ef4444',
      icon: 'fas fa-exclamation-circle',
    },
    warning: {
      backgroundColor: '#f59e0b',
      icon: 'fas fa-exclamation-triangle',
    },
    info: {
      backgroundColor: 'var(--accent-primary)',
      icon: 'fas fa-info-circle',
    },
  };

  const style = typeStyles[type] || typeStyles.info;

  return (
    <div
      style={{
        position: 'fixed',
        top: '20px',
        right: '20px',
        backgroundColor: style.backgroundColor,
        color: 'white',
        padding: '1rem 1.5rem',
        borderRadius: '12px',
        boxShadow: '0 10px 25px rgba(0, 0, 0, 0.2)',
        display: 'flex',
        alignItems: 'center',
        gap: '0.75rem',
        zIndex: 10000,
        minWidth: '300px',
        maxWidth: '500px',
        animation: isVisible ? 'slideInRight 0.3s ease' : 'slideOutRight 0.3s ease',
        opacity: isVisible ? 1 : 0,
        transform: isVisible ? 'translateX(0)' : 'translateX(400px)',
        transition: 'all 0.3s ease',
      }}
    >
      <i className={style.icon} style={{ fontSize: '1.25rem' }}></i>
      <div style={{ flex: 1, fontWeight: '500' }}>{message}</div>
      <button
        onClick={() => {
          setIsVisible(false);
          setTimeout(() => {
            if (onClose) onClose();
          }, 300);
        }}
        style={{
          background: 'rgba(255, 255, 255, 0.2)',
          border: 'none',
          color: 'white',
          borderRadius: '50%',
          width: '24px',
          height: '24px',
          cursor: 'pointer',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          fontSize: '0.875rem',
          transition: 'background 0.2s ease',
        }}
        onMouseEnter={(e) => {
          e.target.style.background = 'rgba(255, 255, 255, 0.3)';
        }}
        onMouseLeave={(e) => {
          e.target.style.background = 'rgba(255, 255, 255, 0.2)';
        }}
      >
        <i className="fas fa-times"></i>
      </button>
      <style>{`
        @keyframes slideInRight {
          from {
            opacity: 0;
            transform: translateX(400px);
          }
          to {
            opacity: 1;
            transform: translateX(0);
          }
        }
        @keyframes slideOutRight {
          from {
            opacity: 1;
            transform: translateX(0);
          }
          to {
            opacity: 0;
            transform: translateX(400px);
          }
        }
      `}</style>
    </div>
  );
};

export default Toast;

