<?php
// ==============================
// CONEXION BD
// ==============================
$cn = new mysqli("localhost", "root", "", "alexander");
if ($cn->connect_error) {
    die("Error de conexión: " . $cn->connect_error);
}
$cn->set_charset("utf8");

// ==============================
// CARGAR XML
// ==============================
$xml = simplexml_load_file('ies_db.xml') or die('Error: nose cargo el xml.');

// ==============================
// RECORRIDO XML – TU MISMO CÓDIGO
// ==============================
foreach ($xml as $i_pe => $pe) {

    echo 'nombre:' . $pe->nombre . "<br>";
    echo 'codigo:' . $pe->codigo . "<br>";
    echo 'tipo:' . $pe->tipo . "<br>";

    // INSERT → PROGRAMA DE ESTUDIOS
    $stmt = $cn->prepare("INSERT INTO sigi_programa_estudios (codigo, tipo, nombre) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $pe->codigo, $pe->tipo, $pe->nombre);
    $stmt->execute();
    $id_programa = $stmt->insert_id;
    $stmt->close();

    // ==============================
    // PLANES DE ESTUDIO
    // ==============================
    foreach ($pe->planes_estudio[0] as $i_ple => $plan) {

        echo '--' . $plan->nombre . "<br>";
        echo '--' . $plan->resolucion . "<br>";
        echo '--' . $plan->fecha_registro . "<br>";

        // INSERT → PLAN
        $stmt = $cn->prepare("INSERT INTO sigi_planes_estudio (id_programa_estudios, nombre, resolucion, fecha_registro, perfil_egresado)
            VALUES (?, ?, ?, ?, ?)");

        $stmt->bind_param("issss",
            $id_programa,
            $plan->nombre,
            $plan->resolucion,
            $plan->fecha_registro,
            $plan->perfil_egresado
        );
        $stmt->execute();
        $id_plan = $stmt->insert_id;
        $stmt->close();

        // ==============================
        // MODULOS FORMATIVOS
        // ==============================
        foreach ($plan->modulos_formativos[0] as $id_mod => $modulo) {

            echo '----' . $modulo->descripcion . "<br>";
            echo '----' . $modulo->nro_modulo . "<br>";

            // INSERT → MODULO FORMATIVO
            $stmt = $cn->prepare("INSERT INTO sigi_modulo_formativo (descripcion, nro_modulo, id_plan_estudio)
                VALUES (?, ?, ?)");

            $stmt->bind_param("sii",
                $modulo->descripcion,
                $modulo->nro_modulo,
                $id_plan
            );
            $stmt->execute();
            $id_modulo = $stmt->insert_id;
            $stmt->close();

            // ==============================
            // PERIODOS / SEMESTRES
            // ==============================
            foreach ($modulo->periodos[0] as $i_pe => $per) {

                echo '------' . $per->descripcion . "<br>";

                // INSERT → SEMESTRE
                $stmt = $cn->prepare("INSERT INTO sigi_semestre (descripcion, id_modulo_formativo)
                    VALUES (?, ?)");

                $stmt->bind_param("si", $per->descripcion, $id_modulo);
                $stmt->execute();
                $id_semestre = $stmt->insert_id;
                $stmt->close();

                // ==============================
                // UNIDADES DIDACTICAS
                // ==============================
                $orden = 1;
                foreach ($per->unidades_didacticas[0] as $i_ud => $ud) {

                    echo "--------UD: " . $ud->nombre . "<br>";
                    echo "----------Créditos Teóricos: " . $ud->creditos_teorico . "<br>";
                    echo "----------Créditos Prácticos: " . $ud->creditos_practico . "<br>";
                    echo "----------Tipo: " . $ud->tipo . "<br>";
                    echo "----------Horas semanal: " . $ud->horas_semanal . "<br>";
                    echo "----------Horas semestral: " . $ud->horas_semestral . "<br>";

                    // INSERT → UNIDAD DIDACTICA
                    $stmt = $cn->prepare("INSERT INTO sigi_unidad_didactica
                        (nombre, id_semestre, creditos_teorico, creditos_practico, tipo, orden)
                        VALUES (?, ?, ?, ?, ?, ?)");

                    $stmt->bind_param("siiisi",
                        $ud->nombre,
                        $id_semestre,
                        $ud->creditos_teorico,
                        $ud->creditos_practico,
                        $ud->tipo,
                        $orden
                    );
                    $stmt->execute();
                    $stmt->close();

                    $orden++;
                }
            }
        }
    }
}

echo "<br><br><b>IMPORTACIÓN COMPLETA.</b>";
?>
