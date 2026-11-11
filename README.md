
# 🛒 API REST de Ventas e Inventario — Laravel + Sanctum

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?logo=mysql&logoColor=white)
![Sanctum](https://img.shields.io/badge/Auth-Sanctum-FF2D20)
![Postman](https://img.shields.io/badge/Probado%20con-Postman-FF6C37?logo=postman&logoColor=white)

API REST para gestionar **productos, categorías, ventas y usuarios** de un negocio <!-- TODO: de qué negocio era: tienda, bodega, repuestos... -->. Usa **autenticación por token (Laravel Sanctum)** y restringe algunas rutas según el **departamento** del usuario.

> 📅 Mi primer API en Laravel. Aquí practiqué autenticación con tokens, CRUD, relaciones entre modelos y reportes de ventas.

---

## ✨ Funcionalidades

- 🔐 Registro, login y logout con **tokens (Sanctum)**.
- 👥 Gestión de usuarios (listar, ver, editar y eliminar).
- 📦 CRUD de **productos** con precio, stock, imagen y estado.
- 🏷️ CRUD de **categorías** y consulta de **productos por categoría**.
- 💰 Registro de **ventas** y reporte de **ventas diarias**.
- 🏢 Usuarios asignados a un **departamento**, con rutas restringidas por departamento.

## 🛠️ Tecnologías

| Capa | Tecnología |
|---|---|
| Framework | Laravel <!-- TODO: versión --> |
| Lenguaje | PHP <!-- TODO: versión --> |
| Autenticación | Laravel Sanctum |
| Base de datos | Mysql |
| Pruebas manuales | Postman |

## 🗃️ Modelo de datos

```
departamentos ──< users
categorias    ──< productos ──< ventas >── users
```

| Tabla | Campos principales |
|---|---|
| `departamentos` | nombre |
| `users` | name, email, password, departamento_id |
| `categorias` | nombre |
| `productos` | nombre, descripcion, precio, stock, imagen, estado, categoria_id |
| `ventas` | precio_unitario, total_venta, id_producto, id_usuario |

## 📡 Endpoints

Base URL: `http://127.0.0.1:8000/api`

Todas las rutas, salvo `register` y `login`, requieren el header:

```
Authorization: Bearer <token>
Accept: application/json
```

### Autenticación

| Método | Ruta | Descripción |
|---|---|---|
| POST | `/auth/register` | Crea un usuario |
| POST | `/auth/login` | Inicia sesión y devuelve el token |
| GET | `/auth/logout` | Cierra sesión (invalida el token) |
| GET | `/user` | Datos del usuario autenticado |

### Usuarios

| Método | Ruta | Descripción |
|---|---|---|
| GET | `/users` | Lista usuarios |
| GET | `/users/{id}` | Detalle de un usuario |
| PUT | `/users/{id}` | Actualiza un usuario |
| DELETE | `/users/{id}` | Elimina un usuario |

### Productos y categorías

| Método | Ruta | Descripción |
|---|---|---|
| GET / POST | `/product` | Lista / crea productos |
| GET / PUT / DELETE | `/product/{id}` | Detalle / actualiza / elimina |
| GET | `/productbycategory` | Productos agrupados por categoría |
| GET / POST | `/category` | Lista / crea categorías |
| GET / PUT / DELETE | `/category/{id}` | Detalle / actualiza / elimina |

### Ventas

| Método | Ruta | Descripción |
|---|---|---|
| GET / POST | `/venta` | Lista / registra ventas |
| GET / PUT / DELETE | `/venta/{id}` | Detalle / actualiza / elimina |
| GET | `/ventdia` | Reporte de ventas diarias |

### Departamentos

| Método | Ruta | Descripción |
|---|---|---|
| GET / POST | `/departaments` | Solo para usuarios del departamento autorizado |

## 📨 Ejemplos

**Registro** — `POST /api/auth/register`

```json
{
  "name": "Josue Admin",
  "email": "admin@example.com",
  "password": "12345678",
  "departamento_id": 1
}
```

**Login** — `POST /api/auth/login`

```json
{
  "email": "admin@example.com",
  "password": "12345678"
}
```

La respuesta incluye los datos del usuario y un **token**, que se usa como `Bearer Token` en las demás peticiones.

**Crear un producto** — `POST /api/product`

```json
{
  "nombre": "Producto de ejemplo",
  "descripcion": "Descripción del producto",
  "precio": 50.00,
  "stock": 10,
  "estado": 1,
  "categoria_id": 1
}
```

## ⚙️ Instalación

**Requisitos:** PHP <!-- TODO: versión -->, Composer y MySQL.

```bash
# 1. Clonar el repositorio
git clone https://github.com/TU-USUARIO/NOMBRE-DEL-REPO.git
cd NOMBRE-DEL-REPO

# 2. Instalar dependencias
composer install

# 3. Configurar el entorno
cp .env.example .env
php artisan key:generate
# Edita .env con los datos de tu base de datos

# 4. Crear las tablas y los departamentos iniciales
php artisan migrate --seed

# 5. Levantar el servidor
php artisan serve
```

> `migrate --seed` crea los departamentos iniciales (**Administración, Ventas y Almacén**). Son necesarios porque el registro de usuarios exige un `departamento_id`.

### Primer uso

1. Registra un usuario con `POST /api/auth/register` usando `"departamento_id": 1`.
2. Inicia sesión con `POST /api/auth/login` y copia el token.
3. Usa el token como **Bearer Token** para probar el resto de rutas.

## 🧪 Pruebas con Postman

Importa la colección desde [`docs/postman_collection.json`](docs/postman_collection.json) <!-- TODO: expórtala y súbela -->. Después del login, el token se guarda en una variable y se aplica automáticamente a las demás peticiones.

## 🧠 Lo que aprendí

- Autenticación de APIs con **Sanctum** (registro, login, logout y rutas protegidas).
- Diseño de **relaciones** entre modelos (usuarios–departamentos, productos–categorías, ventas–productos–usuarios).
- Uso de **seeders** para resolver dependencias entre datos: el registro exigía un departamento que no podía crearse sin estar autenticado.
- Probar y documentar una API con **Postman**.

## 🚀 Posibles mejoras

- Validación de stock al registrar una venta.
- Documentación automática con Swagger o Scribe.
- Tests automatizados con PHPUnit.

## 👤 Autor

**Josué Cotera Bravo** — Estudiante de Ingeniería de Software (UTP) · Técnico en Ingeniería de Software con IA (SENATI)

- 📧 josuecotb@gmail.com
- 💼 LinkedIn: [Josue C](www.linkedin.com/in/josue-cotera-bravo-a92358336)<!-- TODO: tu enlace -->
- 🐙 GitHub: [@jost3](https://github.com/jost3)
