<?php
require_once 'attendance_common_model.php';


function update_app($pdo,$id){
	try{
		$pdo->beginTransaction();
		$updateSql = "UPDATE jpt_application INNER JOIN jpt_user ON jpt_application.user_id = jpt_user.id SET jpt_application.status=2,jpt_application.update_date=CURRENT_TIMESTAMP where (jpt_user.supervisor=:id or jpt_user.id=:id) and jpt_application.status=1";
		$stmt      = $pdo->prepare($updateSql);
		$stmt      -> bindParam(':id',$id, PDO::PARAM_INT);
		$stmt -> execute();
		$pdo->commit();
		return 'update';
	}catch(PDOException $e){
		$pdo->rollback();
		return 'updateFailed';
	}
}

