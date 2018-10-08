<?php

/*
	REPOSTERIA
	Autor: Harold Cupitra
	Fecha: 06/10/2018
*/

/*
	Se cifra la contrasenia mediante la funcion hash, que a su vez se cifra mediante base64_encode(). Codifica Los datos dados con base64. Despues de que estos datos de usuario se inserten a la base de datos se realiza una consulta para verificar su insercion exitosa lo cual es retornado su respuesta.
*/
class update_user_info{

	private $conn;

	//constructor
	function __construct() {
        include("connect.php");
        // connecting to database
        $db = new login_connect();
        $this->conn = $db->connect();
    }

    //destructor
    function __detruct(){
    	
    }


	public function StoreUserInfo($nombre,$apellido,$correo,$contrasenia,$fechanac,$celular,$sexo,$direccion,$barrio,$departamento,$municipio){
		$hash = $this->hashFunction($contrasenia);//envia la contraseña y obtiene un array con la contraseña encryptada y el salt (Que es-->http://bit.ly/2pB70qa)
		$encrypted_password = $hash["encrypted"]; //Encrypted password
		$salt = $hash["salt"]; //salt

		$stmt = $this->conn->prepare("INSERT INTO usuario(nombre,apellido,correo,contrasenia,salt,fechanac,celular,sexo,direccion,barrio,departamento,municipio) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->bind_param("ssssssssssss",$nombre,$apellido,$correo,$encrypted_password,$salt,$fechanac,$celular,$sexo,$direccion,$barrio,$departamento,$municipio);
		$result = $stmt->execute();
		$stmt->close();

		//Check for successfull store
		if($result){
			$stmt = $this->conn->prepare("SELECT nombre,apellido,correo,contrasenia,salt,fechanac,celular,sexo,direccion,barrio,departamento,municipio FROM usuario WHERE correo = ?");
			$stmt->bind_param("s",$correo);
			$stmt->execute();
			$stmt->bind_result($tokenNom,$tokenApe,$tokenCor,$tokenCon,$tokenSal,$tokenFec,$tokenCel,$tokenSex,$tokenDir,$tokenBar,$tokenDep,$tokenMun);
			while($stmt->fetch()){
				$user["nombre"] = $tokenNom;
				$user["apellido"] = $tokenApe;
				$user["correo"] = $tokenCor;
				$user["fecha"] = $tokenFec;
				$user["celular"] = $tokenCel;
				$user["sexo"] = $tokenSex;
				$user["direccion"] = $tokenDir;
				$user["barrio"] = $tokenBar;
				$user["departamento"] = $tokenDep;
				$user["municipio"] = $tokenMun;
				$stmt->close();
				return $user;
			}
		}else{
			return false;
		}
	}


	public function hashFunction($password){
		$salt = sha1(rand());
		$salt = substr($salt, 0, 10);
		$encrypted = base64_encode(sha1($password . $salt, true). $salt);
		$hash = array("salt" => $salt, "encrypted" => $encrypted);
		return $hash;
	}


	/*
		Verifica las credenciales de inicio de sesion de android correctas.
		Devuelve verdadero si es el nombre de usuario y contrasenia correcto.
		De lo contrario devuelve falso.
	*/
	public function VerifyUserAuthentication($email,$password){
		$stmt = $this->conn->prepare("SELECT nombre,apellido,correo,contrasenia,salt,fechanac,celular,sexo,direccion,barrio,departamento,municipio FROM usuario WHERE correo = ?");
		$stmt->bind_param("s",$email);

		if($stmt->execute()){
			$stmt->bind_result($tokenNom,$tokenApe,$tokenCor,$tokenCon,$tokenSal,$tokenFec,$tokenCel,$tokenSex,$tokenDir,$tokenBar,$tokenDep,$tokenMun);
			while ($stmt->fetch()) {
				$user["nombre"] = $tokenNom;
				$user["apellido"] = $tokenApe;
				$user["correo"] = $tokenCor;
				$user["contrasenia"] = $tokenCon;
				$user["salt"] = $tokenSal;
				$user["fecha"] = $tokenFec;
				$user["celular"] = $tokenCel;
				$user["sexo"] = $tokenSex;
				$user["direccion"] = $tokenDir;
				$user["barrio"] = $tokenBar;
				$user["departamento"] = $tokenDep;
				$user["municipio"] = $tokenMun;
			}
			$stmt->close();

			//Verifying user password
			$salt = $tokenSal;
			$encrypted_password = $tokenCon;
			$hash = $this->checkHashFunction($salt,$password);
			//Check for password equality
			if($encrypted_password == $hash){
				//user authentication details are correct
				return $user;
			}
		} else {
			return null;
		}
	}

	public function checkHashFunction($salt, $password){
		$hash = base64_encode(sha1($password . $salt, true). $salt);
		return $hash;
	}

	/*
		Verifica si el usuario ya existe o no. Si un usuario est duplicando entrada entonces sera atrapado.
	*/
	public function CheckExistingUser($email){
		$stmt = $this->conn->prepare("SELECT correo FROM usuario WHERE correo = ?");
		$stmt->bind_param("s",$email);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			//user existed
			$stmt->close();
			return true;
		}else{
			//user no existed
			$stmt->close();
			return false;
		}
	}
}

?>