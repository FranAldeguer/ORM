<?php
include 'DB.php';
$clase = "";

$database = $_REQUEST['database'];
$tabla = $_REQUEST['tabla'];


$sql = "select * from `information_schema`.`COLUMNS` where `TABLE_SCHEMA`='".$database."' AND table_name = '".$tabla."'";
$sql2 = $sql." and COLUMN_KEY = 'PRI'";

$q = DB::get()->query($sql, PDO::FETCH_ASSOC);


$clase.= "<pre>";
$clase.= "&lt;?php<br>";

/**
 *
 * INCLUYE LOS ARCHIVOS DE CONFIGURACIÓN Y BASE DE DATOS
 *
 */
$clase.="//Dependencias<br>";
$clase.="require_once (\"DB.php\");<br><br>";

$clase.= "class C".$tabla."{<br><br>";

$q = DB::get()->query($sql, PDO::FETCH_ASSOC);
foreach ($q as $campos){
	$clase.= "	var $".$campos["COLUMN_NAME"].";<br>";
}
$clase.="<br><br>";

/**
 * 
 * CREA EL CONSTRUCTOR DE LA CLASE
 * 
 */

$clase.="	/**<br>";
$clase.="	 *Constructor de la clase C".$tabla."<br>";
$clase.="	 */<br>";


$clase.="	public function C".$tabla."(){<br>";
$clase.="		\$this->ini();<br>";
$clase.="	}";
$clase.="<br><br>";

/**
 *
 *CREA LA FUNCIÓN PARA INSERTAR
 *(INSERT)
 *
 */

$clase.="	/**<br>";
$clase.="    * Funcion para insertar un nuevo objeto en la base de datos<br>";
$clase.="    * @throws Exception -> Si hay un fallo al insertar el obj en la BD<br>";
$clase.="    * @return int -> Id del utlimo registro insertado || int -> 0<br>";
$clase.="    */<br>";

$clase.="	public function insert(){<br>";
$clase.="		try{<br>";

$clase.="			//Inicializa la transacción<br>";
$clase.="			DB::get()->beginTransaction();<br><br>";

$clase.="			//Crea la query<br>";
$clase.="			\$sql = \"INSERT INTO ".$tabla."\"; <br>";
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
$clase.=")\";<br>";
$clase.="			\$sql .= \"VALUES(\";<br>";

$i = 1;
$coma = "";
$q = DB::get()->query($sql, PDO::FETCH_ASSOC);
foreach ($q as $campos){
	if($i > 1){
		$coma = ",";
	}
	$clase.="			\$sql .= \"".$coma."'\".\$this->".$campos["COLUMN_NAME"].".\"'\";<br>";
	$i ++;
}
$clase.="			\$sql .= \")\";<br><br>";

$clase.="			//Ejecuta la query<br>";
$clase.="			\$q = DB::get()->exec(\$sql);<br><br>";

$clase.="			//Comprueba que no hay errores en la inserción<br>";
$clase.= "			if (\$q->errorInfo[0] != 00000) throw new Exception(\"Error al insertar\");<br><br>";

$clase.="			//Pone el id correctamten al objeto insertado<br>";
$q = DB::get()->query($sql, PDO::FETCH_ASSOC);
foreach ($q as $campos){
	if($campos["COLUMN_KEY"]=="PRI")
	$clase.="			\$this->".$campos["COLUMN_NAME"]." = DB::get()->lastInsertId();<br><br>";
}

$clase.="			//Devuelve el id del ultimo registro insertado<br>";
$clase.="			\$return = DB::get()->lastInsertId();<br><br>";

$clase.="			 //Finaliza correctamente la transaccion<br>";
$clase.="			DB::get()->commit();<br><br>";

$clase.="			//Devuelve el ultimo id<br>";
$clase.="			return \$return;<br><br>";

$clase.="		}catch(Excepction \$e){<br>";

$clase.="			//Finaliza la transaccion sin guardar los cambios<br>";
$clase.="			DB::get()->rollBack();<br><br>";

$clase.="			//Muestra el mensaje de error de la excepción<br>";
$clase.="			echo \$e->getMessage();<br><br>";

$clase.="			return 0;<br>";

$clase.="		}<br>";
$clase.="	}<br><br><br><br>";


/**
 * 
 * CREA LA FUNCIÓN PARA ELIMINAR
 * (DELETE)
 * 
 */


$clase.= "	/**<br>";
$clase.= "	 * Funcion para eliminar un objeto<br>";
$clase.= "	 * @return boolean<br>";
$clase.= "	 */<br>";


$clase.= "	public function delete(){<br>";
$clase.= "		//Llama al metodo estatico de borrar y le pasa el id del objeto actual<br>";
$clase.= "		self::__delete(\$this->id);<br>";
$clase.= "	}<br><br><br><br>";

/**
 * 
 * CREA LA FUNCIÓN ESTÁTICA PARA ELIMINAR
 * (__DELETE)
 * 
 */

