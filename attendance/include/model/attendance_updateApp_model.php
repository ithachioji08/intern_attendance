<?php
require_once 'attendance_common_model.php';
function updateApp($pdo,$id,$status){
	try{
		$pdo->beginTransaction();
		$updateSql = "UPDATE jpt_application SET status=:status,update_date=CURRENT_TIMESTAMP where id=:id ";
		$stmt      = $pdo->prepare($updateSql);
		$stmt      -> bindParam(':id',$id, PDO::PARAM_INT);
		$stmt      -> bindParam(':status',$status, PDO::PARAM_INT);
		$stmt -> execute();
		$pdo->commit();
		return true;
	}catch(PDOException $e){
		$pdo->rollback();
		return false;
	}
}