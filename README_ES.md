# 📚 ResuMe

![Demo de la aplicación ResuMe](Readme.gif)

## 💡 Project Overview

Plataforma web diseñada para transformar la manera en que los usuarios consumen reseñas de negocios locales. En lugar de forzar la lectura de cientos de comentarios, **ResuMe\*** utiliza Inteligencia Artificial (IA) para generar un **resumen conciso y temático** de todas las opiniones de un lugar, destacando rápidamente los puntos fuertes, las críticas recurrentes y el rating promedio real.

## 📂 Estructura del Proyecto ResuMe

La organización de carpetas y archivos de **ResuMe** es la siguiente:

```

ResuMe/
│
├── index.php # Página principal
├── business.php # Vista de negocio individual
├── includes/ # Archivos de configuración y utilidades
│ ├── app.php # Carga inicial de clases y configuración
│ └── config/
│ └── huggingface.php # Configuración y funciones para la IA
│
├── class/ # Clases PHP del proyecto
│ ├── Business.php # Clase Business
│ └── Reviews.php # Clase Reviews
│
├── css/ # Archivos de estilos
│ └── styles.css # Estilos principales
│
└── vendor/ # Dependencias externas (si se usan)

```

### Notas importantes

- La carpeta `includes/config/huggingface.php` contiene toda la lógica de conexión con la API de Hugging Face.
- Las clases `Business` y `Reviews` manejan la interacción con la base de datos.
- Las vistas (`index.php` y `business.php`) se encargan de mostrar la información al usuario.
- La estructura permite **extender el proyecto fácilmente**, por ejemplo añadiendo más APIs o funcionalidades.

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

## 📝 Next Steps

La siguiente fase crítica del proyecto es la **Integración de la API de IA**:

1. **Integración con Hugging Face:** Desarrollar la lógica PHP (o un endpoint) para agrupar todas las reseñas de un negocio, enviarlas a la API de Hugging Face y procesar el resumen resultante.

2. **Caching de Resúmenes:** Implementar una columna o tabla para almacenar los resúmenes generados por la IA. Esto es vital para optimizar la velocidad y reducir los costos de las llamadas recurrentes a la API.

---

## 🧠 AI Integration

El sistema cuenta con una conexión establecida con la API de **Hugging Face**, utilizando el modelo:

> **`philschmid/bart-large-cnn-samsum`**

Este modelo gratuito permite generar resúmenes de texto, aunque **no alcanza el nivel de precisión de los modelos premium**, su integración demuestra la funcionalidad completa del flujo **(extracción de reseñas → envío a IA → retorno de resumen procesado)**.

La función principal (`resumirResenas`) se encuentra en `config/huggingface.php` e implementa:
- Limpieza de texto previo al envío.
- Conexión mediante `cURL` con la API de Hugging Face.
- Procesamiento del JSON devuelto.
- Eliminación automática de frases redundantes del modelo (“Summarize the customer reviews…”).

---

**Caching de Resúmenes:**

Para la realización de esta parte he tenido que modificar la base de datos con tres nuevas variables , una para llevar el total de reviews que tiene dicho engocio que sube y baja según un trigger , luego un actual_reviews , que es el número de reviews que tenía el negocio a la hora de hacer el resumen y una última variable para guardar el resumen de la IA para no gastar más de lo neecsario de la forma:

```

    ALTER TABLE businesses ADD COLUMN summary TEXT DEFAULT NULL;
    ALTER TABLE businesses ADD COLUMN total_reviews INT DEFAULT 0;
    ALTER TABLE businesses ADD COLUMN actual_reviews INT DEFAULT 0;


    CREATE TRIGGER after_review_insert
    AFTER INSERT ON reviews
    FOR EACH ROW
    BEGIN
        UPDATE businesses
        SET total_reviews = total_reviews + 1
        WHERE id = NEW.business_id;
    END;
    //

    DELIMITER ;

    DELIMITER //

    CREATE TRIGGER after_review_delete
    AFTER DELETE ON reviews
    FOR EACH ROW
    BEGIN
        UPDATE businesses
        SET total_reviews = total_reviews - 1
        WHERE id = OLD.business_id;
    END;
    //

    DELIMITER ;

```

Así solo se llamrá a la API las veecs que sea necesario de forma que cuando el numero de reviews es distinto al número actual de revies se ejecuta la función llamando ala API otra vez y actualizando la base de datos.

He intentado ajusatr la IA gratis lo mejor que he podido con unamejor habría sido muchisimo más sencillo pero ya está todo terminado.



