<?php
include 'conexion.php';
include "DB.php";
$form = "";

$database = $_POST['database'];
$tabla = $_POST['tabla'];

//$database = "Autoescuela";
//$tabla = "alumno";

$sql = "select * from `information_schema`.`COLUMNS` where `TABLE_SCHEMA`='".$database."' AND table_name = '".$tabla."'";
$form.= "<pre>";
$form.="&lt;html&gt;
    &lt;head&gt;
        &lt;title&gt;Formulario de ".$tabla."&lt;/title&gt;
    &lt;/head&gt;
    &lt;body&gt;
        &lt;form id=\"insertar".$tabla."\" action=\"control.php\" method=\"POST\"&gt;<br>";
$form.="            &lt;table align =\"center\"&gt;";
$q = DB::get()->query($sql);
foreach ($q as $resultado){
	$form.= "<br>               &lt;tr&gt;&lt;td&gt;".$resultado[3]."&lt;/td&gt;&lt;td&gt; &lt;input type=\"text\" name=".$resultado[3]."&gt;&lt;/td&gt;&lt;/tr&gt;";
}
$form.="<br><br>               &lt;tr&gt;&lt;td  align =\"center\" colspan=\"2\"&gt;&lt;input type=\"submit\" value=\"Enviar\"&gt; <br>               &lt;input type=\"reset\" value=\"Cancelar\"&gt;&lt;/td&gt;&lt;/tr&gt;
            &lt;/table&gt;
        &lt;/form&gt;
    &lt;/body&gt;
&lt;/html&gt;";
$form .= "</pre>";
echo $form;
//echo $insert;

?>