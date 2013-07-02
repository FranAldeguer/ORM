<?php
include 'conexion.php';
$xml="";

$database = $_POST['param'];

$sql1 = "select * from `information_schema`.schemata where SCHEMA_NAME='".$database."'";
$sql2 = "select * from `information_schema`.`TABLES` where `TABLE_SCHEMA`='".$database."'";

$query1 = mysql_query($sql1, $con);
$query2 = mysql_query($sql2, $con);

$xml.="&lt;?xml version='1.0' encoding='UTF-8'?&gt;\n";
while($resultado = mysql_fetch_row($query1)){
	$xml.="&lt;database schema_name='".$resultado[1]."' default_character_set_name='".$resultado[2]."' default_collation_name='".$resultado[3]."'&gt;\n";
	while($resultado1 = mysql_fetch_row($query2)){
		$xml.="	&lt;tabla table_name='".$resultado1[2]."' engine='".$resultado1[4]."'&gt;\n";
		$sql3 = "select * from `information_schema`.`COLUMNS` where `TABLE_SCHEMA`='".$database."' AND table_name = '".$resultado1[2]."'";
		$query3 = mysql_query($sql3, $con);
		while($resultado2 = mysql_fetch_row($query3)){
			if($resultado2[2] == $resultado1[2]){
			$xml.="		&lt;colum colum_name ='".$resultado2[3]."' data_type='".$resultado2[7]."' character_maximum_lenght='".$resultado2[8]."' colum_type='".$resultado2[14]."' colum_key='".$resultado2[15]."' extra='".$resultado2[16]."'&gt;&lt;/colum&gt;\n";
			}	
		}
		$xml.="	&lt;/tabla&gt;\n";
	}
	$xml.="&lt;/database&gt;\n";
}
echo $xml;

// include"DB.php";
// $xml="";

// $database = $_POST['param'];

// $sql1 = "select * from `information_schema`.schemata where SCHEMA_NAME='".$database."'";
// $sql2 = "select * from `information_schema`.`TABLES` where `TABLE_SCHEMA`='".$database."'";

// $q = DB::get()->query($sql2, PDO::FETCH_ASSOC);
// foreach ($q as $campos){
	
// }

?>