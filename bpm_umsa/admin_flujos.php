<?php
session_start();
if (!isset($_SESSION['rol'])) { header("Location: index.php"); exit; }
require_once 'MotorBPM.php';
$motor = new MotorBPM();
$flujos = $motor->getFlujos();
?>
require_once 'vistas/admin_flujos_vista.php';
?>