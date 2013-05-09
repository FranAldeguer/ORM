<?php
//Dependencias
include_once ("../conf.php");
require_once (DIR_CLASSES."DB.php");

class CAlumno{

	var $id;
	var $dni;
	var $nombre;
	var $apellidos;
	var $fecha_nac;
	var $telefono;
	var $mail;
	var $id_profesor;
	var $pass;


	/**
	 *Constructor de la clase CAlumno
	 */
	public function CAlumno(){
		$this->ini();
	}

       /**
        * Funcion para insertar un nuevo objeto en la base de datos
        * @throws Exception -> Si hay un fallo al insertar el obj en la BD
        * @return int -> Id del utlimo registro insertado || int -> 0
        */
	public function insert(){
		try{
			//Inicializa la transacción
			DB::get()->beginTransaction();

			//Crea la query
			$sql = "INSERT INTO Alumno"; 
			$sql .= "(id, dni, nombre, apellidos, fecha_nac, telefono, mail, id_profesor, pass)";
			$sql .= "VALUES(";
			$sql .= "'".$this->id."'";
			$sql .= ",'".$this->dni."'";
			$sql .= ",'".$this->nombre."'";
			$sql .= ",'".$this->apellidos."'";
			$sql .= ",'".$this->fecha_nac."'";
			$sql .= ",'".$this->telefono."'";
			$sql .= ",'".$this->mail."'";
			$sql .= ",'".$this->id_profesor."'";
			$sql .= ",'".$this->pass."'";
			$sql .= ")";

			//Ejecuta la query
			$q = DB::get()->exec($sql);

			//Comprueba que no hay errores en la inserción
			if ($q->errorInfo[0] != 00000) throw new Exception("Error al insertar");

			//Devuelve el id del ultimo registro insertado
			$return = DB::get()->lastInsertId();

			 //Finaliza correctamente la transaccion
			DB::get()->commit();

			//Devuelve el ultimo id
			return $return;

		}catch(Excepction $e){
			//Finaliza la transaccion sin guardar los cambios
			DB::get()->rollBack();

			//Muestra el mensaje de error de la excepción
			echo $e->getMessage();

			return 0;
		}
	}



	/**
	 * Funcion para eliminar un objeto
	 * @return boolean
	 */
	public function delete(){
		//Llama al metodo estatico de borrar y le pasa el id del objeto actual
		self::__delete($this->id);
	}



	/**
	 * Funcion para eliminar un objeto
	 * @param int $id_prueba
	 * @throws Exception
	 * @return boolean
	 */
	public static function __delete($id){
		try{
			//Inicia la transaccion
			DB::get()->beginTransaction();

			//Ejecucion de la query
			$sql = "DELETE FROM prueba WHERE id = ".$id;

			$q = DB::get()->exec($sql);

			//Comprobación de fallos
			if($q != 1) throw new Exception("Fallo al eliminar");

			//Finaliza la transaccion correctamente
			DB::get()->commit();

			return true;

		}catch (Exception $e){

			//Finaliza la transaccion con errores
			DB::get()->rollBack();

			//Muestra el mensaje de error
			echo $e->getMessage();

			return false;
		}
	}



	/**
	 * Actualiza los parametros de un objeto
	 * @throws Exception
	 * @return boolean
	 */
	public function update(){
		try{
			//Inicializa la transaccion
			DB::get()->beginTransaction();

			//Ejecución de la query
			$sql = "UPDATE prueba SET

";			$sql.= "id='".$this->id."'";
			$sql.= ", dni='".$this->dni."'";
			$sql.= ", nombre='".$this->nombre."'";
			$sql.= ", apellidos='".$this->apellidos."'";
			$sql.= ", fecha_nac='".$this->fecha_nac."'";
			$sql.= ", telefono='".$this->telefono."'";
			$sql.= ", mail='".$this->mail."'";
			$sql.= ", id_profesor='".$this->id_profesor."'";
			$sql.= ", pass='".$this->pass."'";
			$sql.= " WHERE 1=1";
			$sql.= " and id = '".$this->id."'";

			//Ejecución de la query
			$q = DB::get()->exec($sql);

			//Comprobación de errores
			if($q != 1) throw new Exception("Error en la modificación");

			//Finaliza la transaccion correctamente
			DB::get()->commit();

			return true;

		}catch (Exception $e){

			//Finaliza la transaccion con errores
			DB::get()->rollBack();

			//Muestra el mensaje de error
			echo $e->getMessage();

			return false;
		}
	}



