

    <?php
        $dbhost="localhost"; //Seleccionamos el servidor de base de datos
        $username="root"; //Seleccionamos el usuario
     	$password="root"; //Seleccionamos la contraseña         
        $dbname='information_schema'; //Seleccionmos el nombre de la base de datos
        
        $con = mysql_connect($dbhost,$username,$password); //Creamos la conexion de base de datos pasandole los datos de arriba
        mysql_select_db($dbname,$con); //Seleccionamos la base de datos
    ?>