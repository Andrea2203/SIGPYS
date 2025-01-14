<?php
if (!isset($_SESSION['usuario'])) {
    session_start();
}
/* Carga del Modelo */
include_once("../Models/mdl_solicitud.php");
include_once("../Models/mdl_equipo.php");

/* Inicialización de variables */
$nombreEst          = (isset($_POST['txtNomEst'])) ? $_POST['txtNomEst'] : null;
$descripcionEst     = (isset($_POST['sltEquipo'])) ? $_POST['sltEquipo'] : null;
$nombreTip          = (isset($_POST['txtNomTip'])) ? $_POST['txtNomTip'] : null;
$descripcionTip     = (isset($_POST['txtDescTip'])) ? $_POST['txtDescTip'] : null;
$id                 = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
$val                = (isset($_POST['val'])) ? $_POST['val'] : null;
$cod                = (isset($_POST['cod'])) ? $_POST['cod'] : null;
/* Cargar Select equipo vista Estado de solicitud */
$selectEquipo       =  Equipo::selectEquipo(null,null);
/* Peticiones de registro de información en Estados de solicitud o Tipos de solicitud */
if (isset($_POST['btnRegistrarEst'])) {
    Solicitud::registrarEstadoSolicitud($nombreEst, $descripcionEst);
} else if (isset($_POST['btnRegistrarTip'])) {
    Solicitud::registrarTipoSolicitud($nombreTip, $descripcionTip);
}

/* Carga de información en el Modal, de acuerdo con la variable recibida */
if ($id) {
    $prep = substr($id, 0, 3);
    if ($prep == 'ESS') {
        $info = Solicitud::onLoadEstadoSolicitud($id);
        $nombreEst = $info['nombreEstSol'];
        $descripcionEst = Equipo::selectEquipo($info['descripcionEstSol'],null);
    } else if ($prep == "TSO") {
        $info = Solicitud::onLoadTipoSolicitud($id);
        $nombreTip = $info['nombreTSol'];
        $descripcionTip = $info['descripcionTSol'];
    }
}

/* Peticiones de actualización de Estado o Tipo de solicitudes */
if (isset($_POST['btnActEstSol'])) { // Actualización de estado de solicitud
    Solicitud::actualizarEstadoSolicitud($cod, $nombreEst, $descripcionEst);
} else if (isset($_POST['btnActTipSol'])) { // Actualización de tipo de solicitud
    Solicitud::actualizarTipoSolicitud($cod, $nombreTip, $descripcionTip);
}

/* Petición de actualización de solicitud inicial */
if (isset($_POST['btnActualizarSolIni'])) {
    $idSolicitud = $_POST['txtIdSol'];
    $estSolicitud = $_POST['sltEstadoSolicitud'];
    $observacion = $_POST['txtObservacion2'];
    $solicitante = $_POST['sltSolicitante'];
    $registra = $_SESSION['usuario'];
    $idCM = $_POST['txtIdCM'];
    $fechPrev = $_POST['txtFechPrev2'];
    $estProy = $_POST['txtEstProy'];
    $accion = Solicitud::validarDatosSolIni($idSolicitud, $estSolicitud, $observacion, $solicitante, $fechPrev);
    if ($accion == "Actualizar") {
        Solicitud::actualizarSolicitudInicial($idSolicitud, $estSolicitud, $observacion, $solicitante, $registra, $idCM, $fechPrev, $estProy);
    }
}

?>