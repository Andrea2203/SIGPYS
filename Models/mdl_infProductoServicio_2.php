<?php

    Class InformeProductoServicio{

        public static function obtenerNombreProyecto ($idProy) {
            require('../Core/connection.php');
            $consulta = "SELECT codProy, nombreProy FROM pys_actualizacionproy WHERE idProy = '$idProy' AND est = '1';";
            $resultado = mysqli_query($connection, $consulta);
            $datos = mysqli_fetch_array($resultado);
            $nombre = $datos['codProy']." - ".$datos['nombreProy'];
            return $nombre;
            mysqli_close($connection);
        }

        public static function busquedaProductoServicioConSaldo ($idProy) {
            require('../Core/connection.php');
            $conSaldo = 0;
            $sinSaldo = 0;
            $proyectos = [];
            $solicitudesSaldo = [];
            if ($idProy != null) {
                $solicitudes = InformeProductoServicio::verificacionSaldos($idProy);
                foreach ($solicitudes as $solicitud) {
                    if (substr($solicitud, 0, 2) == "C_") {
                        $conSaldo += 1;
                    } else {
                        $sinSaldo += 1;
                    }
                }
                if ($conSaldo > 0) {
                    InformeProductoServicio::mostrarSaldos($solicitudes);
                } else {
                    $nombreProyecto = InformeProductoServicio::obtenerNombreProyecto($idProy);
                    echo '  <div class="row">
                                <div class="col l8 m8 s12 offset-l2 offset-m2">
                                    <div class="card teal darken-1">
                                        <div class="card-content white-text">
                                        <span class="card-title">Productos/Servicios</span>
                                        <div class="divider"></div>
                                        <p>No se encontraron productos/servicios en estado <i>"Terminado / Terminado Parcial"</i> con saldo disponible para el proyecto: <strong>'.$nombreProyecto.'</strong></p>
                                        <p>Para conocer los productos/servicios que tienen saldo disponible, por favor consulte otro proyecto.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                    
                }
            } else {
                $solicitudes = InformeProductoServicio::verificacionSaldosTotales();
                foreach ($solicitudes as $solicitud) {
                    if (substr($solicitud, 0, 2) == "C_") {
                        $conSaldo += 1;
                        $idSol = substr($solicitud, 2);
                        if (!in_array($idSol, $solicitudesSaldo)) {
                            $solicitudesSaldo[] = $idSol;
                        }
                        $consulta = "SELECT pys_cursosmodulos.idProy FROM pys_actsolicitudes
                            INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
                            WHERE pys_actsolicitudes.est = '1' AND pys_actsolicitudes.idSol = '$idSol';";
                        $resultado = mysqli_query($connection, $consulta);
                        $datos = mysqli_fetch_array($resultado);
                        if (!in_array($datos['idProy'], $proyectos)) {
                            $proyectos[] = $datos['idProy'];
                        }
                    } else {
                        $sinSaldo += 1;
                    }
                }
                if ($conSaldo > 0) {
                    $tabla = InformeProductoServicio::mostrarSaldosTotales($solicitudesSaldo, $proyectos);
                    echo $tabla;
                } 
            }
            mysqli_close($connection);
        }

        public static function informeConSaldo ($idProy) {
            require('../Core/connection.php');
            $sinSaldo = 0;
            $conSaldo = 0;
            $proyectos = [];
            $solicitudesSaldo = [];
            if ($idProy == null) {
                $solicitudes = InformeProductoServicio::verificacionSaldosTotales();
                foreach ($solicitudes as $solicitud) {
                    if (substr($solicitud, 0, 2) == "C_") {
                        $conSaldo += 1;
                        $idSol = substr($solicitud, 2);
                        if (!in_array($idSol, $solicitudesSaldo)) {
                            $solicitudesSaldo[] = $idSol;
                        }
                        $consulta = "SELECT pys_cursosmodulos.idProy FROM pys_actsolicitudes
                            INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
                            WHERE pys_actsolicitudes.est = '1' AND pys_actsolicitudes.idSol = '$idSol';";
                        $resultado = mysqli_query($connection, $consulta);
                        $datos = mysqli_fetch_array($resultado);
                        if (!in_array($datos['idProy'], $proyectos)) {
                            $proyectos[] = $datos['idProy'];
                        }
                    } else {
                        $sinSaldo += 1;
                    }
                }
                if ($conSaldo > 0) {
                    header("Content-type: application/vnd.ms-excel");
                    header("Content-Disposition: attachment; filename=inf-producto-servicio-con-saldo.xls");
                    header('Cache-Control: max-age=0');
                    header("Pragma: no-cache");
                    header("Expires: 0");
                    echo '  <html>
                                <head>
                                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
                                    <style>
                                        .row-teal3 {
                                            background-color: #80cbc4;
                                        }
                                        .row-teal4 {
                                            background-color: #b2dfdb;
                                        }
                                        .row-resumen {
                                            text-align: center;
                                            height: 40px;
                                            vertical-align: middle;
                                            background-color: #b2dfdb;
                                        }
                                        .row-total {
                                            text-align: center;
                                            background-color: #e0e0e0;
                                        }
                                    </style>
                                </head>
                                <body>
                                    <h1 align="center">Informe Productos/Servicios con saldo</h1>';
                    $tabla = InformeProductoServicio::mostrarSaldosTotales($solicitudesSaldo, $proyectos);
                    echo $tabla;
                    echo '      </body>
                            </html>';
                }
            } else {
                $nombreProyecto = InformeProductoServicio::obtenerNombreProyecto($idProy);
                $solicitudes = InformeProductoServicio::verificacionSaldos($idProy);
                foreach ($solicitudes as $solicitud) {
                    if (substr($solicitud, 0, 2) == "C_") {
                        $conSaldo += 1;
                    } else {
                        $sinSaldo += 1;
                    }
                }
                if ($conSaldo > 0) {
                    header("Content-type: application/vnd.ms-excel");
                    header("Content-Disposition: attachment; filename=inf-producto-servicio-con-saldo.xls");
                    header('Cache-Control: max-age=0');
                    header("Pragma: no-cache");
                    header("Expires: 0");
                    echo '  <html>
                                <head>
                                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
                                    <style>
                                        .row-teal3 {
                                            background-color: #80cbc4;
                                        }
                                        .row-teal4 {
                                            background-color: #b2dfdb;
                                        }
                                        .row-resumen {
                                            text-align: center;
                                            height: 40px;
                                            vertical-align: middle;
                                            background-color: #b2dfdb;
                                        }
                                        .row-total {
                                            text-align: center;
                                            background-color: #e0e0e0;
                                        }
                                    </style>
                                </head>
                                <body>';
                    
                    echo "          <h1 align='center'>Informe Productos/Servicios con saldo por proyecto</h1>
                                    <h3>Proyecto: $nombreProyecto</h3>";
                    if ($conSaldo > 0) {
                        InformeProductoServicio::mostrarSaldos($solicitudes);
                    }
                    echo '      </body>
                            </html>';
                } else {
                    echo "<script> alert ('No hay productos con saldo positivo para el proyecto \"$nombreProyecto\"');</script>";
                    echo '<meta http-equiv="Refresh" content="0;url=../Views/infProductosConSaldo.php">';
                }
            }
            mysqli_close($connection);
        }

        public static function mostrarSaldosTotales ($solicitudes, $proyectos) {
            require('../Core/connection.php');
            $tabla = '  <table class="centered browser-default responsive-table highlight teal lighten-4" id="tblMain" border="1">
                        <thead>
                            <tr class="row-teal3">
                                <th>Proyecto</th>
                                <th>Solicitudes</th>
                            </tr>
                        </thead>
                        <tbody>';
            foreach ($proyectos as $proyecto) {
                $acumuladoPresupuesto = 0;
                $acumuladoGralPS = 0;
                $acumuladoGralPresupuesto = 0;
                $acumuladoGralHoras = 0;
                $acumuladoGralMinutos = 0;
                $nombreProyecto = InformeProductoServicio::obtenerNombreProyecto($proyecto);
                $productos = InformeProductoServicio::verificacionSaldos($proyecto);
                $tabla .= ' <tr>
                                <td style="width: auto; vertical-align: middle; text-align: center;">'.$nombreProyecto.'</td>
                                <td>
                                    <table border="1">
                                        <thead>
                                            <tr class="row-teal3">
                                                <th>Cod. Producto/Servicio</th>
                                                <th>Estado Producto/Servicio</th>
                                                <th>Producto/Servicio</th>
                                                <th>Presupuesto Producto/Servicio</th>
                                                <th>Asignados</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                foreach ($productos as $producto) {
                    if (substr($producto, 0, 2) == "C_") {
                        $idSol = substr($producto, 2);
                        $consulta = "SELECT pys_actsolicitudes.idSol, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.presupuesto, pys_estadosol.nombreEstSol, pys_actualizacionproy.idProy
                            FROM pys_actualizacionproy
                            INNER JOIN pys_cursosmodulos ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
                            INNER JOIN pys_actsolicitudes ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
                            INNER JOIN pys_estadosol ON pys_actsolicitudes.idEstSol = pys_estadosol.idEstSol
                            WHERE pys_actsolicitudes.est ='1' AND pys_cursosmodulos.estProy='1' AND pys_actualizacionproy.est='1' AND pys_actsolicitudes.idSolicitante='' AND pys_actsolicitudes.idSol = '$idSol';";
                        $resultado = mysqli_query($connection, $consulta);
                        while ($datos = mysqli_fetch_array($resultado)) {
                            $tabla .= '     <tr>
                                                <td style="width: auto; vertical-align: middle; text-align: center;">P'.$idSol.'</td>
                                                <td style="width: auto; vertical-align: middle; text-align: center;">'.$datos['nombreEstSol'].'</td>
                                                <td style="width: auto; vertical-align: middle; text-align: left;">'.$datos['ObservacionAct'].'</td>
                                                <td style="width: auto; vertical-align: middle; text-align: center;">$ '.number_format($datos['presupuesto'], 2, ",", ".").'</td>
                                                <td>';
                            $acumuladoHoras = 0;
                            $acumuladoMinutos = 0;
                            $acumuladoPS = 0;
                            //$idProy = $datos['idProy'];
                            $consulta2 = "SELECT pys_asignados.idAsig, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_asignados.idPersona
                                FROM pys_asignados
                                INNER JOIN pys_personas ON pys_asignados.idPersona = pys_personas.idPersona
                                WHERE (pys_asignados.est = '1' OR pys_asignados.est = '2') AND pys_asignados.idSol='$idSol'
                                ORDER BY pys_personas.apellido1;";
                            $resultado2 = mysqli_query($connection, $consulta2);
                            $registros2 = mysqli_num_rows($resultado2);
                            $acumuladoPresupuesto += $datos['presupuesto'];
                            if ($registros2 > 0) {
                                $tabla .= '         <table border="1">
                                                        <thead>
                                                            <tr class="row-teal4">
                                                                <th>Asignado</th>
                                                                <th>Tiempo invertido</th>
                                                                <th>Valor Producto/Servicio</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>';
                                while ($datos2 = mysqli_fetch_array($resultado2)) {
                                    $horas = 0;
                                    $minutos = 0;
                                    $valorPS = 0;
                                    $nombreAsignado = $datos2['apellido1']." ".$datos2['apellido2']." ".$datos2['nombres'];
                                    $idAsignado = $datos2['idAsig'];
                                    $idPersona = $datos2['idPersona'];
                                    $consulta3 = "SELECT SUM(horaTiempo) AS horas, SUM(minTiempo) AS minutos, fechTiempo
                                        FROM pys_tiempos
                                        WHERE estTiempo = '1' AND idAsig = '$idAsignado'
                                        GROUP BY fechTiempo;";
                                    $resultado3 = mysqli_query($connection, $consulta3);
                                    while ($datos3 = mysqli_fetch_array($resultado3)){
                                        $fecha = $datos3['fechTiempo'];
                                        $horas += $datos3['horas'];
                                        $minutos += $datos3['minutos'];
                                        $consulta4 = "SELECT salario
                                            FROM pys_salarios
                                            WHERE estSal = '1' AND idPersona='$idPersona' AND (mes <= '$fecha' AND anio >= '$fecha');";
                                        $resultado4 = mysqli_query($connection, $consulta4);
                                        $datos4 = mysqli_fetch_array($resultado4);
                                        $salarioHor = $datos4['salario'];
                                        $salarioMin = $salarioHor / 60;
                                        $valorPS += (($datos3['horas'] * 60) + $datos3['minutos']) * $salarioMin;
                                    }
                                    if ($minutos >= 60) {
                                        $horas = ($minutos / 60) + $horas;
                                        $minutos = $minutos % 60;
                                        $horas = intval($horas);
                                        $minutos = intval($minutos);
                                    }
                                    $totalMinutos = ($horas * 60) + $minutos;
                                    if ($totalMinutos != 0) {
                                        $acumuladoHoras += $horas;
                                        $acumuladoMinutos += $minutos;
                                        $acumuladoPS += $valorPS;
                                        $tabla .= '         <tr>
                                                                <td>'.$nombreAsignado.'</td>
                                                                <td>'.$horas.' Horas y '.$minutos.' Minutos</td>
                                                                <td>$ '.number_format($valorPS, 2, ",", ".").'</td>
                                                            </tr>';
                                    }
                                }
                                $acumuladoGralPS += $acumuladoPS;
                                $acumuladoGralPresupuesto += $datos['presupuesto'];
                                if ($acumuladoMinutos >= 60) {
                                    $acumuladoHoras = ($acumuladoMinutos / 60) + $acumuladoHoras;
                                    $acumuladoMinutos = $acumuladoMinutos % 60;
                                    $acumuladoHoras = intval($acumuladoHoras);
                                    $acumuladoMinutos = intval($acumuladoMinutos);
                                }
                                $diferencia = $datos['presupuesto'] - $acumuladoPS;
                                $tabla .= '                 <tr class="teal lighten-3">
                                                                <td class="row-total"><strong>Total tiempo invertido:</strong><br>'.$acumuladoHoras.' Horas y '.$acumuladoMinutos.' Minutos</td>
                                                                <td class="row-total"><strong>Costo total P/S:</strong><br>$ '.number_format($acumuladoPS, 2, ',', '.').'</td>
                                                                <td class="row-total"><strong>Diferencia:</strong><br>$ '.number_format($diferencia, 2, ',', '.').'</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>';
                            }
                            $tabla .= '         </td>
                                            </tr>';
                            $acumuladoGralHoras += $acumuladoHoras;
                            $acumuladoGralMinutos += $acumuladoMinutos;
                            if ($acumuladoGralMinutos >= 60) {
                                $acumuladoGralHoras = ($acumuladoGralMinutos / 60) + $acumuladoGralHoras;
                                $acumuladoGralMinutos = $acumuladoGralMinutos % 60;
                                $acumuladoGralHoras = intval($acumuladoGralHoras);
                                $acumuladoGralMinutos = intval($acumuladoGralMinutos);
                            }
                            $acumuladoHoras = 0;
                            $acumuladoMinutos = 0;
                            $acumuladoPS = 0;
                        }
                    }
                }
                $tabla .= '             </tbody>
                                        <tfoot>
                                            <tr class="row-resumen">
                                                <td colspan="5" class="teal-text">Resumen productos / servicios con saldo disponible para el proyecto: <strong>'.$nombreProyecto.'</strong></td>
                                            </tr>
                                            <tr class="row-total">
                                                <td colspan="2"><strong>Presupuesto de proyecto: </strong><br>$ '.number_format($acumuladoPresupuesto, 2, ',', '.').'</td>
                                                <td><strong>Tiempo trabajado: </strong><br>'.$acumuladoGralHoras.' Horas y '.$acumuladoGralMinutos.' Minutos</td>
                                                <td><strong>Valor total de productos/servicios:</strong><br>$ '.number_format($acumuladoGralPS, 2, ',', '.').'</td>
                                                <td><strong>Diferencia:</strong><br>$ '.(number_format(($acumuladoGralPresupuesto - $acumuladoGralPS), 2, ',', '.')).'</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </td>
                            </tr>';
            }
            $tabla .= '      </tbody>
                    </table>';
            return $tabla;
            mysqli_close($connection);
        }

        public static function mostrarSaldos ($solicitudes) {
            require('../Core/connection.php');
            $acumuladoPresupuesto = 0;
            $acumuladoGralPS = 0;
            $acumuladoGralPresupuesto = 0;
            $acumuladoGralHoras = 0;
            $acumuladoGralMinutos = 0;
            echo '  <table class="centered browser-default responsive-table" id="tblMain" border="1">
                        <thead>
                            <tr class="row-teal3">
                                <th>Cod. Producto/Servicio</th>
                                <th>Estado Producto/Servicio</th>
                                <th>Producto/Servicio</th>
                                <th>Presupuesto Producto/Servicio</th>
                                <th>Asignados</th>
                            </tr>
                        </thead>
                        <tbody>';
            foreach ($solicitudes as $solicitud) {
                if (substr($solicitud, 0, 2) == "C_") {
                    $solicitud = substr($solicitud,2);
                }
                $consulta = "SELECT pys_actsolicitudes.idSol, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.presupuesto, pys_estadosol.nombreEstSol, pys_actualizacionproy.idProy
                    FROM pys_actualizacionproy
                    INNER JOIN pys_cursosmodulos ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
                    INNER JOIN pys_actsolicitudes ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
                    INNER JOIN pys_estadosol ON pys_actsolicitudes.idEstSol = pys_estadosol.idEstSol
                    WHERE pys_actsolicitudes.est ='1' AND pys_cursosmodulos.estProy='1' AND pys_actualizacionproy.est='1' AND pys_actsolicitudes.idSolicitante='' AND pys_actsolicitudes.idSol = '$solicitud';";
                $resultado = mysqli_query($connection, $consulta);
                while ($datos = mysqli_fetch_array($resultado)) {
                    $acumuladoHoras = 0;
                    $acumuladoMinutos = 0;
                    $acumuladoPS = 0;
                    $idProy = $datos['idProy'];
                    $consulta2 = "SELECT pys_asignados.idAsig, pys_personas.apellido1, pys_personas.apellido2, pys_personas.nombres, pys_asignados.idPersona
                        FROM pys_asignados
                        INNER JOIN pys_personas ON pys_asignados.idPersona = pys_personas.idPersona
                        WHERE (pys_asignados.est = '1' OR pys_asignados.est = '2') AND pys_asignados.idSol='$solicitud'
                        ORDER BY pys_personas.apellido1;";
                    $resultado2 = mysqli_query($connection, $consulta2);
                    $registros2 = mysqli_num_rows($resultado2);
                    $acumuladoPresupuesto += $datos['presupuesto'];
                    echo '  <tr>
                                <td style="width: auto; vertical-align: middle; text-align: center;">P'.$datos['idSol'].'</td>
                                <td style="width: auto; vertical-align: middle; text-align: center;">'.$datos['nombreEstSol'].'</td>
                                <td style="width: auto; vertical-align: middle; text-align: left;">'.$datos['ObservacionAct'].'</td>
                                <td style="width: auto; vertical-align: middle; text-align: center;">$ '.number_format($datos['presupuesto'], 2, ",", ".").'</td>
                                <td>
                                    <table class="centered" border="1">
                                        <thead>
                                            <tr class="row-teal4">
                                                <th>Asignado</th>
                                                <th>Tiempo invertido</th>
                                                <th>Valor Producto/Servicio</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                    while ($datos2 = mysqli_fetch_array($resultado2)) {
                        $horas = 0;
                        $minutos = 0;
                        $valorPS = 0;
                        $nombreAsignado = $datos2['apellido1']." ".$datos2['apellido2']." ".$datos2['nombres'];
                        $idAsignado = $datos2['idAsig'];
                        $idPersona = $datos2['idPersona'];
                        $consulta3 = "SELECT SUM(horaTiempo) AS horas, SUM(minTiempo) AS minutos, fechTiempo
                            FROM pys_tiempos
                            WHERE estTiempo = '1' AND idAsig = '$idAsignado'
                            GROUP BY fechTiempo;";
                        $resultado3 = mysqli_query($connection, $consulta3);
                        while ($datos3 = mysqli_fetch_array($resultado3)){
                            $fecha = $datos3['fechTiempo'];
                            $horas += $datos3['horas'];
                            $minutos += $datos3['minutos'];
                            $consulta4 = "SELECT salario
                                FROM pys_salarios
                                WHERE estSal = '1' AND idPersona='$idPersona' AND (mes <= '$fecha' AND anio >= '$fecha');";
                            $resultado4 = mysqli_query($connection, $consulta4);
                            $datos4 = mysqli_fetch_array($resultado4);
                            $salarioHor = $datos4['salario'];
                            $salarioMin = $salarioHor / 60;
                            $valorPS += (($datos3['horas'] * 60) + $datos3['minutos']) * $salarioMin;
                        }
                        if ($minutos >= 60) {
                            $horas = ($minutos / 60) + $horas;
                            $minutos = $minutos % 60;
                            $horas = intval($horas);
                            $minutos = intval($minutos);
                        }
                        $totalMinutos = ($horas * 60) + $minutos;
                        if ($totalMinutos != 0) {
                            $acumuladoHoras += $horas;
                            $acumuladoMinutos += $minutos;
                            $acumuladoPS += $valorPS;
                            echo '              <tr>
                                                    <td>'.$nombreAsignado.'</td>
                                                    <td>'.$horas.' Horas y '.$minutos.' Minutos</td>
                                                    <td>$ '.number_format($valorPS, 2, ",", ".").'</td>
                                                </tr>';
                        }
                    }
                    $acumuladoGralPS += $acumuladoPS;
                    $acumuladoGralPresupuesto += $datos['presupuesto'];
                    if ($acumuladoMinutos >= 60) {
                        $acumuladoHoras = ($acumuladoMinutos / 60) + $acumuladoHoras;
                        $acumuladoMinutos = $acumuladoMinutos % 60;
                        $acumuladoHoras = intval($acumuladoHoras);
                        $acumuladoMinutos = intval($acumuladoMinutos);
                    }
                    $diferencia = $datos['presupuesto'] - $acumuladoPS;
                    echo '                  <tr class="teal lighten-3">
                                                <td class="row-total"><strong>Total tiempo invertido:</strong><br>'.$acumuladoHoras.' Horas y '.$acumuladoMinutos.' Minutos</td>
                                                <td class="row-total"><strong>Costo total P/S:</strong><br>$ '.number_format($acumuladoPS, 2, ',', '.').'</td>
                                                <td class="row-total"><strong>Diferencia:</strong><br>$ '.number_format($diferencia, 2, ',', '.').'</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>';
                    $acumuladoGralHoras += $acumuladoHoras;
                    $acumuladoGralMinutos += $acumuladoMinutos;
                    if ($acumuladoGralMinutos >= 60) {
                        $acumuladoGralHoras = ($acumuladoGralMinutos / 60) + $acumuladoGralHoras;
                        $acumuladoGralMinutos = $acumuladoGralMinutos % 60;
                        $acumuladoGralHoras = intval($acumuladoGralHoras);
                        $acumuladoGralMinutos = intval($acumuladoGralMinutos);
                    }
                    $acumuladoHoras = 0;
                    $acumuladoMinutos = 0;
                    $acumuladoPS = 0;
                }
            }
            $consulta5 = "SELECT codProy, nombreProy FROM pys_actualizacionproy WHERE idProy = '$idProy' AND est = '1';";
            $resultado5 = mysqli_query($connection, $consulta5);
            $datos5 = mysqli_fetch_array($resultado5);
            $nombreProyecto = $datos5['codProy'].' - '.$datos5['nombreProy'];
            echo '      </tbody>
                        <tfooter>
                            <tr class="row-resumen">
                                <td colspan="5" class="teal-text">Resumen productos / servicios con saldo disponible para el proyecto: <strong>'.$nombreProyecto.'</strong></td>
                            </tr>
                            <tr class="row-total">
                                <td colspan="2"><strong>Presupuesto de proyecto: </strong><br>$ '.number_format($acumuladoPresupuesto, 2, ',', '.').'</td>
                                <td><strong>Tiempo trabajado: </strong><br>'.$acumuladoGralHoras.' Horas y '.$acumuladoGralMinutos.' Minutos</td>
                                <td><strong>Valor total de productos/servicios:</strong><br>$ '.number_format($acumuladoGralPS, 2, ',', '.').'</td>
                                <td><strong>Diferencia:</strong><br>$ '.(number_format(($acumuladoGralPresupuesto - $acumuladoGralPS), 2, ',', '.')).'</td>
                            </tr>
                        </tfooter>
                    </table>';
            mysqli_close($connection);
        }

        public static function verificacionSaldosTotales () {
            require('../Core/connection.php');
            $productos = [];
            $acumuladoEjecutado = 0;
            /*$consulta = "SELECT pys_actsolicitudes.idSol, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.presupuesto, pys_estadosol.nombreEstSol, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy
                    FROM pys_actualizacionproy 
                    INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idProy = pys_actualizacionproy.idProy 
                    INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM 
                    INNER JOIN pys_estadosol ON pys_estadosol.idEstSol = pys_actsolicitudes.idEstSol 
                    WHERE pys_actsolicitudes.est ='1' AND pys_cursosmodulos.estProy='1' AND pys_actualizacionproy.est='1' 
                    AND pys_actsolicitudes.idSolicitante='' AND pys_estadosol.est = '1' 
                    AND (pys_actsolicitudes.idEstSol = 'ESS001' OR pys_actsolicitudes.idEstSol = 'ESS006')
                    AND pys_actsolicitudes.presupuesto != '0'
                    ORDER BY pys_actsolicitudes.idSol;";*/
            $consulta = "SELECT pys_solicitudes.fechSol,pys_actsolicitudes.idSol, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.presupuesto, pys_estadosol.nombreEstSol, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy
                FROM pys_actualizacionproy 
                INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idProy = pys_actualizacionproy.idProy 
                INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM 
                INNER JOIN pys_estadosol ON pys_estadosol.idEstSol = pys_actsolicitudes.idEstSol 
                INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
                WHERE pys_actsolicitudes.est ='1' AND pys_cursosmodulos.estProy='1' AND pys_actualizacionproy.est='1' 
                AND pys_actsolicitudes.idSolicitante='' AND pys_estadosol.est = '1' 
                AND (pys_actsolicitudes.idEstSol = 'ESS001' OR pys_actsolicitudes.idEstSol = 'ESS006')
                AND pys_actsolicitudes.presupuesto != '0'
                AND pys_solicitudes.est = '1'
                AND pys_solicitudes.fechSol >= '2018-01-01'
                ORDER BY pys_actsolicitudes.idSol;";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                while ($datos = mysqli_fetch_array($resultado)) {
                    $idSol = $datos['idSol'];
                    $consulta2 = "SELECT pys_tiempos.horaTiempo AS horas, pys_tiempos.minTiempo AS minutos, pys_tiempos.fechTiempo AS fecha, 
                    pys_asignados.idPersona, pys_cursosmodulos.idProy, pys_personas.nombres, pys_personas.apellido1, pys_personas.apellido2, pys_actsolicitudes.idSol
                        FROM pys_tiempos
                        INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig
                        INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_asignados.idSol
                        INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
                        INNER JOIN pys_personas ON pys_personas.idPersona = pys_asignados.idPersona
                        WHERE pys_tiempos.estTiempo = '1' AND (pys_asignados.est = '1' OR pys_asignados.est = '2') AND pys_actsolicitudes.est = '1'
                        AND (pys_actsolicitudes.idEstSol = 'ESS001' OR pys_actsolicitudes.idEstSol = 'ESS006')
                        AND pys_actsolicitudes.idSol = '$idSol'
                        ORDER BY pys_actsolicitudes.idSol;";
                    $resultado2 = mysqli_query($connection, $consulta2);
                    while ($datos2 = mysqli_fetch_array($resultado2)) {
                        $fecha = $datos2['fecha'];
                        $consulta3 = "SELECT salario 
                            FROM pys_salarios 
                            WHERE estSal = '1' AND idPersona = '".$datos2['idPersona']."' 
                            AND (mes <= '".$fecha."' AND anio >= '".$fecha."');";
                        $resultado3 = mysqli_query($connection, $consulta3);
                        $datos3 = mysqli_fetch_array($resultado3);
                        $salarioHora = $datos3['salario'];
                        $salarioMinuto = $salarioHora / 60;
                        $minutosTotales = ($datos2['horas'] * 60) + $datos2['minutos'];
                        $valorTotal = $minutosTotales * $salarioMinuto;
                        $acumuladoEjecutado += $valorTotal;
                    }
                    $presupuesto = $datos['presupuesto'];
                    if ($acumuladoEjecutado > $presupuesto || ($presupuesto == 0 && $acumuladoEjecutado == 0)) {
                        $productos[] = "S_".$idSol;
                    } else {
                        $productos[] = "C_".$idSol;
                    }
                    $acumuladoEjecutado = 0;
                }
                return $productos;
            } else {
                return false;
            }
            mysqli_close($connection);
        }

        public static function verificacionSaldos ($idProy) {
            require('../Core/connection.php');
            $productos = [];
            $acumuladoEjecutado = 0;
            $consulta = "SELECT pys_actsolicitudes.idSol, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.presupuesto, pys_estadosol.nombreEstSol, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy
                FROM pys_actualizacionproy 
                INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idProy = pys_actualizacionproy.idProy 
                INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM 
                INNER JOIN pys_estadosol ON pys_estadosol.idEstSol = pys_actsolicitudes.idEstSol 
                WHERE pys_actsolicitudes.est ='1' AND pys_cursosmodulos.estProy='1' AND pys_actualizacionproy.est='1' 
                AND pys_actsolicitudes.idSolicitante='' AND pys_actualizacionproy.idProy ='$idProy' AND pys_estadosol.est = '1' 
                AND (pys_actsolicitudes.idEstSol = 'ESS001' OR pys_actsolicitudes.idEstSol = 'ESS006')
                ORDER BY pys_actsolicitudes.idSol;";
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                while ($datos = mysqli_fetch_array($resultado)) {
                    $idSol = $datos['idSol'];
                    $consulta2 = "SELECT pys_tiempos.horaTiempo AS horas, pys_tiempos.minTiempo AS minutos, pys_tiempos.fechTiempo AS fecha, 
                    pys_asignados.idPersona, pys_cursosmodulos.idProy, pys_personas.nombres, pys_personas.apellido1, pys_personas.apellido2, pys_actsolicitudes.idSol
                        FROM pys_tiempos
                        INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig
                        INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_asignados.idSol
                        INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
                        INNER JOIN pys_personas ON pys_personas.idPersona = pys_asignados.idPersona
                        WHERE pys_tiempos.estTiempo = '1' AND (pys_asignados.est = '1' OR pys_asignados.est = '2') AND pys_actsolicitudes.est = '1'
                        AND (pys_actsolicitudes.idEstSol = 'ESS001' OR pys_actsolicitudes.idEstSol = 'ESS006')
                        AND pys_cursosmodulos.idProy = '$idProy' AND pys_actsolicitudes.idSol = '$idSol'
                        ORDER BY pys_actsolicitudes.idSol;";
                    $resultado2 = mysqli_query($connection, $consulta2);
                    while ($datos2 = mysqli_fetch_array($resultado2)){
                        $fecha = $datos2['fecha'];
                        $consulta3 = "SELECT salario 
                            FROM pys_salarios 
                            WHERE estSal = '1' AND idPersona = '".$datos2['idPersona']."' 
                            AND (mes <= '".$fecha."' AND anio >= '".$fecha."');";
                        $resultado3 = mysqli_query($connection, $consulta3);
                        $datos3 = mysqli_fetch_array($resultado3);
                        $salarioHora = $datos3['salario'];
                        $salarioMinuto = $salarioHora / 60;
                        $minutosTotales = ($datos2['horas'] * 60) + $datos2['minutos'];
                        $valorTotal = $minutosTotales * $salarioMinuto;
                        $acumuladoEjecutado += $valorTotal;
                    }
                    $presupuesto = $datos['presupuesto'];
                    if ($acumuladoEjecutado > $presupuesto || ($presupuesto == 0 && $acumuladoEjecutado == 0)) {
                        $productos[] = "S_".$idSol;
                    } else {
                        $productos[] = "C_".$idSol;
                    }
                    $acumuladoEjecutado = 0;
                }
            }
            return $productos;
            mysqli_close($connection);
        }

        public static function busqueda ($fechaInicial, $fechaFinal, $historico, $proyecto) {
            require('../Core/connection.php');
            
            /** Verificación de las fechas */
            if ($fechaInicial != null || $fechaFinal != null) {
                if ($fechaInicial != null && $fechaFinal == null) {
                    echo "<script>alert('Por favor seleccione la fecha final para generar el informe');</script>";
                } else if ($fechaInicial == null && $fechaFinal != null) {
                    echo "<script>alert('Por favor seleccione la fecha inicial para generar el informe');</script>";
                }
            }
            
            /** Consulta proyectos que tienen solicitudes en estado terminado de acuerdo con la selección realizada */
            if ($fechaInicial == null && $fechaFinal == null && $historico == null && $proyecto == null) {
                /** Proyectos con solicitudes a partir de 2019 */
                $consulta = "SELECT pys_cursosmodulos.idProy
                    FROM pys_actsolicitudes
                    INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
                    INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
                    INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
                    WHERE pys_actsolicitudes.est = '1' AND pys_actsolicitudes.idSolicitante = '' 
                    AND pys_actualizacionproy.est = '1'
                    AND (pys_actsolicitudes.idEstSol = 'ESS001' OR pys_actsolicitudes.idEstSol = 'ESS006')
                    AND pys_solicitudes.fechSol >= '2019/01/01'
                    GROUP BY pys_actualizacionproy.codProy;";
            } else if ($fechaInicial == null && $fechaFinal == null && $historico != null && $proyecto == null) {
                /** Todos los proyectos */
                $consulta = "SELECT pys_cursosmodulos.idProy
                    FROM pys_actsolicitudes
                    INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
                    INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
                    INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
                    WHERE pys_actsolicitudes.est = '1' AND pys_actsolicitudes.idSolicitante = '' 
                    AND pys_actualizacionproy.est = '1'
                    AND (pys_actsolicitudes.idEstSol = 'ESS001' OR pys_actsolicitudes.idEstSol = 'ESS006')
                    GROUP BY pys_actualizacionproy.codProy;";
            } else if ($fechaInicial != null && $fechaFinal != null && $historico == null && $proyecto == null) {
                /** Proyectos con solicitudes en un fecha específica */
                $consulta = "SELECT pys_cursosmodulos.idProy
                    FROM pys_actsolicitudes
                    INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
                    INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
                    INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
                    WHERE pys_actsolicitudes.est = '1' AND pys_actsolicitudes.idSolicitante = '' 
                    AND pys_actualizacionproy.est = '1'
                    AND (pys_actsolicitudes.idEstSol = 'ESS001' OR pys_actsolicitudes.idEstSol = 'ESS006')
                    AND (pys_solicitudes.fechSol >= '$fechaInicial' AND pys_solicitudes.fechSol <= '$fechaFinal')
                    GROUP BY pys_actualizacionproy.codProy;";
            } else if ($fechaInicial == null && $fechaFinal == null && $historico == null && $proyecto != null) {
                $consulta = "SELECT pys_cursosmodulos.idProy 
                    FROM pys_actsolicitudes 
                    INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM 
                    INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
                    INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol 
                    WHERE pys_actsolicitudes.est = '1' AND pys_actsolicitudes.idSolicitante = '' 
                    AND pys_actualizacionproy.est = '1'
                    AND (pys_actsolicitudes.idEstSol = 'ESS001' OR pys_actsolicitudes.idEstSol = 'ESS006') 
                    AND (pys_solicitudes.fechSol >= '2019/01/01') 
                    AND pys_cursosmodulos.estProy = '1'
                    AND pys_cursosmodulos.idProy = '$proyecto'
                    GROUP BY pys_actualizacionproy.codProy;";
            } else if ($fechaInicial == null && $fechaFinal == null && $historico != null && $proyecto != null) {
                $consulta = "SELECT pys_cursosmodulos.idProy 
                    FROM pys_actsolicitudes 
                    INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM 
                    INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
                    INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol 
                    WHERE pys_actsolicitudes.est = '1' AND pys_actsolicitudes.idSolicitante = '' 
                    AND pys_actualizacionproy.est = '1'
                    AND (pys_actsolicitudes.idEstSol = 'ESS001' OR pys_actsolicitudes.idEstSol = 'ESS006') 
                    AND pys_cursosmodulos.estProy = '1'
                    AND pys_cursosmodulos.idProy = '$proyecto'
                    GROUP BY pys_actualizacionproy.codProy;";
            } else if ($fechaInicial != null && $fechaFinal != null && $historico == null && $proyecto != null) {
                $consulta = "SELECT pys_cursosmodulos.idProy
                    FROM pys_actsolicitudes
                    INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idCM = pys_actsolicitudes.idCM
                    INNER JOIN pys_actualizacionproy ON pys_actualizacionproy.idProy = pys_cursosmodulos.idProy
                    INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
                    WHERE pys_actsolicitudes.est = '1' AND pys_actsolicitudes.idSolicitante = '' 
                    AND pys_actualizacionproy.est = '1'
                    AND (pys_actsolicitudes.idEstSol = 'ESS001' OR pys_actsolicitudes.idEstSol = 'ESS006')
                    AND (pys_solicitudes.fechSol >= '$fechaInicial' AND pys_solicitudes.fechSol <= '$fechaFinal')
                    AND pys_cursosmodulos.idProy = '$proyecto'
                    GROUP BY pys_actualizacionproy.codProy;";
            }
            $resultado = mysqli_query($connection, $consulta);
            $registros = mysqli_num_rows($resultado);
            if ($registros > 0) {
                $tabla = '  <table class="centered browser-default responsive-table striped"  border="1">
                                <thead>
                                    <tr class="row-teal3">
                                        <th>Proyecto</th>
                                        <th>Solicitudes</th>
                                    </tr>
                                </thead>
                                <tbody>';
                while ($datos = mysqli_fetch_array($resultado)) {
                    $idProy = $datos['idProy'];
                    $nombreProyecto = InformeProductoServicio::obtenerNombreProyecto($idProy);
                    $tabla .= '     <tr>
                                        <td>'.$nombreProyecto.'</td>';
                    if ($fechaInicial != null && $fechaFinal != null) {
                        $fecha = "AND (pys_solicitudes.fechSol >= '$fechaInicial' AND pys_solicitudes.fechSol <= '$fechaFinal')";
                    } else if ($historico != null) {
                        $fecha = "";
                    } else if ($fechaInicial == null && $fechaFinal == null && $historico == null) {
                        $fecha = "AND (pys_solicitudes.fechSol >= '2019/01/01')";
                    }
                    $consulta2 = "SELECT pys_actsolicitudes.idSol, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.presupuesto, pys_estadosol.nombreEstSol, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_solicitudes.fechSol, pys_actsolicitudes.fechAct
                        FROM pys_actualizacionproy 
                        INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idProy = pys_actualizacionproy.idProy 
                        INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM 
                        INNER JOIN pys_estadosol ON pys_estadosol.idEstSol = pys_actsolicitudes.idEstSol 
                        INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
                        WHERE pys_actsolicitudes.est ='1' AND pys_cursosmodulos.estProy='1' AND pys_actualizacionproy.est='1' 
                        AND pys_actsolicitudes.idSolicitante='' AND pys_actualizacionproy.idProy ='$idProy' AND pys_estadosol.est = '1' "
                        .$fecha.
                        " AND (pys_actsolicitudes.idEstSol = 'ESS001' OR pys_actsolicitudes.idEstSol = 'ESS006')
                        AND pys_actsolicitudes.presupuesto <> '0'
                        ORDER BY pys_actsolicitudes.idSol;";
                    $resultado2 = mysqli_query($connection, $consulta2);
                    $registros2 = mysqli_num_rows($resultado2);
                    $consulta3 = "SELECT pys_actsolicitudes.idSol, pys_actsolicitudes.ObservacionAct, pys_actsolicitudes.presupuesto, pys_estadosol.nombreEstSol, pys_actualizacionproy.codProy, pys_actualizacionproy.nombreProy, pys_solicitudes.fechSol, pys_actsolicitudes.fechAct
                        FROM pys_actualizacionproy 
                        INNER JOIN pys_cursosmodulos ON pys_cursosmodulos.idProy = pys_actualizacionproy.idProy 
                        INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idCM = pys_cursosmodulos.idCM 
                        INNER JOIN pys_estadosol ON pys_estadosol.idEstSol = pys_actsolicitudes.idEstSol 
                        INNER JOIN pys_solicitudes ON pys_solicitudes.idSol = pys_actsolicitudes.idSol
                        WHERE pys_actsolicitudes.est ='1' AND pys_cursosmodulos.estProy='1' AND pys_actualizacionproy.est='1' 
                        AND pys_actsolicitudes.idSolicitante='' AND pys_actualizacionproy.idProy ='$idProy' AND pys_estadosol.est = '1' "
                        .$fecha.
                        " AND (pys_actsolicitudes.idEstSol = 'ESS001' OR pys_actsolicitudes.idEstSol = 'ESS006')
                        AND pys_actsolicitudes.presupuesto = '0'
                        ORDER BY pys_actsolicitudes.idSol;";
                    $resultado3 = mysqli_query($connection, $consulta3);
                    $registros3 = mysqli_num_rows($resultado3);
                    $tabla .= '         <td>
                                            <table id="tblMain">
                                                <thead>
                                                    <tr>
                                                        <th>Cód. Producto/Servicio</th>
                                                        <th>Estado Producto/Servicio</th>
                                                        <th>Descripción Producto/Servicio</th>
                                                        <th>Fecha Creación</th>
                                                        <th>Fecha Actualización</th>
                                                        <th>Presupuesto</th>
                                                        <th>Ejecutado</th>
                                                        <th>Diferencia</th>
                                                    </tr>
                                                </thead>
                                                <tbody>';
                    if ($registros2 > 0) {
                        $tabla .= '                 <tr class="teal lighten-4">
                                                        <th colspan="8" class="center-align">&darr;&darr; Productos/Servicios con valor presupuestado &darr;&darr;</th>
                                                    </tr>';
                        while ($datos2 = mysqli_fetch_array($resultado2)) {
                            $valorPS = 0;
                            $salarioHor = 0;
                            $salarioMin = 0;
                            $idSol = $datos2['idSol'];
                            $consulta4 = "SELECT pys_tiempos.horaTiempo AS horas, pys_tiempos.minTiempo AS minutos, pys_actsolicitudes.idSol, pys_salarios.salario
                                FROM pys_tiempos 
                                INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig 
                                INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_asignados.idSol 
                                INNER JOIN pys_salarios ON pys_salarios.idPersona = pys_asignados.idPersona AND (pys_salarios.mes <= pys_tiempos.fechTiempo AND pys_salarios.anio >= pys_tiempos.fechTiempo)
                                WHERE pys_tiempos.estTiempo = '1' AND (pys_asignados.est = '1' OR pys_asignados.est = '2') 
                                AND (pys_actsolicitudes.idEstSol = 'ESS001' OR pys_actsolicitudes.idEstSol = 'ESS006') 
                                AND pys_asignados.idProy = '$idProy' AND pys_asignados.idSol = '$idSol'  AND pys_salarios.estSal = '1'
                                ORDER BY pys_asignados.idSol;";
                            $resultado4 = mysqli_query($connection, $consulta4);
                            while ($datos4 = mysqli_fetch_array($resultado4)) {
                                $salarioHor = $datos4['salario'];
                                $salarioMin = $salarioHor / 60;
                                $valorPS += (($datos4['horas'] * 60) + $datos4['minutos']) * $salarioMin;
                            }
                            $diferencia = $datos2['presupuesto'] - $valorPS;
                            $tabla .= '             <tr>
                                                        <td>P'.$datos2['idSol'].'</td>
                                                        <td>'.$datos2['nombreEstSol'].'</td>
                                                        <td>'.$datos2['ObservacionAct'].'</td>
                                                        <td>'.$datos2['fechSol'].'</td>
                                                        <td>'.$datos2['fechAct'].'</td>
                                                        <td>$ '.number_format($datos2['presupuesto'], 2, ",", ".").'</td>
                                                        <td>$ '.number_format($valorPS, 2, ",", ".").'</td>
                                                        <td>$ '.number_format($diferencia, 2, ",", ".").'</td>
                                                    </tr>';
                        }
                    }
                    if ($registros3 > 0) {
                        $tabla .= '                 <tr class="red lighten-4">
                                                        <th colspan="8" class="center-align">&darr;&darr; Productos/Servicios sin valor presupuestado &darr;&darr;</th>
                                                    </tr>';
                        while ($datos3 = mysqli_fetch_array($resultado3)) {
                            $valorPS = 0;
                            $salarioHor = 0;
                            $salarioMin = 0;
                            $idSol = $datos3['idSol'];
                            $consulta4 = "SELECT pys_tiempos.horaTiempo AS horas, pys_tiempos.minTiempo AS minutos, pys_actsolicitudes.idSol, pys_salarios.salario
                                FROM pys_tiempos 
                                INNER JOIN pys_asignados ON pys_asignados.idAsig = pys_tiempos.idAsig 
                                INNER JOIN pys_actsolicitudes ON pys_actsolicitudes.idSol = pys_asignados.idSol 
                                INNER JOIN pys_salarios ON pys_salarios.idPersona = pys_asignados.idPersona AND (pys_salarios.mes <= pys_tiempos.fechTiempo AND pys_salarios.anio >= pys_tiempos.fechTiempo)
                                WHERE pys_tiempos.estTiempo = '1' AND (pys_asignados.est = '1' OR pys_asignados.est = '2') 
                                AND (pys_actsolicitudes.idEstSol = 'ESS001' OR pys_actsolicitudes.idEstSol = 'ESS006') 
                                AND pys_asignados.idProy = '$idProy' AND pys_asignados.idSol = '$idSol'  AND pys_salarios.estSal = '1'
                                ORDER BY pys_asignados.idSol;";
                            $resultado4 = mysqli_query($connection, $consulta4);
                            while ($datos4 = mysqli_fetch_array($resultado4)) {
                                $salarioHor = $datos4['salario'];
                                $salarioMin = $salarioHor / 60;
                                $valorPS += (($datos4['horas'] * 60) + $datos4['minutos']) * $salarioMin;
                            }
                            $diferencia = $datos3['presupuesto'] - $valorPS;
                            $tabla .= '             <tr>
                                                        <td>P'.$datos3['idSol'].'</td>
                                                        <td>'.$datos3['nombreEstSol'].'</td>
                                                        <td>'.$datos3['ObservacionAct'].'</td>
                                                        <td>'.$datos3['fechSol'].'</td>
                                                        <td>'.$datos3['fechAct'].'</td>
                                                        <td>$ '.number_format($datos3['presupuesto'], 2, ",", ".").'</td>
                                                        <td>$ '.number_format($valorPS, 2, ",", ".").'</td>
                                                        <td>$ '.number_format($diferencia, 2, ",", ".").'</td>
                                                    </tr>';
                        }
                    }
                    $tabla .= '                 </tbody>
                                            </table>
                                        </td>
                                    </tr>';
                }
                $tabla .= '     </tbody>
                            </table>';
                echo $tabla;
            } else {
                echo "<strong>No hay solicitudes en estado terminado</strong>";
            }
            
            mysqli_close($connection);
        }

    }

?>