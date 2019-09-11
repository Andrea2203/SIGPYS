<div class="modal-content center-align">
	<h4>Editar/Eliminar Cargo</h4>
	<div class="row">
		<form id="actForm" action="../Controllers/ctrl_cargo.php" method="post" class="col l12 m12 s12">
			<div class="row">
				<input type="hidden" id="cod" name="cod">
				<?php
					require('../Controllers/ctrl_cargo.php');
				?>
				<div class="input-field col l10 m10 s12 offset-l1 offset-m1">
					<input id="txtnomCargo" name="txtnomCargo" type="text" class="validate" autofocus required value="<?php echo $nomCargo;?>">
					<label for="txtnomCargo" class="active">Nombre del cargo</label>
				</div>
				<div class="input-field col l10 m10 s12 offset-l1 offset-m1">
					<textarea id="txtdescCargo" name="txtdescCargo" class="materialize-textarea" ><?php echo $descCargo;?></textarea>
					<label for="txtdescCargo" class="active">Descripción del cargo</label>
				</div>
			</div>
			<button class="btn waves-effect red darken-4 waves-light " type="submit" name="btnEliCargo" >Eliminar</button>
			<button class="btn waves-effect waves-light" type="submit" name="btnActCargo" >Actualizar</button>
		</form>
	</div>
</div>