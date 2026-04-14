<?php
// Model（model.php）を読み込む
//require_once '../../include/utility/session_management.php';
require_once '../../include/model/attendance_register_model.php';
require_once '../../include/config/const.php';



if($_SERVER["REQUEST_METHOD"] == "POST" /*&& isset($_POST['user_id']) && isset($_POST['password']) 
	&& isset($_POST['department']) && isset($_POST['position']) && isset($_POST['patternAlb'])
	 && isset($_POST['patternChar']) && isset($_POST['supervisor'])*/ ) {
	validation($_POST['user_id'],$_POST['user_name'],$_POST['password'],$_POST['department']
				,$_POST['position'],$_POST['patternAlb'],$_POST['patternChar'],$_POST['supervisor']);
}



if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['message']) ) {
    switch($_GET['message']) {
        case 'blank':
            $resultMessage = '未入力の箇所があります';
            break;
        case 'id':
            $resultMessage = 'ユーザーIDは、半角英数字かつ5文字以上です';
            break;
        case 'password':
            $resultMessage = 'パスワードは、半角英数字かつ8文字以上です';
            break;
        case 'sameId':
            $resultMessage = 'すでにそのユーザーIDは使われています';
            break;
		case 'nonSupervisor':
			$resultMessage = '上長が存在しません';
			break;
        case 'success':
            $resultMessage = '新規ユーザーを登録しました';
            break;
        case 'failed':
            $resultMessage = '新規ユーザー登録に失敗しました';
            break;
    }
}else{
    $resultMessage = '';
}

require_once '../../include/view/attendance_register_view.php';
