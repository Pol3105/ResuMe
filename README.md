# 📚 ResuMe

## 💡 Project Overview

Plataforma web diseñada para transformar la manera en que los usuarios consumen reseñas de negocios locales. En lugar de forzar la lectura de cientos de comentarios, **ResuMe\*** utiliza Inteligencia Artificial (IA) para generar un **resumen conciso y temático** de todas las opiniones de un lugar, destacando rápidamente los puntos fuertes, las críticas recurrentes y el rating promedio real.

## 🎯 Motivation and Challenge

Este proyecto tiene como propósito principal demostrar la capacidad de integrar servicios complejos de manera efectiva. El objetivo es crear un producto visible y funcional que exhiba la aplicación práctica de:

1. **Integración de APIs:** Conexión con servicios externos para la importación de datos (reseñas).

2. **Procesamiento de Lenguaje Natural (PLN):** Uso de una API de IA (Hugging Face) para realizar el resumen automático de grandes volúmenes de texto.

## ⚙️ Technology Stack

| Componente | Tecnología | Propósito | 
 | ----- | ----- | ----- | 
| **Backend/API** | PHP (Estructura de Clases) | Lógica de servidor y manejo de datos, clases `Business` y `Review`. | 
| **Frontend** | PHP/HTML | Renderizado de vistas y lógica de presentación. | 
| **Styling** | CSS con Clases Semánticas | Diseño responsive, limpio y coherente (basado en principios de Tailwind). | 
| **Database** | MySQL/MariaDB | Almacenamiento persistente de negocios y reseñas. | 
| **Artificial Intelligence** | Hugging Face API | Generación del resumen de texto (PLN). | 

## 🚀 Current Status (MVP)

### Structure and Aesthetics

El proyecto cuenta con una estructura de clases PHP bien definida que simula la interacción con la base de datos. El diseño responsive para las dos vistas clave (`index.php` y `business.php`) está completamente implementado, asegurando una estética profesional y una buena experiencia de usuario.

### Database Schema (SQL)

Se han definido dos tablas esenciales para el funcionamiento del MVP:

```

\-- Table for the businesses
CREATE TABLE businesses (
id INT AUTO\_INCREMENT PRIMARY KEY,
name VARCHAR(100) NOT NULL,
category VARCHAR(50) NOT NULL
);

\-- Table for the reviews
CREATE TABLE reviews (
id INT AUTO\_INCREMENT PRIMARY KEY,
business\_id INT NOT NULL,
user\_name VARCHAR(100) NOT NULL,
rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
comment TEXT NOT NULL,
created\_at TIMESTAMP DEFAULT CURRENT\_TIMESTAMP,
CONSTRAINT fk\_business
FOREIGN KEY (business\_id)
REFERENCES businesses(id)
ON DELETE CASCADE
);

```

