import React, { useState, useCallback } from 'react';
import Toast from './Toast';

let toastId = 0;
let addToast = null;

export const useToast = () => {
  const showToast = useCallback((message, type = 'info', duration = 3000) => {
    if (addToast) {
      const id = toastId++;
      addToast({ id, message, type, duration });
      return id;
    }
  }, []);

  return {
    success: (message, duration) => showToast(message, 'success', duration),
    error: (message, duration) => showToast(message, 'error', duration),
    warning: (message, duration) => showToast(message, 'warning', duration),
    info: (message, duration) => showToast(message, 'info', duration),
  };
};

const ToastContainer = () => {
  const [toasts, setToasts] = useState([]);

  addToast = ({ id, message, type, duration }) => {
    setToasts((prev) => [...prev, { id, message, type, duration }]);
  };

  const removeToast = (id) => {
    setToasts((prev) => prev.filter((toast) => toast.id !== id));
  };

  return (
    <div
      style={{
        position: 'fixed',
        top: '20px',
        right: '20px',
        zIndex: 10000,
        display: 'flex',
        flexDirection: 'column',
        gap: '0.75rem',
      }}
    >
      {toasts.map((toast, index) => (
        <Toast
          key={toast.id}
          message={toast.message}
          type={toast.type}
          duration={toast.duration}
          onClose={() => removeToast(toast.id)}
        />
      ))}
    </div>
  );
};

export default ToastContainer;

