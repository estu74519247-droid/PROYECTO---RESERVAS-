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
