# OAuth Google — Guía de Integración
## Biconoir's Gourmet

---

## Archivos generados

| Archivo | Acción | Descripción |
|---|---|---|
| `config/oauth.php` | **NUEVO** | Credenciales Google y constantes OAuth |
| `app/Controllers/AuthController.php` | **REEMPLAZAR** | Maneja login, redirect a Google, callback |
| `app/Controllers/CartController.php` | **REEMPLAZAR** | Añade guard de sesión al carrito |
| `app/Models/User.php` | **REEMPLAZAR** | Sin password, con findOrCreateFromGoogle() |
| `app/Views/login.php` | **REEMPLAZAR** | Solo botón "Continuar con Google" |
| `app/Views/redirect_notice.php` | **NUEVO** | Página de aviso de sesión requerida |
| `public/index.php` | **REEMPLAZAR** | Rutas oauth_redirect, oauth_callback, redirect_notice |
| `database/schema.sql` | **NUEVO** | Schema de la nueva BD sin password_hash |

---

## Paso 1 — Crear la nueva base de datos en Supabase

1. Ve a supabase.com → New Project
2. Copia la URL de conexión (Settings → Database → Connection string → URI)
3. En el SQL Editor de Supabase, ejecuta el archivo `database/schema.sql`
4. Actualiza `config/connection.php` con la nueva URL de conexión

---

## Paso 2 — Copiar los archivos al proyecto

Copia cada archivo generado a su ruta correspondiente en tu proyecto
(reemplaza los existentes donde indica "REEMPLAZAR").

---

## Paso 3 — Desplegar en Render

1. Sube el proyecto a GitHub
2. En Render → New Web Service → conecta tu repo
3. En **Environment Variables** agrega:
   ```
   APP_URL = https://tu-app.onrender.com
   ```
4. Una vez desplegado, copia la URL que te da Render

---

## Paso 4 — Agregar la URL de Render en Google Cloud Console

1. Ve a console.cloud.google.com → APIs y servicios → Credenciales
2. Edita tu cliente OAuth
3. En "URIs de redireccionamiento autorizados" AGREGA (no reemplaces):
   ```
   https://tu-app.onrender.com/index.php?action=oauth_callback
   ```
4. Guarda

---

## Flujo completo del OAuth (para tu documentación)

```
Usuario hace clic en "Continuar con Google"
         ↓
[FRONT END] index.php?action=oauth_redirect
  → AuthController::googleRedirect()
  → Genera state aleatorio (anti-CSRF) y lo guarda en sesión
  → Redirige a accounts.google.com con client_id, scope, state
         ↓
Google muestra pantalla de consentimiento
         ↓
Usuario acepta → Google redirige a:
index.php?action=oauth_callback&code=XXX&state=YYY
         ↓
[BACK END] AuthController::googleCallback()
  → Validación #1: ¿Llegaron code y state?
  → Validación #2: ¿El state coincide con el de sesión? (anti-CSRF)
  → POST a Google: intercambia code por access_token
  → GET a Google: obtiene name, email, sub del usuario
  → User::findOrCreateFromGoogle(): busca o crea en BD
  → Guarda $_SESSION['user']
  → Redirige al menú (o a la página que intentaba acceder)
```

## Escenario de redireccionamiento

```
Usuario logueado → accede al carrito → copia la URL
         ↓
Usuario cierra sesión (index.php?action=logout)
         ↓
Usuario pega la URL del carrito
         ↓
CartController::index() detecta que no hay $_SESSION['user']
         ↓
Redirige a index.php?action=redirect_notice&from=cart
         ↓
Se muestra redirect_notice.php con mensaje:
"Debes iniciar sesión para acceder al carrito"
+ botón "Iniciar sesión" + botón "Ver el Menú"
```
