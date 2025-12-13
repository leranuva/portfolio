import React, { useState } from 'react';

const Textarea = ({
  label,
  value,
  onChange,
  error,
  placeholder,
  required = false,
  rows = 4,
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
      <textarea
        value={value}
        onChange={onChange}
        placeholder={placeholder}
        required={required}
        rows={rows}
        onFocus={() => setIsFocused(true)}
        onBlur={() => setIsFocused(false)}
        style={{
          width: '100%',
          padding: '0.875rem 1rem',
          border: `2px solid ${error ? '#ef4444' : isFocused ? 'var(--accent-primary)' : 'var(--border-color)'}`,
          borderRadius: '8px',
          fontSize: '1rem',
          fontFamily: 'inherit',
          backgroundColor: 'var(--bg-primary)',
          color: 'var(--text-primary)',
          transition: 'all 0.2s ease',
          outline: 'none',
          resize: 'vertical',
          boxShadow: isFocused && !error ? '0 0 0 3px rgba(0, 123, 255, 0.1)' : 'none',
        }}
        {...props}
      />
      {error && (
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

export default Textarea;

