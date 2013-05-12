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
$clase.="require_once (\"DB.php\");\n\n";

$clase.= "class C".$tabla."{\n\n";

$q = DB::get()->query($sql, PDO::FETCH_ASSOC);
foreach ($q as $campos){
	$clase.= "	var $".$campos["COLUMN_NAME"].";\n";
}
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
$clase.="		\$this->ini();\n";
$clase.="	}";
$clase.="\n\n";

/**
 *
 *CREA LA FUNCIÓN PARA INSERTAR
 *(INSERT)
 *
 */

$clase.="	/**\n";
$clase.="    * Funcion para insertar un nuevo objeto en la base de datos\n";
$clase.="    * @throws Exception -> Si hay un fallo al insertar el obj en la BD\n";
$clase.="    * @return int -> Id del utlimo registro insertado || int -> 0\n";
$clase.="    */\n";

$clase.="	public function insert(){\n";
$clase.="		try{\n";

$clase.="			//Crea la query\n";
$clase.="			\$sql = \"INSERT INTO ".$tabla."\"; \n";
$clase.="			\$sql .= \"(";

$i = 1;
$coma = "";
$q = DB::get()->query($sql, PDO::FETCH_ASSOC);
foreach ($q as $campos){
	if($i > 1){
		$coma = ", ";
	}
	$clase.= $coma.$campos["COLUMN_NAME"];
	$i ++;
}
$clase.=")\";\n";
$clase.="			\$sql .= \"VALUES(\";\n";

$i = 1;
$coma = "";
$q = DB::get()->query($sql, PDO::FETCH_ASSOC);
foreach ($q as $campos){
	if($i > 1){
		$coma = ",";
	}
	$clase.="			\$sql .= \"".$coma."'\".\$this->".$campos["COLUMN_NAME"].".\"'\";\n";
	$i ++;
}
$clase.="			\$sql .= \")\";\n\n";

$clase.="			//Ejecuta la query\n";
$clase.="			\$q = DB::get()->exec(\$sql);\n\n";

$clase.="			//Comprueba que no hay errores en la inserción\n";
$clase.= "			if (\$q->errorInfo[0] != 00000) throw new Exception(\"Error al insertar\");\n\n";

$clase.="			//Pone el id correctamten al objeto insertado\n";
$q = DB::get()->query($sql, PDO::FETCH_ASSOC);
foreach ($q as $campos){
	if($campos["COLUMN_KEY"]=="PRI"){
		$clase.="			\$this->".$campos["COLUMN_NAME"]." = DB::get()->lastInsertId();\n\n";
	
	
		$clase.="			//Devuelve el ultimo id\n";
		$clase.="			return \$this->".$campos["COLUMN_NAME"].";\n\n";
	}
}
$clase.="		}catch(Excepction \$e){\n";

$clase.="			//Muestra el mensaje de error de la excepción\n";
$clase.="			echo \$e->getMessage();\n\n";

$clase.="			return 0;\n";

$clase.="		}\n";
$clase.="	}\n\n\n\n";


/**
 * 
 * CREA LA FUNCIÓN PARA ELIMINAR
 * (DELETE)
 * 
 */


$clase.= "	/**\n";
$clase.= "	 * Funcion para eliminar un objeto\n";
$clase.= "	 * @return boolean\n";
$clase.= "	 */\n";


$clase.= "	public function delete(){\n";
$clase.= "		//Llama al metodo estatico de borrar y le pasa el id del objeto actual\n";
$clase.= "		self::__delete(\$this->id);\n";
$clase.= "	}\n\n\n\n";

/**
 * 
 * CREA LA FUNCIÓN ESTÁTICA PARA ELIMINAR
 * (__DELETE)
 * 
 */

$clase.="	/**\n";
$clase.="	 * Funcion para eliminar un objeto\n";
$clase.="	 * @param int \$id_prueba\n";
$clase.="	 * @throws Exception\n";
$clase.="	 * @return boolean\n";
$clase.="	 */\n";

$q = DB::get()->query($sql2, PDO::FETCH_ASSOC);
foreach ($q as $campos){
	$cod = $campos["COLUMN_NAME"];
}

$clase.="	public static function __delete(\$".$cod."){\n";
$clase.="		try{\n";
			 
$clase.="			//Ejecucion de la query\n";

$q = DB::get()->query($sql2, PDO::FETCH_ASSOC);
foreach ($q as $campos){
	if($campos["COLUMN_KEY"]=="PRI"){
		$clase.="			\$sql = \"DELETE FROM ".$tabla." WHERE ".$campos["COLUMN_NAME"]." = \".\$".$cod.";\n\n";
	}
}
	

