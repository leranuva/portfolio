# Guía para Agregar Screenshots al README

## 📸 Screenshots Recomendados

Para mejorar la documentación visual del portfolio, agrega los siguientes screenshots:

### 1. Modo Claro (portfolio-light-mode.png)
- Captura de pantalla del portfolio en modo claro
- Muestra la sección Hero, proyectos y habilidades
- Tamaño recomendado: 1920x1080px o similar
- Formato: PNG o JPG

### 2. Modo Oscuro (portfolio-dark-mode.png)
- Captura de pantalla del portfolio en modo oscuro
- Muestra el toggle funcionando
- Tamaño recomendado: 1920x1080px o similar
- Formato: PNG o JPG

### 3. Diseño Responsivo (portfolio-responsive.gif)
- GIF animado mostrando el diseño en diferentes tamaños
- Móvil → Tablet → Desktop
- Herramientas recomendadas: ScreenToGif, LICEcap, o Kap
- Tamaño recomendado: 800x600px

### 4. Panel de Administración (admin-dashboard.png)
- Cuando esté implementado
- Muestra el CRUD de proyectos y habilidades

## 📁 Ubicación de Archivos

Crea una carpeta `docs/images/` y coloca los screenshots allí:

```
portfolio_ram/
└── docs/
    └── images/
        ├── portfolio-light-mode.png
        ├── portfolio-dark-mode.png
        └── portfolio-responsive.gif
```

## 🔗 Cómo Agregarlos al README

Una vez que tengas los screenshots, actualiza el README.md:

```markdown
### Modo Claro
![Portfolio Modo Claro](docs/images/portfolio-light-mode.png)

### Modo Oscuro
![Portfolio Modo Oscuro](docs/images/portfolio-dark-mode.png)

### Diseño Responsivo
![Portfolio Responsivo](docs/images/portfolio-responsive.gif)
```

## 🛠️ Herramientas Recomendadas

- **Screenshots:** Lightshot, ShareX, o la herramienta nativa de tu SO
- **GIFs:** ScreenToGif (Windows), LICEcap, Kap (Mac), Peek (Linux)
- **Edición:** GIMP, Paint.NET, o cualquier editor de imágenes

## 💡 Tips

- Usa el mismo tamaño de ventana para todos los screenshots
- Asegúrate de que el contenido sea visible y claro
- Para el GIF, muestra la transición suave entre tamaños
- Optimiza las imágenes antes de subirlas (TinyPNG, ImageOptim)

