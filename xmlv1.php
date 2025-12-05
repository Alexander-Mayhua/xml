<?php
$ies = [];



$udp1 = [
    'Fundamentos de programación',
    'Redes e internet',
    'Análisis y diseño de sistemas',
    'Introducción de base de datos',
    'Arquitectura de computadoras',
    'Comunicación oral',
    'Aplicaciones en internet'
];
$udp2 = [
    'Ofimática',
    'Interpretación y producción textos',
    'Metodología de desarrollo de software',
    'Programación orientada a objetos',
    'Arquitectura de servidores web',
    'Aplicaciones sistematizadas',
    'Taller de base de datos'
];
$udp3 = [
    'Administración de base de datos',
    'Programación de aplicaciones web',
    'Diseño de interfaces web',
    'Pruebas de software',
    'Inglés para la comunicación oral'
];
$udp4 = [
    'Desarrollo de entornos web',
    'Programación de soluciones web',
    'Proyectos de software',
    'Seguridad en aplicaciones web',
    'Comprensión y redacción en inglés',
    'Comportamiento ético'
];
$udp5 = [
    'Programación de aplicaciones móviles',
    'Marketing digital',
    'Diseño de soluciones web',
    'Gestión y administración de sitios web',
    'Diagramación digital',
    'Solución de problemas',
    'Oportunidades de negocios'
];
$udp6 = [
    'Plataforma de servicios web',
    'Ilustración y gráfica digital',
    'Administración de servidores web',
    'Comercio electrónico',
    'Plan de negocios'
];

//------------------------Periodos-----------------------

$p1 = [];
$p1['nombre'] = "I";
$p1['unidades_didacticas'] = $udp1;


$p2 = array();
$p2['nombre'] = "II";
$p2['unidades_didacticas'] = $udp2;

$p3 = [];
$p3['nombre'] = "III";
$p3['unidades_didacticas'] = $udp3;

$p4 = [];
$p4['nombre'] = "IV";
$p4['unidades_didacticas'] = $udp4;

$p5 = [];
$p5['nombre'] = "V";
$p5['unidades_didacticas'] = $udp5;

$p6 = [];
$p6['nombre'] = "VI";
$p6['unidades_didacticas'] = $udp6;
//-------------------modulos------------------


$m1 = array();
$m1['nombre'] = 'ANALISIS Y DISEÑO DE SISTEMAS WEB';
$m1['periodos'] = [$p1, $p2];


$m2 = array();
$m2['nombre'] = 'DESARROLLO DE APLICACIONES WEB';
$m2['periodos'] = [$p3, $p4];;
$m3 = array();
$m3['nombre'] = 'DISEÑO DE SERVICIOS WEB';
$m3['periodos'] = [$p5, $p6];



//-----------------------programas de estudios---------------
$pe1 = array();
$pe1['nombre'] = "DISEÑO Y PROGRAMCION WEB";
$pe1['modulos'] = [$m1, $m2, $m3];

$pe2 = array();
$pe2['nombre'] = "ENFERMERIA TECNICA";
$pe2['modulos'] = [];

$pe3 = array();
$pe3['nombre'] = "MECATRONICA AUTOMOTRIZ";
$pe3['modulos'] = [];

$pe4 = array();
$pe4['nombre'] = "INDUSTRIA DD ALIMENTOS Y BEBIDAS";
$pe4['modulos'] = [];

$pe5 = array();
$pe5['nombre'] = "PRODUCCION AGROPECUARIA";
$pe5['modulos'] = [];



$ies['nombre']="IES Publico HUANTA";
$ies['programas_estudio'] = [$pe1, $pe2, $pe3, $pe4, $pe5];
//XML----------------------------------------------------------------------------------------------------------
$xml = new DOMDocument('1.0', 'UTF-8');
$xml->formatOutput = true;

$et1 = $xml->createElement('ies');
$xml->appendChild($et1);


$nombre_ies = $xml->createElement('nombre', $ies['nombre']);
$programas_ies = $xml->createElement("programas_estudio");
$et1->appendChild($nombre_ies);
$et1->appendChild($programas_ies);

foreach ($ies["programas_estudio"] as $indice => $PEs) {
    $num_pe = $xml->createElement("pe" . $indice + 1);
    $nombre_pe = $xml->createElement("nombre", $PEs['nombre']);

    foreach ($PEs['modulos'] as $indice_modulo => $Modulo) {
        $num_mod =  $xml->createElement("mod" . $indice_modulo + 1);
        $nom_mod = $xml->createElement("nombre", $Modulo['nombre']);

        foreach ($Modulo['periodos'] as $indice_periodo => $Periodo) {
            $num_per = $xml->createElement("per" . $indice_periodo + 1);
            $nom_per = $xml->createElement("nombre", $Periodo['nombre']);

            $uds = $xml->createElement("unidades_didacticas");


            foreach ($Periodo['unidades_didacticas'] as $indice_ud => $Ud) {
                $num_ud = $xml->createElement("ud" . $indice_ud + 1);
                $nom_ud = $xml->createElement("nombre", $Ud);
                $num_ud->appendChild($nom_ud);
                $uds->appendChild($num_ud);
            }
             
             $num_per->appendChild($nom_per);
            $num_per->appendChild($uds);
            $num_mod->appendChild($num_per);
        }

        $num_mod->appendChild($nom_mod);
        $num_pe->appendChild($num_mod);
    }

    $num_pe->appendChild($nombre_pe);
    $programas_ies->appendChild($num_pe);
}


$archivo = "ies.xml";
$xml->save($archivo);
