<?php
session_start();
if (!isset($_SESSION['usuario_id'])) { header("Location: index.php"); exit; }

require_once 'MotorBPM.php';
$motor = new MotorBPM();
$rol = $_SESSION['rol'];
$nombreUsuario = $_SESSION['nombre'];

$tramites = $motor->getTramites();
$flujosConfig = $motor->getFlujos();

$bandejaEntrada = [];
$bandejaSalida = [];

foreach ($tramites as $t) {
    $pasoActual = $t['proceso_actual'];
    $rolRequerido = $flujosConfig[$t['flujo']]['procesos'][$pasoActual]['rol'] ?? '';
    
    $esAdmin = (strcasecmp($rol, 'Estudiante') !== 0);
    $esMiTramite = ($t['estudiante'] === $nombreUsuario);

    // 1. Mostrar en Bandeja de Entrada solo si me toca a mí y no está finalizado
    if (strcasecmp($rol, $rolRequerido) === 0 && $t['estado'] !== 'Finalizado') {
        // Los estudiantes solo ven sus propios trámites (si por alguna razón otro los asignó)
        // Pero en la práctica el estudiante es el dueño. Los Admins ven todo lo que tenga su rol.
        if ($esAdmin || $esMiTramite) {
            $bandejaEntrada[] = $t;
        }
    } 
    else {
        // 2. Mostrar en Bandeja de Salida (y seguimiento)
        // El estudiante debe ver en su salida TODOS SUS propios trámites
        // Los admins ven los trámites que NO están en su entrada (seguimiento general)
        if (!$esAdmin && $esMiTramite) {
            $bandejaSalida[] = $t;
        } else if ($esAdmin) {
            $bandejaSalida[] = $t;
        }
    }
}
?>
require_once 'vistas/bandeja_vista.php';
?>