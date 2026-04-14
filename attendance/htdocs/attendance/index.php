<?php
require_once '../../include/model/attendance_index_model.php';
require_once '../../include/config/const.php';

$pdo = get_connection();

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id']) && isset($_POST['password'])) {
	$id     = $_POST['user_id'];
	$password = $_POST['password'];
	validation($id,$password);
	$listCheck = get_user_list($pdo,$id,$password);
	if($listCheck == 'none'){
		header("Location: index.php?message=mismatch");
		exit();
	}
	$_SESSION['id'] =  $listCheck['id'];
	setcookie('userid', $listCheck['id'],time()+60*60*24);
	setcookie('position', $listCheck['position'],time()+60*60*24);
	switch($listCheck['position']){			
		case 1:
			header("Location: manager.php");
			exit();
		case 2:
			header("Location: deputy.php");
			exit();
		case 3:
			header("Location: staff.php");
			exit();
		case 4:
			header("Location: director.php");
	}
}


function validation($id,$password){
	if(empty($id) || empty($password)){
		header("Location: index.php?message=blank");
		exit();
	}else if(strlen($id)<5 || !preg_match("/^[a-zA-Z0-9]+$/", $id)){
        header("Location: index.php?message=username");
        exit();
    }else if(strlen($password)<8 || !preg_match("/^[a-zA-Z0-9]+$/", $password)){
        header("Location: index.php?message=password");
        exit();
    }
}

if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['message'])) {
	switch($_GET['message']) {
		case 'blank':
			$resultMessage = 'ユーザーIDまたはパスワードが入力されていません';
			break;
		case 'username':
			$resultMessage = 'ユーザーIDは、半角英数字かつ5文字以上です';
			break;
		case 'password':
			$resultMessage = 'パスワードは、半角英数字かつ8文字以上です';
			break;
		case 'mismatch':
			$resultMessage = 'ユーザーIDまたはパスワードが間違っています';
			break;
	}
}else{
	$resultMessage = '';
}


// View(view.php）読み込み
include_once '../../include/view/attendance_index_view.php';