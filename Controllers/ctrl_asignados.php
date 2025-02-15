<?php
if(!isset($_SESSION)) { 
    session_start();
}
/*Inclusión del Modelo */
include_once('../Models/mdl_asignados.php');
include_once('../Models/mdl_enviarEmail.php');

/*
    cod1: ID de proyecto.
    cod2: ID de solicitud inicial.
    cod3: ID de solicitud específica.
    sup: ID de asignado para hacer eliminación.
    sol: ID de solicitud para hacer eliminación.
    pry: ID de proyecto para hacer eliminación.
*/

/* Inicialización variables  */
$estado            = (isset($_POST['est'])) ? $_POST['est'] : null ;
$maxHora           = (isset($_POST['txtHoras'])) ? $_POST['txtHoras'] : null;
$maxMin            = (isset($_POST['txtMinutos'])) ? $_POST['txtMinutos'] : null;
$idAsig            = (isset($_POST['cod'])) ? $_POST['cod'] : null;
$idSol             = (isset($_POST['txtCodSol'])) ? $_POST['txtCodSol'] : null;
$nombreCompleto    = "";
$nombreFase        = "";
$horas             = "";
$minutos           = "";
$msj1              =(isset($_POST['msj1'])) ? $_POST['msj1'] : null;   
$obs               =(isset($_POST['obs'])) ? $_POST['obs'] : null;
$nombreSer         =(isset($_POST['nombreSer'])) ? $_POST['nombreSer'] : null;
$nombreEqu         =(isset($_POST['nombreEqu'])) ? $_POST['nombreEqu'] : null;
$codProy           =(isset($_POST['codProy'])) ? $_POST['codProy'] : null;
$infoSol           =(isset($_POST['infoSol'])) ? $_POST['infoSol'] : null;
$idSol2            =(isset($_POST['idSol'])) ? $_POST['idSol'] : null;
$infoAsi           =(isset($_POST['infoAsi'])) ? $_POST['infoAsi'] : null;
$nota              =(isset($_POST['nota'])) ? $_POST['nota'] : null;
$msj2              =(isset($_POST['msj2'])) ? $_POST['msj2'] : null;
$correoSol         =(isset($_POST['correoSol'])) ? $_POST['correoSol'] : null;

/* Procesamiento peticiones al controlador  */
if (isset($_GET['cod1'])) {
    $idProy = (isset($_GET['cod1'])) ? $_GET['cod1'] : null;
    unset($_GET['cod1']);
    $resultado = Asignados::asignarPersonaProy($idProy);
} else if (isset($_GET['cod2'])) {
    $idSol = (isset($_GET['cod2'] )) ? $_GET['cod2'] : null;
    unset($_GET['cod2']);
    $resultado = Asignados::asignarPersonaSolIni($idSol);
} else if (isset($_GET['cod3'])) {
    $idSol = (isset($_GET['cod3'])) ? $_GET['cod3'] : null;
    unset($_GET['cod3']);
    $resultado = Asignados::asignarPersonaSolEsp($idSol);
} else if (isset($_GET['sup'])) {
    $idAsig = (isset($_GET['sup'])) ? $_GET['sup'] : null;
    unset($_GET['sup']);
    $resultado = Asignados::eliminarAsignacion($idAsig);
}

if (isset($_POST['btnAsignar'])) {
    $registra   = (isset($_SESSION['usuario'])) ? $_SESSION['usuario'] : null;
    $rol        = (isset($_POST['sltRol'])) ? $_POST['sltRol'] : null;
    $persona    = (isset($_POST['sltPersona'])) ? $_POST['sltPersona'] : null;
    $fase       = (isset($_POST['sltFase'])) ? $_POST['sltFase'] : null;
    $idProy     = (isset($_POST['txtIdProy'])) ? $_POST['txtIdProy'] : null;
    $idCurso    = "";
    $resultado = Asignados::registrarAsignacion($rol, $persona, $fase, $idProy, $idCurso, $registra, $maxHora, $maxMin, $idSol);
} else if (isset($_POST['btnActualizar'])) {
    $resultado = Asignados::actualizarAsignacion($idAsig, $maxHora, $maxMin);
} else if (isset($_POST['btnEliminar'])) {
    $resultado = Asignados::eliminarAsignacion($idAsig);
} else if (isset($_POST['btnInactivar'])) {
    $resultado = Asignados::cambiarEstadoAsignacion($idAsig, $estado);
} else if (isset($_POST['btnEnvAsig'])) {
    $mensaje = "Cordial saludo,<br><br>". $msj1."<br><br>".$infoSol;
    if ($obs != null){
        $mensaje .= "<strong>Observación de la solicitud: </strong><br>".$obs."<br><br>";
    }
    $mensaje .= $infoAsi.$nota.$msj2;
    $asunto = "Asignación formal solicitud / $codProy / $nombreEqu - $nombreSer"; 
    $ccemail= Asignados::correosResponsable($idSol2);
    $gestores = Asignados::correosAsesorGestorRed($idSol2);
    $envioCorreo = EnviarCorreo::enviarCorreos($correoSol, $gestores, $ccemail, $asunto, $mensaje);
    if($envioCorreo == true){
        echo '<script>alert("Se envio correctamente el correo.")</script>'; 
        echo '<meta http-equiv="Refresh" content="0;url=../Views/asignados.php?cod3='.$idSol2.'">';
    }    
}

if (isset($_GET['id'])) {
    $id = (isset($_GET['id'])) ? $_GET['id'] : null;
    $info = Asignados::onLoadAsignacion($id);
    $nombreCompleto     = isset ($info['apellido1']) ? strtoupper($info['apellido1']." ".$info['apellido2']." ".$info['nombres']) : null;
    $nombreFase         = isset ($info['nombreFase']) ? $info['nombreFase'] : null;
    $horas              = isset ($info['maxhora']) ? $info['maxhora'] : null;
    $minutos            = isset ($info['maxmin']) ? $info['maxmin'] : null;
    $estado             = isset ($info['est']) ? $info['est'] : null;
    $info2 = Asignados::infoEnviarCorreoSolIni($id);
    $idSolIni           = isset ($info2['idSolIni']) ? $info2['idSolIni'] : null;
    $nombres            = isset ($info2['nombres']) ? $info2['nombres'] : null;
    $apellido1          = isset ($info2['apellido1']) ? $info2['apellido1'] : null;
    $apellido2          = isset ($info2['apellido2']) ? $info2['apellido2'] : null;
    $correo             = isset ($info2['correo']) ? $info2['correo'] : null;
    $codProy            = isset ($info2['codProy']) ? $info2['codProy'] : null;
    $nombreProy         = isset ($info2['nombreProy']) ? $info2['nombreProy'] : null;
    $info3 = Asignados::infoEnviarCorreoAsig($id);
    $ObservacionAct     = isset ($info3['ObservacionAct']) ? $info3['ObservacionAct'] : null;  
    $nombreEqu          = isset ($info3['nombreEqu']) ? $info3['nombreEqu'] : null;   
    $nombreSer          = isset ($info3['nombreSer']) ? $info3['nombreSer'] : null;   
    $fechPrev           = isset ($info3['fechPrev']) ? $info3['fechPrev'] : null;
    $responsables = Asignados::infoEnviarCorreoResponsable($id);
}

?>