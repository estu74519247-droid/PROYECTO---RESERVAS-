# PrimeLab — Sistema de Reserva de Citas Médicas

Aplicación web académica para la **reserva y el seguimiento de citas médicas** en centros de salud públicos del Perú. Integra tres perfiles (paciente, médico y administrador) sobre **PHP + MySQL/MariaDB + Apache (XAMPP)** y una interfaz accesible y responsiva.

> Versión preparada para la **Semana de Innovación 2026**. Incluye 64 pacientes, 17 médicos, 3 administradores, base de datos auto-reparable y despliegue portable en cualquier IP y puerto de XAMPP.

---

## 1. Características principales

### Pacientes
- Registro con **DNI** y autocompletado de nombres y apellidos.
- Inicio de sesión con **DNI + contraseña** (la contraseña inicial es el DNI).
- Reserva de citas por centro, especialidad, médico, fecha y hora.
- Cálculo del costo según seguro: **SIS S/ 0.00 · EPS S/ 20.00 · Privado S/ 50.00**.
- Historial de citas, **ticket imprimible** y perfil con edición controlada.
- El ticket muestra diagnóstico, indicaciones, medicamentos y reprogramaciones registradas por el médico.

### Médicos
- Acceso mediante **CMP** (solo ven sus propias citas).
- Registro del estado de la cita: **pendiente, atendida, cancelada o extendida**.
- Registro de la **descripción de lo observado al paciente** (diagnóstico/evaluación).
- Registro de **indicaciones y medicamentos** entregados o recetados.
- **Reprogramación** de una cita creando una nueva con el **mismo médico, paciente y centro**, con fecha, hora y motivo.
- Motivos admitidos: inasistencia del paciente, cancelación, seguimiento del tratamiento, evaluación de resultados de exámenes, error administrativo del sistema u otro.
- Validación de disponibilidad del horario y historial de cada acción.

### Administración
- Panel con indicadores: citas totales, pendientes, atendidas, extendidas, reprogramadas y canceladas.
- Gestión de citas, médicos, centros, especialidades y consultas.
- **Tres cuentas de administrador** listas para la demostración.

### Autocompletado de DNI (cascada)
1. Tabla `paciente` de PrimeLab.
2. **Directorio local** `datos/usuarios.json` (funciona sin la API Node.js y sin internet).
3. API interna `API_Citas_Medicas` (Node.js + Express), si está encendida.
4. Servicios públicos de consulta RENIEC (SUNAT, apis.net.pe, miapi.cloud, apiperu.dev, perudevs, reniec.cloud).

> RENIEC no ofrece una API pública oficial, gratuita y abierta. Si los servicios externos no responden, el sistema lo informa y permite completar los nombres manualmente.

---

## 2. Inicio rápido

1. Inicia **Apache** y **MySQL/MariaDB** desde XAMPP.
2. Copia la carpeta `PrimeLab` dentro de `htdocs`:
   ```
   C:\xampp\htdocs\PrimeLab
   ```
3. Importa la base de datos (opcional, recomendado para la demo):
   - Abre `http://localhost/phpmyadmin`
   - Importa `PrimeLab/BD/bd_primelab.sql`
4. Abre el sistema:
   ```
   http://localhost/PrimeLab/
   ```
   También funciona con otra IP o puerto:
   ```
   http://192.168.1.50/PrimeLab/
   http://localhost:8080/PrimeLab/
   ```

> No uses Live Server: solo sirve archivos estáticos y no ejecuta PHP.

### Configuración automática
- El puerto de MySQL se detecta probando **3306, 3307, 3308, 3309 y 3009**.
- Si la base `bd_primelab` no existe, se **crea automáticamente**.
- Si la tabla `cita` es antigua, se **agregan las columnas faltantes** (`diagnostico`, `indicaciones`, `motivo_reprogramacion`, `reprogramada_de`, `observaciones`).
- La URL de la API se arma con el **host de la petición** y el puerto 3000.
- Los enlaces del sitio son **relativos**: no dependen de una IP ni de un puerto fijos.

