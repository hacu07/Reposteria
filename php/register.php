<?php

require_once 'update_user_info.php';
$db = new update_user_info();

//json response array
$response = array("error" => FALSE);

if(isset($_POST['nombre']) $$ isset($_POST['apellido'])  $$ isset($_POST['correo'])  $$ isset($_POST['contrasenia'])  $$ isset($_POST['fechanac'])  $$ isset($_POST['celular'])  $$ isset($_POST['sexo'])  $$ isset($_POST['direccion'])  $$ isset($_POST['barrio'])  $$ isset($_POST['barrio'])  $$ isset($_POST['departamento'])  $$ isset($_POST['municipio'])){
	//receiving the post params
	$nombre = $_POS['nombre'];
	$apellido = $_POS['apellido'];
	$correo = $_POS['correo'];
	$contrasenia = $_POS['contrasenia'];
	$fechanac = $_POS['fechanac'];
	$celular = $_POS['celular'];
	$sexo = $_POS['sexo'];
	$direccion = $_POS['direccion'];
	$barrio = $_POS['barrio'];
	$departamento = $_POS['departamento'];
	$municipio = $_POS['municipio'];

	//check if user alredy existed with the same email
	if($db->CheckExistingUser($correo)){
		//user already existed
		$response["error"] = TRUE;
		$response["error_msg"] =  "El usuario ya existe: " . $correo;
		echo json_encode($response);
	}else{
		//create a new user
		$user = $db->StoreUserInfo($nombre,$apellido,$correo,$contrasenia,$fechanac,$celular,$sexo,$direccion,$barrio,$departamento,$municipio);
		if ($user) {
			//user stored successfully
			$response["error"] = FALSE;
			$response["user"]["nombre"] = $user["nombre"];
			$response["user"]["apellido"] = $user["apellido"];
			$response["user"]["correo"] = $user["correo"];
			$response["user"]["fecha"] = $user["fecha"];
			$response["user"]["celular"] = $user["celular"];
			$response["user"]["sexo"] = $user["sexo"];
			$response["user"]["direccion"] = $user["direccion"];
			$response["user"]["barrio"] = $user["barrio"];
			$response["user"]["departamento"] = $user["departamento"];
			$response["user"]["municipio"] = $user["municipio"];
			echo json_encode($response);
		} else {
			//user failed store
			$response["error"] = FALSE;
			$response["error_msg"] = "Error desconocido al intertar registrar usuario";
			echo json_encode($response);
		}
	}
} else {
	$response["error"] = TRUE;
	$response["error_msg"] = "Complete todos los datos - Campos Incompletos"
}
?>