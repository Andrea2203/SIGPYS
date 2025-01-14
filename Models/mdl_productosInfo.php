<?php
    Class ProductoInfo {

        public static function carga () {
            require('../Core/connection.php');
            require_once('mdl_solicitudEspecifica.php');
            $consulta = "SELECT pys_solicitudes.idSolIni, pys_actsolicitudes.idSol, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_solicitudes.descripcionSol, pys_equipos.nombreEqu, pys_equipos.idEqu, pys_servicios.nombreSer,  pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.fechPrev, pys_solicitudes.fechSol
            FROM pys_solicitudes
            INNER JOIN pys_actsolicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
            INNER JOIN pys_cursosmodulos ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM
            INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
            INNER JOIN pys_servicios ON pys_actsolicitudes.idSer = pys_servicios.idSer
            INNER JOIN pys_equipos ON pys_servicios.idEqu = pys_equipos.idEqu
            INNER JOIN pys_asignados ON pys_asignados.idsol = pys_actsolicitudes.idSol
            INNER JOIN pys_personas ON pys_asignados.idResponRegistro = pys_personas.idPersona
	        WHERE pys_solicitudes.est = '1' AND pys_actsolicitudes.est = '1' AND pys_actualizacionproy.est = '1' AND pys_personas.est = '1' AND pys_cursosmodulos.estProy = '1' AND pys_cursosmodulos.estCurso = '1' AND pys_equipos.est = '1' AND pys_asignados.est = '2' AND pys_servicios.est = '1' AND pys_servicios.productoOservicio = 'SI' AND (pys_servicios.idEqu ='EQU001' OR pys_servicios.idEqu ='EQU002' OR pys_servicios.idEqu ='EQU003') AND  pys_solicitudes.idTSol = 'TSOL02' AND ((pys_actsolicitudes.idEstSol != 'ESS001') AND (pys_actsolicitudes.idEstSol != 'ESS007'))
            ORDER BY pys_actsolicitudes.idSol DESC;";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                $string = ' <table class="responsive-table  left" id ="resulProd">
                                <thead>
                                    <tr>
                                        <th>Código solicitud</th>
                                        <th>Producto</th>
                                        <th>Cód. proyecto en Conecta-TE</th>
                                        <th>Proyecto</th>
                                        <th>Equipo -- Servicio</th>
                                        <th>Descripción Producto</th>
                                        <th>Nombre Producto</th>
                                        <th>Fecha prevista entrega</th>
                                        <th>Fecha creación</th>
                                        <th>Información Producto</th>
                                    </tr>
                                </thead>
                                <tbody >';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $idSol = $datos['idSol'];
                    $comprobar = SolicitudEspecifica::validarResultadoProducto($idSol,$datos['idEqu']);
                    $consultaProd = "SELECT * FROM pys_productos
                    INNER JOIN pys_actproductos ON pys_productos.idProd = pys_actproductos.idProd
                    WHERE idSol = '$idSol' AND pys_actproductos.est = 1 AND pys_productos.est = 1;";
                    $resultadoProd = mysqli_query($connection, $consultaProd);
                    $nomProduc = "";
                    if ($resultadoProd == TRUE ){
                        $datosProd = mysqli_fetch_array($resultadoProd);
                        $nomProduc = $datosProd['nombreProd'];
                    }
                    if ($comprobar == 1){
                        $color = 'teal';
                        $modal = 'COM';
                    } else {
                        $color = 'red';
                        if ($datos['idEqu'] == 'EQU001'){
                            $modal = "REA";
                        } else if ($datos['idEqu'] == 'EQU002'){
                            $modal = "DIS";
                        } else if ($datos['idEqu'] == 'EQU003'){
                            $modal = "SOP";
                        }
                    }
                    $string .= '    <tr>
                                        <td>'.$datos['idSolIni'].'</td>
                                        <td>P'.$datos['idSol'].'</td>
                                        <td>'.$datos['codProy'].'</td>
                                        <td>'.$datos['nombreProy'].'</td>
                                        <td>'.$datos['nombreEqu'].' -- '.$datos['nombreSer'].'</td>
                                        <td><p class="truncate">'.$datos['ObservacionAct'].'</p></td>
                                        <td>'.$nomProduc.'</td>
                                        <td>'.$datos['fechPrev'].'</td>
                                        <td>'.$datos['fechSol'].'</td>
                                        <td><a href="#modalResulProTer" data-position="button" class="modal-trigger tooltipped" data-tooltip="Mas información del Producto" onclick="envioData(\''.$modal.$idSol.'\',\'modalResulProTer.php\');"><i class="material-icons '.$color.'-text">info_outline</i></a></td>
                                    </tr>';
                }
                $string .= '    </tbody>
                            </table>';
            } else {
                $string = '<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados </h6></div>';
            }
            echo $string;
        
        }

        public static function cargaBusqueda($buscar) {
            require('../Core/connection.php');
            $buscar = mysqli_real_escape_string($connection, $buscar);
            require_once('mdl_solicitudEspecifica.php');
           
            $consulta = "SELECT pys_solicitudes.idSolIni, pys_actsolicitudes.idSol, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_solicitudes.descripcionSol, pys_equipos.nombreEqu, pys_equipos.idEqu, pys_servicios.nombreSer,  pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.fechPrev, pys_solicitudes.fechSol
            FROM pys_solicitudes
            INNER JOIN pys_actsolicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
            INNER JOIN pys_cursosmodulos ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM
            INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
            INNER JOIN pys_servicios ON pys_actsolicitudes.idSer = pys_servicios.idSer
            INNER JOIN pys_equipos ON pys_servicios.idEqu = pys_equipos.idEqu
            INNER JOIN pys_asignados ON pys_asignados.idsol = pys_actsolicitudes.idSol
            INNER JOIN pys_personas ON pys_asignados.idResponRegistro = pys_personas.idPersona
	        WHERE pys_solicitudes.est = '1' AND pys_actsolicitudes.est = '1' AND pys_actualizacionproy.est = '1' AND pys_personas.est = '1' AND pys_cursosmodulos.estProy = '1' AND pys_cursosmodulos.estCurso = '1' AND pys_equipos.est = '1' AND pys_asignados.est = '2' AND pys_servicios.est = '1' AND pys_servicios.productoOservicio = 'SI' AND (pys_servicios.idEqu ='EQU001' OR pys_servicios.idEqu ='EQU002' OR pys_servicios.idEqu ='EQU003') AND  pys_solicitudes.idTSol = 'TSOL02' AND ((pys_actsolicitudes.idEstSol != 'ESS001') AND (pys_actsolicitudes.idEstSol != 'ESS007')) AND ((pys_actualizacionproy.codProy LIKE '%$buscar%') OR (pys_actualizacionproy.nombreProy LIKE '%$buscar%') OR (pys_actsolicitudes.idSol LIKE '%$buscar%')) 
            ORDER BY pys_actsolicitudes.idSol DESC;";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                $string = ' <table class="responsive-table  left" id ="resulProd">
                                <thead>
                                    <tr>
                                        <th>Código solicitud</th>
                                        <th>Producto</th>
                                        <th>Cód. proyecto en Conecta-TE</th>
                                        <th>Proyecto</th>
                                        <th>Equipo -- Servicio</th>
                                        <th>Descripción Producto</th>
                                        <th>Nombre Producto</th>
                                        <th>Fecha prevista entrega</th>
                                        <th>Fecha creación</th>
                                        <th>Información Producto</th>
                                    </tr>
                                </thead>
                                <tbody>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $idSol = $datos['idSol'];
                    $comprobar = SolicitudEspecifica::validarResultadoProducto($idSol,$datos['idEqu']);
                    $consultaProd = "SELECT * FROM pys_productos
                    INNER JOIN pys_actproductos ON pys_productos.idProd = pys_actproductos.idProd
                    WHERE idSol = '$idSol' AND pys_actproductos.est = 1 AND pys_productos.est = 1;";
                    $resultadoProd = mysqli_query($connection, $consultaProd);
                    $nomProduc = "";
                    if ($resultadoProd == TRUE ){
                        $datosProd = mysqli_fetch_array($resultadoProd);
                        $nomProduc = $datosProd['nombreProd'];
                    }
                    if ($comprobar == 1){
                        $color = 'teal';
                        $modal = 'COM';
                    } else {
                        $color = 'red';
                        if ($datos['idEqu'] == 'EQU001'){
                            $modal = "REA";
                        } else if ($datos['idEqu'] == 'EQU002'){
                            $modal = "DIS";
                        } else if ($datos['idEqu'] == 'EQU003'){
                            $modal = "SOP";
                        }
                    }
                    $string .= '    <tr>
                                        <td>'.$datos['idSolIni'].'</td>
                                        <td>P'.$datos['idSol'].'</td>
                                        <td>'.$datos['codProy'].'</td>
                                        <td>'.$datos['nombreProy'].'</td>
                                        <td>'.$datos['nombreEqu'].' -- '.$datos['nombreSer'].'</td>
                                        <td><p class="truncate">'.$datos['ObservacionAct'].'</p></td>
                                        <td>'.$nomProduc.'</td>
                                        <td>'.$datos['fechPrev'].'</td>
                                        <td>'.$datos['fechSol'].'</td>
                                        <td><a href="#modalResulProTer" data-position="right" class="modal-trigger tooltipped" data-tooltip="Mas información del Producto" onclick="envioData(\''.$modal.$idSol.'\',\'modalResulProTer.php\')"><i class="material-icons '.$color.'-text">info_outline</i></a></td>
                                    </tr>';
                }
                $string .= '    </tbody>
                            </table>';
            } else {
                $string = '<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda: <strong>'.$buscar.'</strong></h6></div>';
            }
            echo $string;
        
        }

    }  
?>  