$clase.="	/**<br>";
$clase.="	 * Funcion para eliminar un objeto<br>";
$clase.="	 * @param int \$id_prueba<br>";
$clase.="	 * @throws Exception<br>";
$clase.="	 * @return boolean<br>";
$clase.="	 */<br>";

$q = DB::get()->query($sql2, PDO::FETCH_ASSOC);
foreach ($q as $campos){
	$cod = $campos["COLUMN_NAME"];
}

$clase.="	public static function __delete(\$".$cod."){<br>";
$clase.="		try{<br>";
$clase.="			//Inicia la transaccion<br>";
$clase.="			DB::get()->beginTransaction();<br><br>";
			 
$clase.="			//Ejecucion de la query<br>";

$q = DB::get()->query($sql2, PDO::FETCH_ASSOC);
foreach ($q as $campos){
	if($campos["COLUMN_KEY"]=="PRI"){
		$clase.="			\$sql = \"DELETE FROM ".$tabla." WHERE ".$campos["COLUMN_NAME"]." = \".\$".$cod.";<br><br>";
	}
}
	

$clase.="			\$q = DB::get()->exec(\$sql);<br><br>";

$clase.="			//Comprobación de fallos<br>";
$clase.="			if(\$q != 1) throw new Exception(\"Fallo al eliminar\");<br><br>";

$clase.="			//Finaliza la transaccion correctamente<br>";
$clase.="			DB::get()->commit();<br><br>";
$clase.="			return true;<br><br>";
$clase.="		}catch (Exception \$e){<br><br>";
$clase.="			//Finaliza la transaccion con errores<br>";
$clase.="			DB::get()->rollBack();<br><br>";
$clase.="			//Muestra el mensaje de error<br>";
$clase.="			echo \$e->getMessage();<br><br>";
$clase.="			return false;<br>";
$clase.="		}<br>";
$clase.="	}<br><br><br><br>";



/**
 * 
 * CREA LA FUNCIÓN PARA ACTULIZAR
 * (UPDATE)
 * 
 */

$clase.="	/**<br>";
$clase.="	 * Actualiza los parametros de un objeto<br>";
$clase.="	 * @throws Exception<br>";
$clase.="	 * @return boolean<br>";
$clase.="	 */<br>";
$clase.="	public function update(){<br>";
$clase.="		try{<br>";
$clase.="			//Inicializa la transaccion<br>";
$clase.="			DB::get()->beginTransaction();<br><br>";

$clase.="			//Ejecución de la query<br>";
$clase.="			\$sql = \"UPDATE ".$tabla." SET\";<br><br>";
 
$i = 1;
$coma = "";
$q = DB::get()->query($sql, PDO::FETCH_ASSOC);
foreach ($q as $campos){
	if($i > 1){
		$coma = ", ";
	}
	$clase.= "			\$sql.= \"".$coma." ".$campos["COLUMN_NAME"]."='\".\$this->".$campos["COLUMN_NAME"].".\"'\";<br>";
	$i ++;
}

$clase.="			\$sql.= \" WHERE 1=1\";<br>";
$clase.="			\$sql.= \" and ".$cod." = '\".\$this->".$cod.".\"'\";<br><br>";

$clase.="			//Ejecución de la query<br>";
$clase.="			\$q = DB::get()->exec(\$sql);<br><br>";
	
$clase.="			//Comprobación de errores<br>";
$clase.="			if(\$q != 1) throw new Exception(\"Error en la modificación\");<br><br>";

$clase.="			//Finaliza la transaccion correctamente<br>";
$clase.="			DB::get()->commit();<br><br>";
$clase.="			return true;<br><br>";
$clase.="		}catch (Exception \$e){<br><br>";
$clase.="			//Finaliza la transaccion con errores<br>";
$clase.="			DB::get()->rollBack();<br><br>";
$clase.="			//Muestra el mensaje de error<br>";
$clase.="			echo \$e->getMessage();<br><br>";
$clase.="			return false;<br>";
$clase.="		}<br>";
$clase.="	}<br><br><br><br>";

/**
 *
 *CREA LA FUNCIÓN PARA BUSCAR UN OBJETO
 *(__getObj)
 *
 */

$clase.= "	/**<br>";
$clase.= "	 * Devuelve un objeto de tipo C".$tabla."<br>";
$clase.= "	 * @param int \$".$cod."<br>";
$clase.= "	 * @return C".$tabla."<br>";
$clase.= "	 */<br>";
$clase.= "	public static function __getObj(\$".$cod."){<br>";
$clase.= "		//Recoger los resultados de la BD<br>";
$clase.= "		//Solo debe de devolver 1<br>";


$clase.= "		\$sql = \"SELECT * FROM ".$tabla." WHERE ".$cod." = \".\$".$cod.";<br><br>";


$clase.= "		\$q = DB::get()->query(\$sql, PDO::FETCH_ASSOC);<br><br>";
	 
