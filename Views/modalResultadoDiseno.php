<h4>Metadata Producto/Servicio</h4>
<div class="row">
    <form id="actForm" action="../Controllers/ctrl_missolicitudes.php" method="post" class="col l12 m12 s12">
        <div class="row">
            <input type="text" name="idSol" id="idSol" value="<?php echo $idSol;?>" hidden>
            <div class="input-field col l2 m12 s12">
                <label for="idSol" class="active">Código PS:</label>
                <p class="left-align">P<?php echo $idSol ;?></p>
            </div>
            <div class="input-field col l7 m12 s12 ">
                <label for="codProy" class="active">Código Proyecto en Conecta-TE:</label>
                <p class="left-align"><?php echo $idProy. " - ".$nomProy ;?></p>
            </div>
            <div class="input-field col l3 m12 s12">
                <label for="descSol" class="active">Estado:</label>
                <p class="left-align"><?php echo $estado ;?></p>
            </div>
            <div class="input-field col l12 m12 s12">
                <label for="descSol" class="active">Descripción Solicitud Específica:</label>
                <p class="left-align"><?php echo $desSol ;?></p>
            </div>
            <div class="input-field col l3 m12 s12">
                <label for="duraSer" class="active">Fecha prevista de entrega al cliente:</label>
                <p class="left-align"><?php echo $fechaPrev?></p>
            </div>
            <div class="input-field col l6 m12 s12">
                <label for="monEqu" class="active">Equipo - Servicio:</label>
                <p class="left-align"><?php echo $equipo. " - ".$nomProdOSer;?></p>
            </div>
            <div class="input-field col l3 m12 s12">
                <label for="duraSer" class="active">Duración del Servicio:</label>
                <p class="left-align"><?php echo $hora." h ".$min." m";?></p>
            </div>
            <div class="input-field col l12 m12 s12">
                <input type="text" name="nomProd" id="nomProd" value="<?php echo $nomProduc;?>" >
                <label for="nomProd" class="active">Nombre Producto</label>
            </div>
            <div class="input-field col l6 m12 s12">
                <?php echo $sltClase;?>
            </div>
            <div class="input-field col l6 m12 s12 " id="sltModalTipo">
                <?php 
                if ( !empty($tipo)){
                    echo $sltTipo;
                } else {
                ?>
                    <select>
                        <option value="" disabled>Seleccione una clase de producto</option>
                    </select>
                    <label for="">Tipo de producto</label>
                <?php 
                }
                ?>
            </div>
            <div class="input-field col l6 m12 s12" id="sltModalIdioma">
                <?php echo $sltIdioma; ?>
            </div>
            <div class="input-field col l6 m12 s12" id="sltModalFormato">
                <?php echo $sltFormato; ?>
            </div>
            <div class="input-field col l6 m12 s12 " id="sltModalIdioma">
                <?php echo $sltTipoContenido; ?>
            </div>
            <div class="input-field col l6 m12 s12">
                <?php echo $sltRED;?>
            </div>
            <div class="input-field col l12 m12 s12">
                <?php echo $sltAreaConocimiento;?>
            </div>
            <div class="input-field col l12 m12 s12 left-align">
                <textarea id="palabrasClave" name="palabrasClave" class="materialize-textarea"><?php echo $palabrasClave;?></textarea>
                <label for="palabrasClave" class="active">Palabras clave:</label>
            </div>
            <div class="input-field col l12 m12 s12">
                <input type="text" name="url" id="url" value="<?php echo $url;?>">
                <label for="url" class="active">Enlace para inventario:</label>
            </div>
            <div class="input-field col l12 m12 s12 left-align">
                <textarea name="labor" id="labor" class="materialize-textarea"><?php echo $labor;?></textarea>
                <label for="labor" class="active">Observaciones:</label>
            </div>
            <div class="input-field col l6 m12 s12 ">
                <?php echo $sltPlata; ?>
            </div>
            <div class="input-field col l6 m12 s12">
                <input class="datepicker" type="text" name="txtfechEntr" id="txtfechEntr"  value="<?php echo $fechaEntre;?>" >
                <label for="txtfechEntr" class="active">Fecha de Entrega al Cliente</label>
            </div>
            <div class="input-field col l6 m12 s12 offset-l3 ">
                <button class="btn waves-effect waves-light" type="submit" name="btnGuaSopo">Guardar</button>
            </div>
        </div>
    </form>
</div>