Para fijar valores manualmente, crea `config.local.php`:
```php
<?php
define('PRIMELAB_DB_HOST', '127.0.0.1');
define('PRIMELAB_DB_PORT', 3009);
define('PRIMELAB_DB_USER', 'root');
define('PRIMELAB_DB_PASS', '');
define('PRIMELAB_DB_NAME', 'bd_primelab');
// define('PRIMELAB_API_URL', 'http://127.0.0.1:3000');
```

También se admiten variables de entorno con los mismos nombres.

---

## 3. Credenciales de prueba

### Administradores
| Usuario | Correo | Contraseña |
|---|---|---|
| `prime` | `primelab@centrosalud.pe` | `pr1m3l4b` |
| `admin2` | `gestion@centrosalud.pe` | `PrimeLab2026` |
| `admin3` | `soporte@centrosalud.pe` | `PrimeLab2026` |

### Médicos (acceso por CMP)
| CMP | Médico | Especialidad |
|---|---|---|
| `10234` | Dra. Ana Torres Mendoza | Medicina General |
| `18542` | Dr. Luis Alberto Mendoza Quispe | Cardiología |
| `21306` | Dra. Rosa María Salazar Huamán | Pediatría |
| `45876` | Dra. Gabriela Inés Ortiz Villanueva | Medicina Interna |

El listado completo (MED001–MED017) está en `Usuarios_PrimeLab.xlsx` y en la tabla `medico`.

### Pacientes (DNI y contraseña = DNI)
| DNI | Paciente | Seguro |
|---|---|---|
| `45678912` | Ana Torres Mendoza | SIS |
| `41234567` | Luis Ramos Cárdenas | EPS |
| `61161158` | Eidan Valencia Zamora | Privado |
| `10000000` | Ana García Chávez | SIS |
| `10000049` | Héctor Núñez Ramírez | SIS |
| `61216468` | Ademir Andrew Alberto Sanchez Chinchay | SIS |

- Los DNI **10000000–10000049** corresponden a los 50 usuarios de `usuarios.json`.
- Los DNI **61216468, 74519247, 72850117, 75532819, 72484597, 72689307 y 61161158** son cuentas de estudiante.
- La contraseña inicial de todos es su **DNI**. Las cuentas de la API también aceptan su clave original (por ejemplo `Demo0001!`).

El directorio completo está en **`Usuarios_PrimeLab.xlsx`** (hojas Accesos, Resumen e Instrucciones).

---

## 4. Estructura del proyecto

```
PrimeLab/
├── index.php                               Punto de entrada y lista de rutas
├── .htaccess                               Reglas de Apache que conservan las URLs existentes
├── app/                                    Archivos internos (acceso HTTP directo bloqueado en Apache)
│   ├── acceso/                             Inicio y cierre de sesión, registro
│   ├── admin/                              Gestión de citas, consultas, médicos y centros
│   ├── medicos/                            Panel y gestión clínica
│   ├── pacientes/                          Panel, reservas, historial, consultas, perfil y tickets
│   ├── api/                                Endpoint PHP de consulta de DNI
│   ├── vistas/                             Pantallas HTML de pacientes, médicos y administración
│   └── core/                               Configuración, conexión y utilidades compartidas
├── css/, js/, img/                          Estilos, JavaScript e imágenes
├── BD/                                     Base inicial, migraciones y respaldo local
├── datos/                                  Directorio local de usuarios
├── API_Citas_Medicas/                       API opcional (Node.js + Express)
├── Doc_PDF/                                Documentación de entrega
└── README.md
```

