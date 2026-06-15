<?php
class MotorBPM {
    private $archivoFlujos = 'data/flujos.json';
    private $archivoTramites = 'data/tramites.json';

    // Leer el archivo de configuración de flujos
    public function getFlujos() {
        $json = file_get_contents($this->archivoFlujos);
        return json_decode($json, true);
    }

    // Leer todos los trámites activos
    public function getTramites() {
        if (!file_exists($this->archivoTramites)) return [];
        $json = file_get_contents($this->archivoTramites);
        return json_decode($json, true) ?: [];
    }

    // Guardar trámites en el JSON
    private function guardarTramites($tramites) {
        file_put_contents($this->archivoTramites, json_encode($tramites, JSON_PRETTY_PRINT));
    }

    // Obtener los trámites que le corresponden a un rol específico
    public function getTramitesPorRol($rol) {
        $tramites = $this->getTramites();
        $filtrados = [];
        foreach ($tramites as $t) {
            if ($t['actor_actual'] === $rol && $t['estado'] !== 'Finalizado') {
                $filtrados[] = $t;
            }
        }
        return $filtrados;
    }

    // Crear un nuevo trámite (Inicia en P1)
    public function crearTramite($codFlujo, $estudiante_id) {
        $flujos = $this->getFlujos();
        $procesoInicial = $flujos[$codFlujo]['procesos']['P1'];

        $tramites = $this->getTramites();
        $nuevoId = count($tramites) + 1;

        $nuevoTramite = [
            "id" => $nuevoId,
            "flujo" => $codFlujo,
            "estudiante" => $estudiante_id,
            "proceso_actual" => "P1",
            "actor_actual" => $procesoInicial['rol'],
            "estado" => "En progreso",
            "fecha_inicio" => date('Y-m-d H:i:s'),
            "historial" => [
                ["proceso" => "P1", "accion" => "Trámite iniciado", "fecha" => date('Y-m-d H:i:s')]
            ]
        ];

        $tramites[] = $nuevoTramite;
        $this->guardarTramites($tramites);
        return $nuevoId;
    }

    // Avanzar el flujo dinámicamente
    public function procesarTramite($idTramite, $decision = null, $observacion = '') {
        $tramites = $this->getTramites();
        $flujos = $this->getFlujos();

        foreach ($tramites as &$t) {
            if ($t['id'] == $idTramite) {
                $flujoActual = $t['flujo'];
                $pasoActual = $t['proceso_actual'];
                $infoProceso = $flujos[$flujoActual]['procesos'][$pasoActual];

                $siguientePaso = null;
                $estado = "En progreso";

                // Lógica del BPM según el TIPO de proceso
                if ($infoProceso['tipo'] == 'C') {
                    // Es condicional (Aprobado/Rechazado)
                    $siguientePaso = $infoProceso['opciones'][$decision];
                    if ($decision == 'false') {
                        $estado = "Observado";
                    }
                } else {
                    // Es lineal (P, I, R)
                    $siguientePaso = $infoProceso['siguiente'];
                }

                // Si ya no hay siguiente paso, finaliza
                if ($siguientePaso == null) {
                    $t['estado'] = "Finalizado";
                    $t['actor_actual'] = "Ninguno";
                } else {
                    // Actualiza al nuevo proceso y le asigna el actor correspondiente
                    $infoSiguiente = $flujos[$flujoActual]['procesos'][$siguientePaso];
                    $t['proceso_actual'] = $siguientePaso;
                    $t['actor_actual'] = $infoSiguiente['rol'];
                    $t['estado'] = $estado;
                }

                // Guardar historial
                $t['historial'][] = [
                    "proceso" => $siguientePaso ?: 'FIN',
                    "accion" => $observacion ?: 'Avanzó al siguiente paso',
                    "fecha" => date('Y-m-d H:i:s')
                ];

                break;
            }
        }
        $this->guardarTramites($tramites);
    }
}
?>