<?php
require_once '../../include/model/attendance_manager_model.php';
require_once '../../include/config/const.php';

$pdo = get_connection();

$id = $_COOKIE['userid'];

$appList = getApplications($pdo,$id);

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['settle'])) {
	if(updateApp($pdo,$_POST['appId'],3)){
		header("Location: manager.php?message=settle");
	}else{
		header("Location: manager.php?message=failed");
	}
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancel'])) {
	if(updateApp($pdo,$_POST['appId'],4)){
		header("Location: manager.php?message=cancel");
	}else{
		header("Location: manager.php?message=failed");
	}
}

if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['message'])) {
	if($_GET['message'] == "settle"){
		$resultMessage = "残業申請を承認しました";
	}elseif($_GET['message'] == "cancel"){
		$resultMessage = "残業申請を取り消しました";
	}elseif($_GET['message'] == "failed"){
		$resultMessage = "更新に失敗しました";
	}
}else{
	$resultMessage ='';
}
// View(view.php）読み込み
include_once '../../include/view/attendance_manager_view.php';