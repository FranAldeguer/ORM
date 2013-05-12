<?php include 'conexion.php'; 
include 'DB.php';?>
<html>
    <head>
        <script src="scripts/jquery-1.8.2.min.js"></script>
	<script type="text/javascript">
        function cargarTablas(db){
            //database = db;
            //alert(database);
            $.post('listatablas.php', {param: db},
            function (data){
                    $('#listadotable').html(data);
            });
	   }
        
        function xml(){
            var db = $('#listadodb').val();
            $.post("xml.php",{ param: db },
            function (data){
                    $('#codigo').html(data);
            });
	   }
        
        function claseDB(){
            var db = $('#listadodb').val();
            var tabla = $('#listadotable').val();
            $.post("claseDB.php",{tabla: tabla, database: db},
            function (data){
                    $('#codigo').html(data);
            });
        }

        function claseLogica(){
                var db = $('#listadodb').val();
                var tabla = $('#listadotable').val();
                $.post("claseLogica.php",{tabla: tabla, database: db},
                function (data){
                        $('#codigo').html(data);
                });
        }
        
    	function formulario(){
                var db = $('#listadodb').val();
                var tabla = $('#listadotable').val();
                $.post("formulario.php",{tabla: tabla, database: db},
                function (data){
                    $('#codigo').html(data);
                });
    	}
        
        function listado(){
            var db = $('#listadodb').val();
            var tabla = $('#listadotable').val();
            $.post("listado.php",{tabla: tabla, database: db},
            function (data){
                $('#codigo').html(data);
            });
        }
        
        function seleccionarCodigo() {
			textarea = document.getElementById("codigo");
			textarea.select();		
		}
	</script>
    </head>
    <body>
        <form id="formulario" method="POST" action="ejem_json.php">
            <div id = "listado_div">
                <select id="listadodb" name="listadodb" onchange="cargarTablas(this.value)">
                    <option>--SELECCIONA UNA BD--</option>
                    <?php 
                        $sql = "SELECT SCHEMA_NAME FROM information_schema.schemata";
                        $q = DB::get()->query($sql);
                        foreach ($q as $fila){
                            echo "<option value=". $fila['SCHEMA_NAME'].">". $fila['SCHEMA_NAME']."</option>";
                        }
                    ?>
                </select>
                <select id="listadotable" name="listadotable" onchange="tabla(this.value)">

                </select>
                <button type="button" onclick="xml()"> XML </button>
                <button type="button" onclick="claseDB()"> DB </button>
                <button type="button" onclick="claseLogica()"> Logica </button>
                <button type="button" onclick="formulario()"> Formulario </button>
                <button type="button" onclick="listado()"> Listado </button>
            </div>

			<div>
				<button type='button' onclick='seleccionarCodigo()' >Select all</button>
			</div>
            <div>
              	<textarea id='codigo' cols='200' style='width:1430px;height:665px;'>

				</textarea>
            </div>
        </form>
    </body>
</html>
