<body>
    <h1>Enviar archivo a PHP con JavaScript + FormData + Fetch</h1>
    <a target="_blank" href="https://parzibyte.me/blog">parzibyte.me/blog</a>
    <hr>
    <input id="inputFile" type="file">
    <br><br>
    <button id="btnEnviar">Enviar</button>

    <script>
        const btnEnviar = document.querySelector("#btnEnviar");
        const inputFile = document.querySelector("#inputFile");
        btnEnviar.addEventListener("click", () => {
            if (inputFile.files.length > 0) {
                let formData = new FormData();
                formData.append("archivo", inputFile.files[0]); // En la posición 0; es decir, el primer elemento
                fetch("../Controllers/ctrl_subidaPyS.php", {
                    method: 'POST',
                    body: formData,
                })
                    .then(respuesta => respuesta.text())
                    .then(decodificado => {
                        console.log(decodificado);
                    });
            } else {
                // El usuario no ha seleccionado archivos
                alert("Selecciona un archivo");
            }
        });
    </script>
</body>
<?php echo php_ini_loaded_file(); ?>