<?php
include 'DB.php';
$clase = "";

$database = $_REQUEST['database'];
$tabla = $_REQUEST['tabla'];


$sql = "select * from `information_schema`.`COLUMNS` where `TABLE_SCHEMA`='".$database."' AND table_name = '".$tabla."'";
$sql2 = $sql." and COLUMN_KEY = 'PRI'";

$q = DB::get()->query($sql, PDO::FETCH_ASSOC);


$clase.= "&lt;?php\n";

/**
 *
 * INCLUYE LOS ARCHIVOS DE CONFIGURACIÓN Y BASE DE DATOS
 *
 */
$clase.="//Dependencias\n";
$clase.="require_once (\"clases_db/CDB".$tabla.".php\");\n\n";

$clase.= "class C".$tabla." extends CDB".$tabla."{\n\n";

$clase.="	static \$arrList = Array(";

$q = DB::get()->query($sql, PDO::FETCH_ASSOC);
$coma = "";
$i = 1;
foreach ($q as $campos){
	if($i > 1){
		$coma = ",";
	}
	$clase.= $coma."'".$campos["COLUMN_NAME"]."' => '".$campos["COLUMN_NAME"]."'";
	$i++;
}

$clase.=");";

$clase.="\n\n";

/**
 * 
 * CREA EL CONSTRUCTOR DE LA CLASE
 * 
 */

$clase.="	/**\n";
$clase.="	 *Constructor de la clase C".$tabla."\n";
$clase.="	 */\n";


$clase.="	public function C".$tabla."(){\n";
$clase.="		parent::CDB".$tabla."();\n";
$clase.="	}";
$clase.="\n\n";

$q = DB::get()->query($sql2, PDO::FETCH_ASSOC);
foreach ($q as $campos){
	$cod = $campos["COLUMN_NAME"];
}

/**
 *
 *CREA LA FUNCIÓN PARA BUSCAR UN OBJETO
 *(__getObj)
 *
 */

$clase.= "	/**\n";
$clase.= "	 * Devuelve un objeto de tipo C".$tabla."\n";
$clase.= "	 * @param int \$".$cod."\n";
$clase.= "	 * @return C".$tabla."\n";
$clase.= "	 */\n";
$clase.= "	public static function __getObj(\$".$cod."){\n";
$clase.= "		//Recoger los resultados de la BD\n";
$clase.= "		//Solo debe de devolver 1\n";


$clase.= "		\$sql = \"SELECT * FROM ".$tabla." WHERE ".$cod." = \".\$".$cod.";\n\n";


$clase.= "		\$q = DB::get()->query(\$sql, PDO::FETCH_ASSOC);\n\n";
	 
$clase.= "		//Inicializar un objeto con los valores devueltos\n";
$clase.= "		foreach (\$q as \$arr){\n";
$clase.= "			\$temp = self::_inicializar(\$arr);\n";
$clase.= "		}\n\n";
$clase.= "		return \$temp;\n";
$clase.= "	}\n\n\n\n";



/**
 * 
 * FUNCIÓN PARA BUSCAR OBJETOS SEGÚN LOS PARAMETROS
 * 
 */

$clase.="	/**\n";
$clase.="	 * Devuelve un listado con objetos  según los parametros que le pasemos\n";
$clase.="	 * @param array \$info\n";
$clase.="	 * @param string \$order\n";
$clase.="	 * @param array \$filtros\n";
$clase.="	 * @return array C".$tabla."\n";
$clase.="	 */\n";


$clase.="	public static function __getListado(&\$info, \$order = \"id asc\", \$filtros=\"\"){\n";
$clase.="		//Query\n";
$clase.="		\$sql = \"SELECT * FROM ".$tabla." WHERE 1 = 1\";\n\n";
	 
$clase.="		//Filtros de busqueda\n";

$q = DB::get()->query($sql, PDO::FETCH_ASSOC);
foreach ($q as $campos){
	$clase.="		if(isset(\$filtros[\"".$campos["COLUMN_NAME"]."\"])) \$sql.=\" and ".$campos["COLUMN_NAME"]." = '\".\$filtros[\"".$campos["COLUMN_NAME"]."\"].\"'\";\n";
};

$clase.="\n";
$clase.="		//Filtros de orden\n";
$clase.="		\$sql.=\" ORDER BY \".\$order;\n\n";
	 
$clase.="		//Recoger los valores de la BD\n";
$clase.="		\$q = DB::get()->query(\$sql, PDO::FETCH_ASSOC);\n";
$clase.="		\$info[\"num\"] = \$q->rowCount();\n\n";
	 
$clase.="		//Creamos un array de pruebas vacío\n";
$clase.="		\$arrPru = Array();\n\n";
	 
$clase.="		//Recogemos los datos e inicializamos objetos con esos valores\n";
$clase.="		//Cada objeto lo metemos dentro del array\n";
$clase.="		foreach (\$q as \$cObj){\n";
$clase.="			\$temp = self::_inicializar(\$cObj);\n";
$clase.="			\$arrPru[] = \$temp;\n";
$clase.="		}\n\n";

$clase.="		return \$arrPru;\n";
$clase.="	}\n";


/**
 *Cerrar clase y php
 */
$clase.="}\n";
$clase.="?>";

echo $clase;