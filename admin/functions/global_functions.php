<?php

	$error = '';
	$advertencia = ''; 

	if(!defined('bienestar_plus')) {
	   header('Location: index.php');
	} 

	//LOGIN *** create after
	if( isset($_POST['login']) && $_POST['login'] === '1' ) {
		if (isset($_POST['usuario']) AND $_POST['usuario'] != "") {
			$con = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$sql = $con->prepare('SELECT * FROM `usuarios` WHERE `usuario` = :user');
			$user = $_POST['usuario'];
			$sql->bindValue(':user', $user);
			$sql->execute();
			//$sql->debugDumpParams();
			if ($row = $sql->fetch(PDO::FETCH_OBJ)) { 
				//$hashedPassword = md5(md5($row->id).$_POST['password']);
				if ($_POST['pass'] == $row->pass) {
					$_SESSION['bp_login'] = $row->id;
				} else {
					$error = "El usuario y la contraseña no coinciden";
				}
			}
		}  else {
			$error = "Usuario o contraseña errada";
		}
	}

	//LOGOUT

	if(isset($_POST['logout']) && $_POST['logout'] === '1' ) {
	   	session_unset();
		session_destroy();
		$_SESSION = array();
	}
?>
