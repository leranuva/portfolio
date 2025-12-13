import React from 'react';
import Modal from './Modal';

const ConfirmModal = ({ isOpen, onClose, onConfirm, title, message, confirmText = 'Confirmar', cancelText = 'Cancelar', type = 'warning' }) => {
  const typeStyles = {
    danger: {
      confirmColor: '#ef4444',
      icon: 'fas fa-exclamation-triangle',
      iconColor: '#ef4444',
    },
    warning: {
      confirmColor: '#f59e0b',
      icon: 'fas fa-exclamation-triangle',
      iconColor: '#f59e0b',
    },
    info: {
      confirmColor: 'var(--accent-primary)',
      icon: 'fas fa-info-circle',
      iconColor: 'var(--accent-primary)',
    },
  };

  const style = typeStyles[type] || typeStyles.warning;

  const handleConfirm = () => {
    onConfirm();
    onClose();
  };

  return (
    <Modal isOpen={isOpen} onClose={onClose} title={title} size="small">
      <div style={{ textAlign: 'center' }}>
        <div
          style={{
            width: '64px',
            height: '64px',
            borderRadius: '50%',
            backgroundColor: `${style.iconColor}20`,
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            margin: '0 auto 1.5rem',
          }}
        >
          <i className={style.icon} style={{ fontSize: '2rem', color: style.iconColor }}></i>
        </div>
        <p
          style={{
            fontSize: '1.1rem',
            color: 'var(--text-primary)',
            marginBottom: '2rem',
            lineHeight: '1.6',
          }}
        >
          {message}
        </p>
        <div
          style={{
            display: 'flex',
            gap: '1rem',
            justifyContent: 'center',
          }}
        >
          <button
            onClick={onClose}
            className="btn btn-secondary"
            style={{
              padding: '0.75rem 2rem',
              minWidth: '120px',
            }}
          >
            {cancelText}
          </button>
          <button
            onClick={handleConfirm}
            style={{
              padding: '0.75rem 2rem',
              backgroundColor: style.confirmColor,
              color: 'white',
              border: 'none',
              borderRadius: '8px',
              fontSize: '1rem',
              fontWeight: '600',
              cursor: 'pointer',
              minWidth: '120px',
              transition: 'all 0.2s ease',
            }}
            onMouseEnter={(e) => {
              e.target.style.opacity = '0.9';
              e.target.style.transform = 'translateY(-2px)';
            }}
            onMouseLeave={(e) => {
              e.target.style.opacity = '1';
              e.target.style.transform = 'translateY(0)';
            }}
          >
            {confirmText}
          </button>
        </div>
      </div>
    </Modal>
  );
};

export default ConfirmModal;

