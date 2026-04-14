<?php
require_once 'attendance_common_model.php';

function getStaffData($pdo,$id){
	$sql =  'SELECT count(1) from jpt_application INNER JOIN jpt_user where jpt_user.supervisor=:id and status=1';
	$stmt = $pdo->prepare($sql);
	$stmt -> bindParam(':id',$id, PDO::PARAM_INT);
	$stmt -> execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result[0]['count(1)'];
}