	/**
	 * Devuelve un objeto de tipo CAlumno
	 * @param int $id
	 * @return CAlumno
	 */
	public static function __getObj($id){
		//Recoger los resultados de la BD
		//Solo debe de devolver 1
		$sql = "SELECT * FROM Alumno WHERE id = ".$id;

		$q = DB::get()->query($sql, PDO::FETCH_ASSOC);

		//Inicializar un objeto con los valores devueltos
		foreach ($q as $arr){
			$temp = self::_inicializar($arr);
		}

		return $temp;
	}



	/**
	 * Inicializa un objeto con los valores que se le pasen como parametros
	 * @param Array $arrValores
	 * @return CAlumno
	 */
	private static function _inicializar($arrValores){
		//Estanciar el objeto
		$temp = new CAlumno();
		//Asignarle los valores que se le pasan
		$temp->id= $arrValores["id"];
		$temp->dni= $arrValores["dni"];
		$temp->nombre= $arrValores["nombre"];
		$temp->apellidos= $arrValores["apellidos"];
		$temp->fecha_nac= $arrValores["fecha_nac"];
		$temp->telefono= $arrValores["telefono"];
		$temp->mail= $arrValores["mail"];
		$temp->id_profesor= $arrValores["id_profesor"];
		$temp->pass= $arrValores["pass"];
		return $temp;
	}



	/**
	 * Inicializa las propiedades del objeto
	 * @return Prueba
	 */
	private static function ini(){
		$temp->id= "";
		$temp->dni= "";
		$temp->nombre= "";
		$temp->apellidos= "";
		$temp->fecha_nac= "";
		$temp->telefono= "0";
		$temp->mail= "";
		$temp->id_profesor= "";
		$temp->pass= "passwd";
	}



	/**
	 * Mostrar el contenido del objeto
	 */
	public function mostrar(){
		echo $this->id;
		echo $this->dni;
		echo $this->nombre;
		echo $this->apellidos;
		echo $this->fecha_nac;
		echo $this->telefono;
		echo $this->mail;
		echo $this->id_profesor;
		echo $this->pass;
	}



	/**
	 * Devuelve un listado con objetos  según los parametros que le pasemos
	 * @param array $info
	 * @param string $order
	 * @param array $filtros
	 * @return array CAlumno
	 */
	public static function __getListado(&$info, $order = "id asc", $filtros=""){
		//Query
		$sql = "SELECT * FROM Alumno WHERE 1 = 1";

		//Filtros de busqueda
		if(isset($filtros["id"])) $sql.=" and id = ".$filtros["id"]."";
		if(isset($filtros["dni"])) $sql.=" and id = ".$filtros["dni"]."";
		if(isset($filtros["nombre"])) $sql.=" and id = ".$filtros["nombre"]."";
		if(isset($filtros["apellidos"])) $sql.=" and id = ".$filtros["apellidos"]."";
		if(isset($filtros["fecha_nac"])) $sql.=" and id = ".$filtros["fecha_nac"]."";
		if(isset($filtros["telefono"])) $sql.=" and id = ".$filtros["telefono"]."";
		if(isset($filtros["mail"])) $sql.=" and id = ".$filtros["mail"]."";
		if(isset($filtros["id_profesor"])) $sql.=" and id = ".$filtros["id_profesor"]."";
		if(isset($filtros["pass"])) $sql.=" and id = ".$filtros["pass"]."";

		//Filtros de orden
		$sql.=" ORDER BY ".$order;

		//Recoger los valores de la BD
		$q = DB::get()->query($sql, PDO::FETCH_ASSOC);

		//Creamos un array de pruebas vacío
		$arrPru = Array();

		//Recogemos los datos e inicializamos objetos con esos valores
		//Cada objeto lo metemos dentro del array
		foreach ($q as $cPru){
			$temp = self::_inicializar($cPru);
			$arrPru[] = $temp;
		}

		return $arrPru;
	}
}
?>