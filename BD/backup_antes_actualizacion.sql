-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: bd_primelab
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `bd_primelab`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `bd_primelab` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci */;

USE `bd_primelab`;

--
-- Table structure for table `administrador`
--

DROP TABLE IF EXISTS `administrador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `administrador`
--

LOCK TABLES `administrador` WRITE;
/*!40000 ALTER TABLE `administrador` DISABLE KEYS */;
INSERT INTO `administrador` VALUES (1,'prime','primelab@centrosalud.pe','pr1m3l4b','Administrador principal',1),(2,'admin2','gestion@centrosalud.pe','PrimeLab2026','Coordinación de citas',1),(3,'admin3','soporte@centrosalud.pe','PrimeLab2026','Soporte técnico',1);
/*!40000 ALTER TABLE `administrador` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `centro`
--

DROP TABLE IF EXISTS `centro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `centro` (
  `codce` char(6) NOT NULL,
  `nomce` varchar(80) NOT NULL,
  `dirc` varchar(100) NOT NULL,
  `distrito` varchar(50) NOT NULL,
  `provincia` varchar(40) NOT NULL DEFAULT 'Lima',
  `departamento` varchar(40) NOT NULL DEFAULT 'Lima',
  `telefono` varchar(15) NOT NULL,
  `horario_atencion` varchar(60) NOT NULL DEFAULT 'Lunes a Viernes 07:00 - 17:00',
  `tipo` varchar(20) NOT NULL DEFAULT 'Público',
  PRIMARY KEY (`codce`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `centro`
--

LOCK TABLES `centro` WRITE;
/*!40000 ALTER TABLE `centro` DISABLE KEYS */;
INSERT INTO `centro` VALUES ('CEN001','Centro de Salud San Martín de Porres','Av. Perú 1250, Urb. San Martín','Cercado de Lima','Lima','Lima','014567890','Lunes a Sábado 07:00 - 19:00','Público'),('CEN002','Centro de Salud Santa Rosa - SJL','Jr. Los Jardines 420, AA.HH. Santa Rosa','San Juan de Lurigancho','Lima','Lima','014321876','Lunes a Viernes 07:00 - 17:00','Público'),('CEN003','Centro Materno Infantil Surco','Av. Caminos del Inca 730, Urb. Los Álamos','Santiago de Surco','Lima','Lima','012345678','Lunes a Sábado 07:00 - 18:00','Público'),('CEN004','Centro de Salud Jesús María','Av. Horacio Urteaga 950, Residencial San Felipe','Jesús María','Lima','Lima','014637281','Lunes a Viernes 07:00 - 17:00','Público'),('CEN005','Policlínico Municipal Los Olivos','Av. Carlos Izaguirre 820, Urb. Sol de Oro','Los Olivos','Lima','Lima','015238476','Lunes a Sábado 07:00 - 19:00','Público'),('CEN006','Centro Médico Integral Villa El Salvador','Av. Revolución 1530, Sector 3 Grupo 10','Villa El Salvador','Lima','Lima','012938475','Lunes a Viernes 07:00 - 17:00','Público'),('CEN007','Centro de Salud Breña','Jr. Zorritos 1234','Breña','Lima','Lima','014298763','Lunes a Viernes 07:00 - 17:00','Público'),('CEN008','Centro Materno Infantil Comas','Av. Túpac Amaru 3450','Comas','Lima','Lima','015432187','Lunes a Sábado 07:00 - 18:00','Público');
/*!40000 ALTER TABLE `centro` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `centro_especialidad`
--

DROP TABLE IF EXISTS `centro_especialidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `centro_especialidad` (
  `codce` char(6) NOT NULL,
  `codesp` char(6) NOT NULL,
  PRIMARY KEY (`codce`,`codesp`),
  KEY `fk_ce_especialidad` (`codesp`),
  CONSTRAINT `fk_ce_centro` FOREIGN KEY (`codce`) REFERENCES `centro` (`codce`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ce_especialidad` FOREIGN KEY (`codesp`) REFERENCES `especialidad` (`codesp`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `centro_especialidad`
--

LOCK TABLES `centro_especialidad` WRITE;
/*!40000 ALTER TABLE `centro_especialidad` DISABLE KEYS */;
INSERT INTO `centro_especialidad` VALUES ('CEN001','ESP001'),('CEN001','ESP003'),('CEN001','ESP008'),('CEN001','ESP009'),('CEN001','ESP010'),('CEN001','ESP014'),('CEN002','ESP001'),('CEN002','ESP005'),('CEN002','ESP009'),('CEN002','ESP012'),('CEN002','ESP014'),('CEN002','ESP016'),('CEN003','ESP001'),('CEN003','ESP003'),('CEN003','ESP005'),('CEN003','ESP008'),('CEN003','ESP010'),('CEN003','ESP015'),('CEN004','ESP001'),('CEN004','ESP002'),('CEN004','ESP007'),('CEN004','ESP011'),('CEN004','ESP014'),('CEN004','ESP016'),('CEN005','ESP001'),('CEN005','ESP002'),('CEN005','ESP003'),('CEN005','ESP006'),('CEN005','ESP009'),('CEN005','ESP013'),('CEN005','ESP017'),('CEN006','ESP001'),('CEN006','ESP004'),('CEN006','ESP005'),('CEN006','ESP007'),('CEN006','ESP010'),('CEN006','ESP012'),('CEN007','ESP001'),('CEN007','ESP003'),('CEN007','ESP009'),('CEN007','ESP010'),('CEN007','ESP014'),('CEN008','ESP001'),('CEN008','ESP003'),('CEN008','ESP005'),('CEN008','ESP007'),('CEN008','ESP008'),('CEN008','ESP009'),('CEN008','ESP017');
/*!40000 ALTER TABLE `centro_especialidad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cita`
--

DROP TABLE IF EXISTS `cita`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cita` (
  `codci` char(6) NOT NULL,
  `fechaci` date NOT NULL,
  `horaci` time NOT NULL,
  `motivo` varchar(255) NOT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'pendiente',
  `costo` decimal(8,2) NOT NULL DEFAULT 0.00,
  `codp` char(6) NOT NULL,
  `codm` char(6) NOT NULL,
  `codce` char(6) NOT NULL,
  `fechareg` datetime NOT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `diagnostico` text DEFAULT NULL,
  `indicaciones` text DEFAULT NULL,
  `motivo_reprogramacion` varchar(255) DEFAULT NULL,
  `reprogramada_de` char(6) DEFAULT NULL,
  PRIMARY KEY (`codci`),
  KEY `fk_cita_paciente` (`codp`),
  KEY `fk_cita_medico` (`codm`),
  KEY `fk_cita_centro` (`codce`),
  KEY `ix_cita_agenda` (`codm`,`fechaci`,`horaci`),
  CONSTRAINT `fk_cita_centro` FOREIGN KEY (`codce`) REFERENCES `centro` (`codce`) ON UPDATE CASCADE,
  CONSTRAINT `fk_cita_medico` FOREIGN KEY (`codm`) REFERENCES `medico` (`codm`) ON UPDATE CASCADE,
  CONSTRAINT `fk_cita_paciente` FOREIGN KEY (`codp`) REFERENCES `paciente` (`codp`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cita`
--

LOCK TABLES `cita` WRITE;
/*!40000 ALTER TABLE `cita` DISABLE KEYS */;
INSERT INTO `cita` VALUES ('CIT001','2026-11-20','09:00:00','Control general y dolor de cabeza recurrente por 3 días.','pendiente',0.00,'PAC001','MED001','CEN001','2026-11-08 10:15:00',NULL,NULL,NULL,NULL,NULL),('CIT002','2026-11-18','10:30:00','Evaluación pediátrica de control anual. Crecimiento y desarrollo.','pendiente',20.00,'PAC002','MED003','CEN003','2026-11-09 16:40:00',NULL,NULL,NULL,NULL,NULL),('CIT003','2026-10-30','08:00:00','Chequeo cardiológico anual - Electrocardiograma preventivo.','cancelada',0.00,'PAC001','MED002','CEN004','2026-10-25 09:05:00','Cancelada por el paciente - Motivos laborales',NULL,NULL,NULL,NULL),('CIT004','2026-11-26','11:00:00','Consulta por malestar general, fiebre y dolor muscular.','pendiente',50.00,'PAC003','MED002','CEN004','2026-11-12 14:51:49',NULL,NULL,NULL,NULL,NULL),('CIT005','2026-11-22','09:30:00','Control de diabetes mellitus tipo 2. Revisión de exámenes.','pendiente',0.00,'PAC004','MED014','CEN001','2026-11-13 08:20:00',NULL,NULL,NULL,NULL,NULL),('CIT006','2026-11-23','14:00:00','Consulta ginecológica - Control prenatal rutinario.','pendiente',20.00,'PAC005','MED005','CEN002','2026-11-14 11:35:00',NULL,NULL,NULL,NULL,NULL),('CIT007','2026-11-24','15:30:00','Evaluación dermatológica - Lesión cutánea en brazo derecho.','pendiente',50.00,'PAC006','MED006','CEN005','2026-11-15 09:45:00',NULL,NULL,NULL,NULL,NULL),('CIT008','2026-11-21','10:00:00','Consulta odontológica - Dolor en molar inferior derecho.','pendiente',0.00,'PAC007','MED009','CEN008','2026-11-16 13:10:00',NULL,NULL,NULL,NULL,NULL),('CIT009','2026-11-25','16:00:00','Evaluación nutricional para control de peso.','pendiente',20.00,'PAC008','MED007','CEN004','2026-11-17 10:25:00',NULL,NULL,NULL,NULL,NULL),('CIT010','2026-11-27','08:30:00','Consulta traumatológica - Dolor de rodilla tras caída.','pendiente',50.00,'PAC003','MED004','CEN006','2026-11-18 15:40:00',NULL,NULL,NULL,NULL,NULL),('CIT011','2026-11-30','08:30:00','me duele un pulmon','0',50.00,'PAC003','MED016','CEN004','2026-11-12 18:22:20',NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `cita` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cita_historial`
--

DROP TABLE IF EXISTS `cita_historial`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cita_historial` (
  `idh` int(11) NOT NULL AUTO_INCREMENT,
  `codci` char(6) NOT NULL,
  `accion` varchar(20) NOT NULL,
  `motivo` varchar(255) NOT NULL,
  `fecha` datetime NOT NULL,
  `usuario_accion` varchar(40) DEFAULT 'Sistema',
  PRIMARY KEY (`idh`),
  KEY `fk_historial_cita` (`codci`),
  CONSTRAINT `fk_historial_cita` FOREIGN KEY (`codci`) REFERENCES `cita` (`codci`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cita_historial`
--

LOCK TABLES `cita_historial` WRITE;
/*!40000 ALTER TABLE `cita_historial` DISABLE KEYS */;
INSERT INTO `cita_historial` VALUES (1,'CIT003','cancelacion','El paciente no pudo asistir por motivos laborales.','2026-10-28 18:20:00','PAC001');
/*!40000 ALTER TABLE `cita_historial` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `especialidad`
--

DROP TABLE IF EXISTS `especialidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `especialidad` (
  `codesp` char(6) NOT NULL,
  `nomesp` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`codesp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `especialidad`
--

LOCK TABLES `especialidad` WRITE;
/*!40000 ALTER TABLE `especialidad` DISABLE KEYS */;
INSERT INTO `especialidad` VALUES ('ESP001','Medicina General','Atención primaria integral, diagnóstico y tratamiento de enfermedades comunes'),('ESP002','Cardiología','Diagnóstico y tratamiento de enfermedades del corazón y sistema cardiovascular'),('ESP003','Pediatría','Atención integral a niños, niñas y adolescentes'),('ESP004','Traumatología y Ortopedia','Lesiones óseas, articulares, musculares y rehabilitación'),('ESP005','Ginecología y Obstetricia','Salud reproductiva femenina, embarazo, parto y postparto'),('ESP006','Dermatología','Enfermedades de la piel, cabello, uñas y tratamientos dermatológicos'),('ESP007','Nutrición y Dietética','Evaluación nutricional, dietoterapia y prevención de enfermedades'),('ESP008','Oftalmología','Diagnóstico y tratamiento de enfermedades oculares y corrección visual'),('ESP009','Odontología','Salud bucodental, prevención, diagnóstico y tratamiento'),('ESP010','Psicología Clínica','Evaluación, diagnóstico y terapia psicológica integral'),('ESP011','Neurología','Enfermedades del sistema nervioso central y periférico'),('ESP012','Otorrinolaringología','Enfermedades de oído, nariz, garganta y cabeza-cuello'),('ESP013','Urología','Tracto urinario masculino y femenino, sistema reproductor masculino'),('ESP014','Medicina Interna','Atención integral al adulto con múltiples patologías'),('ESP015','Endocrinología','Trastornos hormonales, diabetes, tiroides y metabolismo'),('ESP016','Gastroenterología','Enfermedades del aparato digestivo y hepatobiliares'),('ESP017','Neumología','Enfermedades respiratorias, asma, EPOC y tuberculosis');
/*!40000 ALTER TABLE `especialidad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medico`
--

DROP TABLE IF EXISTS `medico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `medico` (
  `codm` char(6) NOT NULL,
  `nomm` varchar(80) NOT NULL,
  `cmpm` varchar(10) NOT NULL,
  `codesp` char(6) NOT NULL,
  `dni` char(8) DEFAULT NULL,
  `telefono` varchar(9) DEFAULT NULL,
  `correo` varchar(80) DEFAULT NULL,
  `anios_experiencia` int(3) DEFAULT 0,
  `formacion` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`codm`),
  UNIQUE KEY `uq_medico_cmp` (`cmpm`),
  KEY `fk_medico_especialidad` (`codesp`),
  CONSTRAINT `fk_medico_especialidad` FOREIGN KEY (`codesp`) REFERENCES `especialidad` (`codesp`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medico`
--

LOCK TABLES `medico` WRITE;
/*!40000 ALTER TABLE `medico` DISABLE KEYS */;
INSERT INTO `medico` VALUES ('MED001','Dra. Ana Torres Mendoza','10234','ESP001','07896543','987123456','ana.torres@primelab.pe',15,'UNMSM - Medicina Humana'),('MED002','Dr. Luis Alberto Mendoza Quispe','18542','ESP002','09234156','986234567','luis.mendoza@primelab.pe',18,'UNMSM - Medicina Humana, Especialización en Cardiología'),('MED003','Dra. Rosa María Salazar Huamán','21306','ESP003','05678912','985345678','rosa.salazar@primelab.pe',12,'UPCH - Medicina Humana, Especialización en Pediatría'),('MED004','Dr. Carlos Eduardo Ramírez Castro','24618','ESP004','06789123','984456789','carlos.ramirez@primelab.pe',16,'UNMSM - Traumatología y Ortopedia'),('MED005','Dra. Elena Beatriz Paredes López','27190','ESP005','07891234','983567890','elena.paredes@primelab.pe',14,'UPCH - Ginecología y Obstetricia'),('MED006','Dr. Miguel Ángel Cárdenas Vega','30412','ESP006','08912345','982678901','miguel.cardenas@primelab.pe',10,'UNMSM - Dermatología'),('MED007','Dra. Patricia Lucía León Gutiérrez','32874','ESP007','09123456','981789012','patricia.leon@primelab.pe',9,'UPCH - Nutrición Clínica'),('MED008','Dr. Javier Fernando Quispe Yupanqui','35210','ESP008','09234567','980890123','javier.quispe@primelab.pe',11,'UNMSM - Oftalmología'),('MED009','Dra. Carmen Rosa Flores Aguirre','36741','ESP009','09345678','979901234','carmen.flores@primelab.pe',13,'UPCH - Estomatología/Odontología'),('MED010','Dr. Ricardo Martín Huamán Córdova','38902','ESP010','09456789','978012345','ricardo.huaman@primelab.pe',12,'UNMSM - Psicología Clínica'),('MED011','Dra. Sofía Alejandra Vargas Ponce','40156','ESP011','09567890','977123456','sofia.vargas@primelab.pe',17,'UNMSM - Neurología'),('MED012','Dr. Diego Armando Castillo Rojas','42588','ESP012','09678901','976234567','diego.castillo@primelab.pe',14,'UPCH - Otorrinolaringología'),('MED013','Dr. Fernando José Tello Morales','44213','ESP013','09789012','975345678','fernando.tello@primelab.pe',16,'UNMSM - Urología'),('MED014','Dra. Gabriela Inés Ortiz Villanueva','45876','ESP014','09890123','974456789','gabriela.ortiz@primelab.pe',19,'UNMSM - Medicina Interna'),('MED015','Dr. Hugo César Reyes Bustamante','47624','ESP015','09901234','973567890','hugo.reyes@primelab.pe',15,'UPCH - Endocrinología'),('MED016','Dra. Lucía Fernanda Prado Salinas','49238','ESP016','08123456','972678901','lucia.prado@primelab.pe',13,'UNMSM - Gastroenterología'),('MED017','Dr. Andrés Felipe Mena Zegarra','51692','ESP017','08234567','971789012','andres.mena@primelab.pe',14,'UPCH - Neumología');
/*!40000 ALTER TABLE `medico` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paciente`
--

DROP TABLE IF EXISTS `paciente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  `fechareg` date NOT NULL,
  PRIMARY KEY (`codp`),
  UNIQUE KEY `uq_paciente_dni` (`dni`),
  UNIQUE KEY `uq_paciente_correo` (`correo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paciente`
--

LOCK TABLES `paciente` WRITE;
/*!40000 ALTER TABLE `paciente` DISABLE KEYS */;
INSERT INTO `paciente` VALUES ('PAC001','Ana','Torres Mendoza','45678912','987654321','ana.torres@correo.com','45678912','SIS','1985-04-12','Av. Tacna 456','Cercado de Lima','2026-11-01'),('PAC002','Luis','Ramos Cárdenas','41234567','912345678','luis.ramos@correo.com','41234567','EPS','1990-08-25','Jr. Amazonas 789','San Juan de Lurigancho','2026-11-05'),('PAC003','Eidan','Valencia Zamora','61161158','994784726','eidanvalenciazamora824@gmail.com','61161158','Privado','1992-12-03','Av. Benavides 1234','Santiago de Surco','2026-11-12'),('PAC004','María','Gonzales Flores','42345678','965432187','maria.gonzales@correo.com','42345678','SIS','1978-06-18','Calle Los Pinos 234','Los Olivos','2026-11-13'),('PAC005','José','Pérez Rodríguez','43456789','954321876','jose.perez@correo.com','43456789','EPS','1982-03-09','Av. Universitaria 567','Comas','2026-11-14'),('PAC006','Carmen','López Vásquez','44567890','943218765','carmen.lopez@correo.com','44567890','Privado','1988-11-22','Jr. Huancayo 890','Breña','2026-11-15'),('PAC007','Pedro','Martínez Silva','45678901','932187654','pedro.martinez@correo.com','45678901','SIS','1995-07-14','Av. El Sol 321','Villa El Salvador','2026-11-16'),('PAC008','Rosa','Castillo Herrera','46789012','921876543','rosa.castillo@correo.com','46789012','EPS','1986-09-30','Calle Las Flores 654','Jesús María','2026-11-17');
/*!40000 ALTER TABLE `paciente` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-18  8:59:10