$clase.= "		//Inicializar un objeto con los valores devueltos<br>";
$clase.= "		foreach (\$q as \$arr){<br>";
$clase.= "			\$temp = self::_inicializar(\$arr);<br>";
$clase.= "		}<br><br>";
$clase.= "		return \$temp;<br>";
$clase.= "	}<br><br><br><br>";



/**
 * 
 * CREA LA FUNCIÓN PARA INICIALIZAR EL OBJETO CON LOS PARAMETROS QUE LE PASES
 * (_inicializar)
 * 
 */

$clase.="	/**<br>";
$clase.="	 * Inicializa un objeto con los valores que se le pasen como parametros<br>";
$clase.="	 * @param Array \$arrValores<br>";
$clase.="	 * @return C".$tabla."<br>";
$clase.="	 */<br>";
$clase.="	private static function _inicializar(\$arrValores){<br>";
$clase.="		//Estanciar el objeto<br>";
$clase.="		\$temp = new C".$tabla."();<br>";
$clase.="		//Asignarle los valores que se le pasan<br>";
$q = DB::get()->query($sql, PDO::FETCH_ASSOC);
foreach ($q as $campos){
	$clase.= "		\$temp->".$campos["COLUMN_NAME"]."= \$arrValores[\"".$campos["COLUMN_NAME"]."\"];<br>";
} 
$clase.="		return \$temp;<br>";
$clase.="	}<br><br><br><br>";



$clase.="	/**<br>";
$clase.="	 * Inicializa las propiedades del objeto<br>";
$clase.="	 */<br>";
$clase.="	private function ini(){<br>";
$q = DB::get()->query($sql, PDO::FETCH_ASSOC);
foreach ($q as $campos){
	$clase.= "		\$this->".$campos["COLUMN_NAME"]."= \"".$campos["COLUMN_DEFAULT"]."\";<br>";
};
$clase.="	}<br><br><br><br>";


/**
 * 
 * FUNCION PARA MOSTRAR LOS VALORES DEL OBJETO
 * 
 */

$clase.="	/**<br>";
$clase.="	 * Mostrar el contenido del objeto<br>";
$clase.="	 */<br>";
$clase.="	public function mostrar(){<br>";
$q = DB::get()->query($sql, PDO::FETCH_ASSOC);
foreach ($q as $campos){
	$clase.= "		echo \"".$campos["COLUMN_NAME"]." =&gt; \".  \$this->".$campos["COLUMN_NAME"].".\"&lt;br&gt;\";<br>";
};
$clase.="	}<br><br><br><br>";


/**
 * 
 * FUNCIÓN PARA BUSCAR OBJETOS SEGÚN LOS PARAMETROS
 * 
 */

$clase.="	/**<br>";
$clase.="	 * Devuelve un listado con objetos  según los parametros que le pasemos<br>";
$clase.="	 * @param array \$info<br>";
$clase.="	 * @param string \$order<br>";
$clase.="	 * @param array \$filtros<br>";
$clase.="	 * @return array C".$tabla."<br>";
$clase.="	 */<br>";


$clase.="	public static function __getListado(&\$info, \$order = \"id asc\", \$filtros=\"\"){<br>";
$clase.="		//Query<br>";
$clase.="		\$sql = \"SELECT * FROM ".$tabla." WHERE 1 = 1\";<br><br>";
	 
$clase.="		//Filtros de busqueda<br>";

$q = DB::get()->query($sql, PDO::FETCH_ASSOC);
foreach ($q as $campos){
	$clase.="		if(isset(\$filtros[\"".$campos["COLUMN_NAME"]."\"])) \$sql.=\" and ".$campos["COLUMN_NAME"]." = '\".\$filtros[\"".$campos["COLUMN_NAME"]."\"].\"'\";<br>";
};

$clase.="<br>";
$clase.="		//Filtros de orden<br>";
$clase.="		\$sql.=\" ORDER BY \".\$order;<br><br>";
	 
$clase.="		//Recoger los valores de la BD<br>";
$clase.="		\$q = DB::get()->query(\$sql, PDO::FETCH_ASSOC);<br><br>";
	 
$clase.="		//Creamos un array de pruebas vacío<br>";
$clase.="		\$arrPru = Array();<br><br>";
	 
$clase.="		//Recogemos los datos e inicializamos objetos con esos valores<br>";
$clase.="		//Cada objeto lo metemos dentro del array<br>";
$clase.="		foreach (\$q as \$cObj){<br>";
$clase.="			\$temp = self::_inicializar(\$cObj);<br>";
$clase.="			\$arrPru[] = \$temp;<br>";
$clase.="		}<br><br>";

$clase.="		return \$arrPru;<br>";
$clase.="	}<br>";


/**
 *Cerrar clase y php
 */
$clase.="}<br>";
$clase.="?>";
$clase .= "</pre>";

echo $clase;