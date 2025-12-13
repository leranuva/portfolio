import React, { useState } from 'react';

const Input = ({
  label,
  type = 'text',
  value,
  onChange,
  error,
  placeholder,
  required = false,
  icon,
  ...props
}) => {
  const [isFocused, setIsFocused] = useState(false);

  return (
    <div style={{ marginBottom: '1.5rem' }}>
      {label && (
        <label
          style={{
            display: 'block',
            marginBottom: '0.5rem',
            color: 'var(--text-primary)',
            fontWeight: '500',
            fontSize: '0.95rem',
          }}
        >
          {label}
          {required && <span style={{ color: '#ef4444', marginLeft: '0.25rem' }}>*</span>}
        </label>
      )}
      <div
        style={{
          position: 'relative',
        }}
      >
        {icon && (
          <div
            style={{
              position: 'absolute',
              left: '1rem',
              top: '50%',
              transform: 'translateY(-50%)',
              color: isFocused ? 'var(--accent-primary)' : 'var(--text-secondary)',
              transition: 'color 0.2s ease',
            }}
          >
            <i className={icon}></i>
          </div>
        )}
        <input
          type={type}
          value={value}
          onChange={onChange}
          placeholder={placeholder}
          required={required}
          onFocus={() => setIsFocused(true)}
          onBlur={() => setIsFocused(false)}
          style={{
            width: '100%',
            padding: icon ? '0.875rem 1rem 0.875rem 2.75rem' : '0.875rem 1rem',
            border: `2px solid ${error ? '#ef4444' : isFocused ? 'var(--accent-primary)' : 'var(--border-color)'}`,
            borderRadius: '8px',
            fontSize: '1rem',
            fontFamily: 'inherit',
            backgroundColor: 'var(--bg-primary)',
            color: 'var(--text-primary)',
            transition: 'all 0.2s ease',
            outline: 'none',
            boxShadow: isFocused && !error ? '0 0 0 3px rgba(0, 123, 255, 0.1)' : 'none',
          }}
          {...props}
        />
      </div>
      {error && error !== null && error !== undefined && (
        <div
          style={{
            marginTop: '0.5rem',
            color: '#ef4444',
            fontSize: '0.875rem',
            display: 'flex',
            alignItems: 'center',
            gap: '0.5rem',
            animation: 'shake 0.3s ease',
          }}
        >
          <i className="fas fa-exclamation-circle"></i>
          <span>{error}</span>
        </div>
      )}
      <style>{`
        @keyframes shake {
          0%, 100% { transform: translateX(0); }
          25% { transform: translateX(-5px); }
          75% { transform: translateX(5px); }
        }
      `}</style>
    </div>
  );
};

export default Input;