$clase.="			\$q = DB::get()->exec(\$sql);\n\n";

$clase.="			//Comprobación de fallos\n";
$clase.="			if(\$q != 1) throw new Exception(\"Fallo al eliminar\");\n\n";

$clase.="			return true;\n\n";
$clase.="		}catch (Exception \$e){\n\n";
$clase.="			//Muestra el mensaje de error\n";
$clase.="			echo \$e->getMessage();\n\n";
$clase.="			return false;\n";
$clase.="		}\n";
$clase.="	}\n\n\n\n";



/**
 * 
 * CREA LA FUNCIÓN PARA ACTULIZAR
 * (UPDATE)
 * 
 */

$clase.="	/**\n";
$clase.="	 * Actualiza los parametros de un objeto\n";
$clase.="	 * @throws Exception\n";
$clase.="	 * @return boolean\n";
$clase.="	 */\n";
$clase.="	public function update(){\n";
$clase.="		try{\n";

$clase.="			//Ejecución de la query\n";
$clase.="			\$sql = \"UPDATE ".$tabla." SET\";\n\n";
 
$i = 1;
$coma = "";
$q = DB::get()->query($sql, PDO::FETCH_ASSOC);
foreach ($q as $campos){
	if($i > 1){
		$coma = ", ";
	}
	$clase.= "			\$sql.= \"".$coma." ".$campos["COLUMN_NAME"]."='\".\$this->".$campos["COLUMN_NAME"].".\"'\";\n";
	$i ++;
}

$clase.="			\$sql.= \" WHERE 1=1\";\n";
$clase.="			\$sql.= \" and ".$cod." = '\".\$this->".$cod.".\"'\";\n\n";

$clase.="			//Ejecución de la query\n";
$clase.="			\$q = DB::get()->exec(\$sql);\n\n";
	
$clase.="			//Comprobación de errores\n";
$clase.="			if(\$q != 1) throw new Exception(\"Error en la modificación\");\n\n";

$clase.="			return true;\n\n";
$clase.="		}catch (Exception \$e){\n\n";
$clase.="			//Muestra el mensaje de error\n";
$clase.="			echo \$e->getMessage();\n\n";
$clase.="			return false;\n";
$clase.="		}\n";
$clase.="	}\n\n\n\n";

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
 * CREA LA FUNCIÓN PARA INICIALIZAR EL OBJETO CON LOS PARAMETROS QUE LE PASES
 * (_inicializar)
 * 
 */

$clase.="	/**\n";
$clase.="	 * Inicializa un objeto con los valores que se le pasen como parametros\n";
$clase.="	 * @param Array \$arrValores\n";
$clase.="	 * @return C".$tabla."\n";
$clase.="	 */\n";
$clase.="	private static function _inicializar(\$arrValores){\n";
$clase.="		//Estanciar el objeto\n";
$clase.="		\$temp = new C".$tabla."();\n";
$clase.="		//Asignarle los valores que se le pasan\n";
$q = DB::get()->query($sql, PDO::FETCH_ASSOC);
foreach ($q as $campos){
	$clase.= "		\$temp->".$campos["COLUMN_NAME"]."= \$arrValores[\"".$campos["COLUMN_NAME"]."\"];\n";
} 
$clase.="		return \$temp;\n";
$clase.="	}\n\n\n\n";



$clase.="	/**\n";
$clase.="	 * Inicializa las propiedades del objeto\n";
$clase.="	 */\n";
$clase.="	private function ini(){\n";
$q = DB::get()->query($sql, PDO::FETCH_ASSOC);
foreach ($q as $campos){
	$clase.= "		\$this->".$campos["COLUMN_NAME"]."= \"".$campos["COLUMN_DEFAULT"]."\";\n";
};
$clase.="	}\n\n\n\n";


/**
 * 
 * FUNCION PARA MOSTRAR LOS VALORES DEL OBJETO
 * 
 */

$clase.="	/**\n";
$clase.="	 * Mostrar el contenido del objeto\n";
$clase.="	 */\n";
$clase.="	public function mostrar(){\n";
$q = DB::get()->query($sql, PDO::FETCH_ASSOC);
foreach ($q as $campos){
	$clase.= "		echo \"".$campos["COLUMN_NAME"]." =&gt; \".  \$this->".$campos["COLUMN_NAME"].".\"&lt;br&gt;\";\n";
};
$clase.="	}\n\n\n\n";


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