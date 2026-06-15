<?php
session_start();
if (!isset($_SESSION['rol'])) { header("Location: index.php"); exit; }

require_once 'MotorBPM.php';
$motor = new MotorBPM();
$rolActual = $_SESSION['rol'];

// ACCIÓN: CREAR UN NUEVO TRÁMITE
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['accion']) && $_GET['accion'] == 'nuevo') {
    $flujo = $_GET['flujo'];
    $nombreEstudiante = $_SESSION['nombre']; 
    
    $nuevoId = $motor->crearTramite($flujo, $nombreEstudiante);
    header("Location: flujo.php?id=" . $nuevoId);
    exit;
}

// ACCIÓN: AVANZAR EN EL FLUJO
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'avanzar') {
    $id = $_POST['id'];
    $decision = $_POST['decision'] ?? null; // Atrapa el 'true' o 'false' del botón presionado
    
    $datos_ingresados = $_POST['datos'] ?? [];
    
    // Ajustar observación según la decisión del operador
    $observacion = 'Operación completada de forma regular';
    if ($decision === 'false') {
        $observacion = "Trámite observado/rechazado en la instancia de control.";
    } else if (!empty($datos_ingresados)) {
        $observacion = "Datos del paso guardados y validados en el sistema.";
    }

    // 1. OBTENER PASO ACTUAL
    $tramites = $motor->getTramites();
    $pasoActual = '';
    foreach($tramites as $t) {
        if($t['id'] == $id) { $pasoActual = $t['proceso_actual']; break; }
    }

    // 2. GUARDAR LOS DATOS DE ESTE PASO EN JSON
    $archivo_datos = 'data/datos_guardados.json';
    $todos_los_datos = file_exists($archivo_datos) ? json_decode(file_get_contents($archivo_datos), true) : [];
    
    if ($pasoActual != '') {
        $todos_los_datos[$id][$pasoActual] = $datos_ingresados;
        file_put_contents($archivo_datos, json_encode($todos_los_datos, JSON_PRETTY_PRINT));
    }

    // 3. AVANZAR EL FLUJO EN EL MOTOR
    $motor->procesarTramite($id, $decision, $observacion);
    
    // 4. AUTO-AVANCE INTELIGENTE
    $tramitesActualizados = $motor->getTramites();
    foreach($tramitesActualizados as $t) {
        if($t['id'] == $id && $t['estado'] != 'Finalizado') {
            $actorSiguiente = $t['actor_actual'];
            
            $esMismoRol = ($actorSiguiente === $rolActual);
            if ($actorSiguiente === "Sistema SIA" && $rolActual === "admin_sia") {
                $esMismoRol = true;
            }
            
            if($esMismoRol) {
                header("Location: flujo.php?id=" . $id);
                exit;
            }
        }
    }
    
    header("Location: bandeja.php");
    exit;
}
?>