# LEEME — Entrega PrimeLab (Semana de Innovación 2026)

Gracias por la espera. Esta carpeta contiene la versión final y portable del sistema.

## Qué se corrigió y mejoró

1. **Error fatal del historial de citas**
   - El error `Unknown column 'c.diagnostico'` ya no aparece.
   - El sistema **repara automáticamente** el esquema de la tabla `cita` al conectarse.

2. **Consulta de DNI (RENIEC)**
   - Ahora resuelve primero la base de datos, luego un **directorio local** `datos/usuarios.json` y finalmente servicios públicos de RENIEC.
   - Todos los DNI de `usuarios.json` (10000000–10000049 y los 7 estudiantes) muestran nombres y apellidos **aunque la API Node.js esté apagada**.
   - Si ninguna fuente externa responde, el registro permite completar los nombres manualmente.

3. **Inicio de sesión de los usuarios de `usuarios.json`**
   - Se crean como pacientes normales al iniciar sesión.
   - Contraseña = **DNI**. También se acepta la clave original de la API (`Demo0001!`, etc.).

4. **Usuarios de ejemplo**
   - **3 administradores**: `prime`, `admin2`, `admin3`.
   - **17 médicos** (acceso por CMP).
   - **64 pacientes** en la base de datos.
   - Todo listado en **`Usuarios_PrimeLab.xlsx`**.

5. **Médicos: gestión clínica**
   - Estado: pendiente, atendida, cancelada o extendida.
   - Descripción de lo observado al paciente.
   - Indicaciones y medicamentos.
   - **Reprogramación** con el mismo médico, paciente y centro.

6. **Portabilidad total**
   - Detecta el puerto de MySQL (3306, 3307, 3308, 3309 o 3009).
   - Crea la base de datos si no existe e importa el esquema inicial.
   - Enlaces relativos: funciona en cualquier IP y puerto de XAMPP.

7. **Base de datos y documentación**
   - Base completa en **`BD/bd_primelab.sql`**.
   - Documentación formal renovada: **`Documentacion_Proceso_PrimeLab.docx`**.
   - Código completo: **`CODIGO_COMPLETO.md`**.

## Cómo abrirlo

1. Inicia **Apache** y **MySQL** en XAMPP.
2. Copia la carpeta `PrimeLab` en `C:\xampp\htdocs\`.
3. Abre `http://localhost/PrimeLab/`.
4. (Opcional) Importa `BD/bd_primelab.sql` en phpMyAdmin.

## Credenciales rápidas

| Rol | Usuario | Contraseña |
|---|---|---|
| Administrador principal | `prime` · `primelab@centrosalud.pe` | `pr1m3l4b` |
| Administrador 2 | `admin2` · `gestion@centrosalud.pe` | `PrimeLab2026` |
| Administrador 3 | `admin3` · `soporte@centrosalud.pe` | `PrimeLab2026` |
| Médico | CMP `10234` (Dra. Ana Torres Mendoza) | — |
| Paciente | DNI `45678912` | `45678912` |
| Paciente demo | DNI `10000000` | `10000000` |

> Recuerda: el sistema debe abrirse con **Apache**, no con Live Server.
