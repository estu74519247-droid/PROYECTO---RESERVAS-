// API educativa basada en la estructura de la API de pedidos de la sesión 9.
// Almacenamiento temporal en data/usuarios.json, sin base de datos SQL.
const express = require('express');
const fs = require('fs');
const path = require('path');

const app = express();
const PORT = process.env.PORT || 3000;
const DATA_FILE = path.join(__dirname, 'data', 'usuarios.json');
// Solo estos datos son obligatorios. El teléfono y el correo son opcionales
// y la contraseña, si no se envía, se asigna automáticamente igual al DNI.
const CAMPOS = ['nombres', 'apellidos', 'dni', 'telefono', 'correo', 'contrasena'];
const CAMPOS_OBLIGATORIOS = ['nombres', 'apellidos', 'dni'];
app.use(express.json());

// Permite que el sistema PHP (u otro cliente local) consulte esta API.
app.use((req, res, next) => {
  res.header('Access-Control-Allow-Origin', '*');
  res.header('Access-Control-Allow-Methods', 'GET,POST,PATCH,DELETE,OPTIONS');
  res.header('Access-Control-Allow-Headers', 'Content-Type, Accept');
  if (req.method === 'OPTIONS') return res.sendStatus(204);
  next();
});

function leerUsuarios() {
  const data = JSON.parse(fs.readFileSync(DATA_FILE, 'utf8'));
  return data.usuarios;
}
function guardarUsuarios(usuarios) {
  fs.writeFileSync(DATA_FILE, JSON.stringify({ usuarios }, null, 2), 'utf8');
}
function mostrar(usuario) {
  const { contrasena, ...sinContrasena } = usuario;
  return sinContrasena;
}
function aTitulo(texto) {
  return String(texto || '')
    .trim()
    .replace(/\s+/g, ' ')
    .toLocaleLowerCase('es-PE')
    .replace(/(^|[\s'-])([\p{L}])/gu, (m, sep, letra) => sep + letra.toLocaleUpperCase('es-PE'));
}
function extraerDatos(body) {
  const datos = {};
  CAMPOS.forEach(c => {
    if (Object.prototype.hasOwnProperty.call(body, c)) datos[c] = body[c];
  });
  if (datos.nombres !== undefined) datos.nombres = aTitulo(datos.nombres);
  if (datos.apellidos !== undefined) datos.apellidos = aTitulo(datos.apellidos);
  if (datos.dni !== undefined) datos.dni = String(datos.dni).trim();
  if (datos.telefono !== undefined) datos.telefono = String(datos.telefono).trim();
  if (datos.correo !== undefined) datos.correo = String(datos.correo).trim();
  return datos;
}
function validarCampos(datos, parcial = false) {
  if (!parcial) {
    for (const campo of CAMPOS_OBLIGATORIOS) {
      if (typeof datos[campo] !== 'string' || !datos[campo].trim()) {
        return 'Los nombres, apellidos y el DNI son obligatorios.';
      }
    }
  }
  if (Object.entries(datos).some(([k, v]) => typeof v !== 'string')) {
    return 'Los campos enviados deben ser textos.';
  }
  if (datos.nombres !== undefined && !datos.nombres.trim()) return 'Los nombres no pueden estar vacíos.';
  if (datos.apellidos !== undefined && !datos.apellidos.trim()) return 'Los apellidos no pueden estar vacíos.';
  if (datos.nombres !== undefined && !/^[\p{L}\s'’.,\/()\-]+$/u.test(datos.nombres)) {
    return 'Los nombres solo deben contener letras, sin números.';
  }
  if (datos.apellidos !== undefined && !/^[\p{L}\s'’.,\/()\-]+$/u.test(datos.apellidos)) {
    return 'Los apellidos solo deben contener letras, sin números.';
  }
  if (datos.dni !== undefined && !/^\d{8}$/.test(datos.dni)) return 'El DNI debe tener 8 dígitos.';
  if (datos.telefono !== undefined && datos.telefono !== '' && !/^\d{9}$/.test(datos.telefono)) {
    return 'El teléfono debe tener 9 dígitos o quedar vacío.';
  }
  if (datos.correo !== undefined && datos.correo !== '' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(datos.correo)) {
    return 'Correo electrónico no válido.';
  }
  if (datos.contrasena !== undefined && datos.contrasena !== '' && datos.contrasena.length < 6) {
    return 'La contraseña debe tener al menos 6 caracteres o quedar vacía para usar el DNI.';
  }
  return null;
}
function duplicado(usuarios, datos, idActual = null) {
  return usuarios.some(u => u.id !== idActual && (
    (datos.dni !== undefined && u.dni === datos.dni) ||
    (datos.correo !== undefined && datos.correo !== '' && (u.correo || '').toLowerCase() === datos.correo.toLowerCase())
  ));
}
function buscarPorDni(dni) {
  return leerUsuarios().find(u => u.dni === String(dni));
}

app.get('/', (req, res) => res.json({
  mensaje: 'API de usuarios - Sistema de Citas Médicas',
  endpoints: [
    'GET /api/usuarios',
    'GET /api/usuarios/buscar?dni=12345678',
    'GET /api/usuarios/:id',
    'POST /api/usuarios',
    'PATCH /api/usuarios/:id',
    'DELETE /api/usuarios/:id'
  ]
}));

app.get('/api/usuarios', (req, res) => {
  const dni = String(req.query.dni || '').trim();
  const usuarios = leerUsuarios();
  if (dni !== '') {
    const usuario = usuarios.find(u => u.dni === dni);
    if (!usuario) return res.status(404).json({ mensaje: 'Usuario no encontrado' });
    return res.status(200).json(mostrar(usuario));
  }
  res.status(200).json(usuarios.map(mostrar));
});

// Ruta usada por PrimeLab para autocompletar nombres y para permitir el
// ingreso de las cuentas que existen en esta API.
app.get('/api/usuarios/buscar', (req, res) => {
  const dni = String(req.query.dni || '').trim();
  if (!/^\d{8}$/.test(dni)) {
    return res.status(400).json({ mensaje: 'El DNI debe tener 8 dígitos.' });
  }
  const usuario = buscarPorDni(dni);
  if (!usuario) return res.status(404).json({ mensaje: 'Usuario no encontrado' });
  res.status(200).json({ encontrado: true, usuario: mostrar(usuario) });
});

app.get('/api/usuarios/:id', (req, res) => {
  const usuario = leerUsuarios().find(u => u.id === Number(req.params.id));
  if (!usuario) return res.status(404).json({ mensaje: 'Usuario no encontrado' });
  res.status(200).json(mostrar(usuario));
});

app.post('/api/usuarios', (req, res) => {
  const datos = extraerDatos(req.body || {});
  const error = validarCampos(datos);
  if (error) return res.status(400).json({ mensaje: error });
  const usuarios = leerUsuarios();
  if (duplicado(usuarios, datos)) return res.status(409).json({ mensaje: 'El DNI o correo ya está registrado' });
  const id = usuarios.length ? Math.max(...usuarios.map(u => u.id)) + 1 : 1;
  // Si no se indica contraseña, se usa el DNI (regla del sistema).
  const contrasena = (datos.contrasena && datos.contrasena.trim()) ? datos.contrasena.trim() : datos.dni;
  const nuevo = {
    id,
    nombres: datos.nombres,
    apellidos: datos.apellidos,
    dni: datos.dni,
    telefono: datos.telefono || '',
    correo: datos.correo || '',
    contrasena
  };
  usuarios.push(nuevo);
  guardarUsuarios(usuarios);
  res.status(201).json({ mensaje: 'Paciente registrado', usuario: mostrar(nuevo) });
});

app.patch('/api/usuarios/:id', (req, res) => {
  const usuarios = leerUsuarios();
  const id = Number(req.params.id);
  const index = usuarios.findIndex(u => u.id === id);
  if (index === -1) return res.status(404).json({ mensaje: 'Usuario no encontrado' });
  const datos = extraerDatos(req.body || {});
  if (!Object.keys(datos).length) return res.status(400).json({ mensaje: 'Envía al menos un campo válido para actualizar' });
  const error = validarCampos(datos, true);
  if (error) return res.status(400).json({ mensaje: error });
  if (duplicado(usuarios, datos, id)) return res.status(409).json({ mensaje: 'El DNI o correo ya pertenece a otro usuario' });
  if (datos.contrasena !== undefined && datos.contrasena === '') {
    datos.contrasena = usuarios[index].dni;
  }
  usuarios[index] = { ...usuarios[index], ...datos, id };
  guardarUsuarios(usuarios);
  res.status(200).json({ mensaje: 'Paciente actualizado', usuario: mostrar(usuarios[index]) });
});

app.delete('/api/usuarios/:id', (req, res) => {
  const usuarios = leerUsuarios();
  const index = usuarios.findIndex(u => u.id === Number(req.params.id));
  if (index === -1) return res.status(404).json({ mensaje: 'Usuario no encontrado' });
  const [eliminado] = usuarios.splice(index, 1);
  guardarUsuarios(usuarios);
  res.status(200).json({ mensaje: 'Paciente eliminado', usuario: mostrar(eliminado) });
});

app.use((req, res) => res.status(404).json({ mensaje: 'Ruta no encontrada' }));
app.listen(PORT, () => console.log(`API ejecutándose en http://localhost:${PORT}`));
