<?php
include 'conexion.php';
include "DB.php";
$db = $_POST['param'];
$filas.="<option>--SELECCIONA UNA TABLA--</option>";
$sql = "select * from `information_schema`.`TABLES` where `TABLE_SCHEMA`='".$db."'";

$q = DB::get()->query($sql);
foreach($q as $fila){
	$filas.= "<option value=". $fila['TABLE_NAME'].">". $fila['TABLE_NAME']."</option>";
}


echo $filas;