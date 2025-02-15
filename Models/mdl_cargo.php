<?php
    class Cargo{

        public static function onLoad($idCargo){
            require('../Core/connection.php');
            $consulta="SELECT * FROM pys_cargos WHERE est='1' AND idCargo ='".$idCargo."';";
            $resultado = mysqli_query($connection, $consulta);
            $datos =mysqli_fetch_array($resultado);
            return $datos;
            mysqli_close($connection);
        }

        public static function busquedaTotal(){
            require('../Core/connection.php');
            $consulta="SELECT * FROM pys_cargos WHERE est='1' ORDER BY nombreCargo;";
            $resultado = mysqli_query($connection, $consulta);
            echo'
            <table class="left responsive-table">
                <thead>
                    <tr>
                        <th>Nombre del cargo</th>
                        <th>Descripción del cargo</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody>';
            while ($datos = mysqli_fetch_array($resultado)){
                echo'
                    <tr>
                        <td>'.$datos['nombreCargo'].'</td>
                        <td>'.$datos['descripcionCargo'].'</td>
                        <td><a href="#modalCargo" class="waves-effect waves-light modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalCargo.php'".');" title="Editar"><i class="material-icons teal-text">edit</i></a></td>
                    </tr>';
            }
            echo "
                </tbody>
            </table>";
            mysqli_close($connection);
        }

        public static function busqueda($busqueda){
            require('../Core/connection.php');
            
            $busqueda = mysqli_real_escape_string($connection, $busqueda);
            $consulta="SELECT * FROM pys_cargos WHERE est='1' AND nombreCargo LIKE '%".$busqueda."%' ORDER BY nombreCargo;";
            $resultado = mysqli_query($connection, $consulta);
            $count=mysqli_num_rows($resultado);
            if($count > 0){
                echo'
                <table class="left responsive-table">
                    <thead>
                        <tr>
                            <th>Nombre del cargo</th>
                            <th>Descripción del cargo</th>
                            <th>Editar</th>
                        </tr>
                    </thead>
                    <tbody>';
                while ($datos =mysqli_fetch_array($resultado)){
                    echo'
                        <tr>
                            <td>'.$datos['nombreCargo'].'</td>
                            <td>'.$datos['descripcionCargo'].'</td>
                            <td><a href="#modalCargo" class="waves-effect waves-light  modal-trigger" onclick="envioData('."'$datos[0]'".','."'modalCargo.php'".');" title="Editar"><i class="material-icons teal-text ">edit</i></a></td>
                        </tr>';
                }
                echo "
                    </tbody>
                </table>";
            } else {
                echo'<div class="card-panel teal darken-1"><h6 class="white-text">No hay resultados para la busqueda: <strong>'.$busqueda.'</strong></h6></div>';
            }
            mysqli_close($connection);
        }

        public static function registrarcargo($nomCargo, $descCargo){
            require('../Core/connection.php');
            $consulta = "SELECT COUNT(idCargo),MAX(idCargo) FROM pys_cargos;";
            $resultado = mysqli_query($connection, $consulta);
            while ($datos =mysqli_fetch_array($resultado)){
                $count=$datos[0];
                $max=$datos[1];
            }
            if ($count==0){
                $codCargo="CAR001";
            }
            else {
                $codCargo='CAR'.substr((substr($max,3)+1001),1);	
            }		
            $nomCargo = mysqli_real_escape_string($connection, $nomCargo);
            $descCargo = mysqli_real_escape_string($connection, $descCargo);

            $sql="INSERT INTO pys_cargos VALUES ('$codCargo', '$nomCargo',  '$descCargo', '1');";
            $resultado = mysqli_query($connection, $sql);
            if($resultado){
                echo "<script> alert ('Se guardó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/cargo.php">';
            } else{
                echo "<script> alert ('No se guardó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/cargo.php">';
            }
                mysqli_close($connection);
        }

        public static function actualizarCargo($idCargo2, $nomCargo, $descCargo){
            require('../Core/connection.php');
            $nomCargo = mysqli_real_escape_string($connection, $nomCargo);
            $descCargo = mysqli_real_escape_string($connection, $descCargo);
            $consulta = "UPDATE pys_cargos SET nombreCargo='$nomCargo2', descripcionCargo='$descCargo' WHERE idCargo='$idCargo';";
            $resultado = mysqli_query($connection, $consulta);
            if($resultado){
                echo "<script> alert ('Se actualizó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/cargo.php">';
            } else{
                echo "<script> alert ('No se actualizó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/cargo.php">';
            }
            mysqli_close($connection);
        }

        public static function suprimirCargo($idCargo2){
            require('../Core/connection.php');
            $consulta = "UPDATE pys_cargos SET est = '0' WHERE idCargo='$idCargo2';";
            $resultado = mysqli_query($connection, $consulta);
            if($resultado){
                echo "<script> alert ('Se eliminó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/cargo.php">';
            }else{
                echo "<script> alert ('No se eliminó correctamente la información');</script>";
                echo '<meta http-equiv="Refresh" content="0;url=../Views/cargo.php">';
            }
            mysqli_close($connection);
        }
    }

