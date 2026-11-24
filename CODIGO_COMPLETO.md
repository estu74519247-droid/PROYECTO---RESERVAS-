# PrimeLab — Código completo del proyecto

Sistema de Reserva de Citas Médicas · Semana de Innovación 2026

- **Base de datos:** `bd_primelab` (carpeta `BD/`)
- **Administrador principal:** `prime` / `primelab@centrosalud.pe` / `pr1m3l4b`
- **Administradores adicionales:** `admin2` y `admin3` (clave `PrimeLab2026`)
- **Pacientes:** contraseña = DNI (64 cuentas)
- **Médicos:** acceso por CMP (17 cuentas)
- **Generado:** 18/11/2026 15:05

## Contenido

1. `BD/bd_primelab.sql`
2. `BD/migracion_v2.sql`
3. `BD/migracion_v3_datos.sql`
4. `config.php`
5. `file_conexion.php`
6. `index.html`
7. `login_paciente.php`
8. `consulta_dni.php`
9. `registro.html`
10. `registro.php`
11. `medico_login.html`
12. `login_medico.php`
13. `panel_medico.php`
14. `admin_login.html`
15. `login.php`
16. `logout.php`
17. `panel.php`
18. `reservar.php`
19. `ticket.php`
20. `mis_citas.php`
21. `perfil.php`
22. `admin_citas.php`
23. `admin_medicos.php`
24. `admin_centros.php`
25. `admin_consultas.php`
26. `consultas.php`
27. `css/style.css`
28. `js/main.js`
29. `datos/usuarios.json`
30. `API_Citas_Medicas/server.js`
31. `API_Citas_Medicas/package.json`
32. `API_Citas_Medicas/README.md`
33. `README.md`
34. `LEEME_ENTREGA.md`
35. `img/medicos/MED001.svg`
36. `img/medicos/MED002.svg`
37. `img/medicos/MED003.svg`
38. `img/medicos/MED004.svg`
39. `img/medicos/MED005.svg`
40. `img/medicos/MED006.svg`
41. `img/medicos/MED007.svg`
42. `img/medicos/MED008.svg`
43. `img/medicos/MED009.svg`
44. `img/medicos/MED010.svg`
45. `img/medicos/MED011.svg`
46. `img/medicos/MED012.svg`
47. `img/medicos/MED013.svg`
48. `img/medicos/MED014.svg`
49. `img/medicos/MED015.svg`
50. `img/medicos/MED016.svg`
51. `img/medicos/MED017.svg`
52. `img/PrimeLab Logo.png`
53. `Usuarios_PrimeLab.xlsx`
54. `Documentacion_Proceso_PrimeLab.docx`

---

## `BD/bd_primelab.sql`

````sql
-- phpMyAdmin SQL Dump
-- PrimeLab - Sistema de Reserva de Citas Médicas
-- Base de datos enriquecida con información más detallada
-- Versión 5.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `bd_primelab` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci;
USE `bd_primelab`;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------
-- Tabla `centro`
-- --------------------------------------------------------

CREATE TABLE `centro` (
  `codce` char(6) NOT NULL,
  `nomce` varchar(80) NOT NULL,
  `dirc` varchar(100) NOT NULL,
  `distrito` varchar(50) NOT NULL,
  `provincia` varchar(40) NOT NULL DEFAULT 'Lima',
  `departamento` varchar(40) NOT NULL DEFAULT 'Lima',
  `telefono` varchar(15) NOT NULL,
  `horario_atencion` varchar(60) NOT NULL DEFAULT 'Lunes a Viernes 07:00 - 17:00',
  `tipo` varchar(20) NOT NULL DEFAULT 'Público'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

INSERT INTO `centro` (`codce`, `nomce`, `dirc`, `distrito`, `provincia`, `departamento`, `telefono`, `horario_atencion`, `tipo`) VALUES
('CEN001', 'Centro de Salud San Martín de Porres', 'Av. Perú 1250, Urb. San Martín', 'Cercado de Lima', 'Lima', 'Lima', '014567890', 'Lunes a Sábado 07:00 - 19:00', 'Público'),
('CEN002', 'Centro de Salud Santa Rosa - SJL', 'Jr. Los Jardines 420, AA.HH. Santa Rosa', 'San Juan de Lurigancho', 'Lima', 'Lima', '014321876', 'Lunes a Viernes 07:00 - 17:00', 'Público'),
('CEN003', 'Centro Materno Infantil Surco', 'Av. Caminos del Inca 730, Urb. Los Álamos', 'Santiago de Surco', 'Lima', 'Lima', '012345678', 'Lunes a Sábado 07:00 - 18:00', 'Público'),
('CEN004', 'Centro de Salud Jesús María', 'Av. Horacio Urteaga 950, Residencial San Felipe', 'Jesús María', 'Lima', 'Lima', '014637281', 'Lunes a Viernes 07:00 - 17:00', 'Público'),
('CEN005', 'Policlínico Municipal Los Olivos', 'Av. Carlos Izaguirre 820, Urb. Sol de Oro', 'Los Olivos', 'Lima', 'Lima', '015238476', 'Lunes a Sábado 07:00 - 19:00', 'Público'),
('CEN006', 'Centro Médico Integral Villa El Salvador', 'Av. Revolución 1530, Sector 3 Grupo 10', 'Villa El Salvador', 'Lima', 'Lima', '012938475', 'Lunes a Viernes 07:00 - 17:00', 'Público'),
('CEN007', 'Centro de Salud Breña', 'Jr. Zorritos 1234', 'Breña', 'Lima', 'Lima', '014298763', 'Lunes a Viernes 07:00 - 17:00', 'Público'),
('CEN008', 'Centro Materno Infantil Comas', 'Av. Túpac Amaru 3450', 'Comas', 'Lima', 'Lima', '015432187', 'Lunes a Sábado 07:00 - 18:00', 'Público');

-- --------------------------------------------------------
-- Tabla `especialidad`
-- --------------------------------------------------------

CREATE TABLE `especialidad` (
  `codesp` char(6) NOT NULL,
  `nomesp` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

INSERT INTO `especialidad` (`codesp`, `nomesp`, `descripcion`) VALUES
('ESP001', 'Medicina General', 'Atención primaria integral, diagnóstico y tratamiento de enfermedades comunes'),
('ESP002', 'Cardiología', 'Diagnóstico y tratamiento de enfermedades del corazón y sistema cardiovascular'),
('ESP003', 'Pediatría', 'Atención integral a niños, niñas y adolescentes'),
('ESP004', 'Traumatología y Ortopedia', 'Lesiones óseas, articulares, musculares y rehabilitación'),
('ESP005', 'Ginecología y Obstetricia', 'Salud reproductiva femenina, embarazo, parto y postparto'),
('ESP006', 'Dermatología', 'Enfermedades de la piel, cabello, uñas y tratamientos dermatológicos'),
('ESP007', 'Nutrición y Dietética', 'Evaluación nutricional, dietoterapia y prevención de enfermedades'),
('ESP008', 'Oftalmología', 'Diagnóstico y tratamiento de enfermedades oculares y corrección visual'),
('ESP009', 'Odontología', 'Salud bucodental, prevención, diagnóstico y tratamiento'),
('ESP010', 'Psicología Clínica', 'Evaluación, diagnóstico y terapia psicológica integral'),
('ESP011', 'Neurología', 'Enfermedades del sistema nervioso central y periférico'),
('ESP012', 'Otorrinolaringología', 'Enfermedades de oído, nariz, garganta y cabeza-cuello'),
('ESP013', 'Urología', 'Tracto urinario masculino y femenino, sistema reproductor masculino'),
('ESP014', 'Medicina Interna', 'Atención integral al adulto con múltiples patologías'),
('ESP015', 'Endocrinología', 'Trastornos hormonales, diabetes, tiroides y metabolismo'),
('ESP016', 'Gastroenterología', 'Enfermedades del aparato digestivo y hepatobiliares'),
('ESP017', 'Neumología', 'Enfermedades respiratorias, asma, EPOC y tuberculosis');

-- --------------------------------------------------------
-- Tabla `centro_especialidad`
-- --------------------------------------------------------

CREATE TABLE `centro_especialidad` (
  `codce` char(6) NOT NULL,
  `codesp` char(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

INSERT INTO `centro_especialidad` (`codce`, `codesp`) VALUES
('CEN001', 'ESP001'),('CEN001', 'ESP003'),('CEN001', 'ESP008'),('CEN001', 'ESP009'),('CEN001', 'ESP010'),('CEN001', 'ESP014'),
('CEN002', 'ESP001'),('CEN002', 'ESP005'),('CEN002', 'ESP009'),('CEN002', 'ESP012'),('CEN002', 'ESP014'),('CEN002', 'ESP016'),
('CEN003', 'ESP001'),('CEN003', 'ESP003'),('CEN003', 'ESP005'),('CEN003', 'ESP008'),('CEN003', 'ESP010'),('CEN003', 'ESP015'),
('CEN004', 'ESP001'),('CEN004', 'ESP002'),('CEN004', 'ESP007'),('CEN004', 'ESP011'),('CEN004', 'ESP014'),('CEN004', 'ESP016'),
('CEN005', 'ESP001'),('CEN005', 'ESP002'),('CEN005', 'ESP003'),('CEN005', 'ESP006'),('CEN005', 'ESP009'),('CEN005', 'ESP013'),('CEN005', 'ESP017'),
('CEN006', 'ESP001'),('CEN006', 'ESP004'),('CEN006', 'ESP005'),('CEN006', 'ESP010'),('CEN006', 'ESP012'),('CEN006', 'ESP007'),
('CEN007', 'ESP001'),('CEN007', 'ESP003'),('CEN007', 'ESP009'),('CEN007', 'ESP010'),('CEN007', 'ESP014'),
('CEN008', 'ESP001'),('CEN008', 'ESP003'),('CEN008', 'ESP005'),('CEN008', 'ESP007'),('CEN008', 'ESP008'),('CEN008', 'ESP009'),('CEN008', 'ESP017');

-- --------------------------------------------------------
-- Tabla `medico`
-- --------------------------------------------------------

CREATE TABLE `medico` (
  `codm` char(6) NOT NULL,
  `nomm` varchar(80) NOT NULL,
  `cmpm` varchar(10) NOT NULL,
  `codesp` char(6) NOT NULL,
  `dni` char(8) DEFAULT NULL,
  `telefono` varchar(9) DEFAULT NULL,
  `correo` varchar(80) DEFAULT NULL,
  `anios_experiencia` int(3) DEFAULT 0,
  `formacion` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

INSERT INTO `medico` (`codm`, `nomm`, `cmpm`, `codesp`, `dni`, `telefono`, `correo`, `anios_experiencia`, `formacion`) VALUES
('MED001', 'Dra. Ana Torres Mendoza', '10234', 'ESP001', '07896543', '987123456', 'ana.torres@primelab.pe', 15, 'UNMSM - Medicina Humana'),
('MED002', 'Dr. Luis Alberto Mendoza Quispe', '18542', 'ESP002', '09234156', '986234567', 'luis.mendoza@primelab.pe', 18, 'UNMSM - Medicina Humana, Especialización en Cardiología'),
('MED003', 'Dra. Rosa María Salazar Huamán', '21306', 'ESP003', '05678912', '985345678', 'rosa.salazar@primelab.pe', 12, 'UPCH - Medicina Humana, Especialización en Pediatría'),
('MED004', 'Dr. Carlos Eduardo Ramírez Castro', '24618', 'ESP004', '06789123', '984456789', 'carlos.ramirez@primelab.pe', 16, 'UNMSM - Traumatología y Ortopedia'),
('MED005', 'Dra. Elena Beatriz Paredes López', '27190', 'ESP005', '07891234', '983567890', 'elena.paredes@primelab.pe', 14, 'UPCH - Ginecología y Obstetricia'),
('MED006', 'Dr. Miguel Ángel Cárdenas Vega', '30412', 'ESP006', '08912345', '982678901', 'miguel.cardenas@primelab.pe', 10, 'UNMSM - Dermatología'),
('MED007', 'Dra. Patricia Lucía León Gutiérrez', '32874', 'ESP007', '09123456', '981789012', 'patricia.leon@primelab.pe', 9, 'UPCH - Nutrición Clínica'),
('MED008', 'Dr. Javier Fernando Quispe Yupanqui', '35210', 'ESP008', '09234567', '980890123', 'javier.quispe@primelab.pe', 11, 'UNMSM - Oftalmología'),
('MED009', 'Dra. Carmen Rosa Flores Aguirre', '36741', 'ESP009', '09345678', '979901234', 'carmen.flores@primelab.pe', 13, 'UPCH - Estomatología/Odontología'),
('MED010', 'Dr. Ricardo Martín Huamán Córdova', '38902', 'ESP010', '09456789', '978012345', 'ricardo.huaman@primelab.pe', 12, 'UNMSM - Psicología Clínica'),
('MED011', 'Dra. Sofía Alejandra Vargas Ponce', '40156', 'ESP011', '09567890', '977123456', 'sofia.vargas@primelab.pe', 17, 'UNMSM - Neurología'),
('MED012', 'Dr. Diego Armando Castillo Rojas', '42588', 'ESP012', '09678901', '976234567', 'diego.castillo@primelab.pe', 14, 'UPCH - Otorrinolaringología'),
('MED013', 'Dr. Fernando José Tello Morales', '44213', 'ESP013', '09789012', '975345678', 'fernando.tello@primelab.pe', 16, 'UNMSM - Urología'),
('MED014', 'Dra. Gabriela Inés Ortiz Villanueva', '45876', 'ESP014', '09890123', '974456789', 'gabriela.ortiz@primelab.pe', 19, 'UNMSM - Medicina Interna'),
('MED015', 'Dr. Hugo César Reyes Bustamante', '47624', 'ESP015', '09901234', '973567890', 'hugo.reyes@primelab.pe', 15, 'UPCH - Endocrinología'),
('MED016', 'Dra. Lucía Fernanda Prado Salinas', '49238', 'ESP016', '08123456', '972678901', 'lucia.prado@primelab.pe', 13, 'UNMSM - Gastroenterología'),
('MED017', 'Dr. Andrés Felipe Mena Zegarra', '51692', 'ESP017', '08234567', '971789012', 'andres.mena@primelab.pe', 14, 'UPCH - Neumología');

-- --------------------------------------------------------
-- Tabla `paciente`
-- --------------------------------------------------------

CREATE TABLE `paciente` (
  `codp` char(6) NOT NULL,
  `nomp` varchar(40) NOT NULL,
  `apep` varchar(40) NOT NULL,
  `dni` char(8) NOT NULL,
  `telp` char(9) DEFAULT NULL,
  `correo` varchar(80) DEFAULT NULL,
  `contrasena` varchar(60) DEFAULT NULL,
  `seguro` varchar(10) NOT NULL DEFAULT 'SIS',
  `fechanac` date DEFAULT NULL,
  `direccion` varchar(120) DEFAULT NULL,
  `distrito` varchar(50) DEFAULT NULL,
  `fechareg` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

INSERT INTO `paciente` (`codp`, `nomp`, `apep`, `dni`, `telp`, `correo`, `contrasena`, `seguro`, `fechanac`, `direccion`, `distrito`, `fechareg`) VALUES
('PAC001', 'Ana', 'Torres Mendoza', '45678912', '987654321', 'ana.torres@correo.com', '45678912', 'SIS', '1985-04-12', 'Av. Tacna 456', 'Cercado de Lima', '2026-11-01'),
('PAC002', 'Luis', 'Ramos Cárdenas', '41234567', '912345678', 'luis.ramos@correo.com', '41234567', 'EPS', '1990-08-25', 'Jr. Amazonas 789', 'San Juan de Lurigancho', '2026-11-05'),
('PAC003', 'Eidan', 'Valencia Zamora', '61161158', '994784726', 'estu61161158@institutobolivar.edu.pe', '61161158', 'Privado', '1992-12-03', 'Av. Benavides 1234', 'Santiago de Surco', '2026-11-12'),
('PAC004', 'María', 'Gonzales Flores', '42345678', '965432187', 'maria.gonzales@correo.com', '42345678', 'SIS', '1978-06-18', 'Calle Los Pinos 234', 'Los Olivos', '2026-11-13'),
('PAC005', 'José', 'Pérez Rodríguez', '43456789', '954321876', 'jose.perez@correo.com', '43456789', 'EPS', '1982-03-09', 'Av. Universitaria 567', 'Comas', '2026-11-14'),
('PAC006', 'Carmen', 'López Vásquez', '44567890', '943218765', 'carmen.lopez@correo.com', '44567890', 'Privado', '1988-11-22', 'Jr. Huancayo 890', 'Breña', '2026-11-15'),
('PAC007', 'Pedro', 'Martínez Silva', '45678901', '932187654', 'pedro.martinez@correo.com', '45678901', 'SIS', '1995-07-14', 'Av. El Sol 321', 'Villa El Salvador', '2026-11-16'),
('PAC008', 'Rosa', 'Castillo Herrera', '46789012', '921876543', 'rosa.castillo@correo.com', '46789012', 'EPS', '1986-09-30', 'Calle Las Flores 654', 'Jesús María', '2026-11-17'),
('PAC009', 'Ademir Andrew Alberto', 'Sanchez Chinchay', '61216468', '972135089', 'estu61216468@institutobolivar.edu.pe', '61216468', 'SIS', NULL, NULL, NULL, '2026-11-16'),
('PAC010', 'Fabbio Giuliano Abedi', 'Flores Padilla', '74519247', '977473318', 'estu74519247@institutobolivar.edu.pe', '74519247', 'SIS', NULL, NULL, NULL, '2026-11-16'),
('PAC011', 'Alexandro David', 'Villachica Rodriguez', '72850117', '987751145', 'estu72850117@institutobolivar.edu.pe', '72850117', 'SIS', NULL, NULL, NULL, '2026-11-16'),
('PAC012', 'Leandro Francesco', 'Alvarez Marres', '75532819', '902323479', 'estu75532819@institutobolivar.edu.pe', '75532819', 'SIS', NULL, NULL, NULL, '2026-11-16'),
('PAC013', 'Humberto Vicente', 'Portal Retamoso', '72484597', '969424717', 'estu72484597@institutobolivar.edu.pe', '72484597', 'SIS', NULL, NULL, NULL, '2026-11-16'),
('PAC014', 'Estuar Sebastian', 'Aparco Bedia', '72689307', '904866021', 'estu72689307@institutobolivar.edu.pe', '72689307', 'SIS', NULL, NULL, NULL, '2026-11-16'),
('PAC015', 'Ana', 'García Chávez', '10000000', '900100000', 'usuario01@example.com', '10000000', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC016', 'Luis', 'Rodríguez Ortiz', '10000001', '900100001', 'usuario02@example.com', '10000001', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC017', 'María', 'Pérez Silva', '10000002', '900100002', 'usuario03@example.com', '10000002', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC018', 'Carlos', 'López Cárdenas', '10000003', '900100003', 'usuario04@example.com', '10000003', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC019', 'Sofía', 'Torres Cabrera', '10000004', '900100004', 'usuario05@example.com', '10000004', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC020', 'José', 'Flores Tapia', '10000005', '900100005', 'usuario06@example.com', '10000005', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC021', 'Valeria', 'Ramírez Flores', '10000006', '900100006', 'usuario07@example.com', '10000006', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC022', 'Miguel', 'Sánchez Mendoza', '10000007', '900100007', 'usuario08@example.com', '10000007', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC023', 'Camila', 'Díaz Morales', '10000008', '900100008', 'usuario09@example.com', '10000008', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC024', 'Jorge', 'Castro Huamán', '10000009', '900100009', 'usuario10@example.com', '10000009', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC025', 'Lucía', 'Vargas Salazar', '10000010', '900100010', 'usuario11@example.com', '10000010', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC026', 'Diego', 'Rojas Valdez', '10000011', '900100011', 'usuario12@example.com', '10000011', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC027', 'Daniela', 'Mendoza Palacios', '10000012', '900100012', 'usuario13@example.com', '10000012', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC028', 'Pedro', 'Chávez Torres', '10000013', '900100013', 'usuario14@example.com', '10000013', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC029', 'Andrea', 'Ruiz Rojas', '10000014', '900100014', 'usuario15@example.com', '10000014', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC030', 'Ricardo', 'Herrera Reyes', '10000015', '900100015', 'usuario16@example.com', '10000015', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC031', 'Paola', 'Medina Quispe', '10000016', '900100016', 'usuario17@example.com', '10000016', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC032', 'Fernando', 'Cruz Navarro', '10000017', '900100017', 'usuario18@example.com', '10000017', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC033', 'Gabriela', 'Reyes Peña', '10000018', '900100018', 'usuario19@example.com', '10000018', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC034', 'Alonso', 'Morales Suárez', '10000019', '900100019', 'usuario20@example.com', '10000019', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC035', 'Claudia', 'Ortiz López', '10000020', '900100020', 'usuario21@example.com', '10000020', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC036', 'Martín', 'Gutiérrez Vargas', '10000021', '900100021', 'usuario22@example.com', '10000021', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC037', 'Natalia', 'Vega Cruz', '10000022', '900100022', 'usuario23@example.com', '10000022', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC038', 'Ángel', 'Ramos Paredes', '10000023', '900100023', 'usuario24@example.com', '10000023', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC039', 'Rosa', 'Paredes Fernández', '10000024', '900100024', 'usuario25@example.com', '10000024', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC040', 'Kevin', 'Quispe Molina', '10000025', '900100025', 'usuario26@example.com', '10000025', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC041', 'Fiorella', 'Huamán Miranda', '10000026', '900100026', 'usuario27@example.com', '10000026', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC042', 'Bruno', 'Silva Pérez', '10000027', '900100027', 'usuario28@example.com', '10000027', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC043', 'Patricia', 'Espinoza Castro', '10000028', '900100028', 'usuario29@example.com', '10000028', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC044', 'Andrés', 'Campos Medina', '10000029', '900100029', 'usuario30@example.com', '10000029', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC045', 'Milagros', 'Aguilar Ramos', '10000030', '900100030', 'usuario31@example.com', '10000030', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC046', 'Eduardo', 'Fernández Aguilar', '10000031', '900100031', 'usuario32@example.com', '10000031', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC047', 'Mónica', 'Navarro Acosta', '10000032', '900100032', 'usuario33@example.com', '10000032', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC048', 'Sebastián', 'Salazar León', '10000033', '900100033', 'usuario34@example.com', '10000033', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC049', 'Ximena', 'Cárdenas Rodríguez', '10000034', '900100034', 'usuario35@example.com', '10000034', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC050', 'Raúl', 'Delgado Díaz', '10000035', '900100035', 'usuario36@example.com', '10000035', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC051', 'Isabel', 'Romero Herrera', '10000036', '900100036', 'usuario37@example.com', '10000036', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC052', 'Franco', 'Acosta Vega', '10000037', '900100037', 'usuario38@example.com', '10000037', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC053', 'Elena', 'Molina Campos', '10000038', '900100038', 'usuario39@example.com', '10000038', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC054', 'Marco', 'Peña Romero', '10000039', '900100039', 'usuario40@example.com', '10000039', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC055', 'Carmen', 'Valdez Fuentes', '10000040', '900100040', 'usuario41@example.com', '10000040', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC056', 'Óscar', 'Cabrera García', '10000041', '900100041', 'usuario42@example.com', '10000041', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC057', 'Adriana', 'Bravo Sánchez', '10000042', '900100042', 'usuario43@example.com', '10000042', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC058', 'Piero', 'Fuentes Ruiz', '10000043', '900100043', 'usuario44@example.com', '10000043', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC059', 'Silvia', 'León Gutiérrez', '10000044', '900100044', 'usuario45@example.com', '10000044', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC060', 'Renzo', 'Miranda Espinoza', '10000045', '900100045', 'usuario46@example.com', '10000045', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC061', 'Teresa', 'Suárez Delgado', '10000046', '900100046', 'usuario47@example.com', '10000046', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC062', 'Gustavo', 'Palacios Bravo', '10000047', '900100047', 'usuario48@example.com', '10000047', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC063', 'Juliana', 'Tapia Núñez', '10000048', '900100048', 'usuario49@example.com', '10000048', 'SIS', NULL, NULL, NULL, '2026-11-18'),
('PAC064', 'Héctor', 'Núñez Ramírez', '10000049', '900100049', 'usuario50@example.com', '10000049', 'SIS', NULL, NULL, NULL, '2026-11-18');

-- --------------------------------------------------------
-- Tabla `administrador`
-- --------------------------------------------------------

CREATE TABLE `administrador` (
  `idadm` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(30) NOT NULL,
  `correo` varchar(80) NOT NULL,
  `contrasena` varchar(60) NOT NULL,
  `nombre` varchar(80) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`idadm`),
  UNIQUE KEY `uq_admin_usuario` (`usuario`),
  UNIQUE KEY `uq_admin_correo` (`correo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

INSERT INTO `administrador` (`idadm`, `usuario`, `correo`, `contrasena`, `nombre`, `activo`) VALUES
(1, 'prime', 'primelab@centrosalud.pe', 'pr1m3l4b', 'Administrador principal', 1),
(2, 'admin2', 'gestion@centrosalud.pe', 'PrimeLab2026', 'Coordinación de citas', 1),
(3, 'admin3', 'soporte@centrosalud.pe', 'PrimeLab2026', 'Soporte técnico', 1);

-- --------------------------------------------------------
-- Tabla `cita`
-- --------------------------------------------------------

CREATE TABLE `cita` (
  `codci` char(6) NOT NULL,
  `fechaci` date NOT NULL,
  `horaci` time NOT NULL,
  `motivo` varchar(255) NOT NULL,
  `estado` varchar(15) NOT NULL DEFAULT 'pendiente',
  `costo` decimal(8,2) NOT NULL DEFAULT 0.00,
  `codp` char(6) NOT NULL,
  `codm` char(6) NOT NULL,
  `codce` char(6) NOT NULL,
  `fechareg` datetime NOT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `diagnostico` text DEFAULT NULL,
  `indicaciones` text DEFAULT NULL,
  `motivo_reprogramacion` varchar(255) DEFAULT NULL,
  `reprogramada_de` char(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

INSERT INTO `cita` (`codci`, `fechaci`, `horaci`, `motivo`, `estado`, `costo`, `codp`, `codm`, `codce`, `fechareg`, `observaciones`, `diagnostico`, `indicaciones`, `motivo_reprogramacion`, `reprogramada_de`) VALUES
('CIT001', '2026-11-20', '09:00:00', 'Control general y dolor de cabeza recurrente por 3 días.', 'pendiente', 0.00, 'PAC001', 'MED001', 'CEN001', '2026-11-08 10:15:00', NULL, NULL, NULL, NULL, NULL),
('CIT002', '2026-11-18', '10:30:00', 'Evaluación pediátrica de control anual. Crecimiento y desarrollo.', 'pendiente', 20.00, 'PAC002', 'MED003', 'CEN003', '2026-11-09 16:40:00', NULL, NULL, NULL, NULL, NULL),
('CIT003', '2026-10-30', '08:00:00', 'Chequeo cardiológico anual - Electrocardiograma preventivo.', 'cancelada', 0.00, 'PAC001', 'MED002', 'CEN004', '2026-10-25 09:05:00', 'Cancelada por el paciente - Motivos laborales', NULL, NULL, 'Inasistencia del paciente', NULL),
('CIT004', '2026-11-26', '11:00:00', 'Consulta por malestar general, fiebre y dolor muscular.', 'pendiente', 50.00, 'PAC003', 'MED002', 'CEN004', '2026-11-12 14:51:49', NULL, NULL, NULL, NULL, NULL),
('CIT005', '2026-11-22', '09:30:00', 'Control de diabetes mellitus tipo 2. Revisión de exámenes.', 'pendiente', 0.00, 'PAC004', 'MED014', 'CEN001', '2026-11-13 08:20:00', NULL, NULL, NULL, NULL, NULL),
('CIT006', '2026-11-23', '14:00:00', 'Consulta ginecológica - Control prenatal rutinario.', 'pendiente', 20.00, 'PAC005', 'MED005', 'CEN002', '2026-11-14 11:35:00', NULL, NULL, NULL, NULL, NULL),
('CIT007', '2026-11-24', '15:30:00', 'Evaluación dermatológica - Lesión cutánea en brazo derecho.', 'pendiente', 50.00, 'PAC006', 'MED006', 'CEN005', '2026-11-15 09:45:00', NULL, NULL, NULL, NULL, NULL),
('CIT008', '2026-11-21', '10:00:00', 'Consulta odontológica - Dolor en molar inferior derecho.', 'atendida', 0.00, 'PAC007', 'MED009', 'CEN008', '2026-11-16 13:10:00', 'Atención completada', 'Caries dental en pieza 46, inflamación gingival leve.', 'Obturación realizada, ibuprofeno 400 mg cada 8h por 3 días, enjuague bucal y control en 15 días.', NULL, NULL),
('CIT009', '2026-11-25', '16:00:00', 'Evaluación nutricional para control de peso.', 'pendiente', 20.00, 'PAC008', 'MED007', 'CEN004', '2026-11-17 10:25:00', NULL, NULL, NULL, NULL, NULL),
('CIT010', '2026-11-27', '08:30:00', 'Consulta traumatológica - Dolor de rodilla tras caída.', 'pendiente', 50.00, 'PAC003', 'MED004', 'CEN006', '2026-11-18 15:40:00', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------
-- Tabla `cita_historial`
-- --------------------------------------------------------

CREATE TABLE `cita_historial` (
  `idh` int(11) NOT NULL,
  `codci` char(6) NOT NULL,
  `accion` varchar(20) NOT NULL,
  `motivo` varchar(255) NOT NULL,
  `fecha` datetime NOT NULL,
  `usuario_accion` varchar(40) DEFAULT 'Sistema'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

INSERT INTO `cita_historial` (`idh`, `codci`, `accion`, `motivo`, `fecha`, `usuario_accion`) VALUES
(1, 'CIT003', 'cancelacion', 'El paciente no pudo asistir por motivos laborales.', '2026-10-28 18:20:00', 'PAC001');

-- --------------------------------------------------------
-- Índices
-- --------------------------------------------------------

ALTER TABLE `centro`
  ADD PRIMARY KEY (`codce`);

ALTER TABLE `especialidad`
  ADD PRIMARY KEY (`codesp`);

ALTER TABLE `centro_especialidad`
  ADD PRIMARY KEY (`codce`,`codesp`),
  ADD KEY `fk_ce_especialidad` (`codesp`);

ALTER TABLE `medico`
  ADD PRIMARY KEY (`codm`),
  ADD UNIQUE KEY `uq_medico_cmp` (`cmpm`),
  ADD KEY `fk_medico_especialidad` (`codesp`);

ALTER TABLE `paciente`
  ADD PRIMARY KEY (`codp`),
  ADD UNIQUE KEY `uq_paciente_dni` (`dni`),
  ADD UNIQUE KEY `uq_paciente_correo` (`correo`);

ALTER TABLE `cita`
  ADD PRIMARY KEY (`codci`),
  ADD KEY `fk_cita_paciente` (`codp`),
  ADD KEY `fk_cita_medico` (`codm`),
  ADD KEY `fk_cita_centro` (`codce`),
  ADD KEY `ix_cita_agenda` (`codm`,`fechaci`,`horaci`);

ALTER TABLE `cita_historial`
  ADD PRIMARY KEY (`idh`),
  ADD KEY `fk_historial_cita` (`codci`);

-- --------------------------------------------------------
-- AUTO_INCREMENT
-- --------------------------------------------------------

ALTER TABLE `cita_historial`
  MODIFY `idh` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

-- --------------------------------------------------------
-- Restricciones (claves foráneas)
-- --------------------------------------------------------

ALTER TABLE `centro_especialidad`
  ADD CONSTRAINT `fk_ce_centro` FOREIGN KEY (`codce`) REFERENCES `centro` (`codce`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ce_especialidad` FOREIGN KEY (`codesp`) REFERENCES `especialidad` (`codesp`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `medico`
  ADD CONSTRAINT `fk_medico_especialidad` FOREIGN KEY (`codesp`) REFERENCES `especialidad` (`codesp`) ON UPDATE CASCADE;

ALTER TABLE `cita`
  ADD CONSTRAINT `fk_cita_centro` FOREIGN KEY (`codce`) REFERENCES `centro` (`codce`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cita_medico` FOREIGN KEY (`codm`) REFERENCES `medico` (`codm`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cita_paciente` FOREIGN KEY (`codp`) REFERENCES `paciente` (`codp`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `cita_historial`
  ADD CONSTRAINT `fk_historial_cita` FOREIGN KEY (`codci`) REFERENCES `cita` (`codci`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
````

## `BD/migracion_v2.sql`

````sql
-- ============================================================
-- PrimeLab — Migración v2
-- Agrega contraseña (DNI), permite teléfono opcional y registra
-- las cuentas nuevas en una base de datos ya existente.
-- Ejecutar UNA VEZ en phpMyAdmin sobre la base bd_primelab.
-- ============================================================

USE `bd_primelab`;

-- 1) Columna `contrasena` (solo si no existe)
SET @existe_col := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'paciente'
    AND COLUMN_NAME = 'contrasena'
);
SET @sql_col := IF(
  @existe_col = 0,
  'ALTER TABLE `paciente` ADD COLUMN `contrasena` VARCHAR(60) DEFAULT NULL AFTER `correo`',
  'SELECT 1'
);
PREPARE stmt_col FROM @sql_col;
EXECUTE stmt_col;
DEALLOCATE PREPARE stmt_col;

-- 2) Teléfono opcional para cuentas importadas sin número
ALTER TABLE `paciente` MODIFY `telp` CHAR(9) NULL DEFAULT NULL;

-- 3) Contraseña inicial = DNI para todas las cuentas existentes
UPDATE `paciente`
SET `contrasena` = `dni`
WHERE `contrasena` IS NULL OR `contrasena` = '';

-- 4) Cuentas nuevas de la API interna
INSERT INTO `paciente`
  (`codp`, `nomp`, `apep`, `dni`, `telp`, `correo`, `contrasena`, `seguro`, `fechanac`, `direccion`, `distrito`, `fechareg`)
VALUES
  ('PAC009', 'Ademir Andrew Alberto', 'Sanchez Chinchay', '61216468', '972135089', 'estu61216468@institutobolivar.edu.pe', '61216468', 'SIS', NULL, NULL, NULL, CURDATE()),
  ('PAC010', 'Fabbio Giuliano Abedi', 'Flores Padilla', '74519247', '977473318', 'estu74519247@institutobolivar.edu.pe', '74519247', 'SIS', NULL, NULL, NULL, CURDATE()),
  ('PAC011', 'Alexandro David', 'Villachica Rodriguez', '72850117', '987751145', 'estu72850117@institutobolivar.edu.pe', '72850117', 'SIS', NULL, NULL, NULL, CURDATE()),
  ('PAC012', 'Leandro Francesco', 'Alvarez Marres', '75532819', '902323479', 'estu75532819@institutobolivar.edu.pe', '75532819', 'SIS', NULL, NULL, NULL, CURDATE()),
  ('PAC013', 'Humberto Vicente', 'Portal Retamoso', '72484597', '969424717', 'estu72484597@institutobolivar.edu.pe', '72484597', 'SIS', NULL, NULL, NULL, CURDATE()),
  ('PAC014', 'Estuar Sebastian', 'Aparco Bedia', '72689307', '904866021', 'estu72689307@institutobolivar.edu.pe', '72689307', 'SIS', NULL, NULL, NULL, CURDATE()),
  ('PAC003', 'Eidan', 'Valencia Zamora', '61161158', '994784726', 'estu61161158@institutobolivar.edu.pe', '61161158', 'Privado', '1992-12-03', 'Av. Benavides 1234', 'Santiago de Surco', '2026-11-12')
ON DUPLICATE KEY UPDATE
  `nomp` = VALUES(`nomp`),
  `apep` = VALUES(`apep`),
  `telp` = VALUES(`telp`),
  `correo` = VALUES(`correo`),
  `contrasena` = VALUES(`contrasena`);

-- 5) Comprobación
SELECT `codp`, `nomp`, `apep`, `dni`, `contrasena`, `seguro`
FROM `paciente`
WHERE `dni` IN ('61216468','74519247','72850117','75532819','72484597','72689307','61161158')
ORDER BY `codp`;
````

## `BD/migracion_v3_datos.sql`

````sql
-- ============================================================
-- PrimeLab — Migración v3 (datos de contacto)
-- Actualiza teléfono y correo institucional de las 7 cuentas.
-- Ejecutar UNA VEZ en phpMyAdmin sobre la base bd_primelab,
-- después de haber importado bd_primelab.sql o migracion_v2.sql.
-- Es seguro ejecutarlo varias veces: solo actualiza esos DNI.
-- ============================================================

USE `bd_primelab`;

UPDATE `paciente` SET
  `telp`   = '972135089',
  `correo` = 'estu61216468@institutobolivar.edu.pe',
  `contrasena` = '61216468'
WHERE `dni` = '61216468';

UPDATE `paciente` SET
  `telp`   = '977473318',
  `correo` = 'estu74519247@institutobolivar.edu.pe',
  `contrasena` = '74519247'
WHERE `dni` = '74519247';

UPDATE `paciente` SET
  `telp`   = '987751145',
  `correo` = 'estu72850117@institutobolivar.edu.pe',
  `contrasena` = '72850117'
WHERE `dni` = '72850117';

UPDATE `paciente` SET
  `telp`   = '902323479',
  `correo` = 'estu75532819@institutobolivar.edu.pe',
  `contrasena` = '75532819'
WHERE `dni` = '75532819';

UPDATE `paciente` SET
  `telp`   = '969424717',
  `correo` = 'estu72484597@institutobolivar.edu.pe',
  `contrasena` = '72484597'
WHERE `dni` = '72484597';

UPDATE `paciente` SET
  `telp`   = '904866021',
  `correo` = 'estu72689307@institutobolivar.edu.pe',
  `contrasena` = '72689307'
WHERE `dni` = '72689307';

UPDATE `paciente` SET
  `telp`   = '994784726',
  `correo` = 'estu61161158@institutobolivar.edu.pe',
  `contrasena` = '61161158'
WHERE `dni` = '61161158';

-- Comprobación
SELECT `codp`, `nomp`, `apep`, `dni`, `telp`, `correo`, `contrasena`
FROM `paciente`
WHERE `dni` IN ('61216468','74519247','72850117','75532819','72484597','72689307','61161158')
ORDER BY `codp`;
````

## `config.php`

````php
<?php
/**
 * config.php — Configuración de conexiones de PrimeLab.
 *
 * El sistema es PORTABLE: funciona en cualquier IP y puerto de XAMPP.
 *  - El puerto de MySQL se detecta automáticamente (3306, 3307, 3308, 3309 o 3009).
 *  - La URL de la API interna se arma con el mismo host desde el que se abre el sitio.
 *  - Si necesitas fijar valores manualmente, crea un archivo `config.local.php`
 *    o define variables de entorno:
 *      PRIMELAB_DB_HOST, PRIMELAB_DB_PORT, PRIMELAB_DB_USER,
 *      PRIMELAB_DB_PASS, PRIMELAB_DB_NAME, PRIMELAB_API_URL
 */

/* -------------------------------------------------------------------------
 * 1) Detección automática del puerto de MySQL/MariaDB de XAMPP
 * ---------------------------------------------------------------------- */
if (!function_exists('primelab_detectar_puerto_mysql')) {
    /**
     * Prueba los puertos habituales de XAMPP y devuelve el primero abierto.
     * Si ninguno responde, devuelve 3306 (valor estándar de MySQL).
     */
    function primelab_detectar_puerto_mysql()
    {
        static $puerto = null;
        if ($puerto !== null) {
            return $puerto;
        }

        $host = defined('PRIMELAB_DB_HOST') ? PRIMELAB_DB_HOST : (getenv('PRIMELAB_DB_HOST') ?: '127.0.0.1');
        $candidatos = [3306, 3307, 3308, 3309, 3009];

        foreach ($candidatos as $p) {
            $socket = @fsockopen($host, $p, $errno, $errstr, 0.4);
            if ($socket) {
                fclose($socket);
                return $puerto = $p;
            }
        }

        return $puerto = 3306;
    }
}

/* -------------------------------------------------------------------------
 * 2) Valores opcionales definidos por el usuario (config.local.php)
 * ---------------------------------------------------------------------- */
$primelab_config_local = __DIR__ . '/config.local.php';
if (is_file($primelab_config_local)) {
    require_once $primelab_config_local;
}

/* -------------------------------------------------------------------------
 * 3) Constantes de conexión
 * ---------------------------------------------------------------------- */
if (!defined('PRIMELAB_DB_HOST')) {
    define('PRIMELAB_DB_HOST', getenv('PRIMELAB_DB_HOST') ?: '127.0.0.1');
}
if (!defined('PRIMELAB_DB_USER')) {
    define('PRIMELAB_DB_USER', getenv('PRIMELAB_DB_USER') ?: 'root');
}
if (!defined('PRIMELAB_DB_PASS')) {
    define('PRIMELAB_DB_PASS', getenv('PRIMELAB_DB_PASS') ?: '');
}
if (!defined('PRIMELAB_DB_NAME')) {
    define('PRIMELAB_DB_NAME', getenv('PRIMELAB_DB_NAME') ?: 'bd_primelab');
}
if (!defined('PRIMELAB_DB_PORT')) {
    $primelab_puerto_env = getenv('PRIMELAB_DB_PORT');
    if ($primelab_puerto_env !== false && $primelab_puerto_env !== '') {
        define('PRIMELAB_DB_PORT', (int) $primelab_puerto_env);
    } else {
        define('PRIMELAB_DB_PORT', primelab_detectar_puerto_mysql());
    }
}

/* -------------------------------------------------------------------------
 * 4) URL de la API interna (Node.js + Express)
 *    Se calcula con el mismo host de la petición para que funcione en LAN.
 * ---------------------------------------------------------------------- */
if (!defined('PRIMELAB_API_URL')) {
    $primelab_api_env = getenv('PRIMELAB_API_URL');
    if ($primelab_api_env) {
        define('PRIMELAB_API_URL', rtrim($primelab_api_env, '/'));
    } else {
        $primelab_host = '127.0.0.1';
        if (!empty($_SERVER['HTTP_HOST'])) {
            $primelab_host = preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST']);
        } elseif (!empty($_SERVER['SERVER_NAME'])) {
            $primelab_host = $_SERVER['SERVER_NAME'];
        }
        if ($primelab_host === '' || $primelab_host === 'localhost') {
            $primelab_host = '127.0.0.1';
        }
        define('PRIMELAB_API_URL', 'http://' . $primelab_host . ':3000');
    }
}
````

## `file_conexion.php`

````php
<?php
// file_conexion.php — Conexión y utilidades comunes (estilo ByteCore/procedural)

require_once __DIR__ . '/config.php';

// Estilo procedural: las funciones mysqli devuelven false en lugar de lanzar
// excepciones, lo que permite crear la base de datos o mostrar un mensaje claro.
if (function_exists('mysqli_report')) {
    mysqli_report(MYSQLI_REPORT_OFF);
}

if (session_status() === PHP_SESSION_NONE && !defined('PRIMELAB_SIN_SESION')) {
    session_start();
}

/** Ejecuta una consulta ignorando errores (para migraciones automáticas). */
function primelab_query($mysqli, $sql)
{
    try {
        return $mysqli->query($sql);
    } catch (\Throwable $e) {
        return false;
    }
}

/** Indica si una tabla tiene una columna determinada. */
function primelab_tiene_columna($mysqli, $tabla, $columna)
{
    $tabla = preg_replace('/[^A-Za-z0-9_]/', '', $tabla);
    $res = primelab_query($mysqli, "SHOW COLUMNS FROM `$tabla` LIKE '" . $mysqli->real_escape_string($columna) . "'");
    return ($res && $res->num_rows > 0);
}

/**
 * Abre la conexión con MySQL. Si la base de datos todavía no existe la crea,
 * de modo que el sistema arranca en cualquier instalación de XAMPP.
 */
function primelab_abrir_conexion($detener = true)
{
    if (!function_exists('mysqli_init')) {
        if ($detener) die("La extensión mysqli de PHP no está habilitada. Actívala en XAMPP.");
        return null;
    }

    $mysqli = mysqli_init();
    if (!$mysqli) {
        if ($detener) die("No se pudo inicializar la conexión a MySQL.");
        return null;
    }
    @$mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 4);
    @$mysqli->real_connect(
        PRIMELAB_DB_HOST,
        PRIMELAB_DB_USER,
        PRIMELAB_DB_PASS,
        PRIMELAB_DB_NAME,
        (int) PRIMELAB_DB_PORT
    );

    // Error 1049 = base de datos inexistente: la creamos automáticamente.
    if ($mysqli->connect_errno === 1049) {
        $creador = @mysqli_init();
        if ($creador) {
            @$creador->options(MYSQLI_OPT_CONNECT_TIMEOUT, 4);
            @$creador->real_connect(PRIMELAB_DB_HOST, PRIMELAB_DB_USER, PRIMELAB_DB_PASS, '', (int) PRIMELAB_DB_PORT);
            if (!$creador->connect_errno) {
                @$creador->query("CREATE DATABASE IF NOT EXISTS `" . PRIMELAB_DB_NAME . "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci");
            }
            @$creador->close();
            @$mysqli->close();
            $mysqli = mysqli_init();
            @$mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 4);
            @$mysqli->real_connect(PRIMELAB_DB_HOST, PRIMELAB_DB_USER, PRIMELAB_DB_PASS, PRIMELAB_DB_NAME, (int) PRIMELAB_DB_PORT);
        }
    }

    if ($mysqli->connect_errno) {
        if ($detener) {
            die("Error de conexión a MySQL (" . PRIMELAB_DB_HOST . ":" . PRIMELAB_DB_PORT . "): " . $mysqli->connect_error
                . "<br>Verifica que MySQL esté iniciado en XAMPP y que el puerto sea el correcto.");
        }
        return null;
    }

    $mysqli->set_charset("utf8mb4");
    return $mysqli;
}

/**
 * Abre una conexión con MySQL usando la configuración de config.php.
 * Devuelve null si el servidor no está disponible (sin detener la página).
 */
function ConectarseOpcional()
{
    $mysqli = primelab_abrir_conexion(false);
    if (!$mysqli) return null;
    asegurarEsquemaPaciente($mysqli);
    asegurarEsquemaCita($mysqli);
    return $mysqli;
}

function Conectarse()
{
    $mysqli = primelab_abrir_conexion(true);
    asegurarBaseInicial($mysqli);
    asegurarEsquemaPaciente($mysqli);
    asegurarEsquemaCita($mysqli);
    asegurarAdministradores($mysqli);
    return $mysqli;
}

/**
 * Si la base de datos está vacía, importa el script BD/bd_primelab.sql.
 * Así el sistema queda operativo en cualquier XAMPP recién instalado.
 */
function asegurarBaseInicial($mysqli)
{
    static $verificado = false;
    if ($verificado) return;
    $verificado = true;

    $res = primelab_query($mysqli, "SHOW TABLES LIKE 'paciente'");
    if ($res && $res->num_rows > 0) return;

    $archivo = __DIR__ . '/BD/bd_primelab.sql';
    if (!is_file($archivo)) return;
    $sql = @file_get_contents($archivo);
    if (!is_string($sql) || trim($sql) === '') return;

    // Se elimina CREATE DATABASE / USE para importar sobre la base ya conectada.
    $sql = preg_replace('/^\s*CREATE DATABASE[^;]*;/mi', '', $sql);
    $sql = preg_replace('/^\s*USE\s+`[^`]+`\s*;/mi', '', $sql);

    if (@$mysqli->multi_query($sql)) {
        do {
            if ($res = @$mysqli->store_result()) {
                $res->free();
            }
        } while (@$mysqli->more_results() && @$mysqli->next_result());
    }
}

/**
 * Ajustes idempotentes para bases de datos creadas con la versión anterior.
 * - Agrega la columna `contrasena` si no existe.
 * - Permite teléfono nulo para cuentas importadas desde la API.
 * - Asigna como contraseña inicial el DNI de cada paciente.
 */
function asegurarEsquemaPaciente($mysqli)
{
    static $verificado = false;
    if ($verificado) return;
    $verificado = true;

    $agregada = false;
    if (!primelab_tiene_columna($mysqli, 'paciente', 'contrasena')) {
        if (primelab_query($mysqli, "ALTER TABLE paciente ADD COLUMN contrasena VARCHAR(60) DEFAULT NULL AFTER correo")) {
            $agregada = true;
        }
    }

    $colTel = primelab_query($mysqli, "SHOW COLUMNS FROM paciente LIKE 'telp'");
    if ($colTel && ($infoTel = $colTel->fetch_assoc()) && strtoupper($infoTel['Null']) === 'NO') {
        primelab_query($mysqli, "ALTER TABLE paciente MODIFY telp CHAR(9) NULL DEFAULT NULL");
    }

    if ($agregada) {
        primelab_query($mysqli, "UPDATE paciente SET contrasena = dni WHERE contrasena IS NULL OR contrasena = ''");
    } else {
        $sinClave = primelab_query($mysqli, "SELECT 1 FROM paciente WHERE contrasena IS NULL OR contrasena = '' LIMIT 1");
        if ($sinClave && $sinClave->num_rows > 0) {
            primelab_query($mysqli, "UPDATE paciente SET contrasena = dni WHERE contrasena IS NULL OR contrasena = ''");
        }
    }
}

/**
 * Auto-reparación del esquema de `cita` y `cita_historial`.
 * Evita el error fatal "Unknown column 'c.diagnostico'" cuando la base de
 * datos fue importada con una versión anterior del sistema.
 */
function asegurarEsquemaCita($mysqli)
{
    static $verificado = false;
    if ($verificado) return;
    $verificado = true;

    if (!primelab_tiene_columna($mysqli, 'cita', 'diagnostico')) {
        primelab_query($mysqli, "ALTER TABLE cita ADD COLUMN diagnostico TEXT DEFAULT NULL");
    }
    if (!primelab_tiene_columna($mysqli, 'cita', 'indicaciones')) {
        primelab_query($mysqli, "ALTER TABLE cita ADD COLUMN indicaciones TEXT DEFAULT NULL");
    }
    if (!primelab_tiene_columna($mysqli, 'cita', 'motivo_reprogramacion')) {
        primelab_query($mysqli, "ALTER TABLE cita ADD COLUMN motivo_reprogramacion VARCHAR(255) DEFAULT NULL");
    }
    if (!primelab_tiene_columna($mysqli, 'cita', 'reprogramada_de')) {
        primelab_query($mysqli, "ALTER TABLE cita ADD COLUMN reprogramada_de CHAR(6) DEFAULT NULL");
    }
    if (!primelab_tiene_columna($mysqli, 'cita', 'observaciones')) {
        primelab_query($mysqli, "ALTER TABLE cita ADD COLUMN observaciones VARCHAR(255) DEFAULT NULL");
    }
    // El estado ahora admite 'extendida' y 'reprogramada'.
    primelab_query($mysqli, "ALTER TABLE cita MODIFY estado VARCHAR(20) NOT NULL DEFAULT 'pendiente'");

    if (!primelab_tiene_columna($mysqli, 'cita_historial', 'usuario_accion')) {
        primelab_query($mysqli, "ALTER TABLE cita_historial ADD COLUMN usuario_accion VARCHAR(40) DEFAULT 'Sistema'");
    }
}

/**
 * Crea la tabla `administrador` y siembra tres cuentas de acceso si está vacía.
 * El administrador principal sigue siendo `prime`.
 */
function asegurarAdministradores($mysqli)
{
    static $verificado = false;
    if ($verificado) return;
    $verificado = true;

    $ok = primelab_query($mysqli, "
        CREATE TABLE IF NOT EXISTS `administrador` (
          `idadm` int(11) NOT NULL AUTO_INCREMENT,
          `usuario` varchar(30) NOT NULL,
          `correo` varchar(80) NOT NULL,
          `contrasena` varchar(60) NOT NULL,
          `nombre` varchar(80) DEFAULT NULL,
          `activo` tinyint(1) NOT NULL DEFAULT 1,
          PRIMARY KEY (`idadm`),
          UNIQUE KEY `uq_admin_usuario` (`usuario`),
          UNIQUE KEY `uq_admin_correo` (`correo`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci
    ");
    if (!$ok) return;

    $res = primelab_query($mysqli, "SELECT COUNT(*) AS n FROM administrador");
    $n = $res ? (int) $res->fetch_assoc()['n'] : 0;
    if ($n > 0) return;

    foreach (administradoresDefecto() as $a) {
        $stmt = @$mysqli->prepare("INSERT INTO administrador (usuario, correo, contrasena, nombre, activo) VALUES (?, ?, ?, ?, 1)");
        if (!$stmt) continue;
        $stmt->bind_param("ssss", $a['usuario'], $a['correo'], $a['contrasena'], $a['nombre']);
        @$stmt->execute();
        $stmt->close();
    }
}

/** Lista de administradores de ejemplo (mínimo 3, solicitado para la demo). */
function administradoresDefecto()
{
    return [
        ['usuario' => 'prime',  'correo' => 'primelab@centrosalud.pe', 'contrasena' => 'pr1m3l4b', 'nombre' => 'Administrador principal'],
        ['usuario' => 'admin2', 'correo' => 'gestion@centrosalud.pe',  'contrasena' => 'PrimeLab2026', 'nombre' => 'Coordinación de citas'],
        ['usuario' => 'admin3', 'correo' => 'soporte@centrosalud.pe',  'contrasena' => 'PrimeLab2026', 'nombre' => 'Soporte técnico'],
    ];
}

/** Devuelve los administradores activos desde la base de datos. */
function administradoresActivos($mysqli)
{
    $lista = [];
    $res = primelab_query($mysqli, "SELECT usuario, correo, contrasena, nombre FROM administrador WHERE activo = 1");
    if ($res) {
        while ($fila = $res->fetch_assoc()) {
            $lista[] = $fila;
        }
    }
    return $lista;
}

/** Busca un administrador por usuario + correo (sin distinguir mayúsculas). */
function buscarAdministrador($mysqli, $usuario, $correo)
{
    $usuario = mb_strtolower(trim((string) $usuario), 'UTF-8');
    $correo  = mb_strtolower(trim((string) $correo), 'UTF-8');

    foreach (administradoresActivos($mysqli) as $a) {
        if (mb_strtolower($a['usuario'], 'UTF-8') === $usuario && mb_strtolower($a['correo'], 'UTF-8') === $correo) {
            return $a;
        }
    }
    // Respaldo: aunque la tabla no exista o esté vacía, el admin principal funciona.
    foreach (administradoresDefecto() as $a) {
        if (mb_strtolower($a['usuario'], 'UTF-8') === $usuario && mb_strtolower($a['correo'], 'UTF-8') === $correo) {
            return $a;
        }
    }
    return null;
}

/**
 * Carga la copia local de `usuarios.json` incluida en el proyecto.
 * Así la búsqueda por DNI funciona incluso si la API Node.js está apagada.
 */
function usuariosLocales()
{
    static $usuarios = null;
    if (is_array($usuarios)) return $usuarios;
    $usuarios = [];

    $rutas = [
        __DIR__ . '/datos/usuarios.json',
        __DIR__ . '/API_Citas_Medicas/data/usuarios.json',
    ];
    foreach ($rutas as $ruta) {
        if (!is_file($ruta)) continue;
        $json = json_decode((string) @file_get_contents($ruta), true);
        if (isset($json['usuarios']) && is_array($json['usuarios'])) {
            $usuarios = $json['usuarios'];
            break;
        }
    }
    return $usuarios;
}

/** Busca un usuario en la copia local de usuarios.json por DNI. */
function usuarioLocalPorDni($dni)
{
    $dni = trim((string) $dni);
    if (!preg_match('/^\d{8}$/', $dni)) return null;
    foreach (usuariosLocales() as $u) {
        if ((string) ($u['dni'] ?? '') === $dni) {
            return $u;
        }
    }
    return null;
}

/**
 * Devuelve el usuario (local o de la API interna) que corresponde al DNI.
 */
function usuarioPorDni($dni)
{
    $local = usuarioLocalPorDni($dni);
    if ($local) return $local;
    return apiUsuarioPorDni($dni);
}

/** Convierte un texto a formato título: primera letra de cada palabra en mayúscula. */
function tituloPersona($texto)
{
    $texto = trim(preg_replace('/\s+/', ' ', (string)$texto));
    if ($texto === '') return '';
    return mb_convert_case(mb_strtolower($texto, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
}

/**
 * Busca un usuario por DNI en la API interna de usuarios.
 * Devuelve null si la API no está disponible o el DNI no existe.
 */
function apiUsuarioPorDni($dni)
{
    static $cache = [];
    $dni = trim((string)$dni);
    if (!preg_match('/^\d{8}$/', $dni)) return null;
    if (array_key_exists($dni, $cache)) return $cache[$dni];

    $url = PRIMELAB_API_URL . '/api/usuarios/buscar?dni=' . urlencode($dni);
    $body = null;

    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 2,
            CURLOPT_TIMEOUT        => 4,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER     => ['Accept: application/json'],
        ]);
        $resp = curl_exec($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($resp !== false && $code >= 200 && $code < 300) {
            $body = $resp;
        }
    }

    if ($body === null && ini_get('allow_url_fopen')) {
        $ctx = stream_context_create([
            'http' => [
                'method'        => 'GET',
                'timeout'       => 3,
                'header'        => "Accept: application/json\r\n",
                'ignore_errors' => true,
            ],
        ]);
        $resp = @file_get_contents($url, false, $ctx);
        if (is_string($resp) && $resp !== '') $body = $resp;
    }

    $usuario = null;
    if (is_string($body) && $body !== '') {
        $json = json_decode($body, true);
        if (is_array($json)) {
            $u = $json['usuario'] ?? $json;
            if (is_array($u) && (string)($u['dni'] ?? '') === $dni) {
                $usuario = $u;
            }
        }
    }

    $cache[$dni] = $usuario;
    return $usuario;
}

/**
 * Busca nombres y apellidos por DNI en las dos fuentes del sistema:
 * primero en la base de datos de PrimeLab y luego en la API interna.
 * Devuelve null si el DNI no está registrado en ninguna.
 */
function buscarPersonaPorDni($dni)
{
    $dni = trim((string)$dni);
    if (!preg_match('/^\d{8}$/', $dni)) return null;

    // 1) Pacientes ya registrados en MySQL (incluye las 7 cuentas nuevas).
    $db = ConectarseOpcional();
    if ($db) {
        $stmt = @$db->prepare("SELECT nomp, apep, telp, correo FROM paciente WHERE dni = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("s", $dni);
            $stmt->execute();
            $fila = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            $db->close();
            if ($fila) {
                return [
                    'dni'       => $dni,
                    'nombres'   => tituloPersona($fila['nomp'] ?? ''),
                    'apellidos' => tituloPersona($fila['apep'] ?? ''),
                    'telefono'  => (string)($fila['telp'] ?? ''),
                    'correo'    => (string)($fila['correo'] ?? ''),
                    'fuente'    => 'Base de datos PrimeLab',
                ];
            }
        } else {
            $db->close();
        }
    }

    // 2) Copia local de usuarios.json incluida en el proyecto (sin depender de la API).
    $local = usuarioLocalPorDni($dni);
    if ($local) {
        return [
            'dni'       => $dni,
            'nombres'   => tituloPersona($local['nombres'] ?? ''),
            'apellidos' => tituloPersona($local['apellidos'] ?? ''),
            'telefono'  => (string)($local['telefono'] ?? ''),
            'correo'    => (string)($local['correo'] ?? ''),
            'fuente'    => 'Directorio local de usuarios',
        ];
    }

    // 3) Usuarios registrados en la API interna.
    $api = apiUsuarioPorDni($dni);
    if ($api) {
        return [
            'dni'       => $dni,
            'nombres'   => tituloPersona($api['nombres'] ?? ''),
            'apellidos' => tituloPersona($api['apellidos'] ?? ''),
            'telefono'  => (string)($api['telefono'] ?? ''),
            'correo'    => (string)($api['correo'] ?? ''),
            'fuente'    => 'API interna',
        ];
    }

    return null;
}

/**
 * Crea un paciente local a partir de un usuario de la API interna.
 * La contraseña inicial siempre es el DNI y el correo/teléfono son opcionales.
 */
function crearPacienteDesdeApi($mysqli, array $usuario)
{
    $dni = preg_replace('/\D/', '', (string)($usuario['dni'] ?? ''));
    if (!preg_match('/^\d{8}$/', $dni)) return null;

    $stmt = $mysqli->prepare("SELECT codp, nomp, apep, dni, telp, correo, seguro, fechanac, direccion, distrito, fechareg FROM paciente WHERE dni = ? LIMIT 1");
    $stmt->bind_param("s", $dni);
    $stmt->execute();
    $existente = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if ($existente) return $existente;

    $nomp = tituloPersona($usuario['nombres'] ?? '');
    $apep = tituloPersona($usuario['apellidos'] ?? '');
    if ($nomp === '' || $apep === '') return null;

    $telp = preg_replace('/\D/', '', (string)($usuario['telefono'] ?? ''));
    $telpDb = (strlen($telp) === 9) ? $telp : null;

    $correo = trim((string)($usuario['correo'] ?? ''));
    $correoDb = ($correo !== '' && filter_var($correo, FILTER_VALIDATE_EMAIL)) ? $correo : null;

    $codp = generarCodigo($mysqli, 'paciente', 'codp', 'PAC');
    $seguro = 'SIS';
    $fechareg = date('Y-m-d');
    $contrasena = $dni;

    $stmt = $mysqli->prepare("INSERT INTO paciente (codp, nomp, apep, dni, telp, correo, contrasena, seguro, fechareg) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $codp, $nomp, $apep, $dni, $telpDb, $correoDb, $contrasena, $seguro, $fechareg);
    $ok = $stmt->execute();
    $stmt->close();

    // Respaldo por si la base antigua aún exige teléfono obligatorio.
    if (!$ok && $telpDb === null) {
        $telpDb = '000000000';
        $stmt = $mysqli->prepare("INSERT INTO paciente (codp, nomp, apep, dni, telp, correo, contrasena, seguro, fechareg) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $codp, $nomp, $apep, $dni, $telpDb, $correoDb, $contrasena, $seguro, $fechareg);
        $ok = $stmt->execute();
        $stmt->close();
    }
    if (!$ok) return null;

    return [
        'codp' => $codp,
        'nomp' => $nomp,
        'apep' => $apep,
        'dni' => $dni,
        'telp' => $telpDb,
        'correo' => $correoDb,
        'seguro' => $seguro,
        'fechanac' => null,
        'direccion' => null,
        'distrito' => null,
        'fechareg' => $fechareg,
    ];
}

/** Escapa texto para mostrarlo en HTML. */
function e($texto)
{
    return htmlspecialchars((string)$texto, ENT_QUOTES, 'UTF-8');
}

/** Redirige y termina la ejecución. */
function redirigir($url)
{
    header("Location: " . $url);
    exit;
}

/** Guarda un mensaje flash en sesión. */
function flash($tipo, $texto)
{
    $_SESSION['flash'] = ['tipo' => $tipo, 'texto' => $texto];
}

/** Muestra y limpia el mensaje flash si existe. */
function mostrarFlash()
{
    if (empty($_SESSION['flash'])) {
        return;
    }
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    $clase = ($f['tipo'] === 'exito' || $f['tipo'] === 'success') ? 'success' : 'error';
    echo '<div class="alert show ' . $clase . '" role="alert">' . e($f['texto']) . '</div>';
}

/** Exige sesión de paciente; si no hay, redirige al inicio. */
function requerirPaciente()
{
    if (empty($_SESSION['paciente_codp'])) {
        redirigir("index.html?msg=sesion");
    }
}

/** Exige sesión de administrador. */
function requerirAdmin()
{
    if (empty($_SESSION['admin_logged'])) {
        redirigir("admin_login.html");
    }
}

/** Genera un código correlativo tipo PREF001, PREF002... */
function generarCodigo($mysqli, $tabla, $campo, $prefijo)
{
    $sql = "SELECT $campo AS cod FROM $tabla WHERE $campo LIKE ? ORDER BY $campo DESC LIMIT 1";
    $stmt = $mysqli->prepare($sql);
    $like = $prefijo . '%';
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $res = $stmt->get_result();
    $ultimo = $res->fetch_assoc();
    $stmt->close();

    $num = 0;
    if ($ultimo && preg_match('/(\d+)$/', $ultimo['cod'], $m)) {
        $num = (int)$m[1];
    }
    $num++;
    return $prefijo . str_pad((string)$num, 3, '0', STR_PAD_LEFT);
}

/** Formatea fecha YYYY-MM-DD a texto legible. */
function fechaTexto($fecha)
{
    if (!$fecha) return '';
    $ts = strtotime($fecha);
    if (!$ts) return $fecha;
    $meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
    $d = date('j', $ts);
    $m = (int)date('n', $ts);
    $y = date('Y', $ts);
    return $d . ' de ' . $meses[$m - 1] . ' de ' . $y;
}

/** Formatea hora HH:MM:SS a HH:MM. */
function horaTexto($hora)
{
    if (!$hora) return '';
    return substr($hora, 0, 5);
}

/** Devuelve el paciente actual desde sesión (o null). */
function pacienteActual($mysqli)
{
    if (empty($_SESSION['paciente_codp'])) return null;
    $stmt = $mysqli->prepare("SELECT codp, nomp, apep, dni, telp, correo, seguro, fechanac, direccion, distrito, fechareg FROM paciente WHERE codp = ? LIMIT 1");
    $stmt->bind_param("s", $_SESSION['paciente_codp']);
    $stmt->execute();
    $res = $stmt->get_result();
    $pac = $res->fetch_assoc();
    $stmt->close();
    return $pac ?: null;
}

/** Exige sesión de médico. */
function requerirMedico()
{
    if (empty($_SESSION['medico_codm'])) {
        redirigir("medico_login.html?msg=sesion");
    }
}

/** Devuelve el médico actual desde sesión (o null). */
function medicoActual($mysqli)
{
    if (empty($_SESSION['medico_codm'])) return null;
    $stmt = $mysqli->prepare("SELECT m.codm, m.nomm, m.cmpm, m.codesp, m.dni, m.telefono, m.correo, m.anios_experiencia, m.formacion, e.nomesp
                              FROM medico m JOIN especialidad e ON e.codesp = m.codesp
                              WHERE m.codm = ? LIMIT 1");
    $stmt->bind_param("s", $_SESSION['medico_codm']);
    $stmt->execute();
    $res = $stmt->get_result();
    $med = $res->fetch_assoc();
    $stmt->close();
    return $med ?: null;
}

/** Calcula el costo de la cita según el tipo de seguro. */
function calcularCosto($seguro)
{
    $s = strtoupper(trim((string)$seguro));
    if ($s === 'EPS') return 20.00;
    if ($s === 'PRIVADO') return 50.00;
    return 0.00; // SIS u otros
}

/** Devuelve texto legible del costo. */
function costoTexto($seguro, $costo = null)
{
    if ($costo === null) $costo = calcularCosto($seguro);
    $monto = number_format((float)$costo, 2);
    $s = strtoupper(trim((string)$seguro));
    if ((float)$costo <= 0) return 'S/ 0.00 (cubierto por ' . ($s ?: 'SIS') . ')';
    return 'S/ ' . $monto;
}

/** Nombre legible del seguro. */
function textoSeguro($seguro)
{
    $s = strtoupper(trim((string)$seguro));
    if ($s === 'EPS') return 'EPS';
    if ($s === 'PRIVADO') return 'Seguro privado';
    return 'SIS';
}

/**
 * Devuelve el HTML de la foto/avatar del médico.
 * Usa img/medicos/{codm}.svg si existe; si no, muestra un avatar con inicial.
 */
function fotoMedico($codm, $nomm = '', $tam = 44, $clase = 'medico-foto')
{
    $codm = preg_replace('/[^A-Za-z0-9_-]/', '', (string)$codm);
    $archivo = __DIR__ . '/img/medicos/' . $codm . '.svg';
    $src = 'img/medicos/' . $codm . '.svg';
    $alt = $nomm !== '' ? $nomm : 'Médico';
    $style = 'width:' . (int)$tam . 'px;height:' . (int)$tam . 'px;';
    if ($codm !== '' && file_exists($archivo)) {
        return '<img src="' . e($src) . '" alt="' . e($alt) . '" class="' . e($clase) . '" style="' . $style . '">';
    }
    $inicial = $nomm !== '' ? mb_strtoupper(mb_substr(trim($nomm), 0, 1, 'UTF-8'), 'UTF-8') : 'M';
    return '<span class="' . e($clase) . ' medico-foto-fallback" style="' . $style . '" aria-label="' . e($alt) . '">' . e($inicial) . '</span>';
}
?>
````

## `index.html`

````html
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión — PrimeLab</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body data-page="login">

<header class="topbar">
    <a href="index.html" class="brand">
        <img src="img/PrimeLab%20Logo.png" alt="PrimeLab" class="brand-logo">
        <span class="brand-copy">
            <strong>PrimeLab</strong>
            <small>Centros de salud públicos del Perú</small>
        </span>
    </a>
    <div class="topbar-right">
        <div class="section-tag">
            <span>Sección actual</span>
            <b>INGRESO</b>
        </div>
    </div>
</header>

<main class="login-page">
    <div class="login-card">
        <h1>Ingreso de pacientes</h1>
        <p class="muted">Escribe tu <strong>número de DNI</strong> y tu <strong>contraseña</strong> para reservar o revisar tus citas. Si es tu primera vez, tu contraseña es tu mismo DNI.</p>

        <div id="alertBox" class="alert" aria-live="polite"></div>

        <form method="POST" action="login_paciente.php" class="form-stack">
            <label class="field">
                <span>Número de DNI</span>
                <input type="text" name="dni" id="dni" maxlength="8" inputmode="numeric"
                       autocomplete="username" aria-describedby="ayudaDni"
                       data-numerico="true" data-max="8" data-exacto="8" data-label="DNI"
                       placeholder="8 números, sin puntos" required>
                <small class="field-help" id="ayudaDni">Son los 8 números de tu documento de identidad. No escribas puntos ni espacios.</small>
            </label>

            <label class="field">
                <span>Contraseña</span>
                <input type="password" name="contrasena" id="contrasena" maxlength="60"
                       autocomplete="current-password" aria-describedby="ayudaClave"
                       placeholder="Escribe tu contraseña" required>
                <small class="field-help" id="ayudaClave">La primera vez que ingresas, tu contraseña es tu DNI. Puedes usar el botón «Mostrar» para ver lo que escribes.</small>
            </label>

            <button type="submit" class="button primary full">Ingresar</button>
        </form>

        <div class="ayuda-pasos">
            <p class="ayuda-titulo">¿Cómo ingresar? (3 pasos)</p>
            <ol>
                <li>Escribe tu <strong>DNI</strong> (8 números).</li>
                <li>Escribe tu <strong>contraseña</strong>. Si es tu primera vez, es tu DNI.</li>
                <li>Pulsa el botón <strong>Ingresar</strong>.</li>
            </ol>
            <p class="ayuda-nota">Si no recuerdas tu contraseña, comunícate con el centro de salud donde te atendiste.</p>
        </div>

        <p class="login-foot">
            ¿No tienes cuenta? <a href="registro.html">Regístrate aquí</a>
        </p>
        <p class="login-foot">
            <a href="medico_login.html">Acceso para médicos</a>
        </p>
        <p class="login-foot">
            <a href="admin_login.html">Acceso administrativo</a>
        </p>
    </div>
</main>

<footer class="site-footer">
    <p>PrimeLab — Sistema de reserva de citas en centros de salud públicos</p>
    <p>Proyecto académico. Los datos mostrados son de ejemplo.</p>
</footer>

<script src="js/main.js"></script>
</body>
</html>
````

## `login_paciente.php`

````php
<?php
// login_paciente.php — Ingreso del paciente con DNI y contraseña.
// La contraseña inicial de cada cuenta es su propio DNI.
// También se acepta la contraseña registrada en la API interna (usuarios.json).
session_start();
require_once "file_conexion.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir("index.html");
}

$dni = trim($_POST['dni'] ?? '');
$contrasena = trim($_POST['contrasena'] ?? '');

if (!preg_match('/^\d{8}$/', $dni)) {
    redirigir("index.html?error=dni&dni=" . urlencode($dni));
}
if ($contrasena === '') {
    redirigir("index.html?error=pass&dni=" . urlencode($dni));
}

$mysqli = Conectarse();
$stmt = $mysqli->prepare("SELECT codp, nomp, apep, dni, contrasena FROM paciente WHERE dni = ? LIMIT 1");
$stmt->bind_param("s", $dni);
$stmt->execute();
$paciente = $stmt->get_result()->fetch_assoc();
$stmt->close();

// El DNI puede existir en el directorio local de usuarios o en la API interna
// y todavía no en MySQL: se crea la cuenta local automáticamente.
$usuarioDirectorio = usuarioPorDni($dni);
if (!$paciente && $usuarioDirectorio) {
    $paciente = crearPacienteDesdeApi($mysqli, $usuarioDirectorio);
}

if (!$paciente) {
    $mysqli->close();
    redirigir("index.html?error=noexiste&dni=" . urlencode($dni));
}

$guardada = (string)($paciente['contrasena'] ?? '');
if ($guardada === '') {
    // Compatibilidad con registros creados antes de agregar la contraseña.
    $guardada = (string)$paciente['dni'];
    $stmt = $mysqli->prepare("UPDATE paciente SET contrasena = ? WHERE codp = ?");
    $stmt->bind_param("ss", $guardada, $paciente['codp']);
    $stmt->execute();
    $stmt->close();
}

$claveApi = (string)($usuarioDirectorio['contrasena'] ?? '');
$claveOk = hash_equals($guardada, $contrasena)
        || hash_equals((string)$paciente['dni'], $contrasena)
        || ($claveApi !== '' && hash_equals($claveApi, $contrasena));

if (!$claveOk) {
    $mysqli->close();
    redirigir("index.html?error=pass&dni=" . urlencode($dni));
}

session_regenerate_id(true);
$_SESSION['paciente_codp']   = $paciente['codp'];
$_SESSION['paciente_nombre'] = $paciente['nomp'] . ' ' . $paciente['apep'];
$_SESSION['paciente_dni']    = $paciente['dni'];

$mysqli->close();
flash('exito', 'Bienvenido, ' . $paciente['nomp'] . ' ' . $paciente['apep'] . '.');
redirigir("panel.php");
````

## `consulta_dni.php`

````php
<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

// La consulta puede probar varias fuentes externas; se amplía el límite de tiempo
// para que XAMPP no corte la respuesta a la mitad.
@set_time_limit(45);

// Este endpoint solo devuelve JSON: no necesita abrir sesión.
if (!defined('PRIMELAB_SIN_SESION')) define('PRIMELAB_SIN_SESION', true);

require_once __DIR__ . '/file_conexion.php';

// consulta_dni.php — Obtiene nombres/apellidos por DNI.
// Primero consulta la base de datos y la API interna de PrimeLab (Node.js)
// y, si no hay datos, prueba servicios públicos/terceros disponibles sin token.
// Uso: consulta_dni.php?dni=12345678&debug=1

function limpiarTexto($texto) {
    $texto = trim((string)$texto);
    if ($texto === '') return '';
    $texto = preg_replace('/\s+/', ' ', $texto);
    return mb_convert_case(mb_strtolower($texto, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
}

function responder($ok, $dni, $nombres = '', $apellidos = '', $mensaje = '', $extra = []) {
    $out = [
        'ok'        => $ok,
        'dni'       => $dni,
        'nombres'   => $nombres,
        'apellidos' => $apellidos,
        'mensaje'   => $mensaje
    ];
    if (!empty($extra)) $out['detalle'] = $extra;
    echo json_encode($out, JSON_UNESCAPED_UNICODE);
    exit;
}

function fetchRaw($url, $timeout = 10) {
    // Se limita el tiempo para no agotar el máximo de ejecución de PHP.
    $timeout = min((int)$timeout, 6);
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS      => 5,
        CURLOPT_TIMEOUT        => $timeout,
        CURLOPT_CONNECTTIMEOUT => 4,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_ENCODING       => '',
        CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36',
        CURLOPT_HTTPHEADER     => [
            'Accept: application/json, text/plain, */*',
            'Accept-Language: es-PE,es;q=0.9,en;q=0.8',
            'Cache-Control: no-cache'
        ]
    ]);
    $resp = curl_exec($ch);
    $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err  = curl_error($ch);
    curl_close($ch);
    return ['body' => $resp, 'code' => $code, 'error' => $err];
}

function parseJson($body) {
    if (!is_string($body) || $body === '') return null;
    $json = json_decode($body, true);
    return is_array($json) ? $json : null;
}

function normalizarPersona($nombres, $apPaterno = '', $apMaterno = '', $apellidos = '') {
    $n = limpiarTexto($nombres);
    $ap = limpiarTexto(trim($apPaterno . ' ' . $apMaterno));
    if ($ap === '') $ap = limpiarTexto($apellidos);
    // si viene todo junto en nombres, intentar no duplicar
    if ($n === '' && $ap !== '') { $n = ''; }
    return ['nombres' => $n, 'apellidos' => $ap];
}

$dni = trim($_GET['dni'] ?? '');
$debug = isset($_GET['debug']) && $_GET['debug'] == '1';

if (!preg_match('/^\d{8}$/', $dni)) {
    responder(false, $dni, '', '', 'El DNI debe tener exactamente 8 dígitos numéricos.');
}

$intentos = [];

// 0) Base de datos de PrimeLab y API interna de usuarios.
// Aquí se resuelven tanto los DNI de la API como las 7 cuentas nuevas.
$persona = buscarPersonaPorDni($dni);
$intentos['sistema'] = ['fuente' => $persona['fuente'] ?? 'sin coincidencia'];
if ($persona && ($persona['nombres'] !== '' || $persona['apellidos'] !== '')) {
    responder(
        true,
        $dni,
        $persona['nombres'],
        $persona['apellidos'],
        'Datos encontrados en ' . $persona['fuente'] . '.',
        $debug ? $intentos : []
    );
}

if (!function_exists('curl_init')) {
    responder(false, $dni, '', '', 'El servidor no tiene habilitada la extensión cURL de PHP. Activa php_curl en XAMPP/Apache para consultar el DNI.', ['curl' => false]);
}

// 1) SUNAT (consulta pública usada en algunos servicios)
$r = fetchRaw('https://ww1.sunat.gob.pe/ol-ti-itelav455nameregsunat/api/registro/solicitud?nrodocumento=' . $dni, 12);
$j = parseJson($r['body']);
$intentos['sunat'] = ['http' => $r['code'], 'error' => $r['error']];
if ($j) {
    $nombreCompleto = trim($j['nombre'] ?? '');
    if ($nombreCompleto !== '' && $nombreCompleto !== ',') {
        $partes = explode(',', $nombreCompleto, 2);
        $apellidos = limpiarTexto($partes[0] ?? '');
        $nombres = limpiarTexto($partes[1] ?? '');
        if ($nombres !== '' || $apellidos !== '') {
            responder(true, $dni, $nombres, $apellidos, 'Datos encontrados (SUNAT).', $debug ? $intentos : []);
        }
    }
}

// 2) apis.net.pe v1 (pública sin token en algunos casos, puede requerir token)
$r = fetchRaw('https://api.apis.net.pe/v1/dni?numero=' . $dni, 10);
$j = parseJson($r['body']);
$intentos['apis.net.pe.v1'] = ['http' => $r['code'], 'error' => $r['error']];
if ($j && empty($j['message']) && (isset($j['nombres']) || isset($j['apellidoPaterno']) || isset($j['nombre']))) {
    $p = normalizarPersona($j['nombres'] ?? ($j['nombre'] ?? ''), $j['apellidoPaterno'] ?? '', $j['apellidoMaterno'] ?? '', $j['apellidos'] ?? '');
    if ($p['nombres'] !== '' || $p['apellidos'] !== '') {
        responder(true, $dni, $p['nombres'], $p['apellidos'], 'Datos encontrados.', $debug ? $intentos : []);
    }
}

// 3) apis.net.pe v2
$r = fetchRaw('https://api.apis.net.pe/v2/reniec/dni?numero=' . $dni, 10);
$j = parseJson($r['body']);
$intentos['apis.net.pe.v2'] = ['http' => $r['code'], 'error' => $r['error']];
if ($j && empty($j['message']) && (isset($j['nombres']) || isset($j['apellidoPaterno']))) {
    $p = normalizarPersona($j['nombres'] ?? '', $j['apellidoPaterno'] ?? '', $j['apellidoMaterno'] ?? '');
    if ($p['nombres'] !== '' || $p['apellidos'] !== '') {
        responder(true, $dni, $p['nombres'], $p['apellidos'], 'Datos encontrados.', $debug ? $intentos : []);
    }
}

// 4) miapi.cloud
$r = fetchRaw('https://miapi.cloud/v1/dni/' . $dni, 10);
$j = parseJson($r['body']);
$intentos['miapi.cloud'] = ['http' => $r['code'], 'error' => $r['error']];
if ($j && (isset($j['nombres']) || isset($j['datos']['nombres']) || isset($j['name']))) {
    $src = isset($j['datos']) && is_array($j['datos']) ? $j['datos'] : $j;
    $p = normalizarPersona($src['nombres'] ?? ($src['name'] ?? ''), $src['apellido_paterno'] ?? ($src['apellidoPaterno'] ?? ''), $src['apellido_materno'] ?? ($src['apellidoMaterno'] ?? ''), $src['apellidos'] ?? ($src['apellido'] ?? ''));
    if ($p['nombres'] !== '' || $p['apellidos'] !== '') {
        responder(true, $dni, $p['nombres'], $p['apellidos'], 'Datos encontrados.', $debug ? $intentos : []);
    }
}

// 5) apiperu.dev (público - puede cambiar)
$r = fetchRaw('https://apiperu.dev/api/dni/' . $dni, 10);
$j = parseJson($r['body']);
$intentos['apiperu.dev'] = ['http' => $r['code'], 'error' => $r['error']];
if ($j && (isset($j['data']) || isset($j['nombres']))) {
    $src = isset($j['data']) && is_array($j['data']) ? $j['data'] : $j;
    $p = normalizarPersona($src['nombres'] ?? '', $src['apellido_paterno'] ?? ($src['apellidoPaterno'] ?? ''), $src['apellido_materno'] ?? ($src['apellidoMaterno'] ?? ''), $src['nombre_completo'] ?? '');
    if ($p['nombres'] === '' && $p['apellidos'] === '' && !empty($src['nombre_completo'])) {
        // intentar split simple
        $nc = limpiarTexto($src['nombre_completo']);
        $p['nombres'] = $nc;
    }
    if ($p['nombres'] !== '' || $p['apellidos'] !== '') {
        responder(true, $dni, $p['nombres'], $p['apellidos'], 'Datos encontrados.', $debug ? $intentos : []);
    }
}

// 6) api.perudevs.com (demo público a veces)
$r = fetchRaw('https://api.perudevs.com/api/v1/dni/simple?document=' . $dni . '&key=demo', 10);
$j = parseJson($r['body']);
$intentos['perudevs.demo'] = ['http' => $r['code'], 'error' => $r['error']];
if ($j && (isset($j['nombres']) || isset($j['resultado']))) {
    $src = isset($j['resultado']) && is_array($j['resultado']) ? $j['resultado'] : $j;
    $p = normalizarPersona($src['nombres'] ?? '', $src['apellido_paterno'] ?? ($src['apellidoPaterno'] ?? ''), $src['apellido_materno'] ?? ($src['apellidoMaterno'] ?? ''), $src['apellidos'] ?? '');
    if ($p['nombres'] !== '' || $p['apellidos'] !== '') {
        responder(true, $dni, $p['nombres'], $p['apellidos'], 'Datos encontrados.', $debug ? $intentos : []);
    }
}

// 7) reniec.cloud (servicio alternativo de consulta RENIEC)
$r = fetchRaw('https://api.reniec.cloud/dni/' . $dni, 10);
$j = parseJson($r['body']);
$intentos['reniec.cloud'] = ['http' => $r['code'], 'error' => $r['error']];
if ($j && (isset($j['nombres']) || isset($j['apellidoPaterno']) || isset($j['nombres_completos']))) {
    $p = normalizarPersona(
        $j['nombres'] ?? ($j['nombres_completos'] ?? ''),
        $j['apellidoPaterno'] ?? ($j['apellido_paterno'] ?? ''),
        $j['apellidoMaterno'] ?? ($j['apellido_materno'] ?? '')
    );
    if ($p['nombres'] !== '' || $p['apellidos'] !== '') {
        responder(true, $dni, $p['nombres'], $p['apellidos'], 'Datos encontrados (RENIEC).', $debug ? $intentos : []);
    }
}

$mensaje = 'No se pudo obtener los nombres y apellidos para este DNI en el directorio interno ni en las fuentes RENIEC consultadas. ';
$mensaje .= 'RENIEC no ofrece una API pública oficial gratuita y directa; los servicios alternativos pueden estar caídos, limitados o requerir token. ';
$mensaje .= 'Verifica que tengas internet y que la extensión cURL esté habilitada en PHP. ';
$mensaje .= 'También puedes escribir tus nombres y apellidos manualmente (el sistema lo permite como respaldo).';

responder(false, $dni, '', '', $mensaje, $debug ? $intentos : []);
?>
````

## `registro.html`

````html
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar paciente — PrimeLab</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body data-page="registro">

<header class="topbar">
    <a href="index.html" class="brand">
        <img src="img/PrimeLab%20Logo.png" alt="PrimeLab" class="brand-logo">
        <span class="brand-copy">
            <strong>PrimeLab</strong>
            <small>Centros de salud públicos del Perú</small>
        </span>
    </a>
    <div class="topbar-right">
        <div class="section-tag">
            <span>Sección actual</span>
            <b>REGISTRO</b>
        </div>
    </div>
</header>

<main class="login-page">
    <div class="login-card">
        <h1>Registrar paciente</h1>
        <p class="muted">Escribe tu <strong>número de DNI</strong> (8 números). El sistema buscará tus nombres y apellidos automáticamente. Después solo completa tu teléfono y elige tu seguro.</p>

        <div id="alertBox" class="alert" aria-live="polite"></div>

        <form method="POST" action="registro.php" class="form-stack" id="formRegistro">
            <label class="field">
                <span>Número de DNI</span>
                <div class="input-with-button">
                    <input type="text" name="dni" id="dni" maxlength="8" inputmode="numeric"
                           data-numerico="true" data-max="8" data-exacto="8" data-label="DNI"
                           placeholder="8 números, sin puntos" required>
                    <button type="button" class="button secondary" id="btnBuscarDni" onclick="buscarDni(true)">
                        Buscar DNI
                    </button>
                </div>
                <small class="field-help" id="dniEstado" aria-live="polite">Escribe tus 8 números y la búsqueda será automática. También puedes pulsar «Buscar DNI».</small>
            </label>

            <div class="form-grid-2">
                <label class="field">
                    <span>Nombres <span class="hint">(automático)</span></span>
                    <input type="text" name="nomp" id="nomp" maxlength="40" data-texto="true" data-label="Nombres" placeholder="Se completará con tu DNI" readonly required>
                </label>

                <label class="field">
                    <span>Apellidos <span class="hint">(automático)</span></span>
                    <input type="text" name="apep" id="apep" maxlength="40" data-texto="true" data-label="Apellidos" placeholder="Se completará con tu DNI" readonly required>
                </label>
            </div>

            <label class="field">
                <span>Número de teléfono</span>
                <input type="text" name="telp" id="telp" maxlength="9" inputmode="numeric"
                       data-numerico="true" data-max="9" data-exacto="9" data-label="Teléfono"
                       placeholder="9 números, sin espacios" required>
                <small class="field-help">Escribe 9 números. Ejemplo: 987654321.</small>
            </label>

            <label class="field">
                <span>Correo electrónico <span class="hint">(opcional)</span></span>
                <input type="email" name="correo" id="correo" maxlength="60" placeholder="correo@ejemplo.com">
                <small class="field-help">Si no tienes correo, puedes dejar este espacio vacío.</small>
            </label>

            <label class="field">
                <span>¿Qué seguro tienes?</span>
                <select name="seguro" id="seguro" required>
                    <option value="">Elige una opción</option>
                    <option value="SIS">SIS (Seguro Integral de Salud)</option>
                    <option value="EPS">EPS</option>
                    <option value="Privado">Seguro privado</option>
                </select>
                <small class="field-help">El costo de la cita puede variar según tu seguro.</small>
            </label>

            <button type="submit" class="button primary full">Crear mi cuenta</button>
            <small class="field-help">Tu contraseña será tu número de DNI. Guárdala para ingresar la próxima vez.</small>
        </form>

        <div class="ayuda-pasos">
            <p class="ayuda-titulo">¿Cómo registrarme? (3 pasos)</p>
            <ol>
                <li>Escribe tu <strong>DNI</strong> y espera a que aparezcan tus nombres.</li>
                <li>Completa tu <strong>teléfono</strong> y elige tu <strong>seguro</strong>.</li>
                <li>Pulsa <strong>Crear mi cuenta</strong>.</li>
            </ol>
            <p class="ayuda-nota">Si tus nombres no aparecen, escríbelos tú mismo y continúa con el registro.</p>
        </div>

        <p class="login-foot">
            <a href="index.html">¿Ya tienes cuenta? Inicia sesión</a>
        </p>
    </div>
</main>

<footer class="site-footer">
    <p>PrimeLab — Sistema de reserva de citas en centros de salud públicos</p>
    <p>Proyecto académico. Los datos mostrados son de ejemplo.</p>
</footer>

<script src="js/main.js"></script>
</body>
</html>
````

## `registro.php`

````php
<?php
// registro.php — Crea una cuenta de paciente.
// La contraseña inicial es el DNI; el correo es opcional.
session_start();
require_once "file_conexion.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir("registro.html");
}

$dni       = trim($_POST['dni'] ?? '');
$nomp      = tituloPersona($_POST['nomp'] ?? '');
$apep      = tituloPersona($_POST['apep'] ?? '');
$telp      = trim($_POST['telp'] ?? '');
$correo    = trim($_POST['correo'] ?? '');
$seguro    = trim($_POST['seguro'] ?? '');
$contrasena = $dni; // Regla del sistema: la contraseña es el DNI.

// Respaldo del autocompletado: si el JavaScript no pudo completar los nombres,
// se buscan por DNI en la base de datos y en la API interna antes de validar.
if (($nomp === '' || $apep === '') && preg_match('/^\d{8}$/', $dni)) {
    $persona = buscarPersonaPorDni($dni);
    if ($persona) {
        if ($nomp === '') $nomp = $persona['nombres'];
        if ($apep === '') $apep = $persona['apellidos'];
        if ($telp === '' && preg_match('/^\d{9}$/', (string)$persona['telefono'])) {
            $telp = $persona['telefono'];
        }
    }
}

$params = '&dni=' . urlencode($dni) . '&nomp=' . urlencode($nomp) . '&apep=' . urlencode($apep) . '&telp=' . urlencode($telp) . '&correo=' . urlencode($correo) . '&seguro=' . urlencode($seguro);

$segurosValidos = ['SIS', 'EPS', 'Privado'];
if ($dni === '' || $nomp === '' || $apep === '' || $telp === '' || $seguro === '') {
    redirigir("registro.html?error=campos" . $params);
}
// Los nombres y apellidos solo pueden contener letras y signos de nombre.
$patronNombre = "/^[\p{L}\s'’.,\/()\-]+$/u";
if (!preg_match($patronNombre, $nomp) || !preg_match($patronNombre, $apep)) {
    redirigir("registro.html?error=nombre" . $params);
}
if (!preg_match('/^\d{8}$/', $dni)) {
    redirigir("registro.html?error=dni" . $params);
}
if (!preg_match('/^\d{9}$/', $telp)) {
    redirigir("registro.html?error=tel" . $params);
}
if ($correo !== '' && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    redirigir("registro.html?error=correo" . $params);
}
if (!in_array($seguro, $segurosValidos, true)) {
    $seguro = 'SIS';
}

$mysqli = Conectarse();

// Verifica DNI duplicado
$stmt = $mysqli->prepare("SELECT codp FROM paciente WHERE dni = ? LIMIT 1");
$stmt->bind_param("s", $dni);
$stmt->execute();
$existe = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($existe) {
    $mysqli->close();
    redirigir("registro.html?error=existe" . $params);
}

$codp = generarCodigo($mysqli, 'paciente', 'codp', 'PAC');
$fechareg = date('Y-m-d');
$correoDb = ($correo === '') ? null : $correo;

$stmt = $mysqli->prepare("INSERT INTO paciente (codp, nomp, apep, dni, telp, correo, contrasena, seguro, fechareg) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssss", $codp, $nomp, $apep, $dni, $telp, $correoDb, $contrasena, $seguro, $fechareg);
$ok = $stmt->execute();
$stmt->close();

if (!$ok) {
    $mysqli->close();
    redirigir("registro.html?error=sistema" . $params);
}

session_regenerate_id(true);
$_SESSION['paciente_codp']   = $codp;
$_SESSION['paciente_nombre'] = $nomp . ' ' . $apep;
$_SESSION['paciente_dni']    = $dni;

$mysqli->close();
flash('exito', 'Tu perfil fue creado correctamente. ¡Bienvenido a PrimeLab!');
redirigir("panel.php");
?>
````

## `medico_login.html`

````html
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso de médicos — PrimeLab</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body data-page="medico_login">

<header class="topbar">
    <a href="index.html" class="brand">
        <img src="img/PrimeLab%20Logo.png" alt="PrimeLab" class="brand-logo">
        <span class="brand-copy">
            <strong>PrimeLab</strong>
            <small>Centros de salud públicos del Perú</small>
        </span>
    </a>
    <div class="topbar-right">
        <div class="section-tag">
            <span>Sección actual</span>
            <b>MÉDICO</b>
        </div>
    </div>
</header>

<main class="login-page">
    <div class="login-card">
        <h1>Acceso de médicos</h1>
        <p class="muted">Ingresa tu número de CMP para ver tu perfil y tus citas programadas.</p>

        <div id="alertBox" class="alert" aria-live="polite"></div>

        <form method="POST" action="login_medico.php" class="form-stack">
            <label class="field">
                <span>Número de CMP</span>
                <input type="text" name="cmpm" id="cmpm" maxlength="10" inputmode="numeric"
                       placeholder="Ejemplo: 10234" required>
                <small class="field-help">Usa tu CMP para ingresar. <strong>Ejemplo:</strong> 10234 (Dra. Ana Torres).</small>
            </label>

            <button type="submit" class="button primary full">Ingresar</button>
        </form>

        <p class="login-foot">
            <a href="index.html">← Volver al acceso de pacientes</a>
        </p>
    </div>
</main>

<footer class="site-footer">
    <p>PrimeLab — Sistema de reserva de citas en centros de salud públicos</p>
    <p>Proyecto académico. Los datos mostrados son de ejemplo.</p>
</footer>

<script src="js/main.js"></script>
</body>
</html>
````

## `login_medico.php`

````php
<?php
// login_medico.php — Ingreso del médico usando su CMP.
session_start();
require_once "file_conexion.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir("medico_login.html");
}

$cmpm = trim($_POST['cmpm'] ?? '');

if ($cmpm === '' || !preg_match('/^\d{4,10}$/', $cmpm)) {
    redirigir("medico_login.html?error=cmp&cmpm=" . urlencode($cmpm));
}

$mysqli = Conectarse();
$stmt = $mysqli->prepare("SELECT m.codm, m.nomm, m.cmpm, m.codesp, e.nomesp
                          FROM medico m JOIN especialidad e ON e.codesp = m.codesp
                          WHERE m.cmpm = ? LIMIT 1");
$stmt->bind_param("s", $cmpm);
$stmt->execute();
$res = $stmt->get_result();
$medico = $res->fetch_assoc();
$stmt->close();

if (!$medico) {
    $mysqli->close();
    redirigir("medico_login.html?error=noexiste&cmpm=" . urlencode($cmpm));
}

session_regenerate_id(true);
$_SESSION['medico_codm']   = $medico['codm'];
$_SESSION['medico_nombre'] = $medico['nomm'];
$_SESSION['medico_cmpm']   = $medico['cmpm'];

$mysqli->close();
flash('exito', 'Bienvenido(a), ' . $medico['nomm'] . '.');
redirigir("panel_medico.php");
?>
````

## `panel_medico.php`

````php
<?php
// panel_medico.php — Panel del médico: perfil, gestión de citas, atención y reprogramación.
require_once "file_conexion.php";
requerirMedico();

$mysqli = Conectarse();
$medico = medicoActual($mysqli);
if (!$medico) { $mysqli->close(); redirigir("medico_login.html?msg=sesion"); }

$codm = $medico['codm'];

function badgeEstadoMed($estado) {
    $e = strtolower(trim((string)$estado));
    $clase = 'status-pending';
    if ($e === 'atendida') $clase = 'status-done';
    if ($e === 'cancelada' || $e === 'reprogramada') $clase = 'status-cancel';
    if ($e === 'extendida') $clase = 'status-pending'; // se estiliza como en curso
    $label = ucfirst($e);
    return '<span class="status-badge ' . $clase . '">' . e($label) . '</span>';
}

// Procesar acciones del médico
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $codci  = trim($_POST['codci'] ?? '');

    // Verificar que la cita pertenezca al médico
    $cita = null;
    if ($codci !== '') {
        $stmt = $mysqli->prepare("SELECT * FROM cita WHERE codci = ? AND codm = ? LIMIT 1");
        $stmt->bind_param("ss", $codci, $codm);
        $stmt->execute();
        $cita = $stmt->get_result()->fetch_assoc();
        $stmt->close();
    }

    if (!$cita && in_array($accion, ['guardar_atencion','reprogramar'], true)) {
        flash('error', 'No se encontró la cita o no tienes permisos sobre ella.');
        $mysqli->close();
        redirigir('panel_medico.php');
    }

    if ($accion === 'guardar_atencion' && $cita) {
        $estadoNuevo  = trim($_POST['estado'] ?? $cita['estado']);
        $diagnostico  = trim($_POST['diagnostico'] ?? '');
        $indicaciones = trim($_POST['indicaciones'] ?? '');
        $observaciones = trim($_POST['observaciones'] ?? '');

        $estadosPermitidos = ['pendiente','atendida','cancelada','extendida','reprogramada'];
        if (!in_array($estadoNuevo, $estadosPermitidos, true)) $estadoNuevo = $cita['estado'];

        $stmt = $mysqli->prepare("UPDATE cita SET estado=?, diagnostico=?, indicaciones=?, observaciones=? WHERE codci=? AND codm=?");
        $diagDb = $diagnostico !== '' ? $diagnostico : null;
        $indDb  = $indicaciones !== '' ? $indicaciones : null;
        $obsDb  = $observaciones !== '' ? $observaciones : null;
        $stmt->bind_param("ssssss", $estadoNuevo, $diagDb, $indDb, $obsDb, $codci, $codm);
        $stmt->execute();
        $stmt->close();

        $stmt = $mysqli->prepare("INSERT INTO cita_historial (codci, accion, motivo, fecha, usuario_accion) VALUES (?, 'atencion', ?, NOW(), ?)");
        $motHist = 'Estado: ' . $estadoNuevo . ($diagnostico ? ' | Dx: ' . mb_substr($diagnostico,0,120) : '');
        $stmt->bind_param("sss", $codci, $motHist, $codm);
        $stmt->execute();
        $stmt->close();

        flash('exito', 'Atención de la cita ' . $codci . ' actualizada correctamente.');
        $mysqli->close();
        redirigir('panel_medico.php');
    }

    if ($accion === 'reprogramar' && $cita) {
        $nuevaFecha = trim($_POST['nueva_fecha'] ?? '');
        $nuevaHora  = trim($_POST['nueva_hora'] ?? '');
        $motivoReprog = trim($_POST['motivo_reprogramacion'] ?? '');
        $motivoCitaNueva = trim($_POST['motivo_nueva_cita'] ?? $cita['motivo']);
        $diagnostico  = trim($_POST['diagnostico'] ?? '');
        $indicaciones = trim($_POST['indicaciones'] ?? '');

        $motivosValidos = [
            'Inasistencia del paciente',
            'Cancelación',
            'Seguimiento del tratamiento',
            'Evaluación de resultados de exámenes',
            'Error administrativo del sistema',
            'Otro'
        ];
        if (!in_array($motivoReprog, $motivosValidos, true)) {
            $motivoReprog = 'Reprogramación solicitada';
        }

        $hoy = date('Y-m-d');
        if ($nuevaFecha === '' || $nuevaHora === '' || $nuevaFecha < $hoy) {
            flash('error', 'Indica una fecha y hora válidas para la nueva cita.');
            $mysqli->close();
            redirigir('panel_medico.php');
        }
        $horaDb = $nuevaHora;
        if (preg_match('/^\d{2}:\d{2}$/', $nuevaHora)) $horaDb = $nuevaHora . ':00';

        // Verificar disponibilidad del mismo médico
        $stmt = $mysqli->prepare("SELECT COUNT(*) AS n FROM cita WHERE codm=? AND fechaci=? AND horaci=? AND estado NOT IN ('cancelada','reprogramada')");
        $stmt->bind_param("sss", $codm, $nuevaFecha, $horaDb);
        $stmt->execute();
        $ocupado = (int)$stmt->get_result()->fetch_assoc()['n'] > 0;
        $stmt->close();
        if ($ocupado) {
            flash('error', 'El horario seleccionado no está disponible para el médico. Elige otro.');
            $mysqli->close();
            redirigir('panel_medico.php');
        }

        // Obtener seguro del paciente para calcular costo
        $stmt = $mysqli->prepare("SELECT seguro FROM paciente WHERE codp=? LIMIT 1");
        $stmt->bind_param("s", $cita['codp']);
        $stmt->execute();
        $pac = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $seguroPac = $pac['seguro'] ?? 'SIS';
        $costoNuevo = calcularCosto($seguroPac);

        // Marcar cita original como reprogramada y guardar notas/motivo
        $diagDb = $diagnostico !== '' ? $diagnostico : null;
        $indDb  = $indicaciones !== '' ? $indicaciones : null;
        $obsDb  = 'Reprogramada: ' . $motivoReprog . ' (nueva cita pendiente de generar código)';
        $estadoOrig = 'reprogramada';
        $stmt = $mysqli->prepare("UPDATE cita SET estado=?, diagnostico=?, indicaciones=?, observaciones=?, motivo_reprogramacion=? WHERE codci=? AND codm=?");
        $stmt->bind_param("sssssss", $estadoOrig, $diagDb, $indDb, $obsDb, $motivoReprog, $codci, $codm);
        $stmt->execute();
        $stmt->close();

        // Crear nueva cita con mismo médico, paciente y centro
        $nuevoCodci = generarCodigo($mysqli, 'cita', 'codci', 'CIT');
        $fechareg = date('Y-m-d H:i:s');
        $estadoNueva = 'pendiente';
        $stmt = $mysqli->prepare("INSERT INTO cita (codci, fechaci, horaci, motivo, estado, costo, codp, codm, codce, fechareg, observaciones, reprogramada_de) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        $obsNueva = 'Reprogramada desde ' . $codci . ' (' . $motivoReprog . ')';
        $stmt->bind_param("ssssdsssssss", $nuevoCodci, $nuevaFecha, $horaDb, $motivoCitaNueva, $estadoNueva, $costoNuevo, $cita['codp'], $codm, $cita['codce'], $fechareg, $obsNueva, $codci);
        $okInsert = $stmt->execute();
        $stmt->close();

        // Actualizar observación original con código nuevo
        $obsFinal = 'Reprogramada a ' . $nuevoCodci . ' (' . $motivoReprog . ')';
        $stmt = $mysqli->prepare("UPDATE cita SET observaciones=? WHERE codci=?");
        $stmt->bind_param("ss", $obsFinal, $codci);
        $stmt->execute();
        $stmt->close();

        // Historial
        $stmt = $mysqli->prepare("INSERT INTO cita_historial (codci, accion, motivo, fecha, usuario_accion) VALUES (?, 'reprogramacion', ?, NOW(), ?)");
        $mh = 'Reprogramada a ' . $nuevoCodci . ' por: ' . $motivoReprog;
        $stmt->bind_param("sss", $codci, $mh, $codm);
        $stmt->execute();
        $stmt->close();
        $stmt = $mysqli->prepare("INSERT INTO cita_historial (codci, accion, motivo, fecha, usuario_accion) VALUES (?, 'creacion', ?, NOW(), ?)");
        $mh2 = 'Nueva cita creada desde ' . $codci . ' por: ' . $motivoReprog;
        $stmt->bind_param("sss", $nuevoCodci, $mh2, $codm);
        $stmt->execute();
        $stmt->close();

        if ($okInsert) {
            flash('exito', 'Cita ' . $codci . ' reprogramada correctamente. Nueva cita: ' . $nuevoCodci . ' (' . fechaTexto($nuevaFecha) . ' ' . horaTexto($horaDb) . ').');
        } else {
            flash('error', 'Se marcó la reprogramación, pero hubo un problema al crear la nueva cita.');
        }
        $mysqli->close();
        redirigir('panel_medico.php');
    }
}

// Listado de citas del médico
$stmt = $mysqli->prepare("
    SELECT c.codci, c.fechaci, c.horaci, c.estado, c.motivo, c.costo, c.observaciones,
           c.diagnostico, c.indicaciones, c.motivo_reprogramacion, c.reprogramada_de,
           p.codp, p.nomp, p.apep, p.dni, p.telp, p.seguro,
           ce.codce, ce.nomce, ce.distrito
    FROM cita c
    JOIN paciente p ON p.codp = c.codp
    JOIN centro ce ON ce.codce = c.codce
    WHERE c.codm = ?
    ORDER BY c.fechaci DESC, c.horaci DESC
");
$stmt->bind_param("s", $codm);
$stmt->execute();
$citas = $stmt->get_result();
$stmt->close();

$total = 0; $pendientes = 0; $atendidas = 0; $canceladas = 0; $extendidas = 0; $reprogramadas = 0;
$resCount = $mysqli->query("SELECT estado, COUNT(*) n FROM cita WHERE codm = '" . $mysqli->real_escape_string($codm) . "' GROUP BY estado");
while ($r = $resCount->fetch_assoc()) {
    $total += (int)$r['n'];
    $st = strtolower($r['estado']);
    if ($st === 'pendiente') $pendientes = (int)$r['n'];
    if ($st === 'atendida') $atendidas = (int)$r['n'];
    if ($st === 'cancelada') $canceladas = (int)$r['n'];
    if ($st === 'extendida') $extendidas = (int)$r['n'];
    if ($st === 'reprogramada') $reprogramadas = (int)$r['n'];
}

$mysqli->close();

$motivosReprog = [
    'Inasistencia del paciente',
    'Cancelación',
    'Seguimiento del tratamiento',
    'Evaluación de resultados de exámenes',
    'Error administrativo del sistema',
    'Otro'
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del médico — PrimeLab</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="topbar">
    <a href="panel_medico.php" class="brand">
        <img src="img/PrimeLab%20Logo.png" alt="PrimeLab" class="brand-logo">
        <span class="brand-copy"><strong>PrimeLab</strong><small>Panel de médicos</small></span>
    </a>
    <div class="topbar-right"><div class="section-tag"><span>Sección actual</span><b>MÉDICO</b></div></div>
</header>

<div class="app-layout">
    <aside class="sidebar">
        <div class="user-chip">
            <div style="flex-shrink:0;"><?= fotoMedico($medico['codm'], $medico['nomm'], 44) ?></div>
            <div><strong><?= e($medico['nomm']) ?></strong><small>CMP <?= e($medico['cmpm']) ?></small></div>
        </div>
        <nav>
            <p class="nav-label">Médico</p>
            <a href="panel_medico.php" class="nav-btn active"><span class="ico">👨‍⚕️</span><span>Mi panel</span></a>
            <a href="panel_medico.php#consultas" class="nav-btn"><span class="ico">🩺</span><span>Consultas</span></a>
        </nav>
        <div class="account-actions">
            <a href="logout.php" class="nav-btn nav-btn-danger"><span class="ico">⎋</span><span>Cerrar sesión</span></a>
        </div>
    </aside>

    <main class="content">
        <?php mostrarFlash(); ?>

        <div class="page-heading">
            <p class="eyebrow">Perfil profesional</p>
            <h1>Bienvenido(a), <?= e($medico['nomm']) ?></h1>
            <p class="muted">Gestiona tus citas: registra atención, estado (atendida, cancelada o extendida), diagnóstico, indicaciones y reprogramaciones con el mismo médico.</p>
        </div>

        <section class="panel" style="margin-bottom:18px;">
            <h2 class="panel-title">Mi perfil</h2>
            <div class="medico-resumen" style="margin:12px 0 16px; max-width:720px;">
                <?= fotoMedico($medico['codm'], $medico['nomm'], 80) ?>
                <div>
                    <strong style="font-size:20px; display:block;"><?= e($medico['nomm']) ?></strong>
                    <span class="muted">Especialidad: <?= e($medico['nomesp']) ?></span>
                    <?php if (!empty($medico['formacion'])): ?>
                        <div class="muted" style="margin-top:4px;"><?= e($medico['formacion']) ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="data-grid">
                <div><span>Código</span><strong><?= e($medico['codm']) ?></strong></div>
                <div><span>CMP</span><strong><?= e($medico['cmpm']) ?></strong></div>
                <div><span>DNI</span><strong><?= e($medico['dni'] ?? '-') ?></strong></div>
                <div><span>Especialidad</span><strong><?= e($medico['nomesp']) ?></strong></div>
                <div><span>Experiencia</span><strong><?= isset($medico['anios_experiencia']) ? e($medico['anios_experiencia'] . ' años') : '-' ?></strong></div>
                <div><span>Teléfono</span><strong><?= e($medico['telefono'] ?? '-') ?></strong></div>
                <div><span>Correo</span><strong><?= e($medico['correo'] ?? '-') ?></strong></div>
            </div>
        </section>

        <section class="dashboard-overview">
            <article class="summary-card"><span class="summary-label">Citas totales</span><strong class="summary-count"><?= $total ?></strong><span class="summary-details">asignadas</span></article>
            <article class="summary-card"><span class="summary-label">Pendientes</span><strong class="summary-count"><?= $pendientes ?></strong><span class="summary-details">por atender</span></article>
            <article class="summary-card"><span class="summary-label">Atendidas</span><strong class="summary-count"><?= $atendidas ?></strong><span class="summary-details">completadas</span></article>
            <article class="summary-card"><span class="summary-label">Extendidas</span><strong class="summary-count"><?= $extendidas ?></strong><span class="summary-details">en seguimiento</span></article>
            <article class="summary-card"><span class="summary-label">Reprogramadas</span><strong class="summary-count"><?= $reprogramadas ?></strong><span class="summary-details">con nueva cita</span></article>
            <article class="summary-card"><span class="summary-label">Canceladas</span><strong class="summary-count"><?= $canceladas ?></strong><span class="summary-details">anuladas</span></article>
        </section>

        <section class="panel" id="consultas">
            <h2 class="panel-title">Gestión de citas y consultas</h2>
            <p class="muted" style="margin-bottom:14px;">Registra el estado, lo observado en el paciente, indicaciones/medicamentos y, si corresponde, reprograma una nueva cita con el mismo médico.</p>

            <?php if ($citas->num_rows === 0): ?>
                <p class="table-empty">No tienes citas asignadas.</p>
            <?php else: while ($c = $citas->fetch_assoc()): ?>
                <details class="cita-gestion-card" style="border:1px solid var(--line,#d8e2e0); border-radius:14px; padding:14px 16px; margin-bottom:12px; background:#fff;">
                    <summary style="cursor:pointer; list-style:none; display:flex; flex-wrap:wrap; gap:10px 18px; align-items:center; justify-content:space-between;">
                        <div>
                            <span class="code"><?= e($c['codci']) ?></span>
                            <strong style="margin-left:8px;"><?= e($c['nomp'] . ' ' . $c['apep']) ?></strong>
                            <span class="muted"> · DNI <?= e($c['dni']) ?> · <?= e(textoSeguro($c['seguro'] ?? 'SIS')) ?></span>
                        </div>
                        <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                            <span class="muted"><?= e(fechaTexto($c['fechaci'])) ?> <?= e(horaTexto($c['horaci'])) ?></span>
                            <?= badgeEstadoMed($c['estado']) ?>
                        </div>
                    </summary>

                    <div style="margin-top:14px; display:grid; gap:14px;">
                        <div class="data-grid">
                            <div><span>Paciente</span><strong><?= e($c['nomp'] . ' ' . $c['apep']) ?></strong></div>
                            <div><span>Teléfono</span><strong><?= $c['telp'] ? e($c['telp']) : 'No registrado' ?></strong></div>
                            <div><span>Seguro / Costo</span><strong><?= e(textoSeguro($c['seguro'] ?? 'SIS')) ?> — S/ <?= number_format((float)$c['costo'], 2) ?></strong></div>
                            <div><span>Centro</span><strong><?= e($c['nomce']) ?> (<?= e($c['distrito']) ?>)</strong></div>
                            <div style="grid-column:1/-1;"><span>Motivo de la cita</span><strong><?= e($c['motivo']) ?></strong></div>
                            <?php if (!empty($c['reprogramada_de'])): ?>
                                <div><span>Reprogramada desde</span><strong><?= e($c['reprogramada_de']) ?></strong></div>
                            <?php endif; ?>
                            <?php if (!empty($c['motivo_reprogramacion'])): ?>
                                <div><span>Motivo de reprogramación</span><strong><?= e($c['motivo_reprogramacion']) ?></strong></div>
                            <?php endif; ?>
                        </div>

                        <form method="POST" action="panel_medico.php" class="form-stack" style="border-top:1px solid var(--line,#e6eeee); padding-top:12px;">
                            <input type="hidden" name="accion" value="guardar_atencion">
                            <input type="hidden" name="codci" value="<?= e($c['codci']) ?>">
                            <h3 class="list-h" style="margin:0;">Registro de atención</h3>
                            <div class="form-grid-2">
                                <label class="field">
                                    <span>Estado de la cita</span>
                                    <select name="estado" required>
                                        <?php foreach (['pendiente','atendida','cancelada','extendida'] as $st): ?>
                                            <option value="<?= $st ?>" <?= strtolower($c['estado']) === $st ? 'selected' : '' ?>><?= ucfirst($st) ?></option>
                                        <?php endforeach; ?>
                                        <?php if (strtolower($c['estado']) === 'reprogramada'): ?>
                                            <option value="reprogramada" selected>Reprogramada</option>
                                        <?php endif; ?>
                                    </select>
                                </label>
                                <label class="field">
                                    <span>Observaciones generales</span>
                                    <input type="text" name="observaciones" maxlength="255" value="<?= e($c['observaciones'] ?? '') ?>" placeholder="Notas adicionales (opcional)">
                                </label>
                            </div>
                            <label class="field">
                                <span>Descripción de lo observado al paciente (diagnóstico/evaluación)</span>
                                <textarea name="diagnostico" rows="3" placeholder="Ej.: síntomas, hallazgos clínicos, evaluación..."><?= e($c['diagnostico'] ?? '') ?></textarea>
                            </label>
                            <label class="field">
                                <span>Indicaciones y medicamentos entregados/recetados</span>
                                <textarea name="indicaciones" rows="3" placeholder="Ej.: medicamentos, dosis, indicaciones, reposo, exámenes solicitados..."><?= e($c['indicaciones'] ?? '') ?></textarea>
                            </label>
                            <div class="ticket-actions">
                                <button type="submit" class="button primary">Guardar atención</button>
                            </div>
                        </form>

                        <form method="POST" action="panel_medico.php" class="form-stack" style="border-top:1px dashed var(--line,#d8e2e0); padding-top:12px; background:#f8fbfa; border-radius:10px; padding:12px;">
                            <input type="hidden" name="accion" value="reprogramar">
                            <input type="hidden" name="codci" value="<?= e($c['codci']) ?>">
                            <h3 class="list-h" style="margin:0;">Reprogramar cita (misma paciente y mismo médico)</h3>
                            <p class="muted" style="margin:0;">Crea una nueva cita con el mismo doctor. La cita actual se marcará como reprogramada.</p>
                            <div class="form-grid-2">
                                <label class="field">
                                    <span>Motivo de reprogramación</span>
                                    <select name="motivo_reprogramacion" required>
                                        <option value="">Selecciona un motivo</option>
                                        <?php foreach ($motivosReprog as $mr): ?>
                                            <option value="<?= e($mr) ?>"><?= e($mr) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>
                                <label class="field">
                                    <span>Motivo de la nueva cita</span>
                                    <input type="text" name="motivo_nueva_cita" maxlength="255" value="<?= e($c['motivo']) ?>">
                                </label>
                                <label class="field">
                                    <span>Nueva fecha</span>
                                    <input type="date" name="nueva_fecha" min="<?= date('Y-m-d') ?>" required>
                                </label>
                                <label class="field">
                                    <span>Nueva hora</span>
                                    <input type="time" name="nueva_hora" required>
                                </label>
                            </div>
                            <div class="form-grid-2">
                                <label class="field">
                                    <span>Diagnóstico/observación (opcional)</span>
                                    <input type="text" name="diagnostico" maxlength="255" value="<?= e(mb_substr((string)($c['diagnostico'] ?? ''),0,255)) ?>" placeholder="Resumen breve">
                                </label>
                                <label class="field">
                                    <span>Indicaciones/medicamentos (opcional)</span>
                                    <input type="text" name="indicaciones" maxlength="255" value="<?= e(mb_substr((string)($c['indicaciones'] ?? ''),0,255)) ?>" placeholder="Resumen breve">
                                </label>
                            </div>
                            <div class="ticket-actions">
                                <button type="submit" class="button secondary" onclick="return confirm('¿Reprogramar esta cita y crear una nueva con el mismo médico?');">Reprogramar y crear nueva cita</button>
                            </div>
                        </form>
                    </div>
                </details>
            <?php endwhile; endif; ?>
        </section>
    </main>
</div>

<footer class="site-footer"><p>PrimeLab — Panel de médicos</p></footer>
<script src="js/main.js"></script>
</body>
</html>
````

## `admin_login.html`

````html
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso administrativo — PrimeLab</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body data-page="admin">

<header class="topbar">
    <a href="index.html" class="brand">
        <img src="img/PrimeLab%20Logo.png" alt="PrimeLab" class="brand-logo">
        <span class="brand-copy">
            <strong>PrimeLab</strong>
            <small>Centros de salud públicos del Perú</small>
        </span>
    </a>
    <div class="topbar-right">
        <div class="section-tag">
            <span>Sección actual</span>
            <b>ADMIN</b>
        </div>
    </div>
</header>

<main class="login-page">
    <div class="login-card">
        <h1>Acceso administrativo</h1>
        <p class="muted">Ingresa tus credenciales para gestionar citas, médicos y centros.</p>
        <p class="field-help" style="margin-top:-4px;">Principal: usuario <strong>prime</strong> · correo <strong>primelab@centrosalud.pe</strong> · contraseña <strong>pr1m3l4b</strong><br>También: <strong>admin2</strong> / gestion@centrosalud.pe y <strong>admin3</strong> / soporte@centrosalud.pe (clave PrimeLab2026).</p>

        <div id="alertBox" class="alert" aria-live="polite"></div>

        <form method="POST" action="login.php" class="form-stack">
            <label class="field">
                <span>Usuario</span>
                <input type="text" name="usuario" id="usuario" placeholder="Usuario" required>
            </label>

            <label class="field">
                <span>Correo electrónico</span>
                <input type="email" name="correo" id="correo" placeholder="correo@ejemplo.com" required>
            </label>

            <label class="field">
                <span>Contraseña</span>
                <input type="password" name="password" id="password" placeholder="Contraseña" required>
            </label>

            <button type="submit" class="button primary full">Ingresar</button>
        </form>

        <p class="login-foot">
            <a href="index.html">← Volver al acceso de pacientes</a>
        </p>
    </div>
</main>

<footer class="site-footer">
    <p>PrimeLab — Sistema de reserva de citas en centros de salud públicos</p>
    <p>Proyecto académico. Los datos mostrados son de ejemplo.</p>
</footer>

<script src="js/main.js"></script>
</body>
</html>
````

## `login.php`

````php
<?php
// login.php
// Procesa el formulario de acceso del administrador que está en admin_login.html
session_start();
require_once "file_conexion.php";

if (isset($_SESSION['admin_logged']) && $_SESSION['admin_logged'] === true) {
    redirigir("admin_citas.php");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir("admin_login.html");
}

$usuario  = trim($_POST['usuario'] ?? '');
$correo   = trim($_POST['correo'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($usuario === '' || $correo === '' || $password === '') {
    redirigir("admin_login.html?error=campos");
}

// Se admiten varios administradores (tabla `administrador`), con respaldo
// en la lista interna. El administrador principal es `prime`.
$mysqli = ConectarseOpcional();
$admin  = $mysqli ? buscarAdministrador($mysqli, $usuario, $correo) : null;

$claveOk = false;
if ($admin) {
    $guardada = (string) ($admin['contrasena'] ?? '');
    if (strpos($guardada, '$2y$') === 0 || strpos($guardada, '$argon2') === 0) {
        $claveOk = password_verify($password, $guardada);
    } else {
        $claveOk = hash_equals($guardada, $password);
    }
}

if ($claveOk) {
    session_regenerate_id(true);
    $_SESSION['admin_logged'] = true;
    $_SESSION['admin_user']   = $admin['usuario'];
    $_SESSION['admin_email']  = $admin['correo'];
    $_SESSION['admin_nombre'] = $admin['nombre'] ?? $admin['usuario'];

    if ($mysqli) $mysqli->close();
    flash('exito', 'Bienvenido al panel administrativo, ' . ($admin['nombre'] ?? $admin['usuario']) . '.');
    redirigir("admin_citas.php");
}

if ($mysqli) $mysqli->close();
redirigir("admin_login.html?error=credenciales");
````

## `logout.php`

````php
<?php
// logout.php — Cierra la sesión del paciente, médico o administrador.
session_start();

$eraAdmin  = !empty($_SESSION['admin_logged']);
$eraMedico = !empty($_SESSION['medico_codm']);

$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
session_destroy();

if ($eraAdmin) {
    header('Location: admin_login.html?msg=salir');
} elseif ($eraMedico) {
    header('Location: medico_login.html?msg=salir');
} else {
    header('Location: index.html?msg=salir');
}
exit;
?>
````

## `panel.php`

````php
<?php
// panel.php — Panel principal del paciente.
require_once "file_conexion.php";
requerirPaciente();

$mysqli = Conectarse();
$paciente = pacienteActual($mysqli);
if (!$paciente) { $mysqli->close(); redirigir("index.html?msg=sesion"); }

$codp = $paciente['codp'];

// Próxima cita pendiente
$stmt = $mysqli->prepare("
    SELECT c.codci, c.fechaci, c.horaci, c.estado, c.motivo,
           ce.nomce, ce.dirc, ce.distrito,
           e.nomesp, m.codm, m.nomm
    FROM cita c
    JOIN centro ce ON ce.codce = c.codce
    JOIN medico m ON m.codm = c.codm
    JOIN especialidad e ON e.codesp = m.codesp
    WHERE c.codp = ? AND c.estado = 'pendiente' AND c.fechaci >= CURDATE()
    ORDER BY c.fechaci ASC, c.horaci ASC
    LIMIT 1
");
$stmt->bind_param("s", $codp);
$stmt->execute();
$proxima = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Conteos
function contarCitas($mysqli, $codp, $estado = null) {
    if ($estado) {
        $stmt = $mysqli->prepare("SELECT COUNT(*) AS n FROM cita WHERE codp = ? AND estado = ?");
        $stmt->bind_param("ss", $codp, $estado);
    } else {
        $stmt = $mysqli->prepare("SELECT COUNT(*) AS n FROM cita WHERE codp = ?");
        $stmt->bind_param("s", $codp);
    }
    $stmt->execute();
    $n = (int)$stmt->get_result()->fetch_assoc()['n'];
    $stmt->close();
    return $n;
}
$total = contarCitas($mysqli, $codp);
$pendientes = contarCitas($mysqli, $codp, 'pendiente');
$atendidas = contarCitas($mysqli, $codp, 'atendida');

$mysqli->close();
$nombreCompleto = $paciente['nomp'] . ' ' . $paciente['apep'];
$inicial = mb_strtoupper(mb_substr($paciente['nomp'], 0, 1, 'UTF-8'), 'UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del paciente — PrimeLab</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="topbar">
    <a href="panel.php" class="brand">
        <img src="img/PrimeLab%20Logo.png" alt="PrimeLab" class="brand-logo">
        <span class="brand-copy"><strong>PrimeLab</strong><small>Centros de salud públicos del Perú</small></span>
    </a>
    <div class="topbar-right">
        <div class="section-tag"><span>Sección actual</span><b>INICIO</b></div>
    </div>
</header>

<div class="app-layout">
    <aside class="sidebar">
        <div class="user-chip">
            <div class="avatar"><?= e($inicial) ?></div>
            <div>
                <strong><?= e($nombreCompleto) ?></strong>
                <small>DNI <?= e($paciente['dni']) ?></small>
            </div>
        </div>
        <nav>
            <p class="nav-label">Paciente</p>
            <a href="panel.php" class="nav-btn active"><span class="ico">🏠</span><span>Inicio</span></a>
            <a href="reservar.php" class="nav-btn"><span class="ico">📅</span><span>Reservar cita</span></a>
            <a href="mis_citas.php" class="nav-btn"><span class="ico">🗂</span><span>Mis citas</span></a>
            <a href="consultas.php" class="nav-btn"><span class="ico">🩺</span><span>Consultas</span></a>
            <a href="perfil.php" class="nav-btn"><span class="ico">👤</span><span>Mi perfil</span></a>
        </nav>
        <div class="account-actions">
            <a href="logout.php" class="nav-btn nav-btn-danger"><span class="ico">⎋</span><span>Cerrar sesión</span></a>
        </div>
    </aside>

    <main class="content">
        <?php mostrarFlash(); ?>

        <div class="page-heading">
            <p class="eyebrow">Bienvenido</p>
            <h1>Bienvenido, <?= e($nombreCompleto) ?></h1>
            <p class="muted">Gestiona tus reservas y consulta tus citas en centros de salud públicos.</p>
        </div>

        <section class="dashboard-banner">
            <div class="banner-copy">
                <h2>Reserva tu cita de forma rápida y segura</h2>
                <p>Elige el centro, la especialidad, el médico y el horario que mejor se adapte a ti.</p>
                <a href="reservar.php" class="button primary">Reservar nueva cita</a>
                <a href="consultas.php" class="button secondary">Ver consultas</a>
            </div>
            <div class="banner-visual">
                <?php if ($proxima): ?>
                <div class="mini-ticket">
                    <strong>Próxima cita</strong>
                    <small><?= e($proxima['nomesp']) ?></small>
                    <small><?= e(fechaTexto($proxima['fechaci'])) ?> · <?= e(horaTexto($proxima['horaci'])) ?></small>
                    <small><?= e($proxima['nomce']) ?></small>
                </div>
                <?php endif; ?>
            </div>
        </section>

        <section class="dashboard-overview">
            <article class="summary-card">
                <span class="summary-label">Citas totales</span>
                <strong class="summary-count"><?= $total ?></strong>
                <span class="summary-details">registradas</span>
            </article>
            <article class="summary-card">
                <span class="summary-label">Pendientes</span>
                <strong class="summary-count"><?= $pendientes ?></strong>
                <span class="summary-details">por atender</span>
            </article>
            <article class="summary-card">
                <span class="summary-label">Atendidas</span>
                <strong class="summary-count"><?= $atendidas ?></strong>
                <span class="summary-details">completadas</span>
            </article>
        </section>

        <?php if ($proxima): ?>
        <section class="panel">
            <div class="panel-head">
                <h2 class="panel-title">Próxima cita pendiente</h2>
                <a href="ticket.php?codigo=<?= e($proxima['codci']) ?>" class="link-inline">Ver ticket</a>
            </div>
            <div class="data-grid">
                <div><span>Código</span><strong><?= e($proxima['codci']) ?></strong></div>
                <div><span>Especialidad</span><strong><?= e($proxima['nomesp']) ?></strong></div>
                <div><span>Médico</span><strong class="medico-inline"><?= fotoMedico($proxima['codm'], $proxima['nomm'], 40) ?><span><?= e($proxima['nomm']) ?></span></strong></div>
                <div><span>Centro</span><strong><?= e($proxima['nomce']) ?></strong></div>
                <div><span>Fecha</span><strong><?= e(fechaTexto($proxima['fechaci'])) ?></strong></div>
                <div><span>Hora</span><strong><?= e(horaTexto($proxima['horaci'])) ?></strong></div>
            </div>
        </section>
        <?php else: ?>
        <section class="panel">
            <h2 class="panel-title">No tienes citas pendientes</h2>
            <p class="muted">Cuando reserves una cita aparecerá aquí con su información.</p>
            <a href="reservar.php" class="button secondary">Reservar ahora</a>
        </section>
        <?php endif; ?>
    </main>
</div>

<footer class="site-footer">
    <p>PrimeLab — Sistema de reserva de citas en centros de salud públicos</p>
</footer>
<script src="js/main.js"></script>
</body>
</html>
````

## `reservar.php`

````php
<?php
// reservar.php — Reserva de cita en 3 pasos.
require_once "file_conexion.php";
requerirPaciente();

$mysqli = Conectarse();
$paciente = pacienteActual($mysqli);
if (!$paciente) { $mysqli->close(); redirigir("index.html?msg=sesion"); }

$codp = $paciente['codp'];
$paso = isset($_GET['paso']) ? (int)$_GET['paso'] : 1;
if ($paso < 1 || $paso > 3) $paso = 1;

// Horarios disponibles (bloques fijos)
$HORARIOS = ['08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','14:00','14:30','15:00','15:30','16:00','16:30'];

// Confirmación (POST desde paso 3)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'confirmar') {
    $centro = trim($_POST['centro'] ?? '');
    $esp    = trim($_POST['esp'] ?? '');
    $med    = trim($_POST['med'] ?? '');
    $fecha  = trim($_POST['fecha'] ?? '');
    $hora   = trim($_POST['hora'] ?? '');
    $motivo = trim($_POST['motivo'] ?? '');

    $base = "reservar.php?paso=3&centro=" . urlencode($centro) . "&esp=" . urlencode($esp) . "&med=" . urlencode($med) . "&fecha=" . urlencode($fecha) . "&hora=" . urlencode($hora);

    if ($centro === '' || $esp === '' || $med === '' || $fecha === '' || $hora === '' || $motivo === '') {
        $mysqli->close();
        redirigir($base . "&error=campos");
    }

    // Validar que el horario siga libre para el médico/fecha
    $stmt = $mysqli->prepare("SELECT COUNT(*) AS n FROM cita WHERE codm = ? AND fechaci = ? AND horaci = ? AND estado <> 'cancelada'");
    $horaDb = $hora . ':00';
    $stmt->bind_param("sss", $med, $fecha, $horaDb);
    $stmt->execute();
    $ocupado = (int)$stmt->get_result()->fetch_assoc()['n'] > 0;
    $stmt->close();

    if ($ocupado) {
        $mysqli->close();
        redirigir("reservar.php?paso=2&centro=" . urlencode($centro) . "&esp=" . urlencode($esp) . "&error=nohorario");
    }

    $codci = generarCodigo($mysqli, 'cita', 'codci', 'CIT');
    $fechareg = date('Y-m-d H:i:s');
    $estado = 'pendiente';
    $seguroPaciente = $paciente['seguro'] ?? 'SIS';
    $costo = calcularCosto($seguroPaciente);

    $stmt = $mysqli->prepare("INSERT INTO cita (codci, fechaci, horaci, motivo, estado, costo, codp, codm, codce, fechareg) VALUES (?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param("ssssdsssss", $codci, $fecha, $horaDb, $motivo, $estado, $costo, $codp, $med, $centro, $fechareg);
    $ok = $stmt->execute();
    $stmt->close();
    $mysqli->close();

    if (!$ok) {
        redirigir($base . "&error=sistema");
    }
    redirigir("ticket.php?codigo=" . urlencode($codci) . "&ok=creada");
}

// Datos comunes
$nombreCompleto = $paciente['nomp'] . ' ' . $paciente['apep'];
$inicial = mb_strtoupper(mb_substr($paciente['nomp'], 0, 1, 'UTF-8'), 'UTF-8');

// Paso 1: filtros de centros
$q = trim($_GET['q'] ?? '');
$fDistrito = trim($_GET['distrito'] ?? '');
$fAtencion = trim($_GET['atencion'] ?? ''); // especialidad

// Listas para filtros
$distritos = [];
$resD = $mysqli->query("SELECT DISTINCT distrito FROM centro ORDER BY distrito");
while ($r = $resD->fetch_assoc()) $distritos[] = $r['distrito'];

$atenciones = [];
$resA = $mysqli->query("SELECT codesp, nomesp FROM especialidad ORDER BY nomesp");
while ($r = $resA->fetch_assoc()) $atenciones[] = $r;

// Consulta de centros (paso 1)
$centros = null;
if ($paso === 1) {
    $sql = "SELECT c.codce, c.nomce, c.dirc, c.distrito, c.provincia, c.departamento, c.telefono, c.horario_atencion, c.tipo,
                   GROUP_CONCAT(DISTINCT e.nomesp ORDER BY e.nomesp SEPARATOR ', ') AS especialidades
            FROM centro c
            LEFT JOIN centro_especialidad ce ON ce.codce = c.codce
            LEFT JOIN especialidad e ON e.codesp = ce.codesp
            WHERE 1=1";
    $params = []; $types = '';
    if ($q !== '') {
        $sql .= " AND (c.nomce LIKE ? OR c.distrito LIKE ? OR c.dirc LIKE ?)";
        $like = '%' . $q . '%';
        $params[] = $like; $params[] = $like; $params[] = $like; $types .= 'sss';
    }
    if ($fDistrito !== '') {
        $sql .= " AND c.distrito = ?";
        $params[] = $fDistrito; $types .= 's';
    }
    if ($fAtencion !== '') {
        $sql .= " AND EXISTS (SELECT 1 FROM centro_especialidad ce2 WHERE ce2.codce = c.codce AND ce2.codesp = ?)";
        $params[] = $fAtencion; $types .= 's';
    }
    $sql .= " GROUP BY c.codce, c.nomce, c.dirc, c.distrito, c.provincia, c.departamento, c.telefono, c.horario_atencion, c.tipo ORDER BY c.nomce";
    $stmt = $mysqli->prepare($sql);
    if ($types !== '') $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $centros = $stmt->get_result();
    $stmt->close();
}

// Paso 2 y 3: datos seleccionados
$centroSel = trim($_GET['centro'] ?? '');
$espSel    = trim($_GET['esp'] ?? '');
$medSel    = trim($_GET['med'] ?? '');
$fechaSel  = trim($_GET['fecha'] ?? date('Y-m-d'));
$horaSel   = trim($_GET['hora'] ?? '');

$centroInfo = null; $espsCentro = null; $medicos = null; $espInfo = null; $medInfo = null;

if ($paso >= 2 && $centroSel !== '') {
    $stmt = $mysqli->prepare("SELECT codce, nomce, dirc, distrito, provincia, departamento, telefono, horario_atencion, tipo FROM centro WHERE codce = ? LIMIT 1");
    $stmt->bind_param("s", $centroSel);
    $stmt->execute();
    $centroInfo = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($centroInfo) {
        $stmt = $mysqli->prepare("
            SELECT e.codesp, e.nomesp,
                   (SELECT COUNT(*) FROM medico m WHERE m.codesp = e.codesp) AS medicos
            FROM especialidad e
            JOIN centro_especialidad ce ON ce.codesp = e.codesp
            WHERE ce.codce = ?
            ORDER BY e.nomesp
        ");
        $stmt->bind_param("s", $centroSel);
        $stmt->execute();
        $espsCentro = $stmt->get_result();
        $stmt->close();
    }
}

if ($paso >= 2 && $espSel !== '') {
    $stmt = $mysqli->prepare("SELECT codesp, nomesp FROM especialidad WHERE codesp = ? LIMIT 1");
    $stmt->bind_param("s", $espSel);
    $stmt->execute();
    $espInfo = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $stmt = $mysqli->prepare("SELECT codm, nomm, cmpm FROM medico WHERE codesp = ? ORDER BY nomm");
    $stmt->bind_param("s", $espSel);
    $stmt->execute();
    $medicos = $stmt->get_result();
    $stmt->close();
}

if ($medSel !== '') {
    $stmt = $mysqli->prepare("SELECT codm, nomm, cmpm FROM medico WHERE codm = ? LIMIT 1");
    $stmt->bind_param("s", $medSel);
    $stmt->execute();
    $medInfo = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// Horarios ocupados para médico+fecha seleccionados (paso 2/3)
$ocupados = [];
if ($medSel !== '' && $fechaSel !== '') {
    $stmt = $mysqli->prepare("SELECT horaci FROM cita WHERE codm = ? AND fechaci = ? AND estado <> 'cancelada'");
    $stmt->bind_param("ss", $medSel, $fechaSel);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($r = $res->fetch_assoc()) $ocupados[] = substr($r['horaci'], 0, 5);
    $stmt->close();
}

$mysqli->close();

if ($paso === 2 && !$centroInfo) { $paso = 1; }
if ($paso === 3 && (!$centroInfo || !$espInfo || !$medInfo || $fechaSel === '' || $horaSel === '')) { $paso = 2; }

function pasoClase($n, $actual) {
    if ($n < $actual) return 'step done';
    if ($n === $actual) return 'step active';
    return 'step';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar cita — PrimeLab</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body data-page="reservar">
<header class="topbar">
    <a href="panel.php" class="brand">
        <img src="img/PrimeLab%20Logo.png" alt="PrimeLab" class="brand-logo">
        <span class="brand-copy"><strong>PrimeLab</strong><small>Centros de salud públicos del Perú</small></span>
    </a>
    <div class="topbar-right"><div class="section-tag"><span>Sección actual</span><b>RESERVAR</b></div></div>
</header>

<div class="app-layout">
    <aside class="sidebar">
        <div class="user-chip">
            <div class="avatar"><?= e($inicial) ?></div>
            <div><strong><?= e($nombreCompleto) ?></strong><small>DNI <?= e($paciente['dni']) ?></small></div>
        </div>
        <nav>
            <p class="nav-label">Paciente</p>
            <a href="panel.php" class="nav-btn"><span class="ico">🏠</span><span>Inicio</span></a>
            <a href="reservar.php" class="nav-btn active"><span class="ico">📅</span><span>Reservar cita</span></a>
            <a href="mis_citas.php" class="nav-btn"><span class="ico">🗂</span><span>Mis citas</span></a>
            <a href="consultas.php" class="nav-btn"><span class="ico">🩺</span><span>Consultas</span></a>
            <a href="perfil.php" class="nav-btn"><span class="ico">👤</span><span>Mi perfil</span></a>
        </nav>
        <div class="account-actions">
            <a href="logout.php" class="nav-btn nav-btn-danger"><span class="ico">⎋</span><span>Cerrar sesión</span></a>
        </div>
    </aside>

    <main class="content">
        <div id="alertBox" class="alert" aria-live="polite"></div>
        <?php mostrarFlash(); ?>

        <div class="page-heading">
            <p class="eyebrow">Reserva</p>
            <h1>Reservar una cita</h1>
            <p class="muted">Selecciona el centro, la especialidad, el médico y el horario.</p>
        </div>

        <div class="steps">
            <div class="<?= pasoClase(1,$paso) ?>"><span class="step-dot">1</span><span>Centro</span></div>
            <div class="<?= pasoClase(2,$paso) ?>"><span class="step-dot">2</span><span>Especialidad y horario</span></div>
            <div class="<?= pasoClase(3,$paso) ?>"><span class="step-dot">3</span><span>Confirmación</span></div>
        </div>

        <?php if ($paso === 1): ?>
            <form method="GET" class="filter-bar" action="reservar.php">
                <input type="hidden" name="paso" value="1">
                <label class="field">
                    <span>Nombre o distrito</span>
                    <input type="text" name="q" value="<?= e($q) ?>" placeholder="Ejemplo: San Martín o Cercado de Lima">
                </label>
                <label class="field">
                    <span>Distrito</span>
                    <select name="distrito">
                        <option value="">Todos los distritos</option>
                        <?php foreach ($distritos as $d): ?>
                            <option value="<?= e($d) ?>" <?= $fDistrito === $d ? 'selected' : '' ?>><?= e($d) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label class="field">
                    <span>Tipo de atención</span>
                    <select name="atencion">
                        <option value="">Todas las atenciones</option>
                        <?php foreach ($atenciones as $a): ?>
                            <option value="<?= e($a['codesp']) ?>" <?= $fAtencion === $a['codesp'] ? 'selected' : '' ?>><?= e($a['nomesp']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <button type="submit" class="button primary">Buscar</button>
            </form>

            <div class="center-grid">
                <?php if ($centros && $centros->num_rows > 0): ?>
                    <?php while ($c = $centros->fetch_assoc()): ?>
                        <article class="center-card">
                            <div class="center-icon">🏥</div>
                            <h3><?= e($c['nomce']) ?></h3>
                            <p class="muted"><?= e($c['dirc']) ?></p>
                            <p class="muted"><?= e($c['distrito']) ?>, <?= e($c['provincia']) ?> - <?= e($c['departamento']) ?></p>
                            <p class="muted"><strong>Tipo:</strong> <?= e($c['tipo']) ?> · <strong>Tel:</strong> <?= e($c['telefono']) ?></p>
                            <p class="muted"><strong>Horario:</strong> <?= e($c['horario_atencion']) ?></p>
                            <p><small><strong>Especialidades:</strong> <?= e($c['especialidades'] ?: 'Sin especialidades registradas') ?></small></p>
                            <a class="button secondary full" href="reservar.php?paso=2&centro=<?= e($c['codce']) ?>">Elegir este centro</a>
                        </article>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="muted">No se encontraron centros con los filtros seleccionados.</p>
                <?php endif; ?>
            </div>

        <?php elseif ($paso === 2 && $centroInfo): ?>
            <div class="selection-box">
                <strong>Centro seleccionado:</strong> <?= e($centroInfo['nomce']) ?> — <?= e($centroInfo['distrito']) ?> (<?= e($centroInfo['provincia']) ?>)<br>
                <span class="muted"><?= e($centroInfo['dirc']) ?> · <?= e($centroInfo['tipo']) ?> · Horario: <?= e($centroInfo['horario_atencion']) ?> · Tel: <?= e($centroInfo['telefono']) ?></span>
            </div>

            <div class="two-col">
                <section class="panel">
                    <h2 class="panel-title">Especialidad</h2>
                    <form method="GET" action="reservar.php">
                        <input type="hidden" name="paso" value="2">
                        <input type="hidden" name="centro" value="<?= e($centroSel) ?>">
                        <label class="field compact-field">
                            <span>Selecciona una especialidad</span>
                            <select name="esp" onchange="this.form.submit()">
                                <option value="">Selecciona una especialidad</option>
                                <?php if ($espsCentro): while ($x = $espsCentro->fetch_assoc()): ?>
                                    <option value="<?= e($x['codesp']) ?>" <?= $espSel === $x['codesp'] ? 'selected' : '' ?>>
                                        <?= e($x['nomesp']) ?> — <?= (int)$x['medicos'] > 0 ? 'Disponible' : 'No disponible' ?>
                                    </option>
                                <?php endwhile; endif; ?>
                            </select>
                        </label>
                    </form>

                    <?php if ($espSel !== '' && $medicos): ?>
                        <h3 class="list-h">Médicos disponibles</h3>
                        <form method="GET" action="reservar.php" class="form-stack">
                            <input type="hidden" name="paso" value="2">
                            <input type="hidden" name="centro" value="<?= e($centroSel) ?>">
                            <input type="hidden" name="esp" value="<?= e($espSel) ?>">
                            <div class="doctor-list">
                                <?php $hay = false; while ($m = $medicos->fetch_assoc()): $hay = true; ?>
                                    <label class="doctor-card <?= $medSel === $m['codm'] ? 'selected' : '' ?>">
                                        <input type="radio" name="med" value="<?= e($m['codm']) ?>" <?= $medSel === $m['codm'] ? 'checked' : '' ?> onchange="this.form.submit()">
                                        <?= fotoMedico($m['codm'], $m['nomm'], 56) ?>
                                        <div class="doctor-info">
                                            <strong><?= e($m['nomm']) ?></strong>
                                            <small>CMP <?= e($m['cmpm']) ?></small>
                                        </div>
                                    </label>
                                <?php endwhile; if (!$hay): ?>
                                    <p class="muted">No hay médicos registrados para esta especialidad.</p>
                                <?php endif; ?>
                            </div>
                            <label class="field">
                                <span>Fecha de la cita</span>
                                <input type="date" name="fecha" value="<?= e($fechaSel) ?>" min="<?= date('Y-m-d') ?>" onchange="this.form.submit()">
                            </label>
                        </form>
                    <?php else: ?>
                        <p class="muted" style="margin-top:12px;">Selecciona una especialidad para ver los médicos disponibles.</p>
                    <?php endif; ?>
                </section>

                <section class="panel">
                    <h2 class="panel-title">Horarios</h2>
                    <?php if ($medSel !== '' && $medInfo): ?>
                        <div class="medico-resumen">
                            <?= fotoMedico($medInfo['codm'], $medInfo['nomm'], 48) ?>
                            <p class="muted" style="margin:0;"><strong><?= e($medInfo['nomm']) ?></strong><br><?= e(fechaTexto($fechaSel)) ?></p>
                        </div>
                        <form method="GET" action="reservar.php" class="form-stack">
                            <input type="hidden" name="paso" value="3">
                            <input type="hidden" name="centro" value="<?= e($centroSel) ?>">
                            <input type="hidden" name="esp" value="<?= e($espSel) ?>">
                            <input type="hidden" name="med" value="<?= e($medSel) ?>">
                            <input type="hidden" name="fecha" value="<?= e($fechaSel) ?>">
                            <div class="slot-grid">
                                <?php foreach ($HORARIOS as $h): $libre = !in_array($h, $ocupados, true); ?>
                                    <label class="slot <?= $libre ? '' : 'disabled' ?> <?= ($horaSel === $h) ? 'selected' : '' ?>">
                                        <input type="radio" name="hora" value="<?= e($h) ?>" <?= $libre ? '' : 'disabled' ?> <?= ($horaSel === $h) ? 'checked' : '' ?> required>
                                        <span><?= e($h) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                            <div class="slot-legend">
                                <span><i class="dot dot-free"></i> Disponible</span>
                                <span><i class="dot dot-off"></i> Ocupado</span>
                            </div>
                            <button type="submit" class="button primary full">Continuar</button>
                        </form>
                    <?php else: ?>
                        <p class="muted">Selecciona especialidad, médico y fecha para ver los horarios.</p>
                    <?php endif; ?>
                </section>
            </div>

            <p style="margin-top:14px;"><a href="reservar.php?paso=1" class="link-muted">← Volver a elegir centro</a></p>

        <?php elseif ($paso === 3 && $centroInfo && $espInfo && $medInfo): ?>
            <section class="panel">
                <h2 class="panel-title">Confirma tu cita</h2>
                <p class="muted">Revisa los datos antes de registrar la reserva.</p>

                <h3 class="list-h">Datos del paciente</h3>
                <div class="data-grid">
                    <div><span>DNI</span><strong><?= e($paciente['dni']) ?></strong></div>
                    <div><span>Paciente</span><strong><?= e($nombreCompleto) ?></strong></div>
                    <div><span>Teléfono</span><strong><?= $paciente['telp'] ? e($paciente['telp']) : 'No registrado' ?></strong></div>
                    <div><span>Correo</span><strong><?= $paciente['correo'] ? e($paciente['correo']) : 'Sin correo registrado' ?></strong></div>
                    <div><span>Seguro</span><strong><?= e(textoSeguro($paciente['seguro'] ?? 'SIS')) ?></strong></div>
                    <div><span>Costo de la cita</span><strong><?= e(costoTexto($paciente['seguro'] ?? 'SIS')) ?></strong></div>
                </div>

                <h3 class="list-h">Detalle de la cita</h3>
                <div class="summary-list">
                    <div><span>Centro</span><strong><?= e($centroInfo['nomce']) ?> — <?= e($centroInfo['distrito']) ?></strong></div>
                    <div><span>Especialidad</span><strong><?= e($espInfo['nomesp']) ?></strong></div>
                    <div><span>Médico</span><strong class="medico-inline"><?= fotoMedico($medInfo['codm'], $medInfo['nomm'], 40) ?><span><?= e($medInfo['nomm']) ?> (CMP <?= e($medInfo['cmpm']) ?>)</span></strong></div>
                    <div><span>Fecha</span><strong><?= e(fechaTexto($fechaSel)) ?></strong></div>
                    <div><span>Hora</span><strong><?= e($horaSel) ?></strong></div>
                    <div><span>Seguro / Costo</span><strong><?= e(textoSeguro($paciente['seguro'] ?? 'SIS')) ?> — <?= e(costoTexto($paciente['seguro'] ?? 'SIS')) ?></strong></div>
                </div>

                <form method="POST" action="reservar.php" class="form-stack" style="margin-top:18px;">
                    <input type="hidden" name="accion" value="confirmar">
                    <input type="hidden" name="centro" value="<?= e($centroSel) ?>">
                    <input type="hidden" name="esp" value="<?= e($espSel) ?>">
                    <input type="hidden" name="med" value="<?= e($medSel) ?>">
                    <input type="hidden" name="fecha" value="<?= e($fechaSel) ?>">
                    <input type="hidden" name="hora" value="<?= e($horaSel) ?>">
                    <label class="field">
                        <span>Motivo de la consulta</span>
                        <textarea name="motivo" rows="3" maxlength="255" required placeholder="Describe brevemente el motivo de tu cita"></textarea>
                    </label>
                    <div class="ticket-actions">
                        <button type="submit" class="button primary">Confirmar reserva</button>
                        <a href="reservar.php?paso=2&centro=<?= e($centroSel) ?>&esp=<?= e($espSel) ?>&med=<?= e($medSel) ?>&fecha=<?= e($fechaSel) ?>" class="button secondary">Volver</a>
                    </div>
                </form>
            </section>
        <?php endif; ?>
    </main>
</div>

<footer class="site-footer"><p>PrimeLab — Sistema de reserva de citas en centros de salud públicos</p></footer>
<script src="js/main.js"></script>
</body>
</html>
````

## `ticket.php`

````php
<?php
// ticket.php — Comprobante de una cita del paciente.
require_once "file_conexion.php";
requerirPaciente();

$mysqli = Conectarse();
$paciente = pacienteActual($mysqli);
if (!$paciente) { $mysqli->close(); redirigir("index.html?msg=sesion"); }

$codigo = trim($_GET['codigo'] ?? '');
$stmt = $mysqli->prepare("
    SELECT c.codci, c.fechaci, c.horaci, c.estado, c.motivo, c.costo, c.fechareg, c.observaciones,
           c.diagnostico, c.indicaciones, c.reprogramada_de, c.motivo_reprogramacion,
           ce.nomce, ce.dirc, ce.distrito, ce.provincia, ce.departamento, ce.telefono AS telce, ce.horario_atencion, ce.tipo AS tipoce,
           e.nomesp, m.codm, m.nomm, m.cmpm,
           p.nomp, p.apep, p.dni, p.telp, p.correo, p.seguro
    FROM cita c
    JOIN centro ce ON ce.codce = c.codce
    JOIN medico m ON m.codm = c.codm
    JOIN especialidad e ON e.codesp = m.codesp
    JOIN paciente p ON p.codp = c.codp
    WHERE c.codci = ? AND c.codp = ?
    LIMIT 1
");
$stmt->bind_param("ss", $codigo, $paciente['codp']);
$stmt->execute();
$cita = $stmt->get_result()->fetch_assoc();
$stmt->close();
$mysqli->close();

if (!$cita) {
    flash('error', 'No se encontró la cita solicitada.');
    redirigir("mis_citas.php");
}

$nombreCompleto = $cita['nomp'] . ' ' . $cita['apep'];
$inicial = mb_strtoupper(mb_substr($cita['nomp'], 0, 1, 'UTF-8'), 'UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket <?= e($cita['codci']) ?> — PrimeLab</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body data-page="ticket">
<header class="topbar">
    <a href="panel.php" class="brand">
        <img src="img/PrimeLab%20Logo.png" alt="PrimeLab" class="brand-logo">
        <span class="brand-copy"><strong>PrimeLab</strong><small>Centros de salud públicos del Perú</small></span>
    </a>
    <div class="topbar-right"><div class="section-tag"><span>Sección actual</span><b>TICKET</b></div></div>
</header>

<div class="app-layout">
    <aside class="sidebar">
        <div class="user-chip">
            <div class="avatar"><?= e($inicial) ?></div>
            <div><strong><?= e($nombreCompleto) ?></strong><small>DNI <?= e($cita['dni']) ?></small></div>
        </div>
        <nav>
            <p class="nav-label">Paciente</p>
            <a href="panel.php" class="nav-btn"><span class="ico">🏠</span><span>Inicio</span></a>
            <a href="reservar.php" class="nav-btn"><span class="ico">📅</span><span>Reservar cita</span></a>
            <a href="mis_citas.php" class="nav-btn active"><span class="ico">🗂</span><span>Mis citas</span></a>
            <a href="consultas.php" class="nav-btn"><span class="ico">🩺</span><span>Consultas</span></a>
            <a href="perfil.php" class="nav-btn"><span class="ico">👤</span><span>Mi perfil</span></a>
        </nav>
        <div class="account-actions">
            <a href="logout.php" class="nav-btn nav-btn-danger"><span class="ico">⎋</span><span>Cerrar sesión</span></a>
        </div>
    </aside>

    <main class="content">
        <div id="alertBox" class="alert" aria-live="polite"></div>
        <?php mostrarFlash(); ?>

        <div class="page-heading">
            <p class="eyebrow">Comprobante</p>
            <h1>Ticket de cita</h1>
            <p class="muted">Presenta este comprobante el día de tu atención.</p>
        </div>

        <section class="ticket-card">
            <div class="ticket-code">
                <span>Código de cita</span>
                <strong><?= e($cita['codci']) ?></strong>
            </div>
            <hr class="rule">
            <div class="summary-list">
                <div><span>Paciente</span><strong><?= e($nombreCompleto) ?> (DNI <?= e($cita['dni']) ?>)</strong></div>
                <div><span>Seguro</span><strong><?= e(textoSeguro($cita['seguro'] ?? 'SIS')) ?></strong></div>
                <div><span>Costo de la cita</span><strong><?= e(costoTexto($cita['seguro'] ?? 'SIS', $cita['costo'])) ?></strong></div>
                <div><span>Especialidad</span><strong><?= e($cita['nomesp']) ?></strong></div>
                <div><span>Médico</span><strong class="medico-inline"><?= fotoMedico($cita['codm'], $cita['nomm'], 40) ?><span><?= e($cita['nomm']) ?> · CMP <?= e($cita['cmpm']) ?></span></strong></div>
                <div><span>Centro</span><strong><?= e($cita['nomce']) ?> (<?= e($cita['tipoce'] ?? 'Público') ?>)</strong></div>
                <div><span>Dirección</span><strong><?= e($cita['dirc']) ?>, <?= e($cita['distrito']) ?>, <?= e($cita['provincia'] ?? '') ?></strong></div>
                <div><span>Horario centro</span><strong><?= e($cita['horario_atencion'] ?? '-') ?></strong></div>
                <div><span>Teléfono centro</span><strong><?= e($cita['telce']) ?></strong></div>
                <div><span>Fecha</span><strong><?= e(fechaTexto($cita['fechaci'])) ?></strong></div>
                <div><span>Hora</span><strong><?= e(horaTexto($cita['horaci'])) ?></strong></div>
                <div><span>Estado</span><strong><?= e(ucfirst($cita['estado'])) ?></strong></div>
                <div><span>Motivo</span><strong><?= e($cita['motivo']) ?></strong></div>
                <?php if (!empty($cita['diagnostico'])): ?>
                <div><span>Diagnóstico / Lo observado</span><strong><?= nl2br(e($cita['diagnostico'])) ?></strong></div>
                <?php endif; ?>
                <?php if (!empty($cita['indicaciones'])): ?>
                <div><span>Indicaciones / Medicamentos</span><strong><?= nl2br(e($cita['indicaciones'])) ?></strong></div>
                <?php endif; ?>
                <?php if (!empty($cita['motivo_reprogramacion'])): ?>
                <div><span>Motivo de reprogramación</span><strong><?= e($cita['motivo_reprogramacion']) ?></strong></div>
                <?php endif; ?>
                <?php if (!empty($cita['reprogramada_de'])): ?>
                <div><span>Reprogramada desde cita</span><strong><?= e($cita['reprogramada_de']) ?></strong></div>
                <?php endif; ?>
                <?php if (!empty($cita['observaciones'])): ?>
                <div><span>Observaciones</span><strong><?= e($cita['observaciones']) ?></strong></div>
                <?php endif; ?>
                <div><span>Registrada</span><strong><?= e($cita['fechareg']) ?></strong></div>
            </div>
            <div class="ticket-actions">
                <button type="button" class="button primary" onclick="window.print()">Imprimir ticket</button>
                <a href="mis_citas.php" class="button secondary">Volver a mis citas</a>
            </div>
        </section>
    </main>
</div>

<footer class="site-footer"><p>PrimeLab — Sistema de reserva de citas en centros de salud públicos</p></footer>
<script src="js/main.js"></script>
</body>
</html>
````

## `mis_citas.php`

````php
<?php
// mis_citas.php — Listado de citas del paciente.
require_once "file_conexion.php";
requerirPaciente();

$mysqli = Conectarse();
$paciente = pacienteActual($mysqli);
if (!$paciente) { $mysqli->close(); redirigir("index.html?msg=sesion"); }

$codp = $paciente['codp'];
$stmt = $mysqli->prepare("
    SELECT c.codci, c.fechaci, c.horaci, c.estado, c.motivo, c.costo, c.fechareg, c.observaciones,
           c.diagnostico, c.indicaciones, c.reprogramada_de, c.motivo_reprogramacion,
           ce.nomce, ce.distrito,
           e.nomesp, m.codm, m.nomm
    FROM cita c
    JOIN centro ce ON ce.codce = c.codce
    JOIN medico m ON m.codm = c.codm
    JOIN especialidad e ON e.codesp = m.codesp
    WHERE c.codp = ?
    ORDER BY c.fechaci DESC, c.horaci DESC
");
$stmt->bind_param("s", $codp);
$stmt->execute();
$citas = $stmt->get_result();
$mysqli->close();

$nombreCompleto = $paciente['nomp'] . ' ' . $paciente['apep'];
$inicial = mb_strtoupper(mb_substr($paciente['nomp'], 0, 1, 'UTF-8'), 'UTF-8');

function badgeEstado($estado) {
    $e = strtolower($estado);
    $clase = 'status-pending';
    if ($e === 'atendida') $clase = 'status-done';
    if ($e === 'cancelada' || $e === 'reprogramada') $clase = 'status-cancel';
    if ($e === 'extendida') $clase = 'status-pending';
    return '<span class="status-badge ' . $clase . '">' . e(ucfirst($e)) . '</span>';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis citas — PrimeLab</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="topbar">
    <a href="panel.php" class="brand">
        <img src="img/PrimeLab%20Logo.png" alt="PrimeLab" class="brand-logo">
        <span class="brand-copy"><strong>PrimeLab</strong><small>Centros de salud públicos del Perú</small></span>
    </a>
    <div class="topbar-right"><div class="section-tag"><span>Sección actual</span><b>MIS CITAS</b></div></div>
</header>

<div class="app-layout">
    <aside class="sidebar">
        <div class="user-chip">
            <div class="avatar"><?= e($inicial) ?></div>
            <div><strong><?= e($nombreCompleto) ?></strong><small>DNI <?= e($paciente['dni']) ?></small></div>
        </div>
        <nav>
            <p class="nav-label">Paciente</p>
            <a href="panel.php" class="nav-btn"><span class="ico">🏠</span><span>Inicio</span></a>
            <a href="reservar.php" class="nav-btn"><span class="ico">📅</span><span>Reservar cita</span></a>
            <a href="mis_citas.php" class="nav-btn active"><span class="ico">🗂</span><span>Mis citas</span></a>
            <a href="consultas.php" class="nav-btn"><span class="ico">🩺</span><span>Consultas</span></a>
            <a href="perfil.php" class="nav-btn"><span class="ico">👤</span><span>Mi perfil</span></a>
        </nav>
        <div class="account-actions">
            <a href="logout.php" class="nav-btn nav-btn-danger"><span class="ico">⎋</span><span>Cerrar sesión</span></a>
        </div>
    </aside>

    <main class="content">
        <?php mostrarFlash(); ?>

        <div class="page-heading">
            <p class="eyebrow">Historial</p>
            <h1>Mis citas</h1>
            <p class="muted">Consulta el estado y detalle de todas tus reservas.</p>
        </div>

        <section class="panel">
            <div class="panel-head">
                <h2 class="panel-title">Listado de citas</h2>
                <a href="reservar.php" class="button secondary">Nueva cita</a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Fecha y hora</th>
                            <th>Centro</th>
                            <th>Especialidad</th>
                            <th>Médico</th>
                            <th>Costo</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($citas->num_rows === 0): ?>
                            <tr><td colspan="8" class="table-empty">Aún no tienes citas registradas.</td></tr>
                        <?php else: ?>
                            <?php while ($c = $citas->fetch_assoc()): ?>
                            <tr>
                                <td><span class="code"><?= e($c['codci']) ?></span></td>
                                <td><?= e(fechaTexto($c['fechaci'])) ?><br><small class="muted"><?= e(horaTexto($c['horaci'])) ?></small></td>
                                <td><?= e($c['nomce']) ?><br><small class="muted"><?= e($c['distrito']) ?></small></td>
                                <td><?= e($c['nomesp']) ?></td>
                                <td><span class="medico-inline"><?= fotoMedico($c['codm'], $c['nomm'], 36) ?><span><?= e($c['nomm']) ?></span></span></td>
                                <td>S/ <?= number_format((float)$c['costo'], 2) ?></td>
                                <td><?= badgeEstado($c['estado']) ?></td>
                                <td class="actions-cell">
                                    <a href="ticket.php?codigo=<?= e($c['codci']) ?>" class="button secondary small">Ver ticket</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>

<footer class="site-footer"><p>PrimeLab — Sistema de reserva de citas en centros de salud públicos</p></footer>
<script src="js/main.js"></script>
</body>
</html>
````

## `perfil.php`

````php
<?php
// perfil.php — Perfil del paciente: actualizar datos o eliminar cuenta.
require_once "file_conexion.php";
requerirPaciente();

$mysqli = Conectarse();
$paciente = pacienteActual($mysqli);
if (!$paciente) { $mysqli->close(); redirigir("index.html?msg=sesion"); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'actualizar') {
        $telp   = trim($_POST['telp'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $seguro = trim($_POST['seguro'] ?? ($paciente['seguro'] ?? 'SIS'));
        $segurosValidos = ['SIS', 'EPS', 'Privado'];

        if ($telp === '' || $seguro === '') {
            $mysqli->close();
            redirigir("perfil.php?error=campos");
        }
        if (!preg_match('/^\d{9}$/', $telp)) {
            $mysqli->close();
            redirigir("perfil.php?error=tel&telp=" . urlencode($telp) . "&correo=" . urlencode($correo));
        }
        if ($correo !== '' && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $mysqli->close();
            redirigir("perfil.php?error=correo&telp=" . urlencode($telp) . "&correo=" . urlencode($correo));
        }
        if (!in_array($seguro, $segurosValidos, true)) {
            $seguro = 'SIS';
        }

        $correoDb = ($correo === '') ? null : $correo;
        $stmt = $mysqli->prepare("UPDATE paciente SET telp = ?, correo = ?, seguro = ? WHERE codp = ?");
        $stmt->bind_param("ssss", $telp, $correoDb, $seguro, $paciente['codp']);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
        redirigir("perfil.php?ok=actualiza");
    }

    if ($accion === 'eliminar') {
        $dniConf = trim($_POST['dni_confirma'] ?? '');
        if ($dniConf === '') {
            $mysqli->close();
            redirigir("perfil.php?error=confirma");
        }
        if ($dniConf !== $paciente['dni']) {
            $mysqli->close();
            redirigir("perfil.php?error=confirma_dni");
        }

        // Eliminar citas del paciente (historial se elimina por FK cascade desde cita)
        $stmt = $mysqli->prepare("DELETE FROM cita WHERE codp = ?");
        $stmt->bind_param("s", $paciente['codp']);
        $stmt->execute();
        $stmt->close();

        $stmt = $mysqli->prepare("DELETE FROM paciente WHERE codp = ?");
        $stmt->bind_param("s", $paciente['codp']);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();

        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
        redirigir("index.html?msg=eliminado");
    }
}

$mysqli->close();
$nombreCompleto = $paciente['nomp'] . ' ' . $paciente['apep'];
$inicial = mb_strtoupper(mb_substr($paciente['nomp'], 0, 1, 'UTF-8'), 'UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi perfil — PrimeLab</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body data-page="perfil">
<header class="topbar">
    <a href="panel.php" class="brand">
        <img src="img/PrimeLab%20Logo.png" alt="PrimeLab" class="brand-logo">
        <span class="brand-copy"><strong>PrimeLab</strong><small>Centros de salud públicos del Perú</small></span>
    </a>
    <div class="topbar-right"><div class="section-tag"><span>Sección actual</span><b>MI PERFIL</b></div></div>
</header>

<div class="app-layout">
    <aside class="sidebar">
        <div class="user-chip">
            <div class="avatar"><?= e($inicial) ?></div>
            <div><strong><?= e($nombreCompleto) ?></strong><small>DNI <?= e($paciente['dni']) ?></small></div>
        </div>
        <nav>
            <p class="nav-label">Paciente</p>
            <a href="panel.php" class="nav-btn"><span class="ico">🏠</span><span>Inicio</span></a>
            <a href="reservar.php" class="nav-btn"><span class="ico">📅</span><span>Reservar cita</span></a>
            <a href="mis_citas.php" class="nav-btn"><span class="ico">🗂</span><span>Mis citas</span></a>
            <a href="consultas.php" class="nav-btn"><span class="ico">🩺</span><span>Consultas</span></a>
            <a href="perfil.php" class="nav-btn active"><span class="ico">👤</span><span>Mi perfil</span></a>
        </nav>
        <div class="account-actions">
            <a href="logout.php" class="nav-btn nav-btn-danger"><span class="ico">⎋</span><span>Cerrar sesión</span></a>
        </div>
    </aside>

    <main class="content">
        <div id="alertBox" class="alert" aria-live="polite"></div>

        <div class="page-heading">
            <p class="eyebrow">Cuenta</p>
            <h1>Mi perfil</h1>
            <p class="muted">Consulta tu información. Para actualizar teléfono, correo o seguro usa “Editar mis datos”. El DNI no se puede modificar.</p>
        </div>

        <section class="panel">
            <h2 class="panel-title">Datos personales</h2>
            <div class="data-grid" style="margin-bottom:18px;">
                <div><span>DNI</span><strong><?= e($paciente['dni']) ?></strong></div>
                <div><span>Nombres</span><strong><?= e($paciente['nomp']) ?></strong></div>
                <div><span>Apellidos</span><strong><?= e($paciente['apep']) ?></strong></div>
                <div><span>Fecha de nacimiento</span><strong><?= !empty($paciente['fechanac']) ? e(fechaTexto($paciente['fechanac'])) : 'No registrada' ?></strong></div>
                <div><span>Dirección</span><strong><?= !empty($paciente['direccion']) ? e($paciente['direccion']) : 'No registrada' ?></strong></div>
                <div><span>Distrito</span><strong><?= !empty($paciente['distrito']) ? e($paciente['distrito']) : 'No registrado' ?></strong></div>
                <div><span>Registrado</span><strong><?= e(fechaTexto($paciente['fechareg'])) ?></strong></div>
            </div>

            <h3 class="list-h" style="margin-top:6px;">Datos de contacto y cobertura</h3>
            <div id="perfilVista">
                <div class="data-grid" style="margin-bottom:14px;">
                    <div><span>Teléfono</span><strong id="vistaTelp"><?= $paciente['telp'] ? e($paciente['telp']) : 'No registrado' ?></strong></div>
                    <div><span>Correo electrónico</span><strong id="vistaCorreo"><?= $paciente['correo'] ? e($paciente['correo']) : 'Sin correo registrado' ?></strong></div>
                    <div><span>Tipo de seguro</span><strong id="vistaSeguro"><?= e(textoSeguro($paciente['seguro'] ?? 'SIS')) ?></strong></div>
                    <div><span>Costo de consulta</span><strong><?= e(costoTexto($paciente['seguro'] ?? 'SIS')) ?></strong></div>
                    <div><span>Contraseña de acceso</span><strong>Tu DNI</strong></div>
                </div>
                <button type="button" class="button secondary" id="btnEditarPerfil">Editar mis datos</button>
            </div>

            <form method="POST" action="perfil.php" class="form-stack" id="formEditarPerfil" style="display:none; margin-top:8px;">
                <input type="hidden" name="accion" value="actualizar">
                <p class="muted" style="margin:0;">Modifica tu teléfono, correo o tipo de seguro y luego guarda los cambios.</p>
                <div class="form-grid-2">
                    <label class="field">
                        <span>Teléfono</span>
                        <input type="text" name="telp" maxlength="9" inputmode="numeric"
                               data-numerico="true" data-max="9" data-exacto="9" data-label="Teléfono"
                               value="<?= e($paciente['telp']) ?>" required>
                    </label>
                    <label class="field">
                        <span>Correo electrónico <span class="hint">(opcional)</span></span>
                        <input type="email" name="correo" maxlength="60"
                               value="<?= e($paciente['correo'] ?? '') ?>" placeholder="correo@ejemplo.com">
                    </label>
                </div>
                <label class="field">
                    <span>Tipo de seguro</span>
                    <select name="seguro" required>
                        <option value="SIS" <?= ($paciente['seguro'] ?? 'SIS') === 'SIS' ? 'selected' : '' ?>>SIS (Seguro Integral de Salud)</option>
                        <option value="EPS" <?= ($paciente['seguro'] ?? '') === 'EPS' ? 'selected' : '' ?>>EPS</option>
                        <option value="Privado" <?= ($paciente['seguro'] ?? '') === 'Privado' ? 'selected' : '' ?>>Seguro privado</option>
                    </select>
                </label>
                <div class="ticket-actions">
                    <button type="submit" class="button primary">Guardar cambios</button>
                    <button type="button" class="button secondary" id="btnCancelarPerfil">Cancelar</button>
                </div>
            </form>
        </section>
        <script>
        (function(){
            var btn = document.getElementById('btnEditarPerfil');
            var cancel = document.getElementById('btnCancelarPerfil');
            var vista = document.getElementById('perfilVista');
            var form = document.getElementById('formEditarPerfil');
            if(!btn || !form || !vista) return;
            btn.addEventListener('click', function(){
                vista.style.display = 'none';
                form.style.display = 'grid';
                var tel = form.querySelector('[name="telp"]');
                if(tel) tel.focus();
            });
            if(cancel){
                cancel.addEventListener('click', function(){
                    form.reset();
                    form.style.display = 'none';
                    vista.style.display = 'block';
                });
            }
            // Si hay error de validación en URL, abrir edición automáticamente
            var p = new URLSearchParams(location.search);
            if(p.get('error')){
                vista.style.display = 'none';
                form.style.display = 'grid';
            }
        })();
        </script>

        <section class="panel danger-zone" style="margin-top:18px;">
            <h2 class="panel-title">Eliminar cuenta</h2>
            <p class="muted">Esta acción eliminará tu cuenta y todas tus citas asociadas. Para confirmar, ingresa tu DNI.</p>
            <form method="POST" action="perfil.php" class="form-stack" style="max-width:420px;">
                <input type="hidden" name="accion" value="eliminar">
                <label class="field">
                    <span>Confirma tu DNI</span>
                    <input type="text" name="dni_confirma" maxlength="8" inputmode="numeric"
                           data-numerico="true" data-max="8" data-exacto="8" data-label="DNI"
                           placeholder="8 dígitos" required>
                </label>
                <button type="submit" class="button danger">Eliminar mi cuenta</button>
            </form>
        </section>
    </main>
</div>

<footer class="site-footer"><p>PrimeLab — Sistema de reserva de citas en centros de salud públicos</p></footer>
<script src="js/main.js"></script>
</body>
</html>
````

## `admin_citas.php`

````php
<?php
// admin_citas.php — Administración de citas.
require_once "file_conexion.php";
requerirAdmin();
$mysqli = Conectarse();

// Acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $codci  = trim($_POST['codci'] ?? '');
    if ($accion === 'cancelar' && $codci !== '') {
        $motivo = trim($_POST['motivo'] ?? 'Cancelada por administración.');
        $stmt = $mysqli->prepare("UPDATE cita SET estado = 'cancelada' WHERE codci = ?");
        $stmt->bind_param("s", $codci);
        $stmt->execute();
        $stmt->close();
        $stmt = $mysqli->prepare("INSERT INTO cita_historial (codci, accion, motivo, fecha) VALUES (?, 'cancelacion', ?, NOW())");
        $stmt->bind_param("ss", $codci, $motivo);
        $stmt->execute();
        $stmt->close();
        flash('exito', 'La cita ' . $codci . ' fue cancelada.');
        redirigir("admin_citas.php");
    }
    if ($accion === 'atender' && $codci !== '') {
        $stmt = $mysqli->prepare("UPDATE cita SET estado = 'atendida' WHERE codci = ?");
        $stmt->bind_param("s", $codci);
        $stmt->execute();
        $stmt->close();
        flash('exito', 'La cita ' . $codci . ' fue marcada como atendida.');
        redirigir("admin_citas.php");
    }
}

$fEstado = trim($_GET['estado'] ?? '');
$fCentro = trim($_GET['centro'] ?? '');

$centros = [];
$res = $mysqli->query("SELECT codce, nomce FROM centro ORDER BY nomce");
while ($r = $res->fetch_assoc()) $centros[] = $r;

$sql = "SELECT c.codci, c.fechaci, c.horaci, c.estado, c.motivo, c.costo,
               p.nomp, p.apep, p.dni, p.seguro,
               ce.nomce, e.nomesp, m.codm, m.nomm
        FROM cita c
        JOIN paciente p ON p.codp = c.codp
        JOIN centro ce ON ce.codce = c.codce
        JOIN medico m ON m.codm = c.codm
        JOIN especialidad e ON e.codesp = m.codesp
        WHERE 1=1";
$params = []; $types = '';
if ($fEstado !== '') { $sql .= " AND c.estado = ?"; $params[] = $fEstado; $types .= 's'; }
if ($fCentro !== '') { $sql .= " AND c.codce = ?"; $params[] = $fCentro; $types .= 's'; }
$sql .= " ORDER BY c.fechaci DESC, c.horaci DESC";
$stmt = $mysqli->prepare($sql);
if ($types !== '') $stmt->bind_param($types, ...$params);
$stmt->execute();
$citas = $stmt->get_result();
$stmt->close();

$statTotal = (int)$mysqli->query("SELECT COUNT(*) n FROM cita")->fetch_assoc()['n'];
$statPend  = (int)$mysqli->query("SELECT COUNT(*) n FROM cita WHERE estado='pendiente'")->fetch_assoc()['n'];
$statAtend = (int)$mysqli->query("SELECT COUNT(*) n FROM cita WHERE estado='atendida'")->fetch_assoc()['n'];
$statExt   = (int)$mysqli->query("SELECT COUNT(*) n FROM cita WHERE estado='extendida'")->fetch_assoc()['n'];
$statRepr  = (int)$mysqli->query("SELECT COUNT(*) n FROM cita WHERE estado='reprogramada'")->fetch_assoc()['n'];
$statCanc  = (int)$mysqli->query("SELECT COUNT(*) n FROM cita WHERE estado='cancelada'")->fetch_assoc()['n'];

$mysqli->close();

function badgeEstado($estado) {
    $e = strtolower($estado);
    $clase = 'status-pending';
    if ($e === 'atendida') $clase = 'status-done';
    if ($e === 'cancelada' || $e === 'reprogramada') $clase = 'status-cancel';
    if ($e === 'extendida') $clase = 'status-pending';
    return '<span class="status-badge ' . $clase . '">' . e(ucfirst($e)) . '</span>';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de citas — PrimeLab Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="topbar">
    <a href="admin_citas.php" class="brand">
        <img src="img/PrimeLab%20Logo.png" alt="PrimeLab" class="brand-logo">
        <span class="brand-copy"><strong>PrimeLab</strong><small>Administración</small></span>
    </a>
    <div class="topbar-right"><div class="section-tag"><span>Sección actual</span><b>ADMIN · CITAS</b></div></div>
</header>

<div class="app-layout">
    <aside class="sidebar">
        <div class="user-chip">
            <div class="avatar">A</div>
            <div><strong>Administrador</strong><small>Panel de control</small></div>
        </div>
        <nav>
            <p class="nav-label">Administración</p>
            <a href="admin_citas.php" class="nav-btn active"><span>Citas</span></a>
            <a href="admin_consultas.php" class="nav-btn"><span>Consultas</span></a>
            <a href="admin_medicos.php" class="nav-btn"><span>Médicos</span></a>
            <a href="admin_centros.php" class="nav-btn"><span>Centros y especialidades</span></a>
        </nav>
        <div class="account-actions">
            <a href="logout.php" class="nav-btn nav-btn-danger"><span>Cerrar sesión</span></a>
        </div>
    </aside>

    <main class="content">
        <?php mostrarFlash(); ?>

        <div class="page-heading">
            <p class="eyebrow">Panel administrativo</p>
            <h1>Administración de citas</h1>
            <p class="muted">Supervisa, filtra y gestiona las reservas registradas en bd_primelab.</p>
        </div>

        <section class="dashboard-overview">
            <article class="summary-card"><span class="summary-label">Citas totales</span><strong class="summary-count"><?= $statTotal ?></strong><span class="summary-details">registradas</span></article>
            <article class="summary-card"><span class="summary-label">Pendientes</span><strong class="summary-count"><?= $statPend ?></strong><span class="summary-details">por atender</span></article>
            <article class="summary-card"><span class="summary-label">Atendidas</span><strong class="summary-count"><?= $statAtend ?></strong><span class="summary-details">completadas</span></article>
            <article class="summary-card"><span class="summary-label">Extendidas</span><strong class="summary-count"><?= $statExt ?></strong><span class="summary-details">en seguimiento</span></article>
            <article class="summary-card"><span class="summary-label">Reprogramadas</span><strong class="summary-count"><?= $statRepr ?></strong><span class="summary-details">reagendadas</span></article>
            <article class="summary-card"><span class="summary-label">Canceladas</span><strong class="summary-count"><?= $statCanc ?></strong><span class="summary-details">anuladas</span></article>
        </section>

        <form method="GET" class="filter-bar compact" action="admin_citas.php">
            <label class="field">
                <span>Estado</span>
                <select name="estado">
                    <option value="">Todos</option>
                    <option value="pendiente" <?= $fEstado === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                    <option value="atendida" <?= $fEstado === 'atendida' ? 'selected' : '' ?>>Atendida</option>
                    <option value="extendida" <?= $fEstado === 'extendida' ? 'selected' : '' ?>>Extendida</option>
                    <option value="reprogramada" <?= $fEstado === 'reprogramada' ? 'selected' : '' ?>>Reprogramada</option>
                    <option value="cancelada" <?= $fEstado === 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                </select>
            </label>
            <label class="field">
                <span>Centro</span>
                <select name="centro">
                    <option value="">Todos</option>
                    <?php foreach ($centros as $c): ?>
                        <option value="<?= e($c['codce']) ?>" <?= $fCentro === $c['codce'] ? 'selected' : '' ?>><?= e($c['nomce']) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <div class="field"><span>&nbsp;</span><button type="submit" class="button primary">Filtrar</button></div>
        </form>

        <section class="panel">
            <div class="panel-head">
                <h2 class="panel-title">Historial de transacciones</h2>
                <input type="text" id="adminSearchInput" class="table-search" placeholder="Filtrar en la tabla..." onkeyup="filtrarTablaAdmin()">
            </div>
            <div class="table-responsive">
                <table class="table" id="tablaAdmin">
                    <thead>
                        <tr>
                            <th>Código</th><th>Fecha / Hora</th><th>Paciente</th><th>DNI</th><th>Seguro</th>
                            <th>Centro</th><th>Especialidad</th><th>Médico</th><th>Costo</th><th>Estado</th><th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($citas->num_rows === 0): ?>
                            <tr><td colspan="11" class="table-empty">No hay citas con los filtros seleccionados.</td></tr>
                        <?php else: while ($c = $citas->fetch_assoc()): ?>
                            <tr>
                                <td><span class="code"><?= e($c['codci']) ?></span></td>
                                <td><?= e(fechaTexto($c['fechaci'])) ?><br><small class="muted"><?= e(horaTexto($c['horaci'])) ?></small></td>
                                <td><?= e($c['nomp'] . ' ' . $c['apep']) ?></td>
                                <td><?= e($c['dni']) ?></td>
                                <td><?= e(textoSeguro($c['seguro'] ?? 'SIS')) ?></td>
                                <td><?= e($c['nomce']) ?></td>
                                <td><?= e($c['nomesp']) ?></td>
                                <td><span class="medico-inline"><?= fotoMedico($c['codm'], $c['nomm'], 34) ?><span><?= e($c['nomm']) ?></span></span></td>
                                <td>S/ <?= number_format((float)$c['costo'], 2) ?></td>
                                <td><?= badgeEstado($c['estado']) ?></td>
                                <td class="actions-cell">
                                    <?php if ($c['estado'] === 'pendiente'): ?>
                                        <form method="POST" action="admin_citas.php" style="display:inline;">
                                            <input type="hidden" name="accion" value="atender">
                                            <input type="hidden" name="codci" value="<?= e($c['codci']) ?>">
                                            <button type="submit" class="button secondary small">Atendida</button>
                                        </form>
                                        <form method="POST" action="admin_citas.php" style="display:inline;">
                                            <input type="hidden" name="accion" value="cancelar">
                                            <input type="hidden" name="codci" value="<?= e($c['codci']) ?>">
                                            <input type="hidden" name="motivo" value="Cancelada por administración.">
                                            <button type="submit" class="button danger small">Cancelar</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="muted">—</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>

<footer class="site-footer"><p>PrimeLab — Administración</p></footer>
<script src="js/main.js"></script>
</body>
</html>
````

## `admin_medicos.php`

````php
<?php
// admin_medicos.php — Gestión de médicos.
require_once "file_conexion.php";
requerirAdmin();
$mysqli = Conectarse();

$mensajeError = '';
$medicoEditar = null;

// Guardar (crear/editar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    if ($accion === 'guardar') {
        $codm   = trim($_POST['codm'] ?? '');
        $nomm   = trim($_POST['nomm'] ?? '');
        $cmpm   = trim($_POST['cmpm'] ?? '');
        $codesp = trim($_POST['codesp'] ?? '');
        $esEdicion = ($_POST['edicion'] ?? '') === '1';

        if ($codm === '' || $nomm === '' || $cmpm === '' || $codesp === '') {
            $mensajeError = 'Completa todos los campos.';
            $medicoEditar = ['codm'=>$codm,'nomm'=>$nomm,'cmpm'=>$cmpm,'codesp'=>$codesp];
        } elseif (!preg_match("/^[\p{L}\s'’.,\/()\-]+$/u", $nomm)) {
            $mensajeError = 'El nombre del médico solo debe contener letras (sin números).';
            $medicoEditar = ['codm'=>$codm,'nomm'=>$nomm,'cmpm'=>$cmpm,'codesp'=>$codesp];
        } elseif (!preg_match('/^\d{2,10}$/', $cmpm)) {
            $mensajeError = 'El CMP debe contener solo números (2 a 10 dígitos).';
            $medicoEditar = ['codm'=>$codm,'nomm'=>$nomm,'cmpm'=>$cmpm,'codesp'=>$codesp];
        } else {
            if ($esEdicion) {
                $stmt = $mysqli->prepare("UPDATE medico SET nomm=?, cmpm=?, codesp=? WHERE codm=?");
                $stmt->bind_param("ssss", $nomm, $cmpm, $codesp, $codm);
            } else {
                $stmt = $mysqli->prepare("INSERT INTO medico (codm, nomm, cmpm, codesp) VALUES (?,?,?,?)");
                $stmt->bind_param("ssss", $codm, $nomm, $cmpm, $codesp);
            }
            $ok = @$stmt->execute();
            $stmt->close();
            if ($ok) {
                flash('exito', $esEdicion ? 'Médico actualizado.' : 'Médico registrado.');
                redirigir("admin_medicos.php");
            } else {
                $mensajeError = 'No se pudo guardar. Verifica que el código no esté duplicado.';
                $medicoEditar = ['codm'=>$codm,'nomm'=>$nomm,'cmpm'=>$cmpm,'codesp'=>$codesp];
            }
        }
    }
    if ($accion === 'eliminar') {
        $codm = trim($_POST['codm'] ?? '');
        $stmt = $mysqli->prepare("DELETE FROM medico WHERE codm = ?");
        $stmt->bind_param("s", $codm);
        @$stmt->execute();
        $stmt->close();
        flash('exito', 'Médico eliminado (si no tenía citas asociadas).');
        redirigir("admin_medicos.php");
    }
}

if (isset($_GET['editar']) && !$medicoEditar) {
    $cod = trim($_GET['editar']);
    $stmt = $mysqli->prepare("SELECT codm, nomm, cmpm, codesp FROM medico WHERE codm = ? LIMIT 1");
    $stmt->bind_param("s", $cod);
    $stmt->execute();
    $medicoEditar = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

$especialidades = [];
$res = $mysqli->query("SELECT codesp, nomesp FROM especialidad ORDER BY nomesp");
while ($r = $res->fetch_assoc()) $especialidades[] = $r;

$medicos = $mysqli->query("
    SELECT m.codm, m.nomm, m.cmpm, m.codesp, e.nomesp
    FROM medico m JOIN especialidad e ON e.codesp = m.codesp
    ORDER BY m.codm
");

$mysqli->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Médicos — PrimeLab Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="topbar">
    <a href="admin_citas.php" class="brand">
        <img src="img/PrimeLab%20Logo.png" alt="PrimeLab" class="brand-logo">
        <span class="brand-copy"><strong>PrimeLab</strong><small>Administración</small></span>
    </a>
    <div class="topbar-right"><div class="section-tag"><span>Sección actual</span><b>ADMIN · MÉDICOS</b></div></div>
</header>

<div class="app-layout">
    <aside class="sidebar">
        <div class="user-chip"><div class="avatar">A</div><div><strong>Administrador</strong><small>Panel de control</small></div></div>
        <nav>
            <p class="nav-label">Administración</p>
            <a href="admin_citas.php" class="nav-btn"><span>Citas</span></a>
            <a href="admin_consultas.php" class="nav-btn"><span>Consultas</span></a>
            <a href="admin_medicos.php" class="nav-btn active"><span>Médicos</span></a>
            <a href="admin_centros.php" class="nav-btn"><span>Centros y especialidades</span></a>
        </nav>
        <div class="account-actions"><a href="logout.php" class="nav-btn nav-btn-danger"><span>Cerrar sesión</span></a></div>
    </aside>

    <main class="content">
        <?php mostrarFlash(); ?>
        <?php if ($mensajeError): ?><div class="alert show error"><?= e($mensajeError) ?></div><?php endif; ?>

        <div class="page-heading">
            <p class="eyebrow">Administración</p>
            <h1>Médicos</h1>
            <p class="muted">Registra y actualiza los médicos del sistema.</p>
        </div>

        <section class="panel">
            <h2 class="panel-title"><?= $medicoEditar ? 'Editar médico' : 'Registrar médico' ?></h2>
            <form method="POST" action="admin_medicos.php" class="form-stack">
                <input type="hidden" name="accion" value="guardar">
                <input type="hidden" name="edicion" value="<?= $medicoEditar ? '1' : '0' ?>">
                <div class="form-grid-2">
                    <label class="field">
                        <span>Código</span>
                        <input type="text" name="codm" maxlength="6" value="<?= e($medicoEditar['codm'] ?? '') ?>" <?= $medicoEditar ? 'readonly' : '' ?> required placeholder="Ej. MED008">
                    </label>
                    <label class="field">
                        <span>CMP</span>
                        <input type="text" name="cmpm" maxlength="10" inputmode="numeric"
                               data-numerico="true" data-max="10" data-label="CMP"
                               value="<?= e($medicoEditar['cmpm'] ?? '') ?>" required placeholder="N° CMP">
                    </label>
                </div>
                <label class="field">
                    <span>Nombre completo</span>
                    <input type="text" name="nomm" maxlength="60" data-texto="true" data-label="Nombre del médico" value="<?= e($medicoEditar['nomm'] ?? '') ?>" required placeholder="Ej. Dra. María López">
                </label>
                <label class="field">
                    <span>Especialidad</span>
                    <select name="codesp" required>
                        <option value="">Seleccione especialidad</option>
                        <?php foreach ($especialidades as $x): ?>
                            <option value="<?= e($x['codesp']) ?>" <?= ($medicoEditar['codesp'] ?? '') === $x['codesp'] ? 'selected' : '' ?>>
                                <?= e($x['nomesp']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <div class="ticket-actions">
                    <button type="submit" class="button primary"><?= $medicoEditar ? 'Actualizar' : 'Registrar' ?></button>
                    <?php if ($medicoEditar): ?><a href="admin_medicos.php" class="button secondary">Cancelar edición</a><?php endif; ?>
                </div>
            </form>
        </section>

        <section class="panel" style="margin-top:18px;">
            <h2 class="panel-title">Listado de médicos</h2>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Código</th><th>Nombre</th><th>CMP</th><th>Especialidad</th><th>Acciones</th></tr></thead>
                    <tbody>
                        <?php while ($m = $medicos->fetch_assoc()): ?>
                        <tr>
                            <td><span class="code"><?= e($m['codm']) ?></span></td>
                            <td><?= e($m['nomm']) ?></td>
                            <td><?= e($m['cmpm']) ?></td>
                            <td><?= e($m['nomesp']) ?></td>
                            <td class="actions-cell">
                                <a href="admin_medicos.php?editar=<?= e($m['codm']) ?>" class="button secondary small">Editar</a>
                                <form method="POST" action="admin_medicos.php" style="display:inline;">
                                    <input type="hidden" name="accion" value="eliminar">
                                    <input type="hidden" name="codm" value="<?= e($m['codm']) ?>">
                                    <button type="submit" class="button danger small">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>

<footer class="site-footer"><p>PrimeLab — Administración</p></footer>
<script src="js/main.js"></script>
</body>
</html>
````

## `admin_centros.php`

````php
<?php
// admin_centros.php — Gestión de centros y especialidades.
require_once "file_conexion.php";
requerirAdmin();
$mysqli = Conectarse();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'guardar_centro') {
        $codce = trim($_POST['codce'] ?? '');
        $nomce = trim($_POST['nomce'] ?? '');
        $dirc  = trim($_POST['dirc'] ?? '');
        $dist  = trim($_POST['distrito'] ?? '');
        $tel   = trim($_POST['telefono'] ?? '');
        $ed    = ($_POST['edicion'] ?? '') === '1';
        $patronNombre = "/^[\p{L}\s'’.,\/()\-]+$/u";
        if ($codce && $nomce && $dirc && $dist && $tel) {
            if (!preg_match($patronNombre, $nomce) || !preg_match('/^\d{6,15}$/', $tel)) {
                flash('error', 'El nombre del centro solo debe contener letras y el teléfono solo números.');
                redirigir("admin_centros.php");
            }
            if ($ed) {
                $stmt = $mysqli->prepare("UPDATE centro SET nomce=?, dirc=?, distrito=?, telefono=? WHERE codce=?");
                $stmt->bind_param("sssss", $nomce, $dirc, $dist, $tel, $codce);
            } else {
                $stmt = $mysqli->prepare("INSERT INTO centro (codce, nomce, dirc, distrito, telefono) VALUES (?,?,?,?,?)");
                $stmt->bind_param("sssss", $codce, $nomce, $dirc, $dist, $tel);
            }
            @$stmt->execute();
            $stmt->close();
            flash('exito', $ed ? 'Centro actualizado.' : 'Centro registrado.');
        }
        redirigir("admin_centros.php");
    }

    if ($accion === 'eliminar_centro') {
        $codce = trim($_POST['codce'] ?? '');
        $stmt = $mysqli->prepare("DELETE FROM centro WHERE codce = ?");
        $stmt->bind_param("s", $codce);
        @$stmt->execute();
        $stmt->close();
        flash('exito', 'Centro eliminado (si no tenía citas asociadas).');
        redirigir("admin_centros.php");
    }

    if ($accion === 'guardar_esp') {
        $codesp = trim($_POST['codesp'] ?? '');
        $nomesp = trim($_POST['nomesp'] ?? '');
        $ed = ($_POST['edicion_esp'] ?? '') === '1';
        if ($codesp && $nomesp) {
            if (!preg_match("/^[\p{L}\s'’.,\/()\-]+$/u", $nomesp)) {
                flash('error', 'El nombre de la especialidad solo debe contener letras.');
                redirigir("admin_centros.php");
            }
            if ($ed) {
                $stmt = $mysqli->prepare("UPDATE especialidad SET nomesp=? WHERE codesp=?");
                $stmt->bind_param("ss", $nomesp, $codesp);
            } else {
                $stmt = $mysqli->prepare("INSERT INTO especialidad (codesp, nomesp) VALUES (?,?)");
                $stmt->bind_param("ss", $codesp, $nomesp);
            }
            @$stmt->execute();
            $stmt->close();
            flash('exito', $ed ? 'Especialidad actualizada.' : 'Especialidad registrada.');
        }
        redirigir("admin_centros.php");
    }

    if ($accion === 'asignar_esp') {
        $codce  = trim($_POST['codce'] ?? '');
        $codesp = trim($_POST['codesp'] ?? '');
        if ($codce && $codesp) {
            $stmt = $mysqli->prepare("INSERT IGNORE INTO centro_especialidad (codce, codesp) VALUES (?,?)");
            $stmt->bind_param("ss", $codce, $codesp);
            @$stmt->execute();
            $stmt->close();
            flash('exito', 'Especialidad asignada al centro.');
        }
        redirigir("admin_centros.php");
    }

    if ($accion === 'quitar_esp') {
        $codce  = trim($_POST['codce'] ?? '');
        $codesp = trim($_POST['codesp'] ?? '');
        $stmt = $mysqli->prepare("DELETE FROM centro_especialidad WHERE codce=? AND codesp=?");
        $stmt->bind_param("ss", $codce, $codesp);
        @$stmt->execute();
        $stmt->close();
        flash('exito', 'Asignación eliminada.');
        redirigir("admin_centros.php");
    }
}

$centroEditar = null; $espEditar = null;
if (isset($_GET['editar_centro'])) {
    $stmt = $mysqli->prepare("SELECT * FROM centro WHERE codce=? LIMIT 1");
    $stmt->bind_param("s", $_GET['editar_centro']);
    $stmt->execute();
    $centroEditar = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
if (isset($_GET['editar_esp'])) {
    $stmt = $mysqli->prepare("SELECT * FROM especialidad WHERE codesp=? LIMIT 1");
    $stmt->bind_param("s", $_GET['editar_esp']);
    $stmt->execute();
    $espEditar = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

$centros = $mysqli->query("SELECT * FROM centro ORDER BY codce");
$especialidades = [];
$res = $mysqli->query("SELECT * FROM especialidad ORDER BY nomesp");
while ($r = $res->fetch_assoc()) $especialidades[] = $r;
$asignaciones = $mysqli->query("
    SELECT ce.codce, ce.codesp, c.nomce, e.nomesp
    FROM centro_especialidad ce
    JOIN centro c ON c.codce = ce.codce
    JOIN especialidad e ON e.codesp = ce.codesp
    ORDER BY c.nomce, e.nomesp
");
$listaCentros = $mysqli->query("SELECT codce, nomce FROM centro ORDER BY nomce");
$listaCentrosArr = [];
while ($r = $listaCentros->fetch_assoc()) $listaCentrosArr[] = $r;

$mysqli->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centros y especialidades — PrimeLab Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="topbar">
    <a href="admin_citas.php" class="brand">
        <img src="img/PrimeLab%20Logo.png" alt="PrimeLab" class="brand-logo">
        <span class="brand-copy"><strong>PrimeLab</strong><small>Administración</small></span>
    </a>
    <div class="topbar-right"><div class="section-tag"><span>Sección actual</span><b>ADMIN · CENTROS</b></div></div>
</header>

<div class="app-layout">
    <aside class="sidebar">
        <div class="user-chip"><div class="avatar">A</div><div><strong>Administrador</strong><small>Panel de control</small></div></div>
        <nav>
            <p class="nav-label">Administración</p>
            <a href="admin_citas.php" class="nav-btn"><span>Citas</span></a>
            <a href="admin_consultas.php" class="nav-btn"><span>Consultas</span></a>
            <a href="admin_medicos.php" class="nav-btn"><span>Médicos</span></a>
            <a href="admin_centros.php" class="nav-btn active"><span>Centros y especialidades</span></a>
        </nav>
        <div class="account-actions"><a href="logout.php" class="nav-btn nav-btn-danger"><span>Cerrar sesión</span></a></div>
    </aside>

    <main class="content">
        <?php mostrarFlash(); ?>

        <div class="page-heading">
            <p class="eyebrow">Administración</p>
            <h1>Centros y especialidades</h1>
            <p class="muted">Gestiona centros de salud, especialidades y sus asignaciones.</p>
        </div>

        <section class="panel">
            <h2 class="panel-title"><?= $centroEditar ? 'Editar centro' : 'Registrar centro' ?></h2>
            <form method="POST" action="admin_centros.php" class="form-stack">
                <input type="hidden" name="accion" value="guardar_centro">
                <input type="hidden" name="edicion" value="<?= $centroEditar ? '1' : '0' ?>">
                <div class="form-grid-2">
                    <label class="field"><span>Código</span><input type="text" name="codce" maxlength="6" value="<?= e($centroEditar['codce'] ?? '') ?>" <?= $centroEditar ? 'readonly' : '' ?> required></label>
                    <label class="field"><span>Teléfono</span><input type="text" name="telefono" maxlength="15" inputmode="numeric" data-numerico="true" data-max="15" data-label="Teléfono" value="<?= e($centroEditar['telefono'] ?? '') ?>" required></label>
                </div>
                <label class="field"><span>Nombre</span><input type="text" name="nomce" maxlength="60" data-texto="true" data-label="Nombre del centro" value="<?= e($centroEditar['nomce'] ?? '') ?>" required></label>
                <div class="form-grid-2">
                    <label class="field"><span>Dirección</span><input type="text" name="dirc" maxlength="80" value="<?= e($centroEditar['dirc'] ?? '') ?>" required></label>
                    <label class="field"><span>Distrito</span><input type="text" name="distrito" maxlength="40" value="<?= e($centroEditar['distrito'] ?? '') ?>" required></label>
                </div>
                <button type="submit" class="button primary"><?= $centroEditar ? 'Actualizar' : 'Registrar' ?></button>
            </form>
            <div class="table-responsive" style="margin-top:14px;">
                <table class="table">
                    <thead><tr><th>Código</th><th>Nombre</th><th>Distrito</th><th>Teléfono</th><th>Acciones</th></tr></thead>
                    <tbody>
                        <?php while ($c = $centros->fetch_assoc()): ?>
                        <tr>
                            <td><span class="code"><?= e($c['codce']) ?></span></td>
                            <td><?= e($c['nomce']) ?></td>
                            <td><?= e($c['distrito']) ?></td>
                            <td><?= e($c['telefono']) ?></td>
                            <td class="actions-cell">
                                <a href="admin_centros.php?editar_centro=<?= e($c['codce']) ?>" class="button secondary small">Editar</a>
                                <form method="POST" style="display:inline;"><input type="hidden" name="accion" value="eliminar_centro"><input type="hidden" name="codce" value="<?= e($c['codce']) ?>"><button class="button danger small">Eliminar</button></form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <div class="two-col" style="margin-top:18px;">
            <section class="panel">
                <h2 class="panel-title"><?= $espEditar ? 'Editar especialidad' : 'Registrar especialidad' ?></h2>
                <form method="POST" action="admin_centros.php" class="form-stack">
                    <input type="hidden" name="accion" value="guardar_esp">
                    <input type="hidden" name="edicion_esp" value="<?= $espEditar ? '1' : '0' ?>">
                    <label class="field"><span>Código</span><input type="text" name="codesp" maxlength="6" value="<?= e($espEditar['codesp'] ?? '') ?>" <?= $espEditar ? 'readonly' : '' ?> required></label>
                    <label class="field"><span>Nombre</span><input type="text" name="nomesp" maxlength="40" data-texto="true" data-label="Nombre de la especialidad" value="<?= e($espEditar['nomesp'] ?? '') ?>" required></label>
                    <button type="submit" class="button primary"><?= $espEditar ? 'Actualizar' : 'Registrar' ?></button>
                </form>
                <ul class="timeline" style="margin-top:12px;">
                    <?php foreach ($especialidades as $e): ?>
                        <li><strong><?= e($e['codesp']) ?></strong> — <?= e($e['nomesp']) ?> <a href="admin_centros.php?editar_esp=<?= e($e['codesp']) ?>" class="link-inline">Editar</a></li>
                    <?php endforeach; ?>
                </ul>
            </section>

            <section class="panel">
                <h2 class="panel-title">Asignar especialidad a centro</h2>
                <form method="POST" action="admin_centros.php" class="form-stack">
                    <input type="hidden" name="accion" value="asignar_esp">
                    <label class="field">
                        <span>Centro</span>
                        <select name="codce" required>
                            <option value="">Seleccione centro</option>
                            <?php foreach ($listaCentrosArr as $c): ?><option value="<?= e($c['codce']) ?>"><?= e($c['nomce']) ?></option><?php endforeach; ?>
                        </select>
                    </label>
                    <label class="field">
                        <span>Especialidad</span>
                        <select name="codesp" required>
                            <option value="">Seleccione especialidad</option>
                            <?php foreach ($especialidades as $e): ?><option value="<?= e($e['codesp']) ?>"><?= e($e['nomesp']) ?></option><?php endforeach; ?>
                        </select>
                    </label>
                    <button type="submit" class="button primary">Asignar</button>
                </form>
                <div class="table-responsive" style="margin-top:12px;">
                    <table class="table">
                        <thead><tr><th>Centro</th><th>Especialidad</th><th></th></tr></thead>
                        <tbody>
                            <?php while ($a = $asignaciones->fetch_assoc()): ?>
                            <tr>
                                <td><?= e($a['nomce']) ?></td>
                                <td><?= e($a['nomesp']) ?></td>
                                <td>
                                    <form method="POST" style="display:inline;"><input type="hidden" name="accion" value="quitar_esp"><input type="hidden" name="codce" value="<?= e($a['codce']) ?>"><input type="hidden" name="codesp" value="<?= e($a['codesp']) ?>"><button class="button danger small">Quitar</button></form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </main>
</div>

<footer class="site-footer"><p>PrimeLab — Administración</p></footer>
<script src="js/main.js"></script>
</body>
</html>
````

## `admin_consultas.php`

````php
<?php
// admin_consultas.php — Consultas médicas registradas en el sistema.
require_once "file_conexion.php";
requerirAdmin();
$mysqli = Conectarse();

$q       = trim($_GET['q'] ?? '');
$fEsp    = trim($_GET['esp'] ?? '');
$fEstado = trim($_GET['estado'] ?? '');

$especialidades = [];
$res = $mysqli->query("SELECT codesp, nomesp FROM especialidad ORDER BY nomesp");
while ($r = $res->fetch_assoc()) $especialidades[] = $r;

$sql = "
    SELECT c.codci, c.fechaci, c.horaci, c.estado, c.motivo, c.costo, c.fechareg,
           c.observaciones, c.diagnostico, c.indicaciones, c.motivo_reprogramacion, c.reprogramada_de,
           p.nomp, p.apep, p.dni, p.seguro,
           ce.nomce, ce.distrito,
           e.codesp, e.nomesp,
           m.codm, m.nomm, m.cmpm
    FROM cita c
    JOIN paciente p ON p.codp = c.codp
    JOIN centro ce ON ce.codce = c.codce
    JOIN medico m ON m.codm = c.codm
    JOIN especialidad e ON e.codesp = m.codesp
    WHERE 1=1
";
$params = []; $types = '';

if ($q !== '') {
    $like = '%' . $q . '%';
    $sql .= " AND (c.codci LIKE ? OR p.nomp LIKE ? OR p.apep LIKE ? OR p.dni LIKE ?
                   OR m.nomm LIKE ? OR e.nomesp LIKE ? OR ce.nomce LIKE ?
                   OR c.diagnostico LIKE ? OR c.indicaciones LIKE ?)";
    for ($i = 0; $i < 9; $i++) { $params[] = $like; $types .= 's'; }
}
if ($fEsp !== '') {
    $sql .= " AND e.codesp = ?";
    $params[] = $fEsp; $types .= 's';
}
if ($fEstado !== '') {
    $sql .= " AND c.estado = ?";
    $params[] = $fEstado; $types .= 's';
}
$sql .= " ORDER BY c.fechaci DESC, c.horaci DESC";

$stmt = $mysqli->prepare($sql);
if ($types !== '') $stmt->bind_param($types, ...$params);
$stmt->execute();
$resultado = $stmt->get_result();
$stmt->close();

$consultas = [];
while ($r = $resultado->fetch_assoc()) $consultas[] = $r;

$total = count($consultas);
$atendidas = 0;
$pendientes = 0;
$conDetalle = 0;
foreach ($consultas as $c) {
    if ($c['estado'] === 'atendida') $atendidas++;
    if ($c['estado'] === 'pendiente') $pendientes++;
    if (!empty($c['diagnostico']) || !empty($c['indicaciones'])) $conDetalle++;
}

$mysqli->close();

function badgeEstado($estado) {
    $e = strtolower($estado);
    $clase = 'status-pending';
    if ($e === 'atendida') $clase = 'status-done';
    if ($e === 'cancelada' || $e === 'reprogramada') $clase = 'status-cancel';
    if ($e === 'extendida') $clase = 'status-pending';
    return '<span class="status-badge ' . $clase . '">' . e(ucfirst($e)) . '</span>';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultas — PrimeLab Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="topbar">
    <a href="admin_citas.php" class="brand">
        <img src="img/PrimeLab%20Logo.png" alt="PrimeLab" class="brand-logo">
        <span class="brand-copy"><strong>PrimeLab</strong><small>Administración</small></span>
    </a>
    <div class="topbar-right"><div class="section-tag"><span>Sección actual</span><b>ADMIN · CONSULTAS</b></div></div>
</header>

<div class="app-layout">
    <aside class="sidebar">
        <div class="user-chip"><div class="avatar">A</div><div><strong>Administrador</strong><small>Panel de control</small></div></div>
        <nav>
            <p class="nav-label">Administración</p>
            <a href="admin_citas.php" class="nav-btn"><span>Citas</span></a>
            <a href="admin_consultas.php" class="nav-btn active"><span>Consultas</span></a>
            <a href="admin_medicos.php" class="nav-btn"><span>Médicos</span></a>
            <a href="admin_centros.php" class="nav-btn"><span>Centros y especialidades</span></a>
        </nav>
        <div class="account-actions"><a href="logout.php" class="nav-btn nav-btn-danger"><span>Cerrar sesión</span></a></div>
    </aside>

    <main class="content">
        <?php mostrarFlash(); ?>

        <div class="page-heading">
            <p class="eyebrow">Panel administrativo</p>
            <h1>Consultas médicas</h1>
            <p class="muted">Revisa las atenciones, diagnósticos e indicaciones registradas por los médicos.</p>
        </div>

        <section class="dashboard-overview">
            <article class="summary-card"><span class="summary-label">Consultas</span><strong class="summary-count"><?= $total ?></strong><span class="summary-details">con los filtros actuales</span></article>
            <article class="summary-card"><span class="summary-label">Atendidas</span><strong class="summary-count"><?= $atendidas ?></strong><span class="summary-details">completadas</span></article>
            <article class="summary-card"><span class="summary-label">Pendientes</span><strong class="summary-count"><?= $pendientes ?></strong><span class="summary-details">por atender</span></article>
            <article class="summary-card"><span class="summary-label">Con detalle clínico</span><strong class="summary-count"><?= $conDetalle ?></strong><span class="summary-details">diagnóstico o indicaciones</span></article>
        </section>

        <form method="GET" action="admin_consultas.php" class="filter-bar">
            <label class="field">
                <span>Buscar</span>
                <input type="text" name="q" value="<?= e($q) ?>" placeholder="Paciente, DNI, médico, código o diagnóstico">
            </label>
            <label class="field">
                <span>Especialidad</span>
                <select name="esp">
                    <option value="">Todas</option>
                    <?php foreach ($especialidades as $esp): ?>
                        <option value="<?= e($esp['codesp']) ?>" <?= $fEsp === $esp['codesp'] ? 'selected' : '' ?>><?= e($esp['nomesp']) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label class="field">
                <span>Estado</span>
                <select name="estado">
                    <option value="">Todos</option>
                    <?php foreach (['pendiente','atendida','extendida','reprogramada','cancelada'] as $est): ?>
                        <option value="<?= e($est) ?>" <?= $fEstado === $est ? 'selected' : '' ?>><?= e(ucfirst($est)) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <button type="submit" class="button primary">Buscar</button>
        </form>

        <section class="panel">
            <div class="panel-head">
                <h2 class="panel-title">Listado de consultas</h2>
                <a href="admin_citas.php" class="button secondary">Ir a citas</a>
            </div>

            <?php if (!$consultas): ?>
                <p class="table-empty">No se encontraron consultas con los filtros seleccionados.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Fecha y hora</th>
                                <th>Paciente</th>
                                <th>Especialidad / Médico</th>
                                <th>Centro</th>
                                <th>Estado</th>
                                <th>Detalle clínico</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($consultas as $c): ?>
                            <tr>
                                <td><span class="code"><?= e($c['codci']) ?></span></td>
                                <td><?= e(fechaTexto($c['fechaci'])) ?><br><small class="muted"><?= e(horaTexto($c['horaci'])) ?></small></td>
                                <td><strong><?= e($c['nomp'] . ' ' . $c['apep']) ?></strong><br><small class="muted">DNI <?= e($c['dni']) ?> · <?= e(textoSeguro($c['seguro'])) ?></small></td>
                                <td><?= e($c['nomesp']) ?><br><small class="muted"><?= e($c['nomm']) ?> · CMP <?= e($c['cmpm']) ?></small></td>
                                <td><?= e($c['nomce']) ?><br><small class="muted"><?= e($c['distrito']) ?></small></td>
                                <td><?= badgeEstado($c['estado']) ?></td>
                                <td>
                                    <?php if (!empty($c['diagnostico']) || !empty($c['indicaciones'])): ?>
                                        <details class="consulta-detalle">
                                            <summary>Ver detalle clínico</summary>
                                            <?php if (!empty($c['diagnostico'])): ?>
                                                <p><strong>Diagnóstico:</strong><br><?= nl2br(e($c['diagnostico'])) ?></p>
                                            <?php endif; ?>
                                            <?php if (!empty($c['indicaciones'])): ?>
                                                <p><strong>Indicaciones:</strong><br><?= nl2br(e($c['indicaciones'])) ?></p>
                                            <?php endif; ?>
                                            <?php if (!empty($c['observaciones'])): ?>
                                                <p><strong>Observaciones:</strong><br><?= nl2br(e($c['observaciones'])) ?></p>
                                            <?php endif; ?>
                                        </details>
                                    <?php else: ?>
                                        <small class="muted">Sin detalle clínico registrado.</small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </main>
</div>

<footer class="site-footer"><p>PrimeLab — Sistema de reserva de citas en centros de salud públicos</p></footer>
<script src="js/main.js"></script>
</body>
</html>
````

## `consultas.php`

````php
<?php
// consultas.php — Consultas médicas del paciente: estados, diagnósticos e indicaciones.
require_once "file_conexion.php";
requerirPaciente();

$mysqli = Conectarse();
$paciente = pacienteActual($mysqli);
if (!$paciente) { $mysqli->close(); redirigir("index.html?msg=sesion"); }

$codp = $paciente['codp'];
$q      = trim($_GET['q'] ?? '');
$fEsp   = trim($_GET['esp'] ?? '');
$fEstado = trim($_GET['estado'] ?? '');

// Especialidades presentes en las citas del paciente (para el filtro).
$especialidades = [];
$stmt = $mysqli->prepare("
    SELECT DISTINCT e.codesp, e.nomesp
    FROM cita c
    JOIN medico m ON m.codm = c.codm
    JOIN especialidad e ON e.codesp = m.codesp
    WHERE c.codp = ?
    ORDER BY e.nomesp
");
$stmt->bind_param("s", $codp);
$stmt->execute();
$res = $stmt->get_result();
while ($r = $res->fetch_assoc()) $especialidades[] = $r;
$stmt->close();

$sql = "
    SELECT c.codci, c.fechaci, c.horaci, c.estado, c.motivo, c.costo, c.fechareg,
           c.observaciones, c.diagnostico, c.indicaciones, c.motivo_reprogramacion, c.reprogramada_de,
           ce.nomce, ce.distrito,
           e.codesp, e.nomesp,
           m.codm, m.nomm, m.cmpm
    FROM cita c
    JOIN centro ce ON ce.codce = c.codce
    JOIN medico m ON m.codm = c.codm
    JOIN especialidad e ON e.codesp = m.codesp
    WHERE c.codp = ?
";
$params = [$codp];
$types = 's';

if ($q !== '') {
    $like = '%' . $q . '%';
    $sql .= " AND (c.codci LIKE ? OR c.motivo LIKE ? OR c.diagnostico LIKE ? OR c.indicaciones LIKE ?
                   OR c.observaciones LIKE ? OR m.nomm LIKE ? OR e.nomesp LIKE ? OR ce.nomce LIKE ?)";
    for ($i = 0; $i < 8; $i++) { $params[] = $like; $types .= 's'; }
}
if ($fEsp !== '') {
    $sql .= " AND e.codesp = ?";
    $params[] = $fEsp;
    $types .= 's';
}
if ($fEstado !== '') {
    $sql .= " AND c.estado = ?";
    $params[] = $fEstado;
    $types .= 's';
}
$sql .= " ORDER BY c.fechaci DESC, c.horaci DESC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$resultado = $stmt->get_result();
$stmt->close();

$consultas = [];
while ($r = $resultado->fetch_assoc()) $consultas[] = $r;

$total = count($consultas);
$atendidas = 0;
$pendientes = 0;
$conDetalle = 0;
foreach ($consultas as $c) {
    if ($c['estado'] === 'atendida') $atendidas++;
    if ($c['estado'] === 'pendiente') $pendientes++;
    if (!empty($c['diagnostico']) || !empty($c['indicaciones'])) $conDetalle++;
}
$mysqli->close();

$nombreCompleto = $paciente['nomp'] . ' ' . $paciente['apep'];
$inicial = mb_strtoupper(mb_substr($paciente['nomp'], 0, 1, 'UTF-8'), 'UTF-8');

function badgeEstado($estado) {
    $e = strtolower($estado);
    $clase = 'status-pending';
    if ($e === 'atendida') $clase = 'status-done';
    if ($e === 'cancelada' || $e === 'reprogramada') $clase = 'status-cancel';
    if ($e === 'extendida') $clase = 'status-pending';
    return '<span class="status-badge ' . $clase . '">' . e(ucfirst($e)) . '</span>';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultas — PrimeLab</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body data-page="consultas">
<header class="topbar">
    <a href="panel.php" class="brand">
        <img src="img/PrimeLab%20Logo.png" alt="PrimeLab" class="brand-logo">
        <span class="brand-copy"><strong>PrimeLab</strong><small>Centros de salud públicos del Perú</small></span>
    </a>
    <div class="topbar-right"><div class="section-tag"><span>Sección actual</span><b>CONSULTAS</b></div></div>
</header>

<div class="app-layout">
    <aside class="sidebar">
        <div class="user-chip">
            <div class="avatar"><?= e($inicial) ?></div>
            <div><strong><?= e($nombreCompleto) ?></strong><small>DNI <?= e($paciente['dni']) ?></small></div>
        </div>
        <nav>
            <p class="nav-label">Paciente</p>
            <a href="panel.php" class="nav-btn"><span class="ico">🏠</span><span>Inicio</span></a>
            <a href="reservar.php" class="nav-btn"><span class="ico">📅</span><span>Reservar cita</span></a>
            <a href="mis_citas.php" class="nav-btn"><span class="ico">🗂</span><span>Mis citas</span></a>
            <a href="consultas.php" class="nav-btn active"><span class="ico">🩺</span><span>Consultas</span></a>
            <a href="perfil.php" class="nav-btn"><span class="ico">👤</span><span>Mi perfil</span></a>
        </nav>
        <div class="account-actions">
            <a href="logout.php" class="nav-btn nav-btn-danger"><span class="ico">⎋</span><span>Cerrar sesión</span></a>
        </div>
    </aside>

    <main class="content">
        <?php mostrarFlash(); ?>

        <div class="page-heading">
            <p class="eyebrow">Historial clínico</p>
            <h1>Consultas médicas</h1>
            <p class="muted">Consulta el estado de tus atenciones, el diagnóstico y las indicaciones registradas por el médico.</p>
        </div>

        <section class="dashboard-overview">
            <article class="summary-card">
                <span class="summary-label">Consultas registradas</span>
                <strong class="summary-count"><?= $total ?></strong>
                <span class="summary-details">con los filtros actuales</span>
            </article>
            <article class="summary-card">
                <span class="summary-label">Atendidas</span>
                <strong class="summary-count"><?= $atendidas ?></strong>
                <span class="summary-details">con atención completada</span>
            </article>
            <article class="summary-card">
                <span class="summary-label">Pendientes</span>
                <strong class="summary-count"><?= $pendientes ?></strong>
                <span class="summary-details">por atender</span>
            </article>
            <article class="summary-card">
                <span class="summary-label">Con diagnóstico</span>
                <strong class="summary-count"><?= $conDetalle ?></strong>
                <span class="summary-details">con indicaciones médicas</span>
            </article>
        </section>

        <form method="GET" action="consultas.php" class="filter-bar">
            <label class="field">
                <span>Buscar</span>
                <input type="text" name="q" value="<?= e($q) ?>" placeholder="Código, médico, especialidad o diagnóstico">
            </label>
            <label class="field">
                <span>Especialidad</span>
                <select name="esp">
                    <option value="">Todas</option>
                    <?php foreach ($especialidades as $esp): ?>
                        <option value="<?= e($esp['codesp']) ?>" <?= $fEsp === $esp['codesp'] ? 'selected' : '' ?>><?= e($esp['nomesp']) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label class="field">
                <span>Estado</span>
                <select name="estado">
                    <option value="">Todos</option>
                    <?php foreach (['pendiente','atendida','extendida','reprogramada','cancelada'] as $est): ?>
                        <option value="<?= e($est) ?>" <?= $fEstado === $est ? 'selected' : '' ?>><?= e(ucfirst($est)) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <button type="submit" class="button primary">Buscar</button>
        </form>

        <section class="panel">
            <div class="panel-head">
                <h2 class="panel-title">Detalle de consultas</h2>
                <a href="reservar.php" class="button secondary">Reservar nueva cita</a>
            </div>

            <?php if (!$consultas): ?>
                <p class="table-empty">No se encontraron consultas con los filtros seleccionados.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Fecha y hora</th>
                                <th>Especialidad</th>
                                <th>Médico</th>
                                <th>Centro</th>
                                <th>Estado</th>
                                <th>Diagnóstico e indicaciones</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($consultas as $c): ?>
                            <tr>
                                <td><span class="code"><?= e($c['codci']) ?></span></td>
                                <td><?= e(fechaTexto($c['fechaci'])) ?><br><small class="muted"><?= e(horaTexto($c['horaci'])) ?></small></td>
                                <td><?= e($c['nomesp']) ?></td>
                                <td><span class="medico-inline"><?= fotoMedico($c['codm'], $c['nomm'], 36) ?><span><?= e($c['nomm']) ?></span></span></td>
                                <td><?= e($c['nomce']) ?><br><small class="muted"><?= e($c['distrito']) ?></small></td>
                                <td><?= badgeEstado($c['estado']) ?></td>
                                <td>
                                    <?php if (!empty($c['diagnostico']) || !empty($c['indicaciones'])): ?>
                                        <details class="consulta-detalle">
                                            <summary>Ver detalle clínico</summary>
                                            <?php if (!empty($c['diagnostico'])): ?>
                                                <p><strong>Diagnóstico:</strong><br><?= nl2br(e($c['diagnostico'])) ?></p>
                                            <?php endif; ?>
                                            <?php if (!empty($c['indicaciones'])): ?>
                                                <p><strong>Indicaciones:</strong><br><?= nl2br(e($c['indicaciones'])) ?></p>
                                            <?php endif; ?>
                                            <?php if (!empty($c['observaciones'])): ?>
                                                <p><strong>Observaciones:</strong><br><?= nl2br(e($c['observaciones'])) ?></p>
                                            <?php endif; ?>
                                        </details>
                                    <?php elseif ($c['estado'] === 'pendiente'): ?>
                                        <small class="muted">Se completará cuando el médico atienda la cita.</small>
                                    <?php else: ?>
                                        <small class="muted">Sin detalle clínico registrado.</small>
                                    <?php endif; ?>
                                </td>
                                <td class="actions-cell">
                                    <a href="ticket.php?codigo=<?= e($c['codci']) ?>" class="button secondary small">Ver ticket</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </main>
</div>

<footer class="site-footer"><p>PrimeLab — Sistema de reserva de citas en centros de salud públicos</p></footer>
<script src="js/main.js"></script>
</body>
</html>
````

## `css/style.css`

````css
/* css/style.css — PrimeLab (tema teal/cream, reconstruido) */
:root {
    --teal-900: #0e3a37;
    --teal-800: #14504b;
    --teal-700: #1b5e57;
    --blue-800: #14504b;
    --blue-600: #24736a;
    --blue-500: #2f8f83;
    --blue-300: #7fc1b8;
    --blue-200: #bfe3de;
    --blue-100: #e6f4f1;
    --blue-50:  #f2faf8;
    --accent:   #e39b72;
    --cream:    #fbf8f1;
    --white:    #ffffff;
    --ink:      #183231;
    --ink-soft: #2b4b49;
    --muted:    #5f7471;
    --muted-2:  #7b8f8c;
    --line:     rgba(20, 80, 75, 0.14);
    --danger:   #a33a30;
    --danger-bg:#fdeceb;
    --danger-bd:#f3c2bc;
    --success:  #1f6b4a;
    --success-bg:#e8f6ef;
    --success-bd:#bfe3cf;
    --warning:  #8a5a12;
    --warning-bg:#fff4de;
    --warning-bd:#efd3a3;
    --shadow: 0 12px 30px rgba(20, 80, 75, 0.10);
    --shadow-soft: 0 6px 16px rgba(20, 80, 75, 0.08);
    --radius: 14px;
}

* { box-sizing: border-box; }
html, body { margin: 0; padding: 0; }
body {
    font-family: "Source Sans 3", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    font-size: 17px;
    color: var(--ink);
    background:
        radial-gradient(circle at 10% 0%, rgba(47,143,131,0.10), transparent 28%),
        radial-gradient(circle at 100% 100%, rgba(227,155,114,0.10), transparent 24%),
        var(--cream);
    line-height: 1.6;
    min-height: 100vh;
}
img { max-width: 100%; display: block; }
a { color: var(--blue-600); text-decoration: none; }
a:hover { color: var(--teal-800); }
h1, h2, h3, .brand-copy strong, .panel-title {
    font-family: "Sora", "Source Sans 3", sans-serif;
    margin: 0;
}
h1 { font-size: 32px; line-height: 1.15; color: var(--teal-800); letter-spacing: -0.02em; }
h2 { font-size: 22px; color: var(--teal-800); }
h3 { font-size: 16px; color: var(--ink); }
p { margin: 0 0 10px; }
.muted { color: #4e6461; }
small, .hint { color: #5f7471; font-size: 15px; }

/* Topbar */
.topbar {
    height: 80px;
    padding: 0 clamp(20px, 4vw, 52px);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    background: rgba(251, 248, 241, 0.88);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid rgba(20, 80, 75, 0.10);
    box-shadow: 0 10px 30px rgba(20, 80, 75, 0.06);
    position: sticky;
    top: 0;
    z-index: 60;
}
.brand { display: flex; align-items: center; gap: 13px; text-decoration: none; min-width: 0; }
.brand-logo { width: 44px; height: 44px; filter: drop-shadow(0 6px 12px rgba(20,80,75,0.18)); flex-shrink: 0; }
.brand-copy { display: flex; flex-direction: column; line-height: 1.15; }
.brand-copy strong { font-size: 20px; font-weight: 700; color: var(--teal-800); }
.brand-copy small { font-size: 12px; color: var(--muted); }
.topbar-right { display: flex; align-items: center; gap: 18px; }
.section-tag { display: flex; align-items: center; gap: 12px; font-size: 13px; color: var(--muted); }
.section-tag b {
    background: var(--white);
    border: 1px solid rgba(20, 80, 75, 0.14);
    border-radius: 999px;
    padding: 8px 16px;
    font-size: 13px;
    letter-spacing: 0.08em;
    color: var(--teal-800);
    box-shadow: 0 3px 10px rgba(20, 80, 75, 0.06);
    white-space: nowrap;
}

/* Buttons */
.button {
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    padding: 13px 22px; min-height: 50px; border-radius: 999px; border: 1px solid transparent;
    font: 700 17px "Source Sans 3", sans-serif; cursor: pointer;
    transition: all .15s ease; text-decoration: none; line-height: 1.2;
}
.button.primary {
    background: linear-gradient(135deg, var(--blue-500), var(--teal-800));
    color: #fff; box-shadow: 0 8px 18px rgba(20,80,75,0.18);
}
.button.primary:hover { filter: brightness(1.03); transform: translateY(-1px); color: #fff; }
.button.secondary {
    background: #fff; color: var(--teal-800); border-color: rgba(20,80,75,0.18);
}
.button.secondary:hover { background: var(--blue-50); }
.button.danger { background: #fff; color: var(--danger); border-color: var(--danger-bd); }
.button.danger:hover { background: var(--danger-bg); }
.button.small { padding: 10px 16px; min-height: 44px; font-size: 15px; }
.button.full { width: 100%; }
button.button { font-family: inherit; }

/* Layout login */
.login-page {
    min-height: calc(100dvh - 80px);
    display: flex; align-items: center; justify-content: center;
    padding: 40px 20px 50px;
}
.login-card {
    width: 100%; max-width: 520px;
    background: rgba(255,255,255,0.96);
    border: 1px solid rgba(20,80,75,0.10);
    border-radius: 24px;
    padding: 36px 38px 32px;
    box-shadow: var(--shadow);
}
.login-card h1 { font-size: 28px; margin-bottom: 6px; }
.login-card .muted { margin-bottom: 18px; }
.login-foot { margin: 16px 0 0; text-align: center; color: #4e6461; font-size: 16px; }

/* App layout */
.app-layout {
    display: grid;
    grid-template-columns: 270px minmax(0, 1fr);
    min-height: calc(100dvh - 80px);
}
.sidebar {
    background:
        radial-gradient(circle at 15% 0%, rgba(47,143,131,0.45), transparent 42%),
        linear-gradient(180deg, var(--teal-900) 0%, #0b2f2c 100%);
    padding: 30px 20px 26px;
    color: #f4efe3;
    display: flex; flex-direction: column;
    position: sticky; top: 80px; height: calc(100dvh - 80px);
    overflow-y: auto;
}
.user-chip { display: flex; align-items: center; gap: 12px; padding: 10px; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12); border-radius: 14px; margin-bottom: 18px; }
.avatar {
    width: 40px; height: 40px; border-radius: 12px;
    background: linear-gradient(135deg, var(--accent), #c97b52);
    color: #2a1a10; display: flex; align-items: center; justify-content: center;
    font: 700 16px "Sora", sans-serif; flex-shrink: 0;
}
.user-chip strong { display: block; font-size: 14px; color: #f4efe3; line-height: 1.2; }
.user-chip small { color: rgba(244,239,227,0.66); font-size: 12px; }
.sidebar nav { display: grid; gap: 4px; }
.nav-label { font-size: 12px; text-transform: uppercase; letter-spacing: 0.12em; color: rgba(244,239,227,0.70); margin: 8px 8px 4px; font-weight: 700; }
.nav-btn {
    display: flex; align-items: center; gap: 10px;
    padding: 13px 14px; border-radius: 12px;
    color: rgba(244,239,227,0.90); font-weight: 600; font-size: 16px;
    border: 1px solid transparent;
}
.nav-btn:hover { background: rgba(255,255,255,0.09); color: #fff; }
.nav-btn.active { background: rgba(255,255,255,0.14); border-color: rgba(255,255,255,0.16); color: #fff; box-shadow: inset 3px 0 0 var(--accent); }
.nav-btn-danger { color: #f2b3a6; }
.nav-btn-danger:hover { background: rgba(242,179,166,0.12); }
.nav-btn .ico { width: 18px; text-align: center; }
.account-actions { margin-top: auto; padding-top: 14px; border-top: 1px solid rgba(255,255,255,0.14); }

.content {
    padding: 44px clamp(24px, 4vw, 56px) 56px;
    min-width: 0;
    width: 100%;
    max-width: 1180px;
    margin: 0 auto;
}
.page-heading { margin-bottom: 22px; text-align: center; }
.page-heading .eyebrow {
    font-size: 13px; text-transform: uppercase; letter-spacing: 0.14em;
    color: var(--blue-600); font-weight: 700; margin-bottom: 4px;
}
.page-heading .muted { margin: 8px auto 0; max-width: 720px; font-size: 17px; }
.rule { border: 0; border-top: 1px solid rgba(20,80,75,0.12); margin: 18px 0; }

/* Panels & cards */
.panel {
    background: #fff; border: 1px solid rgba(20,80,75,0.10);
    border-radius: 20px; padding: 22px 22px;
    box-shadow: 0 10px 30px rgba(20,80,75,0.06);
}
.panel-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 14px; flex-wrap: wrap; }
.panel-title { font-size: 18px; }
.danger-zone { border-color: rgba(163,58,48,0.18); }

.dashboard-banner {
    display: grid; grid-template-columns: 1.4fr 1fr; gap: 18px; align-items: center;
    padding: 28px 30px; border-radius: 26px; color: #fff; margin-bottom: 22px;
    background:
        radial-gradient(circle at 88% 12%, rgba(227,155,114,0.30), transparent 32%),
        radial-gradient(circle at 10% 100%, rgba(47,143,131,0.55), transparent 48%),
        linear-gradient(135deg, var(--teal-900) 0%, var(--teal-800) 58%, #1f6b63 100%);
    box-shadow: 0 22px 50px rgba(14,58,55,0.22);
}
.banner-copy h2 { color: #fff; font-size: 28px; margin-bottom: 8px; }
.banner-copy p { color: rgba(255,255,255,0.85); margin-bottom: 16px; }
.banner-visual { display: flex; justify-content: flex-end; }
.mini-ticket { background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.18); border-radius: 14px; padding: 14px 16px; min-width: 220px; }
.mini-ticket strong { display: block; font-size: 13px; margin-bottom: 4px; }
.mini-ticket small { display: block; color: rgba(255,255,255,0.82); font-size: 12px; }

.dashboard-overview { display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 14px; margin-bottom: 24px; }
.summary-card {
    background: #fff; border: 1px solid rgba(20,80,75,0.10); border-radius: 16px;
    padding: 18px 20px; display: flex; flex-direction: column; gap: 2px;
    box-shadow: 0 4px 14px rgba(20,80,75,0.05);
}
.summary-label { font-size: 12px; text-transform: uppercase; letter-spacing: 0.1em; color: var(--muted); font-weight: 600; }
.summary-count { font-size: 32px; font-weight: 700; color: var(--teal-800); line-height: 1.15; font-family: "Sora", sans-serif; }
.summary-details { font-size: 13px; color: var(--muted-2); }

/* Forms */
.form-stack { display: grid; gap: 14px; }
.field { display: grid; gap: 6px; min-width: 0; }
.field > span, .field-label { color: var(--ink-soft); font-weight: 700; font-size: 16px; }
.field input, .field select, .field textarea, .table-search {
    width: 100%; min-height: 50px; padding: 12px 14px;
    border: 2px solid rgba(20,80,75,0.22); border-radius: 12px;
    background: #fffefb; color: var(--ink);
    font: 400 17px "Source Sans 3", sans-serif;
    transition: border-color .15s, box-shadow .15s;
}
.field textarea { min-height: 90px; resize: vertical; }
.field input:focus, .field select:focus, .field textarea:focus, .table-search:focus {
    outline: none; border-color: var(--blue-500); box-shadow: 0 0 0 4px rgba(47,143,131,0.14);
}
.field input.input-error { border-color: var(--danger); box-shadow: 0 0 0 4px rgba(163,58,48,0.12); }
.field input[readonly], .field textarea[readonly] {
    background: #eef5f3;
    color: var(--ink-soft);
    border-style: dashed;
    cursor: default;
}
.form-grid-2 { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 14px; }
.form-grid-3 { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 14px; }
.form-grid-4 { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 14px; }
.span-2 { grid-column: span 2; }
.compact-field { max-width: 520px; }
.input-with-button { display: grid; grid-template-columns: 1fr auto; gap: 10px; align-items: start; }
.field-help, .field-inline-help { font-size: 15px; color: var(--muted); line-height: 1.5; }
.field-inline-help { display: none; }
.field-inline-help.show { display: block; margin-top: 4px; }
.field-inline-help.error, .field-help.error { color: var(--danger); }
.field-inline-help.success, .field-help.success { color: var(--success); }
input, select, textarea { accent-color: var(--blue-500); }

/* Custom select */
.select-custom { position: relative; width: 100%; min-width: 0; }
.select-native { position: absolute !important; width: 1px !important; height: 1px !important; padding: 0 !important; margin: 0 !important; opacity: 0 !important; pointer-events: none !important; overflow: hidden !important; clip: rect(0 0 0 0) !important; }
.select-trigger {
    width: 100%; min-height: 50px; display: flex; align-items: center; justify-content: space-between; gap: 10px;
    padding: 12px 14px; border: 2px solid rgba(20,80,75,0.22); border-radius: 12px;
    background: #fffefb; color: var(--ink); font-family: inherit; font-size: 17px; line-height: 1.3;
    text-align: left; cursor: pointer; box-shadow: inset 0 1px 2px rgba(20,80,75,0.04);
    transition: border-color .15s ease, box-shadow .15s ease;
}
.select-trigger:hover { border-color: var(--blue-300); }
.select-trigger:focus-visible, .select-custom.open .select-trigger { outline: none; border-color: var(--blue-500); box-shadow: 0 0 0 4px rgba(47,143,131,0.14); }
.select-trigger.input-error { border-color: var(--danger) !important; box-shadow: 0 0 0 4px rgba(163,58,48,0.12) !important; }
.select-trigger.placeholder .select-value { color: var(--muted-2); }
.select-value { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.select-chevron { width: 16px; height: 16px; flex-shrink: 0; color: var(--blue-600); transition: transform .18s ease; }
.select-custom.open .select-chevron { transform: rotate(180deg); }
.select-menu {
    position: absolute; top: calc(100% + 6px); left: 0; right: 0; z-index: 90; display: none;
    max-height: 260px; overflow-y: auto; margin: 0; padding: 6px; list-style: none;
    background: #fffefb; border: 1px solid rgba(20,80,75,0.14); border-radius: 14px;
    box-shadow: 0 18px 38px rgba(20,80,75,0.16);
}
.select-custom.open .select-menu { display: block; }
.select-option { padding: 12px 13px; border-radius: 9px; font-size: 17px; line-height: 1.35; color: var(--ink); cursor: pointer; transition: background .12s ease, color .12s ease; }
.select-option:hover, .select-option.activa { background: rgba(47,143,131,0.12); color: var(--teal-800); }
.select-option.selected { background: var(--teal-800); color: #fff; font-weight: 600; }
.select-option.selected:hover, .select-option.selected.activa { background: var(--blue-500); color: #fff; }
.select-option.disabled { opacity: 0.45; cursor: not-allowed; background: transparent; color: var(--muted); }

/* Alerts */
.alert { display: none; padding: 14px 16px; border-radius: 12px; margin-bottom: 16px; font-size: 16px; border: 1px solid transparent; line-height: 1.5; }
.alert.show { display: block; }
.alert.error { background: var(--danger-bg); color: var(--danger); border-color: var(--danger-bd); }
.alert.success { background: var(--success-bg); color: var(--success); border-color: var(--success-bd); }

/* Steps, filters, reservar */
.steps { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 22px; justify-content: center; }
.step { display: inline-flex; align-items: center; gap: 10px; padding: 10px 16px; border-radius: 999px; background: #fff; border: 2px solid var(--line); color: var(--muted); font-weight: 700; font-size: 16px; }
.step-dot { width: 30px; height: 30px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; background: var(--blue-100); color: var(--teal-800); font-size: 15px; font-weight: 700; }
.step.active { color: var(--teal-800); border-color: var(--blue-300); background: var(--blue-50); }
.step.active .step-dot { background: var(--teal-800); color: #fff; }
.step.done { color: var(--success); }
.step.done .step-dot { background: var(--success-bg); color: var(--success); border: 1px solid var(--success-bd); }

.filter-bar { display: grid; grid-template-columns: 1.4fr 1fr 1fr auto; gap: 12px; align-items: end; background: #fff; border: 1px solid rgba(20,80,75,0.10); border-radius: 14px; padding: 16px 18px; margin-bottom: 14px; }
.filter-bar.compact { grid-template-columns: 1.4fr 1fr auto; }

.center-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 16px; }
.center-card { background: #fff; border: 1px solid rgba(20,80,75,0.10); border-radius: 18px; padding: 18px; box-shadow: 0 8px 22px rgba(20,80,75,0.05); display: grid; gap: 6px; }
.center-card:hover { border-color: var(--blue-300); box-shadow: 0 14px 30px rgba(20,80,75,0.10); }
.center-icon { width: 42px; height: 42px; border-radius: 11px; background: rgba(47,143,131,0.12); color: var(--teal-800); display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 4px; }

.two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; align-items: start; }
.selection-box { background: var(--blue-50); border: 1px solid var(--blue-100); border-radius: 12px; padding: 14px 16px; margin-bottom: 14px; font-size: 17px; }
.list-h { margin: 14px 0 8px; font-size: 14px; color: var(--muted); text-transform: uppercase; letter-spacing: .06em; }
.doctor-list { display: grid; gap: 10px; }
.doctor-card { display: flex; align-items: center; gap: 14px; padding: 14px 14px; border: 2px solid rgba(20,80,75,0.12); border-radius: 13px; cursor: pointer; background: #fff; }
.doctor-card.selected { border-color: var(--blue-500); background: rgba(47,143,131,0.08); box-shadow: 0 6px 16px rgba(47,143,131,0.12); }
.doctor-card input { margin: 0; }
.doctor-card strong { display: block; font-size: 14px; }
.doctor-card small { color: var(--muted); }
.doctor-info { min-width: 0; }

.medico-foto {
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
    border: 2px solid rgba(20,80,75,0.12);
    background: var(--blue-50);
}
.medico-foto-fallback {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--blue-500), var(--teal-800));
    color: #fff;
    font-weight: 700;
    font-family: "Sora", sans-serif;
}
.medico-inline {
    display: inline-flex;
    align-items: center;
    gap: 10px;
}
.medico-inline .medico-foto { width: 40px; height: 40px; }
.medico-resumen {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 10px;
    padding: 10px 12px;
    background: var(--blue-50);
    border: 1px solid var(--blue-100);
    border-radius: 12px;
}

.slot-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(90px, 1fr)); gap: 10px; }
.slot { border: 2px solid rgba(20,80,75,0.16); border-radius: 12px; padding: 14px 10px; text-align: center; cursor: pointer; background: #fff; font-weight: 700; font-size: 17px; }
.slot input { display: none; }
.slot:hover { border-color: var(--blue-300); background: var(--blue-50); }
.slot.selected { background: var(--teal-800); color: #fff; border-color: var(--teal-800); }
.slot.disabled { opacity: .45; cursor: not-allowed; background: #f4f4f1; }
.slot-legend { display: flex; gap: 14px; font-size: 12px; color: var(--muted); margin-top: 4px; }
.dot { display: inline-block; width: 10px; height: 10px; border-radius: 50%; margin-right: 4px; vertical-align: middle; }
.dot-free { background: #fff; border: 1px solid rgba(20,80,75,0.3); }
.dot-off { background: #c8d2d0; }

/* Data & summary */
.data-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(170px, 100%), 1fr)); gap: 12px; margin: 16px 0; }
.data-grid div { background: var(--blue-50); border: 1px solid var(--blue-100); border-radius: 12px; padding: 10px 12px; min-width: 0; }
.data-grid span { display: block; font-size: 13px; text-transform: uppercase; letter-spacing: 0.08em; color: #415856; font-weight: 700; margin-bottom: 2px; }
.data-grid strong { font-size: 17px; color: var(--ink); display: block; overflow-wrap: anywhere; word-break: break-word; }
.summary-list { display: grid; gap: 0; margin: 14px 0 0; }
.summary-list > div { display: grid; grid-template-columns: 180px 1fr; gap: 10px; padding: 10px 0; border-bottom: 1px solid var(--line); }
.summary-list > div span { color: var(--muted); font-weight: 600; }
.summary-list > div strong { color: var(--ink); }

/* Tables */
.table-responsive {
    overflow-x: auto;
    scrollbar-width: thin;
    scrollbar-color: rgba(20,80,75,0.35) transparent;
}
.table { width: 100%; border-collapse: collapse; font-size: 15px; }
.table th {
    background: var(--blue-50); color: var(--teal-800); font-size: 12px; text-transform: uppercase;
    letter-spacing: 0.07em; text-align: left; padding: 12px; border-bottom: 1px solid var(--blue-200); white-space: nowrap;
}
.table td { padding: 12px; border-bottom: 1px solid var(--blue-100); vertical-align: top; line-height: 1.5; }
.table tbody tr:hover { background: #fbfdfc; }
.table-empty { text-align: center; color: var(--muted); padding: 26px 12px !important; }
.code { font-family: Consolas, "Courier New", monospace; font-size: 13px; background: var(--blue-50); border: 1px solid var(--blue-100); padding: 3px 7px; border-radius: 6px; color: var(--teal-800); white-space: nowrap; }
.actions-cell { display: flex; gap: 6px; align-items: center; flex-wrap: wrap; }
.table-search { max-width: 280px; min-height: 44px; font-size: 15px; }
.link-inline { font-size: 14px; font-weight: 700; color: var(--blue-600); }
.link-inline:hover { text-decoration: underline; }

.status-badge { display: inline-block; padding: 5px 11px; border-radius: 999px; font-size: 13px; font-weight: 700; text-transform: capitalize; }
.status-pending { background: var(--warning-bg); color: var(--warning); border: 1px solid var(--warning-bd); }
.status-done { background: var(--success-bg); color: var(--success); border: 1px solid var(--success-bd); }
.status-cancel { background: var(--danger-bg); color: var(--danger); border: 1px solid var(--danger-bd); }

/* Ticket */
.ticket-card { background: #fff; border: 1px solid rgba(20,80,75,0.12); border-radius: 22px; padding: 28px 30px 26px; max-width: 760px; box-shadow: var(--shadow-soft); }
.ticket-code { text-align: center; margin-bottom: 8px; }
.ticket-code span { display: block; font-size: 13px; text-transform: uppercase; letter-spacing: .12em; color: var(--muted); font-weight: 700; }
.ticket-code strong { font-size: 34px; color: var(--teal-800); font-family: "Sora", sans-serif; letter-spacing: .06em; }
.ticket-actions { display: flex; gap: 10px; margin-top: 18px; flex-wrap: wrap; }

.timeline { list-style: none; padding: 0; margin: 12px 0 0; display: grid; gap: 8px; }
.timeline li { padding: 10px 12px; background: var(--blue-50); border: 1px solid var(--blue-100); border-radius: 10px; font-size: 15px; }

.site-footer { text-align: center; color: var(--muted); font-size: 14px; padding: 20px 24px 26px; }
.site-footer p { margin: 2px 0; }

/* Live Server aviso */
.entorno-aviso {
    position: fixed; left: 50%; bottom: 18px; transform: translateX(-50%); z-index: 200;
    width: min(760px, calc(100% - 32px)); background: #8a2f26; color: #fff6f2;
    border: 1px solid rgba(255,255,255,0.22); border-radius: 14px; padding: 13px 18px;
    font-size: 13.5px; line-height: 1.5; box-shadow: 0 16px 36px rgba(60,18,12,0.35);
}
.entorno-aviso strong { color: #fff; }
.entorno-aviso a { color: #ffd9c7; font-weight: 700; text-decoration: underline; white-space: nowrap; }
.entorno-aviso a:hover { color: #fff; }

/* Responsive */
@media (max-width: 1100px) {
    .content { padding: 32px 30px 44px; }
    .dashboard-banner { grid-template-columns: 1fr; min-height: 0; }
    .banner-visual { display: none; }
    .two-col { grid-template-columns: 1fr; }
    .filter-bar.compact { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 900px) {
    .topbar { padding: 0 20px; }
    .section-tag span { display: none; }
    .app-layout { grid-template-columns: minmax(0, 1fr); }
    .sidebar {
        border-right: 0; border-bottom: 0; padding: 18px 20px;
        position: static; height: auto; overflow: visible;
        background: linear-gradient(180deg, var(--teal-900), #0b2f2c);
    }
    .user-chip { display: none; }
    .sidebar nav { display: flex; flex-wrap: wrap; overflow-x: visible; gap: 6px; row-gap: 6px; padding-bottom: 4px; }
    .nav-btn { white-space: nowrap; max-width: 100%; overflow: hidden; text-overflow: ellipsis; }
    .account-actions { margin-top: 10px; display: flex; gap: 6px; border-top: 0; padding-top: 0; }
    .content { padding: 24px 20px 40px; }
    .filter-bar, .filter-bar.compact { grid-template-columns: 1fr; }
    .form-grid-2, .form-grid-3, .form-grid-4 { grid-template-columns: 1fr; }
    .span-2 { grid-column: auto; }
    .summary-list > div { grid-template-columns: 1fr; gap: 2px; }
    .site-footer { padding: 18px 20px; }
    .table-responsive {
        background:
            linear-gradient(to right, #fff 30%, rgba(255,255,255,0)) left center,
            linear-gradient(to left, #fff 30%, rgba(255,255,255,0)) right center,
            radial-gradient(farthest-side at 0 50%, rgba(20,80,75,0.16), transparent) left center,
            radial-gradient(farthest-side at 100% 50%, rgba(20,80,75,0.16), transparent) right center;
        background-repeat: no-repeat;
        background-size: 34px 100%, 34px 100%, 12px 100%, 12px 100%;
        background-attachment: local, local, scroll, scroll;
    }
    .table-responsive .table { min-width: 680px; }
}
@media (max-width: 620px) {
    .topbar { height: 72px; padding: 0 16px; gap: 12px; }
    .brand { gap: 10px; min-width: 0; }
    .brand-logo { width: 38px; height: 38px; }
    .brand-copy { min-width: 0; }
    .brand-copy strong { font-size: 17px; }
    .brand-copy small { display: none; }
    .section-tag b { padding: 7px 12px; font-size: 11px; }
    h1 { font-size: 25px; }
    .login-page { min-height: calc(100dvh - 72px); }
    .app-layout { min-height: calc(100dvh - 72px); }
    .login-card { padding: 26px 20px 22px; border-radius: 20px; }
    .center-grid { grid-template-columns: 1fr; }
    .banner-copy h2 { font-size: 24px; }
    .ticket-card { padding: 26px 20px 22px; }
    .ticket-code strong { font-size: 24px; }
    .ticket-actions { flex-direction: column; }
    .ticket-actions .button { width: 100%; }
    .input-with-button { grid-template-columns: 1fr; }
    .dashboard-overview { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }
    .summary-card { padding: 14px 15px; }
    .summary-card:last-child:nth-child(odd) { grid-column: 1 / -1; }
    .summary-count { font-size: 26px; }
    .summary-label { font-size: 10px; letter-spacing: 0.07em; }
}
@media print {
    body { background: #fff; }
    .topbar, .sidebar, .site-footer, .ticket-actions, .alert, .entorno-aviso { display: none !important; }
    .app-layout { display: block; }
    .content { padding: 0; }
    .ticket-card { box-shadow: none; border: 1px solid #ccc; }
}

/* Detalle clínico de la sección Consultas */
.consulta-detalle summary {
    cursor: pointer;
    color: var(--teal-700, #14504b);
    font-weight: 700;
    font-size: 15px;
    list-style: none;
}
.consulta-detalle summary::-webkit-details-marker { display: none; }
.consulta-detalle summary::before { content: '＋ '; font-weight: 700; }
.consulta-detalle[open] summary::before { content: '− '; }
.consulta-detalle p {
    margin: 8px 0 0;
    font-size: 13px;
    line-height: 1.45;
    color: var(--ink, #1f2d2b);
}

/* ============================================================
   ACCESIBILIDAD Y LECTURA FÁCIL (adultos mayores)
   Barra flotante, letra grande, alto contraste y foco visible.
   ============================================================ */

/* Contornos de foco bien visibles para quienes usan teclado */
a:focus-visible,
button:focus-visible,
input:focus-visible,
select:focus-visible,
textarea:focus-visible,
summary:focus-visible,
[tabindex]:focus-visible {
    outline: 3px solid #0b5f52;
    outline-offset: 3px;
    border-radius: 8px;
}

/* Enlace para saltar al contenido (solo visible con teclado) */
.saltar-contenido {
    position: absolute;
    left: -9999px;
    top: 8px;
    z-index: 300;
    background: #0e3a37;
    color: #fff;
    padding: 14px 22px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 17px;
    text-decoration: none;
}
.saltar-contenido:focus {
    left: 16px;
    color: #fff;
}

/* Barra de accesibilidad */
.barra-accesibilidad {
    position: fixed;
    right: 18px;
    bottom: 18px;
    z-index: 160;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 10px;
    font-family: "Source Sans 3", system-ui, sans-serif;
}
.boton-accesibilidad {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    min-height: 56px;
    padding: 12px 22px;
    border-radius: 999px;
    border: 2px solid #fff;
    background: #0e3a37;
    color: #fff;
    font: 700 17px "Source Sans 3", system-ui, sans-serif;
    cursor: pointer;
    box-shadow: 0 10px 26px rgba(14, 58, 55, 0.32);
}
.boton-accesibilidad:hover { background: #14504b; }
.boton-accesibilidad:focus-visible { outline-color: #e39b72; }

.panel-accesibilidad {
    display: none;
    width: min(300px, calc(100vw - 36px));
    padding: 18px;
    background: #ffffff;
    border: 2px solid #14504b;
    border-radius: 18px;
    box-shadow: 0 18px 44px rgba(14, 58, 55, 0.30);
}
.panel-accesibilidad.abierto { display: grid; gap: 10px; }
.panel-accesibilidad-title {
    margin: 0;
    font: 700 19px "Sora", "Source Sans 3", sans-serif;
    color: #0e3a37;
}
.panel-accesibilidad-help {
    margin: 0;
    font-size: 15px;
    line-height: 1.45;
    color: #415856;
}
.acc-fila {
    display: grid;
    grid-template-columns: 56px 1fr 56px;
    align-items: center;
    gap: 8px;
}
.acc-estado {
    text-align: center;
    font-weight: 700;
    font-size: 15px;
    color: #0e3a37;
}
.acc-opcion {
    min-height: 48px;
    padding: 10px 14px;
    border: 2px solid #14504b;
    border-radius: 12px;
    background: #f2faf8;
    color: #0e3a37;
    font: 700 17px "Source Sans 3", system-ui, sans-serif;
    cursor: pointer;
}
.acc-opcion:hover { background: #e6f4f1; }
.acc-opcion.acc-ancho { width: 100%; text-align: left; }
.acc-opcion.activo {
    background: #0e3a37;
    color: #fff;
    border-color: #0e3a37;
}

/* Botón para mostrar u ocultar la contraseña */
.campo-clave { position: relative; display: flex; }
.campo-clave input { padding-right: 108px !important; }
.boton-ver-clave {
    position: absolute;
    top: 50%;
    right: 8px;
    transform: translateY(-50%);
    min-height: 40px;
    padding: 8px 14px;
    border: 2px solid rgba(20, 80, 75, 0.25);
    border-radius: 999px;
    background: #fff;
    color: #14504b;
    font: 700 15px "Source Sans 3", system-ui, sans-serif;
    cursor: pointer;
}
.boton-ver-clave:hover { background: #f2faf8; }

/* Caja de ayuda con pasos */
.ayuda-pasos {
    margin-top: 18px;
    padding: 16px 18px;
    background: #f2faf8;
    border: 1px solid #bfe3de;
    border-radius: 16px;
    text-align: left;
}
.ayuda-pasos .ayuda-titulo {
    margin: 0 0 8px;
    font: 700 17px "Sora", "Source Sans 3", sans-serif;
    color: #0e3a37;
}
.ayuda-pasos ol {
    margin: 0;
    padding-left: 22px;
    display: grid;
    gap: 6px;
    font-size: 16px;
    line-height: 1.5;
    color: #2b4b49;
}
.ayuda-pasos .ayuda-nota {
    margin: 10px 0 0;
    font-size: 15px;
    color: #415856;
}

/* ---- Letra grande ---- */
body.texto-grande { font-size: 19px; line-height: 1.7; }
body.texto-grande .button,
body.texto-grande .boton-accesibilidad { font-size: 19px; min-height: 58px; padding: 15px 26px; }
body.texto-grande .button.small { font-size: 16px; min-height: 46px; padding: 11px 16px; }
body.texto-grande .field > span,
body.texto-grande .field-label,
body.texto-grande .select-trigger { font-size: 19px; }
body.texto-grande .field input,
body.texto-grande .field select,
body.texto-grande .field textarea,
body.texto-grande .table-search {
    font-size: 19px;
    min-height: 58px;
    padding: 14px 16px;
}
body.texto-grande .select-trigger { min-height: 58px; padding: 14px 16px; }
body.texto-grande .nav-btn { font-size: 18px; padding: 15px 16px; }
body.texto-grande .nav-label { font-size: 13px; }
body.texto-grande h1 { font-size: 40px; }
body.texto-grande h2,
body.texto-grande .panel-title { font-size: 27px; }
body.texto-grande .banner-copy h2 { font-size: 32px; }
body.texto-grande h3 { font-size: 19px; }
body.texto-grande p,
body.texto-grande .muted,
body.texto-grande .login-foot,
body.texto-grande .page-heading .muted { font-size: 19px; }
body.texto-grande small,
body.texto-grande .hint,
body.texto-grande .field-help,
body.texto-grande .field-inline-help { font-size: 17px; }
body.texto-grande .alert { font-size: 18px; padding: 16px 18px; }
body.texto-grande .table { font-size: 17px; }
body.texto-grande .table th { font-size: 14px; padding: 14px; }
body.texto-grande .table td { padding: 14px; }
body.texto-grande .status-badge { font-size: 15px; padding: 6px 13px; }
body.texto-grande .summary-label,
body.texto-grande .data-grid span,
body.texto-grande .ticket-code span { font-size: 14px; }
body.texto-grande .summary-details { font-size: 15px; }
body.texto-grande .site-footer { font-size: 16px; }
body.texto-grande .link-inline { font-size: 16px; }
body.texto-grande .consulta-detalle summary { font-size: 17px; }
body.texto-grande .consulta-detalle p { font-size: 16px; }
body.texto-grande .ayuda-pasos ol { font-size: 18px; }
body.texto-grande .ayuda-pasos .ayuda-nota { font-size: 17px; }
body.texto-grande .acc-opcion { font-size: 18px; min-height: 52px; }
body.texto-grande .boton-ver-clave { font-size: 16px; min-height: 42px; }
body.texto-grande .campo-clave input { padding-right: 116px !important; }

/* ---- Letra muy grande ---- */
body.texto-muy-grande { font-size: 21px; line-height: 1.75; }
body.texto-muy-grande .button,
body.texto-muy-grande .boton-accesibilidad { font-size: 21px; min-height: 64px; padding: 17px 30px; }
body.texto-muy-grande .button.small { font-size: 18px; min-height: 50px; padding: 12px 18px; }
body.texto-muy-grande .field > span,
body.texto-muy-grande .field-label,
body.texto-muy-grande .select-trigger { font-size: 22px; }
body.texto-muy-grande .field input,
body.texto-muy-grande .field select,
body.texto-muy-grande .field textarea,
body.texto-muy-grande .table-search {
    font-size: 22px;
    min-height: 64px;
    padding: 16px 18px;
}
body.texto-muy-grande .select-trigger { min-height: 64px; padding: 16px 18px; }
body.texto-muy-grande .nav-btn { font-size: 20px; padding: 17px 18px; }
body.texto-muy-grande .nav-label { font-size: 15px; }
body.texto-muy-grande h1 { font-size: 46px; }
body.texto-muy-grande h2,
body.texto-muy-grande .panel-title { font-size: 30px; }
body.texto-muy-grande .banner-copy h2 { font-size: 36px; }
body.texto-muy-grande h3 { font-size: 22px; }
body.texto-muy-grande p,
body.texto-muy-grande .muted,
body.texto-muy-grande .login-foot,
body.texto-muy-grande .page-heading .muted { font-size: 22px; }
body.texto-muy-grande small,
body.texto-muy-grande .hint,
body.texto-muy-grande .field-help,
body.texto-muy-grande .field-inline-help { font-size: 19px; }
body.texto-muy-grande .alert { font-size: 20px; padding: 18px 20px; }
body.texto-muy-grande .table { font-size: 19px; }
body.texto-muy-grande .table th { font-size: 15px; padding: 16px; }
body.texto-muy-grande .table td { padding: 16px; }
body.texto-muy-grande .status-badge { font-size: 17px; padding: 7px 15px; }
body.texto-muy-grande .summary-label,
body.texto-muy-grande .data-grid span,
body.texto-muy-grande .ticket-code span { font-size: 16px; }
body.texto-muy-grande .summary-details { font-size: 17px; }
body.texto-muy-grande .site-footer { font-size: 18px; }
body.texto-muy-grande .link-inline { font-size: 18px; }
body.texto-muy-grande .consulta-detalle summary { font-size: 19px; }
body.texto-muy-grande .consulta-detalle p { font-size: 18px; }
body.texto-muy-grande .ayuda-pasos ol { font-size: 20px; }
body.texto-muy-grande .ayuda-pasos .ayuda-nota { font-size: 19px; }
body.texto-muy-grande .acc-opcion { font-size: 20px; min-height: 56px; }
body.texto-muy-grande .boton-ver-clave { font-size: 18px; min-height: 44px; }
body.texto-muy-grande .campo-clave input { padding-right: 128px !important; }

/* ---- Alto contraste ---- */
body.alto-contraste {
    background: #ffffff;
    color: #000000;
}
body.alto-contraste p,
body.alto-contraste .muted,
body.alto-contraste small,
body.alto-contraste .hint,
body.alto-contraste .field-help,
body.alto-contraste .field-inline-help,
body.alto-contraste .summary-details,
body.alto-contraste .site-footer,
body.alto-contraste .ayuda-pasos .ayuda-nota { color: #1a1a1a; }
body.alto-contraste a { color: #00457a; text-decoration: underline; }
body.alto-contraste .panel,
body.alto-contraste .login-card,
body.alto-contraste .summary-card,
body.alto-contraste .ticket-card,
body.alto-contraste .ayuda-pasos {
    background: #ffffff;
    border: 2px solid #000000;
    box-shadow: none;
}
body.alto-contraste .field input,
body.alto-contraste .field select,
body.alto-contraste .field textarea,
body.alto-contraste .table-search,
body.alto-contraste .select-trigger {
    background: #ffffff;
    color: #000000;
    border: 2px solid #000000;
}
body.alto-contraste .button.primary {
    background: #000000;
    color: #ffffff;
    border-color: #000000;
    box-shadow: none;
}
body.alto-contraste .button.secondary,
body.alto-contraste .button.danger {
    background: #ffffff;
    color: #000000;
    border: 2px solid #000000;
}
body.alto-contraste .table th {
    background: #000000;
    color: #ffffff;
    border-color: #000000;
}
body.alto-contraste .table td { border-color: #444444; }
body.alto-contraste .sidebar {
    background: #000000;
    color: #ffffff;
}
body.alto-contraste .nav-btn { color: #ffffff; text-decoration: none; }
body.alto-contraste .nav-btn.active {
    background: #ffffff;
    color: #000000;
    box-shadow: inset 3px 0 0 #000000;
}
body.alto-contraste .nav-label { color: #ffffff; }
body.alto-contraste .status-badge {
    background: #ffffff;
    color: #000000;
    border: 2px solid #000000;
}
body.alto-contraste .alert { border-width: 2px; }
body.alto-contraste .brand-copy strong,
body.alto-contraste h1,
body.alto-contraste h2,
body.alto-contraste h3,
body.alto-contraste .panel-title { color: #000000; }

/* En pantallas pequeñas la barra ocupa menos espacio */
@media (max-width: 620px) {
    .barra-accesibilidad { right: 12px; bottom: 12px; }
    .boton-accesibilidad { padding: 12px 18px; font-size: 16px; min-height: 52px; }
    .panel-accesibilidad { width: min(320px, calc(100vw - 24px)); }
    .ayuda-pasos { padding: 14px 15px; }
}

@media print {
    .barra-accesibilidad,
    .saltar-contenido { display: none !important; }
}
````

## `js/main.js`

````javascript
// js/main.js — Interacciones del sistema PrimeLab.

/**
 * Filtro en tiempo real para las tablas del panel administrativo.
 * Requiere: input#adminSearchInput y table#tablaAdmin
 */
function filtrarTablaAdmin() {
    const input = document.getElementById('adminSearchInput');
    const tabla = document.getElementById('tablaAdmin');
    if (!input || !tabla) return;

    const filtro = input.value.toLowerCase().trim();
    const filas = tabla.querySelectorAll('tbody tr');

    filas.forEach((fila) => {
        if (fila.querySelector('.table-empty')) return;
        const texto = (fila.textContent || '').toLowerCase();
        fila.style.display = texto.includes(filtro) ? '' : 'none';
    });
}

/**
 * Mensajes que llegan por la URL desde los archivos PHP.
 * Ejemplo: index.html?error=noexiste
 */
const MENSAJES = {
    login: {
        dni: 'El DNI debe tener exactamente 8 dígitos numéricos.',
        pass: 'La contraseña no es correcta. Recuerda que la contraseña inicial de tu cuenta es tu DNI.',
        noexiste: 'No encontramos una cuenta con ese DNI. Regístrate para crear tu perfil.'
    },
    medico_login: {
        cmp: 'Ingresa un número de CMP válido.',
        noexiste: 'No encontramos un médico con ese CMP. Verifica el número.'
    },
    admin: {
        campos: 'Completa usuario, correo y contraseña.',
        credenciales: 'Las credenciales no coinciden con la cuenta administrativa.'
    },
    registro: {
        campos: 'Completa todos los campos obligatorios.',
        nombre: 'Los nombres y apellidos solo deben contener letras, sin números.',
        dni: 'El DNI debe tener exactamente 8 dígitos numéricos.',
        tel: 'El teléfono debe tener exactamente 9 dígitos numéricos.',
        correo: 'El correo electrónico no tiene un formato válido.',
        existe: 'Ya existe una cuenta registrada con ese DNI.',
        sistema: 'No se pudo completar el registro. Intenta nuevamente.'
    },
    perfil: {
        campos: 'Completa los campos obligatorios.',
        tel: 'El teléfono debe tener exactamente 9 dígitos numéricos.',
        correo: 'El correo electrónico no tiene un formato válido.',
        actualiza: 'Tus datos fueron actualizados correctamente.',
        confirma: 'Debes confirmar tu DNI para eliminar la cuenta.',
        confirma_dni: 'El DNI ingresado no coincide con tu cuenta.'
    },
    reservar: {
        horario: 'Debes seleccionar un horario disponible.',
        nohorario: 'Ese horario ya no está disponible para el médico seleccionado.',
        sistema: 'No se pudo registrar la cita. Intenta nuevamente.'
    },
    ticket: {
        creada: 'Tu cita fue registrada correctamente. Guarda tu código de cita.'
    }
};

const MENSAJES_GENERALES = {
    salir: { tipo: 'success', texto: 'Cerraste sesión correctamente.' },
    eliminado: { tipo: 'success', texto: 'Tu cuenta y todas tus citas fueron eliminadas.' },
    sesion: { tipo: 'error', texto: 'Debes iniciar sesión para continuar.' }
};

/**
 * Dirección del proyecto cuando se ejecuta con Apache + PHP (XAMPP).
 * Live Server (puerto 5500) solo sirve archivos estáticos y no ejecuta PHP,
 * por eso los enlaces y formularios se redirigen a la carpeta del proyecto
 * en Apache. Se calcula con el mismo host para funcionar en cualquier IP.
 * Puedes cambiarla antes de cargar main.js con:
 *   window.PRIMELAB_APACHE_URL = 'http://192.168.1.50:8080/PrimeLab/';
 */
const RUTA_APACHE = (
    window.PRIMELAB_APACHE_URL ||
    (location.protocol + '//' + location.hostname + '/PrimeLab/')
).replace(/\/+$/, '/');

/** Indica si la página se abrió con un servidor que no ejecuta PHP. */
function entornoSinPhp() {
    const puerto = parseInt(location.port || '0', 10);
    return location.protocol === 'file:' || (puerto >= 5500 && puerto <= 5599);
}

/**
 * Corrige los enlaces y formularios cuando el proyecto se abre con Live Server.
 * Así el registro y el acceso llegan a los archivos PHP de Apache y no a un
 * error 405 del servidor estático.
 */
function configurarEntorno() {
    window.RUTA_API_DNI = 'consulta_dni.php';

    if (!entornoSinPhp()) {
        return;
    }

    window.RUTA_API_DNI = RUTA_APACHE + 'consulta_dni.php';

    const convertirRuta = (ruta) => {
        if (!ruta || /^(https?:|#|mailto:|javascript:)/i.test(ruta)) {
            return ruta;
        }
        return RUTA_APACHE + ruta.replace(/^\.?\//, '');
    };

    // Enlaces entre páginas HTML y PHP
    document.querySelectorAll('a[href]').forEach((enlace) => {
        if (/\.(html|php)(\?.*)?(#.*)?$/i.test(enlace.getAttribute('href'))) {
            enlace.setAttribute('href', convertirRuta(enlace.getAttribute('href')));
        }
    });

    // Formularios que envían datos a los archivos PHP
    document.querySelectorAll('form[action]').forEach((formulario) => {
        if (/\.php(\?.*)?$/i.test(formulario.getAttribute('action'))) {
            formulario.setAttribute('action', convertirRuta(formulario.getAttribute('action')));
        }
    });

    const aviso = document.createElement('div');
    aviso.className = 'entorno-aviso';
    aviso.innerHTML = '<strong>Live Server no ejecuta PHP.</strong> ' +
        'Los enlaces y formularios se enviarán a Apache para que el sistema funcione. ' +
        '<a href="' + RUTA_APACHE + '">Abrir la versión correcta</a>';
    document.body.appendChild(aviso);
}

/**
 * Muestra el mensaje indicado en la URL dentro de #alertBox.
 */
function mostrarMensajeUrl() {
    const box = document.getElementById('alertBox');
    if (!box) return;

    const params = new URLSearchParams(window.location.search);
    const page = document.body.dataset.page || '';

    let texto = '';
    let tipo = 'error';

    const error = params.get('error');
    const ok = params.get('ok');
    const msg = params.get('msg');

    if (ok && MENSAJES[page] && MENSAJES[page][ok]) {
        texto = MENSAJES[page][ok];
        tipo = 'success';
    } else if (error && MENSAJES[page] && MENSAJES[page][error]) {
        texto = MENSAJES[page][error];
        tipo = 'error';
    } else if (msg && MENSAJES_GENERALES[msg]) {
        texto = MENSAJES_GENERALES[msg].texto;
        tipo = MENSAJES_GENERALES[msg].tipo;
    }

    if (!texto) return;

    box.textContent = texto;
    box.className = 'alert show ' + (tipo === 'success' ? 'success' : 'error');

    // Restaura los valores enviados por el formulario (si existen).
    ['dni', 'telp', 'correo', 'nomp', 'apep', 'usuario', 'seguro', 'cmpm'].forEach((nombre) => {
        const valor = params.get(nombre);
        const campo = document.querySelector('[name="' + nombre + '"]');
        if (valor !== null && campo && !campo.value) {
            campo.value = valor;
            if (campo.tagName === 'SELECT') {
                campo.dispatchEvent(new Event('change', { bubbles: true }));
            }
        }
    });
}

/**
 * Aplica restricciones numéricas a los campos marcados con data-numerico.
 * Muestra avisos cuando se escriben letras o se supera el máximo.
 */
function configurarCamposNumericos() {
    const campos = document.querySelectorAll('[data-numerico="true"]');

    campos.forEach((campo) => {
        let ayuda = campo.parentElement.querySelector('.field-inline-help');
        if (!ayuda) {
            ayuda = document.createElement('small');
            ayuda.className = 'field-inline-help';
            const ref = campo.closest('.input-with-button') || campo;
            ref.insertAdjacentElement('afterend', ayuda);
        }

        let temporizador;
        const mostrar = (texto, esError = true) => {
            ayuda.textContent = texto;
            ayuda.className = 'field-inline-help show ' + (esError ? 'error' : '');
            campo.classList.toggle('input-error', esError);
            clearTimeout(temporizador);
            temporizador = setTimeout(() => {
                ayuda.className = 'field-inline-help';
                campo.classList.remove('input-error');
            }, 2800);
        };

        campo.addEventListener('input', () => {
            const original = campo.value;
            const soloNumeros = original.replace(/\D/g, '');
            const max = parseInt(campo.dataset.max || '0', 10);

            if (original !== soloNumeros) {
                mostrar('Solo se permiten números en este campo.');
            } else if (max > 0 && soloNumeros.length > max) {
                mostrar('Máximo ' + max + ' dígitos.');
            } else {
                ayuda.className = 'field-inline-help';
                campo.classList.remove('input-error');
            }

            campo.value = max > 0 ? soloNumeros.slice(0, max) : soloNumeros;
        });

        campo.addEventListener('blur', () => {
            const exacto = parseInt(campo.dataset.exacto || '0', 10);
            if (exacto > 0 && campo.value && campo.value.length !== exacto) {
                const etiqueta = campo.dataset.label || 'Este campo';
                mostrar(etiqueta + ' debe tener exactamente ' + exacto + ' dígitos.');
            }
        });
    });
}

/**
 * Restringe los campos marcados con data-texto="true" a letras y signos
 * propios de un nombre (espacios, apóstrofo, guion y punto). Impide números.
 */
function configurarCamposTexto() {
    const campos = document.querySelectorAll('[data-texto="true"]');
    const noPermitidos = /[^A-Za-zÁÉÍÓÚÜÑáéíóúüñ\s'’\-.,\/()]/g;

    campos.forEach((campo) => {
        let ayuda = campo.parentElement.querySelector('.field-inline-help');
        if (!ayuda) {
            ayuda = document.createElement('small');
            ayuda.className = 'field-inline-help';
            campo.insertAdjacentElement('afterend', ayuda);
        }

        let temporizador;
        const avisar = (texto) => {
            ayuda.textContent = texto;
            ayuda.className = 'field-inline-help show error';
            campo.classList.add('input-error');
            clearTimeout(temporizador);
            temporizador = setTimeout(() => {
                ayuda.className = 'field-inline-help';
                campo.classList.remove('input-error');
            }, 2800);
        };

        campo.addEventListener('input', () => {
            const original = campo.value;
            const limpio = original.replace(noPermitidos, '').replace(/\s{2,}/g, ' ');
            if (limpio !== original) {
                avisar('Solo se permiten letras en este campo.');
                campo.value = limpio;
            }
        });
    });
}

/**
 * Consulta el DNI en SUNAT mediante consulta_dni.php y completa
 * automáticamente los nombres y apellidos.
 * Se ejecuta sola al llegar a 8 dígitos o al pulsar "Buscar DNI".
 */
let dniConsultado = '';
let consultaDniEnCurso = false;
let temporizadorDni = null;

async function buscarDni(forzar = false) {
    const inputDni = document.getElementById('dni');
    const estado = document.getElementById('dniEstado');
    const inputNombres = document.getElementById('nomp');
    const inputApellidos = document.getElementById('apep');
    const boton = document.getElementById('btnBuscarDni');

    if (!inputDni || !estado) return;

    const dni = inputDni.value.trim();

    if (!/^\d{8}$/.test(dni)) {
        if (forzar) {
            estado.className = 'field-help error';
            estado.textContent = 'Ingresa un DNI de 8 dígitos antes de buscar.';
            inputDni.focus();
        }
        return;
    }

    if (consultaDniEnCurso) return;
    if (!forzar && dni === dniConsultado) return;

    consultaDniEnCurso = true;
    estado.className = 'field-help';
    estado.textContent = 'Consultando DNI...';
    if (boton) {
        boton.disabled = true;
        boton.textContent = 'Buscando...';
    }

    try {
        const url = (window.RUTA_API_DNI || 'consulta_dni.php') + '?dni=' + encodeURIComponent(dni);
        const respuesta = await fetch(url, { headers: { 'Accept': 'application/json' } });

        if (!respuesta.ok) {
            throw new Error('Respuesta ' + respuesta.status);
        }

        const datos = await respuesta.json();

        if (datos.ok) {
            if (inputNombres) {
                inputNombres.value = (datos.nombres || '').slice(0, 40);
                inputNombres.readOnly = true;
            }
            if (inputApellidos) {
                inputApellidos.value = (datos.apellidos || '').slice(0, 40);
                inputApellidos.readOnly = true;
            }
            estado.className = 'field-help success';
            estado.textContent = 'DNI encontrado: ' + (datos.nombres || '') + ' ' + (datos.apellidos || '');
            dniConsultado = dni;
        } else {
            if (inputNombres) inputNombres.readOnly = false;
            if (inputApellidos) inputApellidos.readOnly = false;
            estado.className = 'field-help error';
            estado.textContent = (datos.mensaje || 'No encontramos datos para ese DNI.') + ' Puedes escribir tus nombres y apellidos manualmente.';
            dniConsultado = '';
        }
    } catch (error) {
        dniConsultado = '';
        const inputNombres = document.getElementById('nomp');
        const inputApellidos = document.getElementById('apep');
        if (inputNombres) inputNombres.readOnly = false;
        if (inputApellidos) inputApellidos.readOnly = false;
        estado.className = 'field-help error';
        estado.textContent = 'No se pudo consultar el DNI. Revisa tu conexión o escribe tus nombres y apellidos manualmente.';
    } finally {
        consultaDniEnCurso = false;
        if (boton) {
            boton.disabled = false;
            boton.textContent = 'Buscar DNI';
        }
    }
}

/**
 * Lanza la consulta automática al escribir los 8 dígitos del DNI.
 */
function configurarAutoConsultaDni() {
    const inputDni = document.getElementById('dni');
    const estado = document.getElementById('dniEstado');

    if (!inputDni) return;

    if (estado) {
        estado.className = 'field-help';
        estado.textContent = 'Escribe tus 8 números y buscaremos tus nombres y apellidos automáticamente. También puedes usar el botón «Buscar DNI».';
    }

    const limpiarNombres = () => {
        const n = document.getElementById('nomp');
        const a = document.getElementById('apep');
        if (n) { n.value = ''; n.readOnly = true; }
        if (a) { a.value = ''; a.readOnly = true; }
    };

    const intentar = (forzar = false, demora = 350) => {
        clearTimeout(temporizadorDni);
        const dni = inputDni.value.trim();
        if (dni.length < 8) {
            if (dniConsultado !== '' || dni.length < 8) {
                dniConsultado = '';
                limpiarNombres();
            }
            return;
        }
        if (/^\d{8}$/.test(dni)) {
            if (dni !== dniConsultado) {
                limpiarNombres();
            }
            if (forzar || dni !== dniConsultado) {
                temporizadorDni = setTimeout(() => buscarDni(forzar), demora);
            }
        }
    };

    inputDni.addEventListener('input', () => intentar(false, 350));
    inputDni.addEventListener('change', () => intentar(false, 0));
    inputDni.addEventListener('paste', () => setTimeout(() => intentar(false, 100), 0));

    inputDni.addEventListener('blur', () => {
        clearTimeout(temporizadorDni);
        const dni = inputDni.value.trim();
        if (/^\d{8}$/.test(dni) && dni !== dniConsultado) {
            buscarDni();
        }
    });

    // Si el campo ya tiene 8 dígitos (autocompletado, recarga, etc.)
    // Cuando el formulario vuelve con datos restaurados (por ejemplo tras un
    // error de teléfono), se conservan los nombres ya obtenidos y no se
    // vuelve a consultar el DNI para no borrarlos si la consulta falla.
    if (/^\d{8}$/.test(inputDni.value.trim())) {
        const nomActual = (document.getElementById('nomp')?.value || '').trim();
        const apeActual = (document.getElementById('apep')?.value || '').trim();
        if (nomActual !== '' && apeActual !== '') {
            dniConsultado = inputDni.value.trim();
        } else {
            setTimeout(() => intentar(true, 0), 200);
        }
    }

    // En registro, los nombres se obtienen por DNI: bloquear envío si faltan.
    const form = inputDni.form;
    if (form && (document.getElementById('nomp')?.hasAttribute('readonly') || document.getElementById('apep')?.hasAttribute('readonly'))) {
        form.addEventListener('submit', (e) => {
            const nom = (document.getElementById('nomp')?.value || '').trim();
            const ape = (document.getElementById('apep')?.value || '').trim();
            const dni = inputDni.value.trim();
            if (!nom || !ape) {
                e.preventDefault();
                estado.className = 'field-help error';
                estado.textContent = /^\d{8}$/.test(dni)
                    ? 'Escribe tu DNI de 8 números y espera a que aparezcan tus nombres. Si no aparecen, escríbelos manualmente.'
                    : 'Escribe tu DNI de 8 números para buscar tus nombres.';
                inputDni.focus();
            }
        });
    }
}

/**
 * Reemplaza los <select> nativos por un desplegable con el diseño del sistema.
 * El <select> original se conserva oculto para que el formulario siga enviando
 * su valor y para que funcionen los onchange existentes.
 */
let idOpcionSelect = 0;

function mejorarSelects() {
    document.querySelectorAll('select').forEach((select) => {
        if (select.dataset.mejorado || select.multiple || select.size > 1) return;
        select.dataset.mejorado = '1';

        const eraRequerido = select.required;
        if (eraRequerido) {
            select.dataset.requerido = '1';
            select.required = false;
        }

        const contenedor = document.createElement('div');
        contenedor.className = 'select-custom';
        select.parentNode.insertBefore(contenedor, select);
        contenedor.appendChild(select);
        select.classList.add('select-native');

        const boton = document.createElement('button');
        boton.type = 'button';
        boton.className = 'select-trigger';
        boton.setAttribute('aria-haspopup', 'listbox');
        boton.setAttribute('aria-expanded', 'false');
        boton.innerHTML = '<span class="select-value"></span>' +
            '<svg class="select-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" ' +
            'stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
            '<path d="m6 9 6 6 6-6"/></svg>';

        const menu = document.createElement('ul');
        menu.className = 'select-menu';
        menu.setAttribute('role', 'listbox');

        contenedor.appendChild(boton);
        contenedor.appendChild(menu);

        const valor = boton.querySelector('.select-value');
        let indiceActivo = -1;

        const actualizarBoton = () => {
            const opcion = select.options[select.selectedIndex];
            valor.textContent = opcion ? opcion.textContent.trim() : '';
            boton.classList.toggle('placeholder', select.value === '');
            boton.disabled = select.disabled;
        };

        const cerrar = () => {
            contenedor.classList.remove('open');
            boton.setAttribute('aria-expanded', 'false');
            boton.removeAttribute('aria-activedescendant');
            menu.innerHTML = '';
            indiceActivo = -1;
        };

        const marcarActivo = (indice) => {
            const opciones = Array.from(menu.children);
            if (!opciones.length) return;

            indiceActivo = Math.max(0, Math.min(indice, opciones.length - 1));
            opciones.forEach((li, i) => li.classList.toggle('activa', i === indiceActivo));

            const activa = opciones[indiceActivo];
            if (activa) {
                const arriba = activa.offsetTop;
                const abajo = arriba + activa.offsetHeight;
                if (arriba < menu.scrollTop) {
                    menu.scrollTop = arriba;
                } else if (abajo > menu.scrollTop + menu.clientHeight) {
                    menu.scrollTop = abajo - menu.clientHeight;
                }
                boton.setAttribute('aria-activedescendant', activa.id);
            }
        };

        const elegir = (indice) => {
            const opcion = select.options[indice];
            if (!opcion || opcion.disabled) return;

            select.selectedIndex = indice;
            actualizarBoton();
            select.dispatchEvent(new Event('change', { bubbles: true }));
            cerrar();
            boton.focus();
        };

        const construirMenu = () => {
            menu.innerHTML = '';

            Array.from(select.options).forEach((opcion, indice) => {
                const li = document.createElement('li');
                li.className = 'select-option';
                li.id = 'select-opcion-' + (++idOpcionSelect);
                li.setAttribute('role', 'option');
                li.textContent = opcion.textContent.trim();

                if (opcion.disabled) {
                    li.classList.add('disabled');
                    li.setAttribute('aria-disabled', 'true');
                }
                if (opcion.selected) {
                    li.classList.add('selected');
                    li.setAttribute('aria-selected', 'true');
                }

                li.addEventListener('click', () => elegir(indice));
                li.addEventListener('mouseenter', () => {
                    if (!opcion.disabled) marcarActivo(indice);
                });

                menu.appendChild(li);
            });
        };

        const abrir = () => {
            if (boton.disabled) return;

            document.querySelectorAll('.select-custom.open').forEach((otro) => {
                if (otro !== contenedor) {
                    otro.classList.remove('open');
                    const otroBoton = otro.querySelector('.select-trigger');
                    if (otroBoton) otroBoton.setAttribute('aria-expanded', 'false');
                    const otroMenu = otro.querySelector('.select-menu');
                    if (otroMenu) otroMenu.innerHTML = '';
                }
            });

            construirMenu();
            contenedor.classList.add('open');
            boton.setAttribute('aria-expanded', 'true');
            marcarActivo(select.selectedIndex >= 0 ? select.selectedIndex : 0);
        };

        boton.addEventListener('click', (evento) => {
            evento.stopPropagation();
            if (contenedor.classList.contains('open')) {
                cerrar();
            } else {
                abrir();
            }
        });

        boton.addEventListener('keydown', (evento) => {
            const abierto = contenedor.classList.contains('open');

            if (!abierto && ['ArrowDown', 'ArrowUp', 'Enter', ' '].includes(evento.key)) {
                evento.preventDefault();
                abrir();
                return;
            }

            if (!abierto) return;

            const opciones = Array.from(menu.children);

            if (evento.key === 'Escape') {
                evento.preventDefault();
                cerrar();
            } else if (evento.key === 'ArrowDown') {
                evento.preventDefault();
                marcarActivo(indiceActivo + 1);
            } else if (evento.key === 'ArrowUp') {
                evento.preventDefault();
                marcarActivo(indiceActivo - 1);
            } else if (evento.key === 'Home') {
                evento.preventDefault();
                marcarActivo(0);
            } else if (evento.key === 'End') {
                evento.preventDefault();
                marcarActivo(opciones.length - 1);
            } else if (evento.key === 'Enter' || evento.key === ' ') {
                evento.preventDefault();
                elegir(indiceActivo);
            } else if (evento.key === 'Tab') {
                cerrar();
            }
        });

        document.addEventListener('click', (evento) => {
            if (!contenedor.contains(evento.target)) cerrar();
        });

        select.addEventListener('change', actualizarBoton);

        // Validación para los selects que eran "required".
        const formulario = select.form;
        if (formulario && !formulario.dataset.validaSelects) {
            formulario.dataset.validaSelects = '1';
            formulario.addEventListener('submit', (evento) => {
                const faltantes = formulario.querySelectorAll('select[data-requerido="1"]');
                let invalido = null;

                faltantes.forEach((campo) => {
                    if (campo.value === '' && !invalido) invalido = campo;
                });

                if (invalido) {
                    evento.preventDefault();
                    const disparador = invalido.closest('.select-custom')?.querySelector('.select-trigger');
                    if (disparador) {
                        disparador.classList.add('input-error');
                        disparador.focus();
                    }
                }
            });
        }

        select.addEventListener('change', () => {
            const disparador = contenedor.querySelector('.select-trigger');
            if (disparador) disparador.classList.remove('input-error');
        });

        actualizarBoton();
    });
}

/* ============================================================
   ACCESIBILIDAD PARA ADULTOS MAYORES
   Letra más grande, alto contraste, foco visible y botón para
   mostrar la contraseña. Las preferencias se guardan en el navegador.
   ============================================================ */
const ACC_CLAVE = 'primelab_accesibilidad_v1';
const ACC_ESCALAS = ['normal', 'grande', 'muy-grande'];
const ACC_ETIQUETAS = {
    'normal': 'Letra normal',
    'grande': 'Letra grande',
    'muy-grande': 'Letra muy grande'
};

/** Lee las preferencias guardadas (si el navegador las permite). */
function accLeerPreferencias() {
    try {
        const datos = JSON.parse(localStorage.getItem(ACC_CLAVE) || '{}');
        return {
            escala: ACC_ESCALAS.includes(datos.escala) ? datos.escala : 'normal',
            contraste: datos.contraste === true
        };
    } catch (error) {
        return { escala: 'normal', contraste: false };
    }
}

/** Guarda las preferencias en el navegador. */
function accGuardarPreferencias(preferencias) {
    try {
        localStorage.setItem(ACC_CLAVE, JSON.stringify(preferencias));
    } catch (error) {
        /* Si el navegador bloquea el almacenamiento, se ignora. */
    }
}

/** Aplica el tamaño de letra y el contraste al documento. */
function accAplicarPreferencias(preferencias) {
    const body = document.body;
    if (!body) return;
    body.classList.remove('texto-grande', 'texto-muy-grande');
    if (preferencias.escala === 'grande') body.classList.add('texto-grande');
    if (preferencias.escala === 'muy-grande') body.classList.add('texto-muy-grande');
    body.classList.toggle('alto-contraste', preferencias.contraste);
}

/** Crea la barra flotante de accesibilidad en todas las páginas. */
function configurarAccesibilidad() {
    if (!document.body || document.querySelector('.barra-accesibilidad')) return;

    let preferencias = accLeerPreferencias();
    accAplicarPreferencias(preferencias);

    const barra = document.createElement('div');
    barra.className = 'barra-accesibilidad';
    barra.innerHTML =
        '<div class="panel-accesibilidad" id="panelAccesibilidad" role="dialog" aria-label="Opciones de accesibilidad">' +
            '<p class="panel-accesibilidad-title">Accesibilidad</p>' +
            '<p class="panel-accesibilidad-help">Ajusta el tamaño de la letra y el contraste para leer con más comodidad.</p>' +
            '<div class="acc-fila">' +
                '<button type="button" class="acc-opcion" data-accion="menos" aria-label="Reducir el tamaño de la letra">A−</button>' +
                '<span class="acc-estado" id="accEstado" aria-live="polite">Letra normal</span>' +
                '<button type="button" class="acc-opcion" data-accion="mas" aria-label="Aumentar el tamaño de la letra">A+</button>' +
            '</div>' +
            '<button type="button" class="acc-opcion acc-ancho" data-accion="contraste" aria-pressed="false">Alto contraste</button>' +
            '<button type="button" class="acc-opcion acc-ancho" data-accion="reiniciar">Restablecer</button>' +
        '</div>' +
        '<button type="button" class="boton-accesibilidad" id="btnAccesibilidad" aria-expanded="false" aria-controls="panelAccesibilidad">' +
            '<span aria-hidden="true">♿</span> Accesibilidad' +
        '</button>';
    document.body.appendChild(barra);

    const panel = barra.querySelector('#panelAccesibilidad');
    const boton = barra.querySelector('#btnAccesibilidad');
    const estado = barra.querySelector('#accEstado');
    const botonContraste = barra.querySelector('[data-accion="contraste"]');

    const refrescar = () => {
        accAplicarPreferencias(preferencias);
        accGuardarPreferencias(preferencias);
        if (estado) estado.textContent = ACC_ETIQUETAS[preferencias.escala];
        if (botonContraste) {
            botonContraste.setAttribute('aria-pressed', String(preferencias.contraste));
            botonContraste.classList.toggle('activo', preferencias.contraste);
        }
    };

    const cerrarPanel = () => {
        panel.classList.remove('abierto');
        boton.setAttribute('aria-expanded', 'false');
    };

    boton.addEventListener('click', () => {
        const abierto = panel.classList.toggle('abierto');
        boton.setAttribute('aria-expanded', String(abierto));
    });

    barra.addEventListener('click', (evento) => {
        const opcion = evento.target.closest('[data-accion]');
        if (!opcion) return;
        const indice = ACC_ESCALAS.indexOf(preferencias.escala);

        if (opcion.dataset.accion === 'mas') {
            preferencias.escala = ACC_ESCALAS[Math.min(indice + 1, ACC_ESCALAS.length - 1)];
        } else if (opcion.dataset.accion === 'menos') {
            preferencias.escala = ACC_ESCALAS[Math.max(indice - 1, 0)];
        } else if (opcion.dataset.accion === 'contraste') {
            preferencias.contraste = !preferencias.contraste;
        } else if (opcion.dataset.accion === 'reiniciar') {
            preferencias = { escala: 'normal', contraste: false };
        }
        refrescar();
    });

    document.addEventListener('click', (evento) => {
        if (!barra.contains(evento.target)) cerrarPanel();
    });

    document.addEventListener('keydown', (evento) => {
        if (evento.key === 'Escape' && panel.classList.contains('abierto')) {
            cerrarPanel();
            boton.focus();
        }
    });

    refrescar();
}

/** Agrega un botón «Mostrar / Ocultar» a los campos de contraseña. */
function configurarVerContrasena() {
    document.querySelectorAll('input[type="password"]').forEach((input) => {
        if (input.dataset.verClave === '1') return;
        input.dataset.verClave = '1';

        const contenedor = document.createElement('div');
        contenedor.className = 'campo-clave';
        input.parentNode.insertBefore(contenedor, input);
        contenedor.appendChild(input);

        const boton = document.createElement('button');
        boton.type = 'button';
        boton.className = 'boton-ver-clave';
        boton.textContent = 'Mostrar';
        boton.setAttribute('aria-pressed', 'false');
        boton.setAttribute('aria-label', 'Mostrar u ocultar la contraseña');
        contenedor.appendChild(boton);

        boton.addEventListener('click', () => {
            const estabaOculta = input.type === 'password';
            input.type = estabaOculta ? 'text' : 'password';
            boton.textContent = estabaOculta ? 'Ocultar' : 'Mostrar';
            boton.setAttribute('aria-pressed', String(estabaOculta));
        });
    });
}

/** Enlace «Saltar al contenido» para navegación con teclado. */
function configurarSaltarContenido() {
    const destino = document.querySelector('main') || document.querySelector('.content');
    if (!destino) return;
    if (!destino.id) destino.id = 'contenido-principal';

    const enlace = document.createElement('a');
    enlace.className = 'saltar-contenido';
    enlace.href = '#' + destino.id;
    enlace.textContent = 'Saltar al contenido principal';
    document.body.insertBefore(enlace, document.body.firstChild);
}

document.addEventListener('DOMContentLoaded', () => {
    configurarEntorno();
    mostrarMensajeUrl();
    configurarCamposNumericos();
    configurarCamposTexto();
    configurarAutoConsultaDni();
    mejorarSelects();
    configurarAccesibilidad();
    configurarVerContrasena();
    configurarSaltarContenido();
});
````

## `datos/usuarios.json`

````json
{
  "usuarios": [
    {
      "id": 1,
      "nombres": "Ana",
      "apellidos": "García Chávez",
      "dni": "10000000",
      "telefono": "900100000",
      "correo": "usuario01@example.com",
      "contrasena": "Demo0001!"
    },
    {
      "id": 2,
      "nombres": "Luis",
      "apellidos": "Rodríguez Ortiz",
      "dni": "10000001",
      "telefono": "900100001",
      "correo": "usuario02@example.com",
      "contrasena": "Demo0002!"
    },
    {
      "id": 3,
      "nombres": "María",
      "apellidos": "Pérez Silva",
      "dni": "10000002",
      "telefono": "900100002",
      "correo": "usuario03@example.com",
      "contrasena": "Demo0003!"
    },
    {
      "id": 4,
      "nombres": "Carlos",
      "apellidos": "López Cárdenas",
      "dni": "10000003",
      "telefono": "900100003",
      "correo": "usuario04@example.com",
      "contrasena": "Demo0004!"
    },
    {
      "id": 5,
      "nombres": "Sofía",
      "apellidos": "Torres Cabrera",
      "dni": "10000004",
      "telefono": "900100004",
      "correo": "usuario05@example.com",
      "contrasena": "Demo0005!"
    },
    {
      "id": 6,
      "nombres": "José",
      "apellidos": "Flores Tapia",
      "dni": "10000005",
      "telefono": "900100005",
      "correo": "usuario06@example.com",
      "contrasena": "Demo0006!"
    },
    {
      "id": 7,
      "nombres": "Valeria",
      "apellidos": "Ramírez Flores",
      "dni": "10000006",
      "telefono": "900100006",
      "correo": "usuario07@example.com",
      "contrasena": "Demo0007!"
    },
    {
      "id": 8,
      "nombres": "Miguel",
      "apellidos": "Sánchez Mendoza",
      "dni": "10000007",
      "telefono": "900100007",
      "correo": "usuario08@example.com",
      "contrasena": "Demo0008!"
    },
    {
      "id": 9,
      "nombres": "Camila",
      "apellidos": "Díaz Morales",
      "dni": "10000008",
      "telefono": "900100008",
      "correo": "usuario09@example.com",
      "contrasena": "Demo0009!"
    },
    {
      "id": 10,
      "nombres": "Jorge",
      "apellidos": "Castro Huamán",
      "dni": "10000009",
      "telefono": "900100009",
      "correo": "usuario10@example.com",
      "contrasena": "Demo0010!"
    },
    {
      "id": 11,
      "nombres": "Lucía",
      "apellidos": "Vargas Salazar",
      "dni": "10000010",
      "telefono": "900100010",
      "correo": "usuario11@example.com",
      "contrasena": "Demo0011!"
    },
    {
      "id": 12,
      "nombres": "Diego",
      "apellidos": "Rojas Valdez",
      "dni": "10000011",
      "telefono": "900100011",
      "correo": "usuario12@example.com",
      "contrasena": "Demo0012!"
    },
    {
      "id": 13,
      "nombres": "Daniela",
      "apellidos": "Mendoza Palacios",
      "dni": "10000012",
      "telefono": "900100012",
      "correo": "usuario13@example.com",
      "contrasena": "Demo0013!"
    },
    {
      "id": 14,
      "nombres": "Pedro",
      "apellidos": "Chávez Torres",
      "dni": "10000013",
      "telefono": "900100013",
      "correo": "usuario14@example.com",
      "contrasena": "Demo0014!"
    },
    {
      "id": 15,
      "nombres": "Andrea",
      "apellidos": "Ruiz Rojas",
      "dni": "10000014",
      "telefono": "900100014",
      "correo": "usuario15@example.com",
      "contrasena": "Demo0015!"
    },
    {
      "id": 16,
      "nombres": "Ricardo",
      "apellidos": "Herrera Reyes",
      "dni": "10000015",
      "telefono": "900100015",
      "correo": "usuario16@example.com",
      "contrasena": "Demo0016!"
    },
    {
      "id": 17,
      "nombres": "Paola",
      "apellidos": "Medina Quispe",
      "dni": "10000016",
      "telefono": "900100016",
      "correo": "usuario17@example.com",
      "contrasena": "Demo0017!"
    },
    {
      "id": 18,
      "nombres": "Fernando",
      "apellidos": "Cruz Navarro",
      "dni": "10000017",
      "telefono": "900100017",
      "correo": "usuario18@example.com",
      "contrasena": "Demo0018!"
    },
    {
      "id": 19,
      "nombres": "Gabriela",
      "apellidos": "Reyes Peña",
      "dni": "10000018",
      "telefono": "900100018",
      "correo": "usuario19@example.com",
      "contrasena": "Demo0019!"
    },
    {
      "id": 20,
      "nombres": "Alonso",
      "apellidos": "Morales Suárez",
      "dni": "10000019",
      "telefono": "900100019",
      "correo": "usuario20@example.com",
      "contrasena": "Demo0020!"
    },
    {
      "id": 21,
      "nombres": "Claudia",
      "apellidos": "Ortiz López",
      "dni": "10000020",
      "telefono": "900100020",
      "correo": "usuario21@example.com",
      "contrasena": "Demo0021!"
    },
    {
      "id": 22,
      "nombres": "Martín",
      "apellidos": "Gutiérrez Vargas",
      "dni": "10000021",
      "telefono": "900100021",
      "correo": "usuario22@example.com",
      "contrasena": "Demo0022!"
    },
    {
      "id": 23,
      "nombres": "Natalia",
      "apellidos": "Vega Cruz",
      "dni": "10000022",
      "telefono": "900100022",
      "correo": "usuario23@example.com",
      "contrasena": "Demo0023!"
    },
    {
      "id": 24,
      "nombres": "Ángel",
      "apellidos": "Ramos Paredes",
      "dni": "10000023",
      "telefono": "900100023",
      "correo": "usuario24@example.com",
      "contrasena": "Demo0024!"
    },
    {
      "id": 25,
      "nombres": "Rosa",
      "apellidos": "Paredes Fernández",
      "dni": "10000024",
      "telefono": "900100024",
      "correo": "usuario25@example.com",
      "contrasena": "Demo0025!"
    },
    {
      "id": 26,
      "nombres": "Kevin",
      "apellidos": "Quispe Molina",
      "dni": "10000025",
      "telefono": "900100025",
      "correo": "usuario26@example.com",
      "contrasena": "Demo0026!"
    },
    {
      "id": 27,
      "nombres": "Fiorella",
      "apellidos": "Huamán Miranda",
      "dni": "10000026",
      "telefono": "900100026",
      "correo": "usuario27@example.com",
      "contrasena": "Demo0027!"
    },
    {
      "id": 28,
      "nombres": "Bruno",
      "apellidos": "Silva Pérez",
      "dni": "10000027",
      "telefono": "900100027",
      "correo": "usuario28@example.com",
      "contrasena": "Demo0028!"
    },
    {
      "id": 29,
      "nombres": "Patricia",
      "apellidos": "Espinoza Castro",
      "dni": "10000028",
      "telefono": "900100028",
      "correo": "usuario29@example.com",
      "contrasena": "Demo0029!"
    },
    {
      "id": 30,
      "nombres": "Andrés",
      "apellidos": "Campos Medina",
      "dni": "10000029",
      "telefono": "900100029",
      "correo": "usuario30@example.com",
      "contrasena": "Demo0030!"
    },
    {
      "id": 31,
      "nombres": "Milagros",
      "apellidos": "Aguilar Ramos",
      "dni": "10000030",
      "telefono": "900100030",
      "correo": "usuario31@example.com",
      "contrasena": "Demo0031!"
    },
    {
      "id": 32,
      "nombres": "Eduardo",
      "apellidos": "Fernández Aguilar",
      "dni": "10000031",
      "telefono": "900100031",
      "correo": "usuario32@example.com",
      "contrasena": "Demo0032!"
    },
    {
      "id": 33,
      "nombres": "Mónica",
      "apellidos": "Navarro Acosta",
      "dni": "10000032",
      "telefono": "900100032",
      "correo": "usuario33@example.com",
      "contrasena": "Demo0033!"
    },
    {
      "id": 34,
      "nombres": "Sebastián",
      "apellidos": "Salazar León",
      "dni": "10000033",
      "telefono": "900100033",
      "correo": "usuario34@example.com",
      "contrasena": "Demo0034!"
    },
    {
      "id": 35,
      "nombres": "Ximena",
      "apellidos": "Cárdenas Rodríguez",
      "dni": "10000034",
      "telefono": "900100034",
      "correo": "usuario35@example.com",
      "contrasena": "Demo0035!"
    },
    {
      "id": 36,
      "nombres": "Raúl",
      "apellidos": "Delgado Díaz",
      "dni": "10000035",
      "telefono": "900100035",
      "correo": "usuario36@example.com",
      "contrasena": "Demo0036!"
    },
    {
      "id": 37,
      "nombres": "Isabel",
      "apellidos": "Romero Herrera",
      "dni": "10000036",
      "telefono": "900100036",
      "correo": "usuario37@example.com",
      "contrasena": "Demo0037!"
    },
    {
      "id": 38,
      "nombres": "Franco",
      "apellidos": "Acosta Vega",
      "dni": "10000037",
      "telefono": "900100037",
      "correo": "usuario38@example.com",
      "contrasena": "Demo0038!"
    },
    {
      "id": 39,
      "nombres": "Elena",
      "apellidos": "Molina Campos",
      "dni": "10000038",
      "telefono": "900100038",
      "correo": "usuario39@example.com",
      "contrasena": "Demo0039!"
    },
    {
      "id": 40,
      "nombres": "Marco",
      "apellidos": "Peña Romero",
      "dni": "10000039",
      "telefono": "900100039",
      "correo": "usuario40@example.com",
      "contrasena": "Demo0040!"
    },
    {
      "id": 41,
      "nombres": "Carmen",
      "apellidos": "Valdez Fuentes",
      "dni": "10000040",
      "telefono": "900100040",
      "correo": "usuario41@example.com",
      "contrasena": "Demo0041!"
    },
    {
      "id": 42,
      "nombres": "Óscar",
      "apellidos": "Cabrera García",
      "dni": "10000041",
      "telefono": "900100041",
      "correo": "usuario42@example.com",
      "contrasena": "Demo0042!"
    },
    {
      "id": 43,
      "nombres": "Adriana",
      "apellidos": "Bravo Sánchez",
      "dni": "10000042",
      "telefono": "900100042",
      "correo": "usuario43@example.com",
      "contrasena": "Demo0043!"
    },
    {
      "id": 44,
      "nombres": "Piero",
      "apellidos": "Fuentes Ruiz",
      "dni": "10000043",
      "telefono": "900100043",
      "correo": "usuario44@example.com",
      "contrasena": "Demo0044!"
    },
    {
      "id": 45,
      "nombres": "Silvia",
      "apellidos": "León Gutiérrez",
      "dni": "10000044",
      "telefono": "900100044",
      "correo": "usuario45@example.com",
      "contrasena": "Demo0045!"
    },
    {
      "id": 46,
      "nombres": "Renzo",
      "apellidos": "Miranda Espinoza",
      "dni": "10000045",
      "telefono": "900100045",
      "correo": "usuario46@example.com",
      "contrasena": "Demo0046!"
    },
    {
      "id": 47,
      "nombres": "Teresa",
      "apellidos": "Suárez Delgado",
      "dni": "10000046",
      "telefono": "900100046",
      "correo": "usuario47@example.com",
      "contrasena": "Demo0047!"
    },
    {
      "id": 48,
      "nombres": "Gustavo",
      "apellidos": "Palacios Bravo",
      "dni": "10000047",
      "telefono": "900100047",
      "correo": "usuario48@example.com",
      "contrasena": "Demo0048!"
    },
    {
      "id": 49,
      "nombres": "Juliana",
      "apellidos": "Tapia Núñez",
      "dni": "10000048",
      "telefono": "900100048",
      "correo": "usuario49@example.com",
      "contrasena": "Demo0049!"
    },
    {
      "id": 50,
      "nombres": "Héctor",
      "apellidos": "Núñez Ramírez",
      "dni": "10000049",
      "telefono": "900100049",
      "correo": "usuario50@example.com",
      "contrasena": "Demo0050!"
    },
    {
      "id": 51,
      "nombres": "Ademir Andrew Alberto",
      "apellidos": "Sanchez Chinchay",
      "dni": "61216468",
      "telefono": "972135089",
      "correo": "estu61216468@institutobolivar.edu.pe",
      "contrasena": "61216468"
    },
    {
      "id": 52,
      "nombres": "Fabbio Giuliano Abedi",
      "apellidos": "Flores Padilla",
      "dni": "74519247",
      "telefono": "977473318",
      "correo": "estu74519247@institutobolivar.edu.pe",
      "contrasena": "74519247"
    },
    {
      "id": 53,
      "nombres": "Alexandro David",
      "apellidos": "Villachica Rodriguez",
      "dni": "72850117",
      "telefono": "987751145",
      "correo": "estu72850117@institutobolivar.edu.pe",
      "contrasena": "72850117"
    },
    {
      "id": 54,
      "nombres": "Leandro Francesco",
      "apellidos": "Alvarez Marres",
      "dni": "75532819",
      "telefono": "902323479",
      "correo": "estu75532819@institutobolivar.edu.pe",
      "contrasena": "75532819"
    },
    {
      "id": 55,
      "nombres": "Humberto Vicente",
      "apellidos": "Portal Retamoso",
      "dni": "72484597",
      "telefono": "969424717",
      "correo": "estu72484597@institutobolivar.edu.pe",
      "contrasena": "72484597"
    },
    {
      "id": 56,
      "nombres": "Estuar Sebastian",
      "apellidos": "Aparco Bedia",
      "dni": "72689307",
      "telefono": "904866021",
      "correo": "estu72689307@institutobolivar.edu.pe",
      "contrasena": "72689307"
    },
    {
      "id": 57,
      "nombres": "Eidan",
      "apellidos": "Valencia Zamora",
      "dni": "61161158",
      "telefono": "994784726",
      "correo": "estu61161158@institutobolivar.edu.pe",
      "contrasena": "61161158"
    }
  ]
}
````

## `API_Citas_Medicas/server.js`

````javascript
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
````

## `API_Citas_Medicas/package.json`

````json
{
  "name": "api-citas-medicas-usuarios",
  "version": "1.0.0",
  "description": "Adaptación educativa de una API REST de pedidos a pacientes de un sistema de citas médicas",
  "main": "server.js",
  "scripts": {"start": "node server.js", "dev": "node --watch server.js"},
  "dependencies": {"express": "^4.21.1"}
}
````

## `API_Citas_Medicas/README.md`

````markdown
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
````

## `README.md`

````markdown
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
├── admin_login.html          Acceso administrativo
├── admin_citas.php           Gestión de citas
├── admin_consultas.php       Consultas del sistema
├── admin_medicos.php         Gestión de médicos
├── admin_centros.php         Gestión de centros
├── index.html                Acceso de pacientes
├── registro.html / .php      Registro de pacientes
├── login_paciente.php        Validación de pacientes
├── login.php                 Validación de administradores
├── medico_login.html         Acceso de médicos
├── login_medico.php          Validación por CMP
├── panel.php                 Panel del paciente
├── panel_medico.php          Panel y gestión clínica del médico
├── reservar.php              Reserva de citas
├── mis_citas.php             Historial del paciente
├── ticket.php                Comprobante de la cita
├── perfil.php                Perfil del paciente
├── consultas.php             Consultas médicas
├── consulta_dni.php          Endpoint JSON de consulta de DNI
├── file_conexion.php         Conexión, autenticación y auto-reparación
├── config.php                Configuración portable
├── BD/
│   ├── bd_primelab.sql       Base completa (64 pacientes, 17 médicos, 3 admins)
│   ├── migracion_v2.sql
│   ├── migracion_v3_datos.sql
│   └── backup_antes_actualizacion.sql
├── datos/
│   └── usuarios.json         Directorio local para consultar DNI
├── API_Citas_Medicas/        API opcional (Node.js + Express)
├── css/style.css
├── js/main.js
├── img/                      Logotipo y avatares
├── Usuarios_PrimeLab.xlsx
├── Documentacion_Proceso_PrimeLab.docx
└── README.md
```

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
`file_conexion.php` ejecuta `asegurarEsquemaPaciente()`, `asegurarEsquemaCita()` y `asegurarAdministradores()` al conectarse. Esto permite abrir una base creada con una versión anterior sin errores fatales y sin reimportar manualmente.

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
````

## `LEEME_ENTREGA.md`

````markdown
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
````

## `img/medicos/MED001.svg`

````xml
<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120' role='img'>
  <rect width='120' height='120' rx='24' fill='#d9efe9'/>
  <circle cx='60' cy='46' r='22' fill='#f2c9a0'/>
  <path d='M30 112c3-22 17-34 30-34s27 12 30 34' fill='#2f8f83'/>
  <rect x='50' y='66' width='20' height='12' rx='4' fill='#f2c9a0'/>
  <path d='M38 46c0-14 10-24 22-24s22 10 22 24c0 2 0 4-1 6-5-6-12-10-21-10s-16 4-21 10c-1-2-1-4-1-6z' fill='#5b4636'/>
  <circle cx='52' cy='48' r='2.6' fill='#2b2b2b'/><circle cx='68' cy='48' r='2.6' fill='#2b2b2b'/>
  <path d='M53 57c4 4 10 4 14 0' stroke='#8a5a4a' stroke-width='3' fill='none' stroke-linecap='round'/>
  <circle cx='60' cy='84' r='4' fill='#ffffff' fill-opacity='0.85'/>
</svg>
````

## `img/medicos/MED002.svg`

````xml
<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120' role='img'>
  <rect width='120' height='120' rx='24' fill='#e6eef7'/>
  <circle cx='60' cy='46' r='22' fill='#e7b98f'/>
  <path d='M30 112c3-22 17-34 30-34s27 12 30 34' fill='#3b6ea8'/>
  <rect x='50' y='66' width='20' height='12' rx='4' fill='#e7b98f'/>
  <path d='M38 46c0-14 10-24 22-24s22 10 22 24c0 2 0 4-1 6-5-6-12-10-21-10s-16 4-21 10c-1-2-1-4-1-6z' fill='#2f2f2f'/>
  <circle cx='52' cy='48' r='2.6' fill='#2b2b2b'/><circle cx='68' cy='48' r='2.6' fill='#2b2b2b'/>
  <path d='M53 57c4 4 10 4 14 0' stroke='#8a5a4a' stroke-width='3' fill='none' stroke-linecap='round'/>
  <circle cx='60' cy='84' r='4' fill='#ffffff' fill-opacity='0.85'/>
</svg>
````

## `img/medicos/MED003.svg`

````xml
<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120' role='img'>
  <rect width='120' height='120' rx='24' fill='#f7e8f0'/>
  <circle cx='60' cy='46' r='22' fill='#f0c8a8'/>
  <path d='M30 112c3-22 17-34 30-34s27 12 30 34' fill='#9b5c7a'/>
  <rect x='50' y='66' width='20' height='12' rx='4' fill='#f0c8a8'/>
  <path d='M38 46c0-14 10-24 22-24s22 10 22 24c0 2 0 4-1 6-5-6-12-10-21-10s-16 4-21 10c-1-2-1-4-1-6z' fill='#3a2a22'/>
  <circle cx='52' cy='48' r='2.6' fill='#2b2b2b'/><circle cx='68' cy='48' r='2.6' fill='#2b2b2b'/>
  <path d='M53 57c4 4 10 4 14 0' stroke='#8a5a4a' stroke-width='3' fill='none' stroke-linecap='round'/>
  <circle cx='60' cy='84' r='4' fill='#ffffff' fill-opacity='0.85'/>
</svg>
````

## `img/medicos/MED004.svg`

````xml
<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120' role='img'>
  <rect width='120' height='120' rx='24' fill='#e9f2e7'/>
  <circle cx='60' cy='46' r='22' fill='#ddb18b'/>
  <path d='M30 112c3-22 17-34 30-34s27 12 30 34' fill='#4f7a4a'/>
  <rect x='50' y='66' width='20' height='12' rx='4' fill='#ddb18b'/>
  <path d='M38 46c0-14 10-24 22-24s22 10 22 24c0 2 0 4-1 6-5-6-12-10-21-10s-16 4-21 10c-1-2-1-4-1-6z' fill='#2b2b2b'/>
  <circle cx='52' cy='48' r='2.6' fill='#2b2b2b'/><circle cx='68' cy='48' r='2.6' fill='#2b2b2b'/>
  <path d='M53 57c4 4 10 4 14 0' stroke='#8a5a4a' stroke-width='3' fill='none' stroke-linecap='round'/>
  <circle cx='60' cy='84' r='4' fill='#ffffff' fill-opacity='0.85'/>
</svg>
````

## `img/medicos/MED005.svg`

````xml
<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120' role='img'>
  <rect width='120' height='120' rx='24' fill='#fff1e4'/>
  <circle cx='60' cy='46' r='22' fill='#f1cca8'/>
  <path d='M30 112c3-22 17-34 30-34s27 12 30 34' fill='#c97b52'/>
  <rect x='50' y='66' width='20' height='12' rx='4' fill='#f1cca8'/>
  <path d='M38 46c0-14 10-24 22-24s22 10 22 24c0 2 0 4-1 6-5-6-12-10-21-10s-16 4-21 10c-1-2-1-4-1-6z' fill='#6b4a2d'/>
  <circle cx='52' cy='48' r='2.6' fill='#2b2b2b'/><circle cx='68' cy='48' r='2.6' fill='#2b2b2b'/>
  <path d='M53 57c4 4 10 4 14 0' stroke='#8a5a4a' stroke-width='3' fill='none' stroke-linecap='round'/>
  <circle cx='60' cy='84' r='4' fill='#ffffff' fill-opacity='0.85'/>
</svg>
````

## `img/medicos/MED006.svg`

````xml
<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120' role='img'>
  <rect width='120' height='120' rx='24' fill='#eaf4f4'/>
  <circle cx='60' cy='46' r='22' fill='#e3b792'/>
  <path d='M30 112c3-22 17-34 30-34s27 12 30 34' fill='#2c7a7b'/>
  <rect x='50' y='66' width='20' height='12' rx='4' fill='#e3b792'/>
  <path d='M38 46c0-14 10-24 22-24s22 10 22 24c0 2 0 4-1 6-5-6-12-10-21-10s-16 4-21 10c-1-2-1-4-1-6z' fill='#3b3028'/>
  <circle cx='52' cy='48' r='2.6' fill='#2b2b2b'/><circle cx='68' cy='48' r='2.6' fill='#2b2b2b'/>
  <path d='M53 57c4 4 10 4 14 0' stroke='#8a5a4a' stroke-width='3' fill='none' stroke-linecap='round'/>
  <circle cx='60' cy='84' r='4' fill='#ffffff' fill-opacity='0.85'/>
</svg>
````

## `img/medicos/MED007.svg`

````xml
<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120' role='img'>
  <rect width='120' height='120' rx='24' fill='#f0ecfa'/>
  <circle cx='60' cy='46' r='22' fill='#f2cdb0'/>
  <path d='M30 112c3-22 17-34 30-34s27 12 30 34' fill='#6b5ca5'/>
  <rect x='50' y='66' width='20' height='12' rx='4' fill='#f2cdb0'/>
  <path d='M38 46c0-14 10-24 22-24s22 10 22 24c0 2 0 4-1 6-5-6-12-10-21-10s-16 4-21 10c-1-2-1-4-1-6z' fill='#4a3728'/>
  <circle cx='52' cy='48' r='2.6' fill='#2b2b2b'/><circle cx='68' cy='48' r='2.6' fill='#2b2b2b'/>
  <path d='M53 57c4 4 10 4 14 0' stroke='#8a5a4a' stroke-width='3' fill='none' stroke-linecap='round'/>
  <circle cx='60' cy='84' r='4' fill='#ffffff' fill-opacity='0.85'/>
</svg>
````

## `img/medicos/MED008.svg`

````xml
<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120' role='img'>
  <rect width='120' height='120' rx='24' fill='#e8f3ff'/>
  <circle cx='60' cy='46' r='22' fill='#ddb48f'/>
  <path d='M30 112c3-22 17-34 30-34s27 12 30 34' fill='#2f6fb3'/>
  <rect x='50' y='66' width='20' height='12' rx='4' fill='#ddb48f'/>
  <path d='M38 46c0-14 10-24 22-24s22 10 22 24c0 2 0 4-1 6-5-6-12-10-21-10s-16 4-21 10c-1-2-1-4-1-6z' fill='#2b2b2b'/>
  <circle cx='52' cy='48' r='2.6' fill='#2b2b2b'/><circle cx='68' cy='48' r='2.6' fill='#2b2b2b'/>
  <path d='M53 57c4 4 10 4 14 0' stroke='#8a5a4a' stroke-width='3' fill='none' stroke-linecap='round'/>
  <circle cx='60' cy='84' r='4' fill='#ffffff' fill-opacity='0.85'/>
</svg>
````

## `img/medicos/MED009.svg`

````xml
<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120' role='img'>
  <rect width='120' height='120' rx='24' fill='#fff0f3'/>
  <circle cx='60' cy='46' r='22' fill='#f0c7a5'/>
  <path d='M30 112c3-22 17-34 30-34s27 12 30 34' fill='#c85a7a'/>
  <rect x='50' y='66' width='20' height='12' rx='4' fill='#f0c7a5'/>
  <path d='M38 46c0-14 10-24 22-24s22 10 22 24c0 2 0 4-1 6-5-6-12-10-21-10s-16 4-21 10c-1-2-1-4-1-6z' fill='#4b3527'/>
  <circle cx='52' cy='48' r='2.6' fill='#2b2b2b'/><circle cx='68' cy='48' r='2.6' fill='#2b2b2b'/>
  <path d='M53 57c4 4 10 4 14 0' stroke='#8a5a4a' stroke-width='3' fill='none' stroke-linecap='round'/>
  <circle cx='60' cy='84' r='4' fill='#ffffff' fill-opacity='0.85'/>
</svg>
````

## `img/medicos/MED010.svg`

````xml
<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120' role='img'>
  <rect width='120' height='120' rx='24' fill='#eef7f1'/>
  <circle cx='60' cy='46' r='22' fill='#e2b58f'/>
  <path d='M30 112c3-22 17-34 30-34s27 12 30 34' fill='#4a8b6a'/>
  <rect x='50' y='66' width='20' height='12' rx='4' fill='#e2b58f'/>
  <path d='M38 46c0-14 10-24 22-24s22 10 22 24c0 2 0 4-1 6-5-6-12-10-21-10s-16 4-21 10c-1-2-1-4-1-6z' fill='#303030'/>
  <circle cx='52' cy='48' r='2.6' fill='#2b2b2b'/><circle cx='68' cy='48' r='2.6' fill='#2b2b2b'/>
  <path d='M53 57c4 4 10 4 14 0' stroke='#8a5a4a' stroke-width='3' fill='none' stroke-linecap='round'/>
  <circle cx='60' cy='84' r='4' fill='#ffffff' fill-opacity='0.85'/>
</svg>
````

## `img/medicos/MED011.svg`

````xml
<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120' role='img'>
  <rect width='120' height='120' rx='24' fill='#f3efff'/>
  <circle cx='60' cy='46' r='22' fill='#f2c9a8'/>
  <path d='M30 112c3-22 17-34 30-34s27 12 30 34' fill='#6f5aa6'/>
  <rect x='50' y='66' width='20' height='12' rx='4' fill='#f2c9a8'/>
  <path d='M38 46c0-14 10-24 22-24s22 10 22 24c0 2 0 4-1 6-5-6-12-10-21-10s-16 4-21 10c-1-2-1-4-1-6z' fill='#5a4030'/>
  <circle cx='52' cy='48' r='2.6' fill='#2b2b2b'/><circle cx='68' cy='48' r='2.6' fill='#2b2b2b'/>
  <path d='M53 57c4 4 10 4 14 0' stroke='#8a5a4a' stroke-width='3' fill='none' stroke-linecap='round'/>
  <circle cx='60' cy='84' r='4' fill='#ffffff' fill-opacity='0.85'/>
</svg>
````

## `img/medicos/MED012.svg`

````xml
<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120' role='img'>
  <rect width='120' height='120' rx='24' fill='#fff6ea'/>
  <circle cx='60' cy='46' r='22' fill='#e5b892'/>
  <path d='M30 112c3-22 17-34 30-34s27 12 30 34' fill='#b7793b'/>
  <rect x='50' y='66' width='20' height='12' rx='4' fill='#e5b892'/>
  <path d='M38 46c0-14 10-24 22-24s22 10 22 24c0 2 0 4-1 6-5-6-12-10-21-10s-16 4-21 10c-1-2-1-4-1-6z' fill='#2e2e2e'/>
  <circle cx='52' cy='48' r='2.6' fill='#2b2b2b'/><circle cx='68' cy='48' r='2.6' fill='#2b2b2b'/>
  <path d='M53 57c4 4 10 4 14 0' stroke='#8a5a4a' stroke-width='3' fill='none' stroke-linecap='round'/>
  <circle cx='60' cy='84' r='4' fill='#ffffff' fill-opacity='0.85'/>
</svg>
````

## `img/medicos/MED013.svg`

````xml
<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120' role='img'>
  <rect width='120' height='120' rx='24' fill='#e6f7ff'/>
  <circle cx='60' cy='46' r='22' fill='#e0b792'/>
  <path d='M30 112c3-22 17-34 30-34s27 12 30 34' fill='#2563eb'/>
  <rect x='50' y='66' width='20' height='12' rx='4' fill='#e0b792'/>
  <path d='M38 46c0-14 10-24 22-24s22 10 22 24c0 2 0 4-1 6-5-6-12-10-21-10s-16 4-21 10c-1-2-1-4-1-6z' fill='#2b2b2b'/>
  <circle cx='52' cy='48' r='2.6' fill='#2b2b2b'/><circle cx='68' cy='48' r='2.6' fill='#2b2b2b'/>
  <path d='M53 57c4 4 10 4 14 0' stroke='#8a5a4a' stroke-width='3' fill='none' stroke-linecap='round'/>
  <circle cx='60' cy='84' r='4' fill='#ffffff' fill-opacity='0.85'/>
</svg>
````

## `img/medicos/MED014.svg`

````xml
<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120' role='img'>
  <rect width='120' height='120' rx='24' fill='#fef3f2'/>
  <circle cx='60' cy='46' r='22' fill='#f1c9a8'/>
  <path d='M30 112c3-22 17-34 30-34s27 12 30 34' fill='#dc2626'/>
  <rect x='50' y='66' width='20' height='12' rx='4' fill='#f1c9a8'/>
  <path d='M38 46c0-14 10-24 22-24s22 10 22 24c0 2 0 4-1 6-5-6-12-10-21-10s-16 4-21 10c-1-2-1-4-1-6z' fill='#4a3428'/>
  <circle cx='52' cy='48' r='2.6' fill='#2b2b2b'/><circle cx='68' cy='48' r='2.6' fill='#2b2b2b'/>
  <path d='M53 57c4 4 10 4 14 0' stroke='#8a5a4a' stroke-width='3' fill='none' stroke-linecap='round'/>
  <circle cx='60' cy='84' r='4' fill='#ffffff' fill-opacity='0.85'/>
</svg>
````

## `img/medicos/MED015.svg`

````xml
<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120' role='img'>
  <rect width='120' height='120' rx='24' fill='#f0fdf4'/>
  <circle cx='60' cy='46' r='22' fill='#e4b88f'/>
  <path d='M30 112c3-22 17-34 30-34s27 12 30 34' fill='#16a34a'/>
  <rect x='50' y='66' width='20' height='12' rx='4' fill='#e4b88f'/>
  <path d='M38 46c0-14 10-24 22-24s22 10 22 24c0 2 0 4-1 6-5-6-12-10-21-10s-16 4-21 10c-1-2-1-4-1-6z' fill='#333333'/>
  <circle cx='52' cy='48' r='2.6' fill='#2b2b2b'/><circle cx='68' cy='48' r='2.6' fill='#2b2b2b'/>
  <path d='M53 57c4 4 10 4 14 0' stroke='#8a5a4a' stroke-width='3' fill='none' stroke-linecap='round'/>
  <circle cx='60' cy='84' r='4' fill='#ffffff' fill-opacity='0.85'/>
</svg>
````

## `img/medicos/MED016.svg`

````xml
<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120' role='img'>
  <rect width='120' height='120' rx='24' fill='#faf5ff'/>
  <circle cx='60' cy='46' r='22' fill='#f0c8a6'/>
  <path d='M30 112c3-22 17-34 30-34s27 12 30 34' fill='#9333ea'/>
  <rect x='50' y='66' width='20' height='12' rx='4' fill='#f0c8a6'/>
  <path d='M38 46c0-14 10-24 22-24s22 10 22 24c0 2 0 4-1 6-5-6-12-10-21-10s-16 4-21 10c-1-2-1-4-1-6z' fill='#5b4636'/>
  <circle cx='52' cy='48' r='2.6' fill='#2b2b2b'/><circle cx='68' cy='48' r='2.6' fill='#2b2b2b'/>
  <path d='M53 57c4 4 10 4 14 0' stroke='#8a5a4a' stroke-width='3' fill='none' stroke-linecap='round'/>
  <circle cx='60' cy='84' r='4' fill='#ffffff' fill-opacity='0.85'/>
</svg>
````

## `img/medicos/MED017.svg`

````xml
<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120' role='img'>
  <rect width='120' height='120' rx='24' fill='#fff7ed'/>
  <circle cx='60' cy='46' r='22' fill='#ddb48f'/>
  <path d='M30 112c3-22 17-34 30-34s27 12 30 34' fill='#ea580c'/>
  <rect x='50' y='66' width='20' height='12' rx='4' fill='#ddb48f'/>
  <path d='M38 46c0-14 10-24 22-24s22 10 22 24c0 2 0 4-1 6-5-6-12-10-21-10s-16 4-21 10c-1-2-1-4-1-6z' fill='#2f2f2f'/>
  <circle cx='52' cy='48' r='2.6' fill='#2b2b2b'/><circle cx='68' cy='48' r='2.6' fill='#2b2b2b'/>
  <path d='M53 57c4 4 10 4 14 0' stroke='#8a5a4a' stroke-width='3' fill='none' stroke-linecap='round'/>
  <circle cx='60' cy='84' r='4' fill='#ffffff' fill-opacity='0.85'/>
</svg>
````

## `img/PrimeLab Logo.png`

_Archivo binario (png, 1,050.7 KB). Se entrega dentro de la carpeta del proyecto._

## `Usuarios_PrimeLab.xlsx`

_Archivo binario (xlsx, 10.3 KB). Se entrega dentro de la carpeta del proyecto._

## `Documentacion_Proceso_PrimeLab.docx`

_Archivo binario (docx, 11.8 KB). Se entrega dentro de la carpeta del proyecto._
