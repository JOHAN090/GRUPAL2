
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Tarea - <?= $paso ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="estilos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-custom py-3 mb-4">
        <div class="container">
            <span class="navbar-brand fw-bold text-dark">
                <img src="img/Logo_Umsa.png" alt="Logo" style="height: 30px; margin-right: 10px; filter: drop-shadow(0 0 5px rgba(0,243,255,0.5));" onerror="this.src='https://via.placeholder.com/80?text=UMSA'">
                <span class="text-primary">UMSA</span> Sistema Académico
            </span>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted small">Autenticado: <strong class="text-dark"><?= $nombreUsuario ?> (<?= $rol ?>)</strong></span>
                <a href="bandeja.php" class="btn btn-outline-light btn-sm rounded-2">Volver</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="info-top-box p-3 mb-4 fw-semibold cyber-card">
                    <div class="row text-center text-md-start">
                        <div class="col-6 col-md-3">📌 Trámite: <span class="text-dark">#<?= $idTramite ?></span></div>
                        <div class="col-6 col-md-3">🔄 Proceso: <span class="text-dark"><?= $paso ?></span></div>
                        <div class="col-12 col-md-6 mt-2 mt-md-0 text-md-end">👤 Rol: <span class="badge bg-dark"><?= $infoProceso['rol'] ?></span></div>
                    </div>
                </div>

                <div class="card cyber-card form-card p-4">
                    <div class="border-bottom border-info pb-3 mb-4">
                        <h4 class="fw-bold text-primary mb-1">Trámite #<?= $tramiteActual['id'] ?></h4>
                        <span class="badge bg-secondary"><?= $flujos[$flujo]['nombre'] ?></span>
                        <span class="badge bg-primary ms-1">Operación: <?= $infoProceso['nombre'] ?></span>
                    </div>

                    <?php if(!$esPantallaPDF): ?>
                    
                    <form action="controlador.php" method="POST">
                        <input type="hidden" name="accion" value="avanzar">
                        <input type="hidden" name="id" value="<?= $tramiteActual['id'] ?>">

                        <div class="card bg-transparent cyber-card p-4 mb-4 border-info text-dark">
                            <div class="row g-4">
                                <?php foreach($campos as $campo): ?>
                                    <div class="col-md-6">
                                        <div class="form-group position-relative p-3 rounded-3 border" style="border-color: rgba(0, 243, 255, 0.2) !important; background: rgba(0, 0, 0, 0.3);">
                                            <label class="d-block text-info small text-uppercase fw-bold mb-2" style="letter-spacing: 1px;">
                                                <span class="me-2 text-warning">⯎</span><?= $campo['label'] ?>
                                            </label>
                                            
                                            <?php if ($campo['tipo'] === 'select'): ?>
                                                <select name="datos[<?= $campo['nombre'] ?>]" class="form-select border-0 shadow-none text-white py-2 px-3 rounded-2" style="background: rgba(0, 243, 255, 0.05);" required>
                                                    <option value="" disabled selected>— Elija una opción —</option>
                                                    <?php foreach($campo['opciones'] as $opcion): ?>
                                                        <option value="<?= $opcion ?>" class="text-dark"><?= $opcion ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                
                                            <?php elseif ($campo['tipo'] === 'hash' || $campo['tipo'] === 'auto' || $campo['tipo'] === 'auto_ip' || $campo['tipo'] === 'fijo'): ?>
                                                <?php 
                                                    if ($campo['tipo'] === 'fijo') {
                                                        $val = $campo['valor'] ?? '';
                                                    } else if ($campo['tipo'] === 'auto_ip') {
                                                        $val = '192.168.' . rand(1, 254) . '.' . rand(1, 254);
                                                    } else {
                                                        $prefix = $campo['prefix'] ?? 'SFE-';
                                                        $val = strtoupper(uniqid($prefix));
                                                    }
                                                ?>
                                                <input type="text" name="datos[<?= $campo['nombre'] ?>]" class="form-control font-monospace border-0 shadow-none text-warning py-2 px-3 rounded-2" value="<?= $val ?>" readonly style="background: rgba(255, 193, 7, 0.05); font-size: 1.1rem; letter-spacing: 2px;">
                                                
                                            <?php else: ?>
                                                <input type="<?= $campo['tipo'] ?>" name="datos[<?= $campo['nombre'] ?>]" class="form-control border-0 shadow-none text-white py-2 px-3 rounded-2" style="background: rgba(255, 255, 255, 0.05);" required>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <?php if (empty($campos)): ?>
                                <div class="alert alert-info border-info text-info bg-transparent">
                                    <span class="fs-4 d-block mb-2">👁️</span> Revisión de expediente en curso. Por favor, confirme la operación para continuar con el siguiente paso.
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if (isset($infoProceso['tipo']) && $infoProceso['tipo'] === 'C'): ?>
                            <div class="d-flex gap-3 mt-4">
                                <button type="submit" name="decision" value="true" class="btn btn-success w-50 py-2 fw-semibold">✅ Aprobar / Cumple</button>
                                <button type="submit" name="decision" value="false" class="btn btn-danger w-50 py-2 fw-semibold" formnovalidate>❌ Rechazar / Observar</button>
                            </div>
                        <?php else: ?>
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm">Registrar y Continuar</button>
                        <?php endif; ?>
                    </form>
                    <?php else: ?>
                    
                    <div class="table-responsive bg-light p-3 rounded mb-4" style="background: transparent !important;">
                        <div id="renderPDF" style="background-color: white; color: black; padding: 40px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 25px;">
                                <div style="display: flex; gap: 15px;">
                                    <img src="img/Logo_Umsa.png" alt="Logo" style="width: 85px; height: auto;" onerror="this.style.display='none'">
                                    <div style="font-size: 11px; line-height: 1.4; color: black;">
                                        <div style="font-size: 13px; font-weight: bold; color: black;">UNIVERSIDAD MAYOR DE SAN ANDRES</div>
                                        <div style="color: black;">CIENCIAS PURAS Y NATURALES</div>
                                        <div style="color: black;">INFORMATICA</div>
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <img src="img/QR.png" alt="QR" style="width: 75px; margin-bottom: 5px;" onerror="this.style.display='none'"><br>
                                    <span style="font-size: 10px; color: #333;"><?= date('d/m/Y H:i:s') ?></span>
                                </div>
                            </div>

                            <div style="text-align: center; margin-bottom: 30px; color: black;">
                                <h2 style="margin: 0; font-size: 18px; font-weight: bold; color: black;">DOCUMENTO DE TRÁMITE FINALIZADO</h2>
                                <p style="margin: 8px 0 0 0; font-size: 12px; font-family: sans-serif; color: black;">SISTEMA DE GESTIÓN ACADÉMICA</p>
                            </div>

                            <div style="display: flex; justify-content: space-between; font-size: 12px; font-weight: bold; margin-bottom: 15px; color: black;">
                                <div style="flex: 1; color: black;">Nombre(s) : <?= strtoupper($_SESSION['nombre'] ?? 'ESTUDIANTE') ?></div>
                                <div style="width: 200px; color: black;">Cedula : <?= htmlspecialchars($ciEstudiante) ?></div>
                                <div style="width: 150px; color: black;">R.U. : <?= htmlspecialchars($ruEstudiante) ?></div>
                            </div>

                            <div style="border: 1px solid #000; text-align: center; font-size: 11px; padding: 10px; margin-bottom: 20px; color: black;">
                                El presente trámite se ha procesado exitosamente a través del sistema BPM.
                            </div>

                            <div style="display: flex; justify-content: space-between; font-size: 10px; margin-bottom: 4px; color: black;">
                                <div style="color: black;">S.S.A. Sistema de Seguimiento Academico</div>
                                <div style="font-weight: bold; font-size: 11px; color: black;"><?= htmlspecialchars($ruEstudiante) ?>-189.28.95.101</div>
                            </div>

                            <div style="display: flex; justify-content: space-between; margin-top: 80px; text-align: center; font-size: 12px; padding: 0 40px; color: black;">
                                <div>
                                    <div style="border-top: 1px solid #000; width: 220px; padding-top: 5px; color: black;">Firma del Estudiante</div>
                                </div>
                                <div>
                                    <div style="border-top: 1px solid #000; width: 220px; padding-top: 5px; color: black;">SELLO DE KARDEX / DIRECCIÓN</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <button type="button" onclick="generarPDF()" class="btn btn-outline-danger w-50 py-3 fw-semibold fs-5">📥 Descargar PDF</button>
                        
                        <form action="controlador.php" method="POST" class="w-50 m-0">
                            <input type="hidden" name="accion" value="avanzar">
                            <input type="hidden" name="id" value="<?= $tramiteActual['id'] ?>">
                            <button type="submit" class="btn btn-success w-100 py-3 fw-semibold fs-5">✅ Terminar Proceso</button>
                        </form>
                    </div>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="efectos.js"></script>
    <script>
        function generarPDF() {
            const elemento = document.getElementById('renderPDF');
            const opciones = {
                margin:       [0.5, 0.5, 0.5, 0.5],
                filename:     'Documento_Tramite_UMSA.pdf',
                image:        { type: 'jpeg', quality: 1 },
                html2canvas:  { scale: 3, useCORS: true, logging: true },
                jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
            };
            html2pdf().set(opciones).from(elemento).save().then(() => {
                alert("¡El PDF se ha descargado exitosamente!");
            });
        }
    </script>
</body>
</html>