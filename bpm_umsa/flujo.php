<?php
session_start();
if (!isset($_SESSION['rol'])) { header("Location: index.php"); exit; }

require_once 'MotorBPM.php';
if (!isset($_GET['id'])) { header("Location: bandeja.php"); exit; }

$idTramite = $_GET['id'];
$rol = $_SESSION['rol'];
$nombreUsuario = $_SESSION['nombre'];

$motor = new MotorBPM();
$tramites = $motor->getTramites();
$flujos = $motor->getFlujos();

$tramiteActual = null;
foreach($tramites as $t) { if($t['id'] == $idTramite) $tramiteActual = $t; }

if (!$tramiteActual) { header("Location: bandeja.php"); exit; }

$flujo = $tramiteActual['flujo'];
$paso = $tramiteActual['proceso_actual'];
$infoProceso = $flujos[$flujo]['procesos'][$paso];

$archivo_datos = 'data/datos_guardados.json';
$todos_los_datos = file_exists($archivo_datos) ? json_decode(file_get_contents($archivo_datos), true) : [];
$datos_tramite = $todos_los_datos[$idTramite] ?? [];

$actorRequerido = $infoProceso['rol'];
if (strcasecmp($actorRequerido, "Sistema SIA") === 0 && strcasecmp($rol, "admin_sia") === 0) {
    // Acceso permitido
} else if (strcasecmp($actorRequerido, $rol) !== 0 && $tramiteActual['estado'] !== 'Finalizado') {
    header("Location: bandeja.php?error_acceso=1");
    exit;
}

// Pantallas que son exclusivamente para generar el PDF final
$esFinF1 = ($infoProceso['pantalla'] == 'descargar_boleta');
$esFinF2 = ($infoProceso['pantalla'] == 'recibir_certificado');
$esPantallaPDF = ($esFinF1 || $esFinF2);

// Extracción de datos del Estudiante para el PDF
$ruEstudiante = $datos_tramite['P1']['ru'] ?? '1855751';
$ciEstudiante = $datos_tramite['P1']['ci'] ?? '12736759';

// Cargar el JSON de formularios para el MOTOR DINÁMICO
$form_json = file_get_contents('data/formularios.json');
$formularios = json_decode($form_json, true);
$nombre_pantalla = $infoProceso['pantalla'];
$campos = $formularios[$nombre_pantalla] ?? [];

require_once 'vistas/flujo_vista.php';
?>