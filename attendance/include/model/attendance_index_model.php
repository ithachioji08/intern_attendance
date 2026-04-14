<?php
require_once 'attendance_common_model.php';
function get_user_list($pdo,$id,$password){
	$sql    = 'SELECT id,password,position from jpt_user where user_id =:user_id';
	$stmt   = $pdo->prepare($sql);
	$stmt   -> bindParam(':user_id',$id, PDO::PARAM_STR);
	$stmt   -> execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$pass   = $result[0]['password'];
	if(!password_verify($password,$pass)){
		return 'none';
	}
	return $result[0];
}
