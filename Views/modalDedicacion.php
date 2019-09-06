<div class="modal-content center-align">
	<h4>Editar Dedicación</h4>
	<div class="row">
		<form id="actForm" action="../Controllers/ctrl_dedicacion.php" method="post" class="col l12 m12 s12">
			<div class="row">
				<input id="cod" name="cod" type="hidden">
				<input id="val" name="val" type="hidden">
				<?php
					require('../Controllers/ctrl_dedicacion.php');
                ?>
				<div class="input-field col l10 m10 s12 offset-l1 offset-m1">
                    <h6><?php echo $nombreCompleto;?></h6>
				</div>
				<div class="input-field col l5 m5 s12 offset-l1 offset-m1">
                    <input id="txtDedicacion1" name="txtDedicacion1" type="number" class="validate" required value="<?php echo $dedicacionSeg1;?>" oninput="txtHorasSeg1.value = txtDedicacion1.value * <?php echo $diasSeg1 * 8;?> / 100">
					<label for="txtDedicacion1" class="active">% Dedicación Seg. 1:</label>
				</div>
				<div class="input-field col l5 m5 s12">
					<input readonly id="txtHorasSeg1" name="txtHorasSeg1" value="<?php echo $horasSeg1;?>" type="number" step="any">
					<label for="txtHorasSeg1" class="active">Horas a Dedicar Seg 1:</label>
				</div>
				<div class="input-field col l5 m5 s12 offset-l1 offset-m1">
					<input id="txtDedicacion2" name="txtDedicacion2" type="number" class="validate" required value="<?php echo $dedicacionSeg2;?>" oninput="txtHorasSeg2.value = txtDedicacion2.value * <?php echo $diasSeg2 * 8;?> / 100">
					<label for="txtDedicacion2" class="active">% Dedicación Seg. 2:</label>
				</div>
				<div class="input-field col l5 m5 s12">
					<input readonly id="txtHorasSeg2" name="txtHorasSeg2" value="<?php echo $horasSeg2;?>" type="number" step="any">
					<label for="txtHorasSeg2" class="active">Horas a Dedicar Seg 2:</label>
				</div>
			</div>
			<a hidden><button class="btn waves-effect red darken-4 waves-light " type="submit" name="action" onclick="suprimir('2','Controllers/ctrl_dedicacion.php')">Eliminar</button></a><!-- Se oculta boton en modal para no permitir la eliminación de registros-->
			<button class="btn waves-effect waves-light" type="submit" name="action" onclick="actualiza('1','Controllers/ctrl_dedicacion.php')">Actualizar</button>
		</form>
	</div>
</div>