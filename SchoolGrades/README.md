# SchoolGrades — ASP.NET Core MVC + Vue + Supabase

## Requisitos
- [.NET 8 SDK](https://dotnet.microsoft.com/download)
- Cuenta en [Supabase](https://supabase.com)

---

## 1. Configurar Supabase

1. Crea un proyecto nuevo en Supabase.
2. Ve a **Project Settings → Database → Connection string → URI** y copia:
   - Host (ej: `db.xxxx.supabase.co`)
   - Password (la que pusiste al crear el proyecto)

> Las tablas `estudiantes` y `calificaciones` se crean automáticamente al iniciar
> la app gracias a `db.Database.EnsureCreated()` en `Program.cs`.

---

## 2. Configurar la cadena de conexión

Abre `appsettings.json` y reemplaza los valores:

```json
"DefaultConnection": "Host=db.xxxx.supabase.co;Port=5432;Database=postgres;Username=postgres;Password=TU_PASSWORD;SSL Mode=Require;Trust Server Certificate=true"
```

---

## 3. Ejecutar el proyecto

```bash
dotnet restore
dotnet run
```

Abre en el navegador: `http://localhost:5000`

---

## Estructura del proyecto

```
SchoolGrades/
├── Controllers/
│   ├── HomeController.cs          # Redirige al índice
│   └── EstudiantesController.cs   # Index (lista) + Insertar
├── Data/
│   └── AppDbContext.cs             # EF Core DbContext
├── Models/
│   ├── Estudiante.cs               # Entidad estudiante
│   ├── Calificacion.cs             # Entidad calificaciones (1-1)
│   └── EstudianteCalificacionViewModel.cs  # ViewModel del formulario
├── Views/
│   ├── Shared/_Layout.cshtml       # Layout con nav
│   ├── _ViewImports.cshtml
│   ├── _ViewStart.cshtml
│   └── Estudiantes/
│       ├── Index.cshtml            # Lista con Vue (filtrado + ordenamiento)
│       └── Insertar.cshtml         # Formulario con Vue (validación cliente)
├── Program.cs                      # Entrada + configuración DI
├── appsettings.json                # Connection string
└── SchoolGrades.csproj
```

---

## Funcionalidades

### Página Index (`/Estudiantes/Index`)
- Tabla con todos los estudiantes y sus 3 notas + promedio
- Columna **Estado**: Aprobado (promedio ≥ 7) / Reprobado
- **Filtrado en tiempo real** por nombre o cédula (Vue)
- **Ordenamiento** por cualquier columna haciendo clic (Vue)

### Página Insertar (`/Estudiantes/Insertar`)
- Formulario: nombre, cédula, correo + 3 notas (rango 1–10)
- **Validación en cliente** con Vue antes de enviar
- **Validación en servidor** con Data Annotations + EF
- Muestra el **promedio calculado** en tiempo real mientras escribes
- Detecta cédulas duplicadas
- Redirige al índice con mensaje de éxito

---

## Tecnologías

| Capa | Tecnología |
|------|-----------|
| Backend | ASP.NET Core 8 MVC |
| ORM | Entity Framework Core 8 + Npgsql |
| Base de datos | Supabase (PostgreSQL) |
| Frontend reactivo | Vue 3 (CDN, sin build) |
| Autenticación CSRF | ASP.NET AntiForgery tokens |
