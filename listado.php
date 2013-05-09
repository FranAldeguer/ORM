<?php
include 'conexion.php';
$list="";

$database = $_POST['database'];
$tabla = $_POST['tabla'];

//$database = "Autoescuela";
//$tabla = "alumno";

$list.="<pre>";
//Abrimos las etiquetas de <html><head><title><body>y<table>
$list.="&lt;html&gt;
    &lt;head&gt;
        &lt;title&gt;Listado ".$tabla."&lt;/title&gt;
    &lt;/head&gt;
    &lt;body&gt;
        &lt;p align=\"center\"&gt;Lista de ".$tabla."&lt;/p&gt;
        &lt;table border = 1 cellspacing=0 cellpadding=2 align=\"center\"&gt;<br>";

//Esto recoge el encabezado de la tabla <th></th>
$list.="            &lt;?php<br>";
$list.="                //Recoger el encabezado de la tabla desde la base de datos<br>";
$list.="                \$db = new PDO('mysql:host=localhost;dbname=".$database."', 'root', 'root');<br>";
$list.="                \$sql = \"select * from `information_schema`.`COLUMNS` where `TABLE_SCHEMA`='".$database."' AND table_name = '".$tabla."'\";<br>";
$list.="                \$con = \$db->query(\$sql);;
                        \$tabla.=\"&lt;tr&gt;\";
                foreach(\$con as \$row){ 
                    \$tabla.=\"&lt;th&gt;\".\$row['COLUMN_NAME'].\"&lt;/th&gt;\";
                }
                \$tabla.=\"&lt;/tr&gt;\";<br>";


//Esto rellena los datos de la tabla
$list.= "                \$sql = \"SELECT * FROM ".$database.".".$tabla."\";";
$list.="<br>                \$con = \$db->query(\$sql);
                foreach(\$con as \$row){<br>";
$list.="                    \$tabla.=\"&lt;tr&gt;\";
                    \$i = 0;
                    while(\$i < sizeof(\$row)/2){
                        \$tabla.=\"&lt;td&gt;\".\$row[\$i].\"&lt;/td&gt;\";
                        \$i++;
                    }<br>";
    $list.="                    \$tabla.=\"&lt;/tr&gt;\";
                }
                echo \$tabla;
           ?&gt;<br>";
    
//Cerrar <tabla><body>y<html>
$list.="        &lt;/table&gt;
    &lt;/body&gt;
&lt;/html&gt;";
$list.="</pre>";
echo $list;
?>