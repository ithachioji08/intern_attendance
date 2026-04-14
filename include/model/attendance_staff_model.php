<?php
require_once 'attendance_common_model.php';

function getStaffData($pdo,$id){
	$sql = "SELECT user_name,department,supervisor,pattern_alphabet,pattern_char from jpt_user where id=:id";
	$stmt = $pdo->prepare($sql);
	$stmt -> bindParam(':id',$id, PDO::PARAM_INT);
	$stmt -> execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result[0];
}

function getCanceledApp($pdo,$id){
	$sql = "SELECT count(1) from jpt_application where user_id=:id and status = 4";
	$stmt = $pdo->prepare($sql);
	$stmt -> bindParam(':id',$id, PDO::PARAM_INT);
	$stmt -> execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result[0]['count(1)'] >0;
}
function insert_application($pdo,$id,$dep,$per,$ptn,$time,$rsn){
	$pdo->beginTransaction();
	try{
		$sql  = "INSERT into jpt_application(id,user_id,department,name,pattern,time,reason,status,create_date,update_date) 
								values(0,:user_id,:department,:name,:ptn,:time,:reason,1,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)";
		$stmt = $pdo->prepare($sql);
		$stmt -> bindParam(':user_id',$id, PDO::PARAM_INT);
		$stmt -> bindParam(':name',$per, PDO::PARAM_STR);
		$stmt -> bindParam(':department',$dep, PDO::PARAM_STR);
		$stmt -> bindParam(':ptn',$ptn, PDO::PARAM_STR);
		$stmt -> bindParam(':time',$time, PDO::PARAM_STR);
		$stmt -> bindParam(':reason',$rsn, PDO::PARAM_STR);
		$stmt -> execute();

		$deleteSql  = "DELETE from jpt_application where status=4 and user_id=:id";
		$deletestmt = $pdo->prepare($deleteSql);
		$deletestmt -> bindParam(':id',$id,PDO::PARAM_INT);
		$deletestmt -> execute();
		$pdo->commit();
        return true;
	}catch(PDOException $e){
		$pdo->rollback();
		return false;
	}
}
function upload_report($pdo,$id,$report){
	$pdo->beginTransaction();
	try{
		$sql = "INSERT into jpt_report(id,file_name,user_id,status,create_date,update_date)
				values(0,:name,:id,1,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)";
		$stmt = $pdo->prepare($sql);
		$stmt -> bindParam(':name',$report['name'], PDO::PARAM_STR);
		$stmt -> bindParam(':id',$id, PDO::PARAM_INT);
		$stmt -> execute();
	}catch(PDOException $e){
		$pdo->rollback();
		return false;
	}
	
	//ファイルのタイプ
	$allowedMimeTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
	//ファイルの拡張子
	$allowedExtension = ['xls','xlsx'];
	//ファイル容量1キロBに設定
	$maxFileSize =  1024 * 1024; 

	if (!in_array($report['type'], $allowedMimeTypes)) {
		$pdo->rollback();
		return 'type';
	}
	if ($report['size'] > $maxFileSize) {
		$pdo->rollback();
		return 'size';
	}

	$nameSql = "SELECT user_name from jpt_user where id=:id";
	$nameStmt = $pdo->prepare($nameSql);
	$nameStmt -> bindParam(':id',$id, PDO::PARAM_INT);
	$nameStmt -> execute();
	$name    = $nameStmt->fetchAll(PDO::FETCH_ASSOC)[0]['user_name'];

	//報告書の名前がルールに則っているかをチェック
	$fileName  = explode(".",$report['name']);
	$firstName = explode("_",$fileName[0]);
	if(count($firstName) != 3 || $firstName[0] != '残業報告書' 
		|| !is_numeric($firstName[1]) || strlen($firstName[1])!=6
		|| $firstName[2] != $name
	){
		$pdo->rollback();
		return 'name';
	}

	$lastName = $fileName[array_key_last($fileName)];
	if (!in_array($lastName,$allowedExtension)) {
		$pdo->rollback();
		return 'extension';
	}
	
	$save = '../../htdocs/attendance/reports/' .$report['name'];
	if(move_uploaded_file($report['tmp_name'], $save)){
		$pdo->commit();
		return 'OK';
	}else{
		$pdo->rollback();
		return 'upload';
	}
}