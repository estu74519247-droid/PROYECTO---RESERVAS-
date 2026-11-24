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
