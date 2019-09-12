<div class="modal-content center-align">
	<h4>Editar/Eliminar Fase</h4>
	<div class="row">
		<form id="actForm" action="../Controllers/ctrl_fase.php" method="post" class="col l12 m12 s12">
			<div class="row">
				<input id="cod" name="cod" type="hidden">
				<?php
					require('../Controllers/ctrl_fase.php');
				?>
				<div class="input-field col l10 m10 s12 offset-l1 offset-m1">
					<input id="txtNomFase" name="txtNomFase" type="text" class="validate" autofocus required value="<?php echo $nomFase;?>">
					<label for="txtNomFase" class="active">Nombre de la fase</label>
				</div>
				<div class="input-field col l10 m10 s12 offset-l1 offset-m1">
					<textarea id="txtDescFase" name="txtDescFase" class="materialize-textarea" ><?php echo $descFase;?></textarea>
					<label for="txtDescFase" class="active">Descripción de la fase</label>
				</div>
			</div>
			<button class="btn waves-effect red darken-4 waves-light " type="submit" name="btnEliFase">Eliminar</button>
			<button class="btn waves-effect waves-light" type="submit" name="btnActFase">Actualizar</button>
		</form>
	</div>
</div>