<?php
require_once 'attendance_common_model.php';

function sameName($pdo,$name){
    $sql  = 'SELECT count(1) from jpt_user where user_id=:name';
	$stmt = $pdo->prepare($sql);
	$stmt -> bindParam(':name',$name, PDO::PARAM_STR);
	$stmt -> execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result[0]['count(1)'] >0;
}

function superVisorCheck($pdo,$supervisor,$position){
	$sql  = 'SELECT count(1) from jpt_user where user_name=:name and position=:upPosition';
	$stmt = $pdo->prepare($sql);
	$stmt -> bindParam(':name',$supervisor, PDO::PARAM_STR);
	if(intval($position) !=1){
		$upPosition = intval($position) - 1;
	}else{
		$upPosition = 4;
	}
	
	$stmt -> bindParam(':upPosition',$upPosition, PDO::PARAM_INT);
	$stmt -> execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result[0]['count(1)'] == 0;
}

function validation($id,$name,$password,$department,$position,$alb,$char,$supervisor){
    $pdo = get_connection();
    if(empty($id) || empty($name) || empty($password)|| empty($department)|| empty($position)
	|| (intval($position)!=4 && (empty($alb)|| empty($alb)|| empty($char) || empty($supervisor)))){
		header("Location: register.php?message=blank");
		exit();
	}else if(!preg_match("/^[a-zA-Z0-9]+$/", $id)){
        header("Location: register.php?message=id");
        exit();
    }else if(strlen($password)<8 || !preg_match("/^[a-zA-Z0-9]+$/", $password)){
        header("Location: register.php?message=password");
        exit();
	//idが一致した場合	
    }else if(sameName($pdo,$id)){
        header("Location: register.php?message=sameId");
        exit();
	//上長が存在しない場合
    }elseif(intval($position)!=4 && superVisorCheck($pdo,$supervisor,$position)){
		header("Location: register.php?message=nonSupervisor");
        exit();
	}elseif(insert_user($pdo,$id,$name,$password,$department,$position,$alb,$char,$supervisor)){
        header("Location: register.php?message=success");
		exit();
    }else{
        header("Location: register.php?message=failed");
    }
}

function insert_user($pdo,$id,$name,$password,$department,$position,$alb,$char,$supervisor){
	$pdo->beginTransaction();
	$supervisorSql  = 'SELECT id from jpt_user where user_name=:supervisorName';
	$superVisorstmt = $pdo->prepare($supervisorSql);
	$superVisorstmt -> bindParam(':supervisorName',$supervisor, PDO::PARAM_STR);
	$superVisorstmt -> execute();
	$supervisorId = $superVisorstmt->fetchAll(PDO::FETCH_ASSOC)[0]['id'];
	try{
		$sql  = "INSERT into jpt_user(id,user_id,user_name,department,supervisor,pattern_alphabet,pattern_char,password,position,create_date,update_date) 
								values(0,:user_id,:name,:department,:supervisor,:alb,:char,:password,:position,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)";
		$stmt = $pdo->prepare($sql);
		$stmt -> bindParam(':user_id',$id, PDO::PARAM_STR);
		$stmt -> bindParam(':name',$name, PDO::PARAM_STR);
		$stmt -> bindParam(':department',$department, PDO::PARAM_STR);
		$stmt -> bindParam(':supervisor',$supervisorId, PDO::PARAM_STR);
		$stmt -> bindParam(':alb',$alb, PDO::PARAM_STR);
		$stmt -> bindParam(':char',$char, PDO::PARAM_STR);
		$stmt -> bindParam(':password', password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);
		$stmt -> bindParam(':position',$position, PDO::PARAM_INT);
		$stmt -> execute();
		$pdo->commit();
        return true;
	}catch(PDOException $e){
		$pdo->rollback();
		return false;
	}
}

