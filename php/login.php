<?php

include("update_user_info.php");
$db = new update_user_info();

//json response array
$response = array("error" => FALSE);

if (isset($_POST['email']) && isset($_POST['password'])) {
	//receiving the post params
	$email = $_POST['email'];
	$password = $_POST['password'];

	//get the user by email and password
	$user = $db->VerifyUserAuthentication($email,$password);

	if ($user != false) {
		//user is found
		$response["error"] = FALSE;
		$response["uid"] = $user["id"];
		$response["user"]["nombre"] = $user["nombre"];
		$response["user"]["apellido"] = $user["apellido"];
		$response["user"]["correo"] = $user["correo"];
		$response["user"]["fechanac"] = $user["fechanac"];
		$response["user"]["celular"] = $user["celular"];
		$response["user"]["sexo"] = $user["sexo"];
		$response["user"]["direccion"] = $user["direccion"];
		$response["user"]["barrio"] = $user["barrio"];
		$response["user"]["departamento"] = $user["departamento"];
		$response["user"]["municipio"] = $user["municipio"];
		$response["user"]["codrol"] = $user["codrol"];
		$response["user"]["urlimagen"] = $user["urlimagen"];
		echo json_encode($response);
	}
} else{
	//required post params is missing
	$response["error"]= TRUE;
	$response["error_msg"] = "Correo o contrasenia no encontrados!";
	echo json_encode($response);
}

?>