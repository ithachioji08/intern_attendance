<?php
require_once '../../include/model/attendance_staff_model.php';
require_once '../../include/config/const.php';

$pdo = get_connection();

$id = $_COOKIE['userid'];

$staffData   = getStaffData($pdo,$id);
$canceledApp = getCanceledApp($pdo,$id);

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post']) ) {
	if(insert_application($pdo,$id,$_POST['modal-dep'],$_POST['modal-per'],$_POST['modal-ptn'],$_POST['modal-time'],$_POST['modal-rsn'])){
		header("Location: staff.php?message=printSuccess");
	}else{
		header("Location: staff.php?message=printFailed");
	}
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['rep'])) {
	switch(upload_report($pdo,$id,$_FILES['rep'])){
		case 'OK':
			header("Location: staff.php?message=repSuccess");
			break;
		case 'type':
			header("Location: staff.php?message=type");
			break;
		case 'size':
			header("Location: staff.php?message=size");
			break;
		case 'name':
			header("Location: staff.php?message=name");
			break;
		case 'extension':
			header("Location: staff.php?message=extension");
			break;
		case 'repFailed':
			header("Location: staff.php?message=repFailed");
			break;
	}
}else{
	$resultMessage = "";
}

if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['message'])) {
	switch($_GET['message']){
		case 'printSuccess':
			$resultMessage = "申請に成功しました";
			break;
		case 'printFailed':
			$resultMessage = "申請に失敗しました";
			break;
		case 'repSuccess':
			$resultMessage = "送信に成功しました";
			break;
		case 'repFailed':
			$resultMessage = "送信に失敗しました";
			break;
		case 'name':
			$resultMessage = 'ファイル名のルールが守られていません。残業報告書_xxxxxx(数字)_名前.xlsx、の形式にしてください';
			break;
		case 'type':
			$resultMessage = 'ファイルの形式が違います';
			break;
		case 'size':
			$resultMessage  = 'ファイルのサイズが大きすぎます。1メガバイト以内に収めてください';
			break;
		case 'extension':
			$resultMessage  = 'ファイル名が.xls/.xlsx以外です';
			break;
		default:
			$resultMessage = "";
	}
	
}

// View(view.php）読み込み
include_once '../../include/view/attendance_staff_view.php';