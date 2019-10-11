<div class="modal-content center-align">
    <h4>Editar/Inactivar</h4>
    <br>
    <div class="row">
        <div class="col l10 m10 s12 offset-l1 offset-m1 left-align">
            <h6>Ingrese su numero de cedula y usuario de ingreso</h6>
        </div>
    </div>
    <div class="row">
        <form id="modalform" method="post" class="col l12 m12 s12">
            <?php
                require('../Controllers/ctrl_login.php');
                ?>
            <div class="input-field col l3 m3 s12 offset-l1 offset-m1">
                <input id="numCedula" name="numCedula" type="number" required value="" />
                <label for="numCedula">Numero de Cedula*</label>
            </div>
            <div class="input-field col l3 m3 s12 offset-l1 offset-m1">
                <input id="nomUsu" name="nomUsu" type="text" required value="" />
                <label for="nomUsu">Nombre de usuario*</label>
            </div>
            <div class="input-field col l2 m2 s12 offset-l1 offset-m1">
                <button class="btn waves-effect waves-light" type="button" id="ValidarUser"
                    onclick="buscar('../Controllers/ctrl_login.php');" name="ValidarUser">Validar</button>
            </div>
            <div id="div_dinamico" name="div_dinamico"></div>
        </form>
    </div>
</div>