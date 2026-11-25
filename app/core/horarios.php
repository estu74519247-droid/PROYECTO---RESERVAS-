<?php

const HORARIOS_CITA = ['08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','14:00','14:30','15:00','15:30','16:00','16:30'];

function fechaLaboralCita(string $fecha): bool
{
    if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/D', $fecha, $partes)) return false;
    return checkdate((int)$partes[2], (int)$partes[3], (int)$partes[1])
        && $fecha >= date('Y-m-d') && (int)date('N', strtotime($fecha)) <= 5;
}

function horarioFuturoCita(string $fecha, string $hora): bool
{
    return ($fecha . ' ' . substr($hora, 0, 5)) > date('Y-m-d H:i');
}

// Serializa los cambios de horario del mismo médico; se libera al cerrar la conexión.
function bloquearAgendaCita(mysqli $mysqli, string $medico): bool
{
    $nombre = PRIMELAB_DB_NAME . ':agenda:' . $medico;
    $resultado = $mysqli->execute_query('SELECT GET_LOCK(?, 5)', [$nombre]);
    return $resultado && (int)($resultado->fetch_row()[0] ?? 0) === 1;
}
