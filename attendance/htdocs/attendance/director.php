<?php
require_once '../../include/model/attendance_director_model.php';
require_once '../../include/config/const.php';

$pdo = get_connection();

$id = $_COOKIE['userid'];

$appList = getApplications($pdo,$id);

$repNum = getRepNum($pdo,$id);

$pdfBool= getPdfBool($pdo,$id);


if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['settle'])) {
	if(updateApp($pdo,$_POST['appId'],3)){
		header("Location: director.php?message=settle");
	}else{
		header("Location: director.php?message=failed");
	}
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancel'])) {
	if(updateApp($pdo,$_POST['appId'],4)){
		header("Location: director.php?message=cancel");
	}else{
		header("Location: director.php?message=failed");
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

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rep'])) {
	if(update_rep($pdo,$id)){
		header("Location: director.php?rep=success");
	}else{
		header("Location: director.php?rep=failed");
	}
}else{
	$resultMessage = "";
}


if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['rep'])) {
	if($_GET['rep'] == "success"){
		$resultMessage = "残業報告書のダウンロードに成功しました";
	}elseif($_GET['rep'] == "failed"){
		$resultMessage = "残業報告書が見つかりません。リロードして残業報告書があるか確認してください";
	}else{
		$resultMessage = "";
	}
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pdf'])) {
	if(download_pdf($pdo,$id)){
		header("Location: director.php?pdf=success");
	}else{
		header("Location: director.php?pdf=failed");
	}
}


if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['pdf'])) {
	if($_GET['pdf'] == "success"){
		$resultMessage = "残業申請書のダウンロードに成功しました";
	}elseif($_GET['pdf'] == "failed"){
		$resultMessage = "残業申請書が見つかりません。リロードして残業申請書があるか確認してください";
	}else{
		$resultMessage = '';
	}
}
// View(view.php）読み込み
include_once '../../include/view/attendance_director_view.php';