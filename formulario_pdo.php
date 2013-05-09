<?php
include "DB.php";
$form = "";

$database = $_REQUEST['database'];
$tabla = $_REQUEST['tabla'];

//$database = "Autoescuela";
//$tabla = "alumno";

$sql = "select * from `information_schema`.`COLUMNS` where `TABLE_SCHEMA`='".$database."' AND table_name = '".$tabla."'";
$form.= "<pre>";

$form.=	"&lt;div class=\"formulario\"&gt;<br><br>";
	
$form.="	&lt;h2&gt;Insertar ".$tabla."&lt;/h2&gt;<br><br>";
	
$form.="	&lt;form action=\"controles/Control_".$tabla.".php\" method=\"post\" name =\"forminsertar\" id=\"forminsertar\"&gt;<br><br>";

$q = DB::get()->query($sql, PDO::FETCH_ASSOC);
foreach ($q as $campos){
	
	$form.="	&lt;div class = \"registro_fila\"&gt;<br>";
	$form.="		&lt;div class = \"registro_form_eti\"&gt;".$campos["COLUMN_NAME"].":&lt;/div&gt;<br>";
	$form.="		&lt;div class = \"registro_form_campo\"&gt;&lt;input type=\"text\" name =\"input".$campos["COLUMN_NAME"]."\" id=\"input".$campos["COLUMN_NAME"]."\"&gt;&lt;/div&gt;<br>";
	$form.="	&lt;/div&gt;<br><br>";

}
	            

$form.="  	&lt;div class=\"form_btns\"&gt;<br>";
$form.="    		&lt;button type=\"button\" class=\"form_btn\" id=\"btninsert\" onclick=\"insertar_modificar()\"&gt;Insertar&lt;/button&gt;<br>";
$form.="	   	&lt;button type=\"reset\" class=\"form_btn\" id=\"btnborrar\" onclick=\"cancelar()\"&gt;Cancelar&lt;/button&gt;<br>";
$form.="   	&lt;/div&gt;<br>";
	                
	           
$form.="   	&lt;input type=\"text\" id=\"inputid\" name =\"inputid\" value=\"0\"&gt;<br>";
$form.="   	&lt;/form&gt;<br>";
$form.="&lt;/div&gt;<br>";

$form .= "</pre><br>";
echo $form;
//echo $insert;

?>