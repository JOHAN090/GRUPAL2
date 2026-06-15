
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bandeja de Gestión Académica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="estilos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-custom py-3 mb-4">
        <div class="container">
            <span class="navbar-brand mb-0 h1 fw-bold text-dark">
                <img src="img/Logo_Umsa.png" alt="Logo" style="height: 30px; margin-right: 10px; filter: drop-shadow(0 0 5px rgba(0,243,255,0.5));">
                <span class="text-primary">UMSA</span> Sistema Académico
            </span>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted small">Autenticado: <strong class="text-dark"><?= $nombreUsuario ?> (<?= $rol ?>)</strong></span>
                <a href="index.php" class="btn btn-outline-danger btn-sm rounded-2">Salir</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php if(strcasecmp($rol, 'Estudiante') === 0): ?>
            <div class="mb-4 clean-card cyber-card p-4 show-anim">
                <h4 class="fw-bold text-primary mb-1">Bienvenido al Sistema</h4>
                <p class="text-muted mb-4">Seleccione un trámite a realizar</p>
                
                <div class="d-flex flex-column gap-3 mx-auto" style="max-width: 650px;">
                    <a href="controlador.php?accion=nuevo&flujo=F1" class="tramite-block d-flex align-items-center p-3 px-4">
                        <div class="icon-wrapper me-4" style="font-size: 2.5rem; text-shadow: 0 0 15px var(--neon-blue);">
                            🌐
                        </div>
                        <div class="text-start flex-grow-1">
                            <h5 class="fw-bold text-primary mb-1">Inscripción de Materias</h5>
                            <p class="text-secondary small mb-0">Gestión semestral y asignación de carga horaria</p>
                        </div>
                        <div class="ms-3 d-none d-sm-block">
                            <span class="badge bg-primary rounded-pill px-3 py-2" style="font-size: 0.75rem;">Iniciar F1</span>
                        </div>
                    </a>

                    <a href="controlador.php?accion=nuevo&flujo=F2" class="tramite-block d-flex align-items-center p-3 px-4" style="border-color: rgba(188, 19, 254, 0.4) !important;">
                        <div class="icon-wrapper me-4" style="font-size: 2.5rem; text-shadow: 0 0 15px var(--neon-purple);">
                            💠
                        </div>
                        <div class="text-start flex-grow-1">
                            <h5 class="fw-bold mb-1" style="color: var(--neon-purple) !important; text-shadow: 0 0 8px var(--neon-purple);">Trámite de Egreso</h5>
                            <p class="text-secondary small mb-0">Protocolo de emisión de certificados y titulación</p>
                        </div>
                        <div class="ms-3 d-none d-sm-block">
                            <span class="badge bg-secondary rounded-pill px-3 py-2" style="font-size: 0.75rem;">Iniciar F2</span>
                        </div>
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <div class="card clean-card cyber-card p-4 show-anim" style="transition-delay: 0.1s;">
            <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#entrada">
                        📥 Bandeja de Entrada <span class="badge bg-primary rounded-pill"><?= count($bandejaEntrada) ?></span>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#salida">
                        📤 Bandeja de Salida <span class="badge bg-secondary rounded-pill"><?= count($bandejaSalida) ?></span>
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <!-- BANDEJA DE ENTRADA -->
                <div class="tab-pane fade show active" id="entrada">
                    <div class="d-flex flex-column gap-3 mt-3">
                        <?php if(count($bandejaEntrada) == 0): ?>
                            <div class="text-center py-5 text-muted">
                                <span class="fs-1 d-block mb-3 opacity-50">📭</span>
                                <h5>Bandeja Vacía</h5>
                                <p class="small">No tienes trámites pendientes de atención.</p>
                            </div>
                        <?php endif; ?>

                        <?php foreach($bandejaEntrada as $t): ?>
                        <div class="tramite-row-card d-flex flex-column flex-md-row align-items-md-center p-3 rounded-3 border border-info" style="background: rgba(0, 243, 255, 0.03); transition: 0.3s;">
                            <div class="d-flex align-items-center gap-3 flex-grow-1">
                                <div class="icon-box-small rounded-circle d-flex align-items-center justify-content-center border border-info" style="width: 50px; height: 50px; background: rgba(0,243,255,0.1);">
                                    <span class="fs-4">📥</span>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h6 class="fw-bold text-primary mb-0 fs-5">Trámite #<?= $t['id'] ?></h6>
                                        <span class="badge bg-light text-primary"><?= $t['proceso_actual'] ?></span>
                                        <span class="badge bg-dark border border-secondary text-secondary small"><?= $t['flujo'] ?></span>
                                    </div>
                                    <p class="text-light mb-0 fw-semibold"><?= $flujosConfig[$t['flujo']]['procesos'][$t['proceso_actual']]['nombre'] ?? '' ?></p>
                                </div>
                            </div>
                            <div class="text-md-end mt-3 mt-md-0 ms-md-4 d-flex flex-column align-items-md-end justify-content-center">
                                <span class="badge bg-warning-subtle text-warning-emphasis mb-2 align-self-start align-self-md-end">⏳ Pendiente</span>
                                <a href="flujo.php?id=<?= $t['id'] ?>" class="btn btn-sm btn-primary rounded-pill px-4 fw-bold text-uppercase" style="letter-spacing: 1px;">Atender Ahora</a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- BANDEJA DE SALIDA -->
                <div class="tab-pane fade" id="salida">
                    <div class="d-flex flex-column gap-3 mt-3">
                        <?php if(count($bandejaSalida) == 0): ?>
                            <div class="text-center py-5 text-muted">
                                <span class="fs-1 d-block mb-3 opacity-50">💨</span>
                                <h5>Historial Vacío</h5>
                                <p class="small">Aún no hay procesos en seguimiento o finalizados.</p>
                            </div>
                        <?php endif; ?>

                        <?php foreach($bandejaSalida as $t): 
                            $procActual = $t['proceso_actual'];
                            $esFinalizado = ($t['estado'] === 'Finalizado');
                            $esObservado = ($t['estado'] === 'Observado');
                            
                            if ($esFinalizado) {
                                $nombreOperacion = 'Trámite Completado Correctamente';
                                $colorBase = 'success';
                                $hexColor = '25, 135, 84';
                                $icono = '✅';
                            } else if ($esObservado) {
                                $nombreOperacion = $flujosConfig[$t['flujo']]['procesos'][$procActual]['nombre'] ?? 'Desconocido';
                                $colorBase = 'danger';
                                $hexColor = '220, 53, 69';
                                $icono = '⚠️';
                            } else {
                                $nombreOperacion = $flujosConfig[$t['flujo']]['procesos'][$procActual]['nombre'] ?? 'Desconocido';
                                $colorBase = 'secondary';
                                $hexColor = '108, 117, 125';
                                $icono = '📤';
                            }
                        ?>
                        <div class="tramite-row-card d-flex flex-column flex-md-row align-items-md-center p-3 rounded-3 border border-<?= $colorBase ?>" style="background: rgba(<?= $hexColor ?>, 0.03); transition: 0.3s;">
                            <div class="d-flex align-items-center gap-3 flex-grow-1">
                                <div class="icon-box-small rounded-circle d-flex align-items-center justify-content-center border border-<?= $colorBase ?>" style="width: 50px; height: 50px; background: rgba(<?= $hexColor ?>,0.1);">
                                    <span class="fs-4"><?= $icono ?></span>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h6 class="fw-bold text-<?= $colorBase ?> mb-0 fs-5">Trámite #<?= $t['id'] ?></h6>
                                        <span class="badge bg-light text-<?= $colorBase ?>"><?= $procActual ?></span>
                                        <span class="badge bg-dark border border-secondary text-secondary small"><?= $t['flujo'] ?></span>
                                    </div>
                                    <p class="text-light mb-0 fw-semibold"><?= $nombreOperacion ?></p>
                                </div>
                            </div>
                            <div class="text-md-end mt-3 mt-md-0 ms-md-4 d-flex flex-column align-items-md-end justify-content-center">
                                <?php if($esFinalizado): ?>
                                    <span class="badge bg-success text-white px-3 py-2">✨ Finalizado</span>
                                <?php elseif($esObservado): ?>
                                    <span class="badge bg-danger text-white px-3 py-2">Rechazado / Observado</span>
                                <?php else: ?>
                                    <span class="badge border border-warning text-warning px-3 py-2">En curso / Seguimiento</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="efectos.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll('.clean-card').forEach(el => el.classList.add('show-anim'));
        });
    </script>
</body>
</html>