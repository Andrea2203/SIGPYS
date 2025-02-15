<?php
/* Inclusión del Modelo */
include_once"../Models/mdl_dedicacion.php";

/* Inicialización variables*/
$persona        = (isset($_POST['idPersona'])) ? $_POST['idPersona'] : null;
$periodo        = (isset($_POST['sltPeriodo'])) ? $_POST['sltPeriodo'] : null;
$porcenDedica1  = (isset($_POST['dedicacion1Seg'])) ? $_POST['dedicacion1Seg'] : null;
$porcenDedica2  = (isset($_POST['dedicacion2Seg'])) ? $_POST['dedicacion2Seg'] : null;
$idDedica       = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$idDedica2      = (isset($_POST['cod'])) ? $_POST['cod'] : null;

/* Carga de información en el Modal */
if($idDedica != null){
    $info = Dedicacion::onLoad($idDedica);
    $dedicacionSeg1 = $info['porcentajeDedicacion1'];
    $dedicacionSeg2 = $info['porcentajeDedicacion2'];
    $horasSeg1 = (($info['diasSegmento1'] * 8) * $dedicacionSeg1) / 100;
    $horasSeg2 = (($info['diasSegmento2'] * 8) * $dedicacionSeg2) / 100;
    $diasSeg1 = $info['diasSegmento1'];
    $diasSeg2 = $info['diasSegmento2'];
    $dedicacion = $info[3];
    $horasReales = $info[4];
    $nombreCompleto = $info['apellido1']." ".$info['apellido2']." ".$info['nombres'];
}

/* Procesamiento peticiones al controlador */
if ($persona != null){
    $resultado = Dedicacion::guardarDedicacion($periodo, $persona, $porcenDedica1, $porcenDedica2);
}else if (isset($_POST['btnActDedi'])) {
    $horasDedica = $_POST['txtHorasSeg1'] + $_POST['txtHorasSeg2'];
    Dedicacion::actualizarDedicacion($idDedica2, $porcenDedica1, $porcenDedica2, $horasDedica);
} else if (isset($_POST['btnEliDedi'])) {
    Dedicacion::suprimirDedicacion($idDedica2);
}

?>