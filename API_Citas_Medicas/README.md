# API de usuarios – Sistema de Reserva de Citas Médicas

Adaptación educativa de la API de pedidos que usó el profesor en la sesión 9. Reutiliza la misma idea: **Node.js + Express + archivo JSON**, sin base de datos SQL.

**Alcance:** registro y mantenimiento de pacientes. Las rutas `/api/citas` son una propuesta documental y **todavía no están programadas**.

## Pasos para ejecutar

1. Descomprime la carpeta `API_Citas_Medicas`.
2. Abre la carpeta en Visual Studio Code.
3. Abre la terminal y ejecuta `npm install`.
4. Ejecuta `npm start`.
5. Abre RapidAPI Client (o el navegador) y usa `http://localhost:3000`.

> PrimeLab consulta esta API en `http://127.0.0.1:3000` para autocompletar nombres por DNI y para permitir el ingreso de las cuentas registradas aquí.

## Endpoints (estos SÍ funcionan)

| Método | URL | Acción |
|---|---|---|
| GET | `http://localhost:3000/api/usuarios` | Lista usuarios (sin contraseñas) |
| GET | `http://localhost:3000/api/usuarios?dni=61216468` | Busca por DNI |
| GET | `http://localhost:3000/api/usuarios/buscar?dni=61216468` | Búsqueda usada por PrimeLab |
| GET | `http://localhost:3000/api/usuarios/1` | Busca un usuario por ID |
| POST | `http://localhost:3000/api/usuarios` | Registra un usuario |
| PATCH | `http://localhost:3000/api/usuarios/1` | Cambia algunos datos |
| DELETE | `http://localhost:3000/api/usuarios/1` | Elimina usuario |

### POST (Body, JSON)

Solo **nombres**, **apellidos** y **DNI** son obligatorios. El teléfono y el correo son opcionales. Si no se envía contraseña, se asigna automáticamente el DNI.

```json
{
  "nombres": "Julia",
  "apellidos": "Morales Rojas",
  "dni": "87654321",
  "telefono": "987654321",
  "correo": "julia@example.com",
  "contrasena": "87654321"
}
```

### PATCH (Body, JSON)

```json
{ "telefono": "999888777" }
```

Los registros se leen del archivo `data/usuarios.json`. POST, PATCH y DELETE modifican ese archivo. Haz una copia antes de probar.

## Cuentas agregadas

Estas cuentas se agregaron a la API interna con sus nombres y apellidos en formato título (primera letra en mayúscula). Su contraseña es su DNI y el correo/teléfono son opcionales:

| DNI | Apellidos | Nombres |
|---|---|---|
| `61216468` | Sanchez Chinchay | Ademir Andrew Alberto |
| `74519247` | Flores Padilla | Fabbio Giuliano Abedi |
| `72850117` | Villachica Rodriguez | Alexandro David |
| `75532819` | Alvarez Marres | Leandro Francesco |
| `72484597` | Portal Retamoso | Humberto Vicente |
| `72689307` | Aparco Bedia | Estuar Sebastian |
| `61161158` | Valencia Zamora | Eidan |

**Seguridad:** los registros y contraseñas son ficticios. Este ejemplo se usa solo de forma local para aprendizaje. En un sistema real se deben almacenar hashes de contraseñas, usar autenticación y controlar qué pacientes pueden ver o modificar cada registro.
