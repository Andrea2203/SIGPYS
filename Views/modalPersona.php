<div class="modal-content center-align">
	<h4>Personas</h4>
	<div class="row">
		<form id="actForm" action="../Controllers/ctrl_asigDedicacion.php" method="post" class="col l12 m12 s12">
			<div class="row">
				<input id="cod" name="cod" type="hidden">
				<input id="val" name="val" type="hidden">
				<?php
					require('../Controllers/ctrl_asigDedicacion.php');
				?>
				<div class="input-field col l10 m10 s12 offset-l1 offset-m1">
					<input id="txtNomFase" name="txtNomFase" type="text" class="validate" autofocus required value="<?php echo $var1;?>">
					<label for="txtNomFase">Persona</label>
				</div>
				<div class="input-field col l10 m10 s12 offset-l1 offset-m1">
					<textarea id="txtDescFase" name="txtDescFase" class="materialize-textarea" ><?php echo $var2;?></textarea>
					<label for="txtDescFase">Descripción de la fase</label>
				</div>
			</div>
			<button class="btn waves-effect red darken-4 waves-light " type="submit" name="action" onclick="suprimir('2','Controllers/ctrl_fase.php')">Eliminar</button>
			<button class="btn waves-effect waves-light" type="submit" name="action" onclick="actualiza('1','Controllers/ctrl_fase.php')">Actualizar</button>
		</form>
	</div>
</div>