Las URLs existentes no cambian: Apache dirige `/PrimeLab/reservar.php` a `index.php`, que carga `app/pacientes/reservar.php`. Edita las pantallas HTML en `app/vistas/` y el PHP en el módulo correspondiente de `app/`. Apache necesita `mod_rewrite` y permitir los archivos `.htaccess` en `htdocs`. La configuración opcional `config.local.php` se sigue colocando en la raíz del proyecto.

---

## 5. Base de datos

- Nombre: **`bd_primelab`**
- Motor: MySQL / MariaDB (InnoDB, `utf8mb4_spanish_ci`)
- Script completo: **`BD/bd_primelab.sql`**

| Tabla | Registros |
|---|---|
| `centro` | 8 |
| `especialidad` | 17 |
| `centro_especialidad` | 52 |
| `medico` | 17 |
| `paciente` | 64 |
| `cita` | 10 |
| `cita_historial` | 1+ |
| `administrador` | 3 |

### Esquema auto-reparable
`app/core/file_conexion.php` ejecuta `asegurarEsquemaPaciente()`, `asegurarEsquemaCita()` y `asegurarAdministradores()` al conectarse. Esto permite abrir una base creada con una versión anterior sin errores fatales y sin reimportar manualmente.

---

## 6. API interna opcional (Node.js)

La carpeta `API_Citas_Medicas` contiene una API educativa con Express y un archivo JSON.

```bash
cd API_Citas_Medicas
npm install
npm start
```

- URL base: `http://localhost:3000`
- Endpoints: `/api/usuarios`, `/api/usuarios/:id`, `/api/usuarios/buscar?dni=...`
- El sistema **no depende** de esta API para funcionar: el directorio local `datos/usuarios.json` cubre la consulta de DNI.

---

## 7. Diagnóstico de la consulta de DNI

Prueba directa del endpoint:

```
http://localhost/PrimeLab/consulta_dni.php?dni=10000000&debug=1
```

La respuesta incluye `ok`, `nombres`, `apellidos`, `mensaje` y, con `debug=1`, el detalle de cada fuente consultada.

Requisitos para las fuentes externas:
- Conexión a internet.
- Extensión `curl` habilitada en PHP.
- Acceso saliente permitido desde el servidor.

---

## 8. Seguridad y buenas prácticas

- Las consultas usan **sentencias preparadas**.
- La salida HTML se escapa con `htmlspecialchars`.
- Las sesiones se regeneran al iniciar sesión.
- El médico solo puede operar sobre **sus propias citas**.
- Las cuentas de demostración usan contraseñas documentadas con fines académicos. En producción deben almacenarse con `password_hash()` y verificarse con `password_verify()`.
- Cambia las claves por defecto y restringe el acceso a phpMyAdmin antes de publicar el sistema.

---

## 9. Solución de problemas

| Síntoma | Causa probable | Solución |
|---|---|---|
| `Unknown column 'c.diagnostico'` | Base de datos antigua | El sistema la repara solo al conectarse; si persiste, importa `BD/bd_primelab.sql`. |
| `Error de conexión a MySQL` | MySQL apagado o puerto distinto | Inicia MySQL en XAMPP; el sistema prueba 3306–3309 y 3009. |
| No aparecen nombres al buscar DNI | DNI ajeno al directorio y fuentes externas caídas | Verifica internet y `curl`; completa los nombres manualmente. |
| Los enlaces llevan a otra dirección | Se abrió con Live Server | Usa Apache (`http://localhost/PrimeLab/`). |
| La API no responde | Node.js apagado | No es obligatorio: el directorio local cubre la consulta. |

---

## 10. Documentación adicional

- **`Documentacion_Proceso_PrimeLab.docx`** — documentación formal del proceso, arquitectura, modelo de datos, flujos y pruebas.
- **`Usuarios_PrimeLab.xlsx`** — directorio de accesos de demostración.
- **`BD/bd_primelab.sql`** — base de datos completa lista para importar.

---

Proyecto académico. Los datos mostrados son de ejemplo.
