<?php
require_once '../../include/model/attendance_deputy_model.php';
require_once '../../include/config/const.php';

$pdo = get_connection();

$id = $_COOKIE['userid'];

$appNum = getAppNum($pdo,$id);

$repNum = getRepNum($pdo,$id);

$pdfBool= getPdfBool($pdo,$id);


if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['app'])) {
	if(update_app($pdo,$id)){
		header("Location: deputy.php?app=success");
	}else{
		header("Location: deputy.php?app=failed");
	}
}

if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['app'])) {
	if($_GET['app'] == "success"){
		$resultMessage = "残業申請の報告に成功しました";
	}elseif($_GET['app'] == "failed"){
		$resultMessage = "残業申請の報告に失敗しました";
	}else{
		$resultMessage = '';
	}
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rep'])) {
	if(!update_rep($pdo,$id)){
		header("Location: deputy.php?rep=failed");
	}
}


if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['rep'])) {
	if($_GET['rep'] == "success"){
		$resultMessage = "残業報告書のダウンロードに成功しました";
	}elseif($_GET['rep'] == "failed"){
		$resultMessage = "残業報告書が見つかりません。リロードして残業報告書があるか確認してください";
	}else{
		$resultMessage = '';
	}
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pdf'])) {
	if(download_pdf($pdo,$id)){
		header("Location: deputy.php?pdf=success");
	}else{
		header("Location: deputy.php?pdf=failed");
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
include_once '../../include/view/attendance_deputy_view.php';