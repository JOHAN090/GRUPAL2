
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Flujos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="estilos.css" rel="stylesheet">
    <style>
        body { padding-top: 80px; }
        .topbar { height: 60px; width: 100%; position: fixed; top: 0; z-index: 1000; display: flex; justify-content: space-between; align-items: center; padding: 0 20px; }
    </style>
</head>
<body>
    <div class="topbar cyber-card rounded-0">
        <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-diagram-3-fill me-2"></i>Administración de Flujos</h5>
        <a href="bandeja.php" class="btn btn-outline-light btn-sm"><i class="bi bi-arrow-left"></i> Volver a Bandeja</a>
    </div>

    <div class="container mt-4">
        <div class="card cyber-card shadow-sm mb-5 border-0">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4 text-primary">Agregar Nuevo Proceso</h5>
                <form class="row g-3">
                    <div class="col-md-2"><label class="form-label small">Flujo:</label><input type="text" class="form-control"></div>
                    <div class="col-md-2"><label class="form-label small">Proceso:</label><input type="text" class="form-control"></div>
                    <div class="col-md-2"><label class="form-label small">Siguiente Proceso:</label><input type="text" class="form-control"></div>
                    <div class="col-md-3"><label class="form-label small">Pantalla:</label><input type="text" class="form-control"></div>
                    <div class="col-md-3"><label class="form-label small">Rol:</label><input type="text" class="form-control"></div>
                    <div class="col-12 mt-3"><button type="button" class="btn btn-success"><i class="bi bi-plus-circle"></i> Guardar Proceso</button></div>
                </form>
            </div>
        </div>

        <h5 class="fw-bold mb-3 text-primary">Flujos Existentes</h5>
        <div class="table-responsive cyber-card p-3">
            <table class="table table-hover mb-0 align-middle">
                <thead><tr><th>Flujo</th><th>Proceso</th><th>Siguiente</th><th>Pantalla</th><th>Rol</th><th>Acciones</th></tr></thead>
                <tbody>
                    <?php foreach($flujos as $fKey => $fData): ?>
                        <?php foreach($fData['procesos'] as $pKey => $pData): ?>
                        <tr>
                            <td class="fw-bold text-primary"><?= $fKey ?></td>
                            <td><span class="badge bg-light"><?= $pKey ?></span></td>
                            <td><?= $pData['siguiente'] ?? 'Fin' ?></td>
                            <td class="text-muted"><?= $pData['pantalla'] ?></td>
                            <td><span class="badge bg-secondary"><?= $pData['rol'] ?></span></td>
                            <td>
                                <button class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> Editar</button>
                                <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i> Eliminar</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="efectos.js"></script>
</body>
</html>