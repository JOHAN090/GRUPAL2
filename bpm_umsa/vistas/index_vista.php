<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>UMSA Sistema Academico - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="estilos.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center vh-100">

    <div class="card login-card cyber-card p-5" id="cardContainer" style="width: 440px;">
        <div class="text-center mb-4">
            <img src="img/Logo_Umsa.png" alt="Logo UMSA" class="mb-3" style="max-height: 100px; width: auto; filter: drop-shadow(0 0 10px rgba(0,243,255,0.5));">
            <h3 class="fw-bold text-primary mb-1">UMSA Sistema Académico</h3>
            <p class="text-muted small">Motor de Gestión de Procesos y Trámites Internos</p>
        </div>
        
        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-danger border-0 text-center rounded-3 small py-2 mb-4">
                Las credenciales ingresadas son incorrectas. Intente de nuevo.
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="mb-4">
                <label class="form-label mb-1">Cuenta de Usuario</label>
                <input type="text" name="usuario" class="form-control" required placeholder="Ej: johan_estudiante, kardex_ka">
                <div class="form-text text-muted" style="font-size: 0.75rem;">Introduzca su identificador único asignado por la Carrera.</div>
            </div>
            
            <div class="mb-5">
                <label class="form-label mb-1">Contraseña de Seguridad</label>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
                <div class="form-text text-muted" style="font-size: 0.75rem;">Clave de acceso institucional del sistema académico.</div>
            </div>
            
            <button type="submit" class="btn btn-primary w-100 py-3 text-uppercase mb-3">Autenticar Credenciales</button>
        </form>

        <div class="mt-3 pt-3 border-top" style="border-color: rgba(0, 243, 255, 0.2) !important;">
            <p class="text-primary small mb-2 text-center text-uppercase fw-bold" style="letter-spacing: 1px; font-size: 0.75rem;">Cuentas de Acceso (Clave: 123)</p>
            <div class="d-flex flex-wrap justify-content-center gap-2">
                <span class="badge bg-dark border border-info text-info cursor-pointer" onclick="document.querySelector('input[name=usuario]').value='johan_estudiante'" style="cursor: pointer;">johan_estudiante</span>
                <span class="badge bg-dark border border-info text-info cursor-pointer" onclick="document.querySelector('input[name=usuario]').value='admin_umsa'" style="cursor: pointer;">admin_umsa</span>
                <span class="badge bg-dark border border-info text-info cursor-pointer" onclick="document.querySelector('input[name=usuario]').value='admin_sia'" style="cursor: pointer;">admin_sia</span>
                <span class="badge bg-dark border border-info text-info cursor-pointer" onclick="document.querySelector('input[name=usuario]').value='admin_ssa'" style="cursor: pointer;">admin_ssa</span>
                <span class="badge bg-dark border border-info text-info cursor-pointer" onclick="document.querySelector('input[name=usuario]').value='kardex_ka'" style="cursor: pointer;">kardex_ka</span>
                <span class="badge bg-dark border border-info text-info cursor-pointer" onclick="document.querySelector('input[name=usuario]').value='tesoro_tu'" style="cursor: pointer;">tesoro_tu</span>
                <span class="badge bg-dark border border-info text-info cursor-pointer" onclick="document.querySelector('input[name=usuario]').value='director_dc'" style="cursor: pointer;">director_dc</span>
            </div>
        </div>
    </div>

    <script src="efectos.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            setTimeout(() => {
                document.getElementById('cardContainer').classList.add('loaded');
            }, 100);
        });
    </script>
</body>
</html>