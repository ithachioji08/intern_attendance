<?php
/**
* DB接続を行いPDOインスタンスを返す
* 
* @return object $pdo 
*/
function get_connection() {
  try{
    // PDOインスタンスの生成
   $pdo = new PDO(dsn, login_user, password);
  } catch (PDOException $e) {
    echo $e->getMessage();
    exit();
  }

  return $pdo;
}
 
/**
* SQL文を実行・結果を配列で取得する
*
* @param object $pdo
* @param string $sql 実行されるSQL文章
* @return array 結果セットの配列
*/
function get_sql_result($pdo, $sql) {
  $data = [];
  if ($result = $pdo->query($sql)) {
    if ($result->rowCount() > 0) {
      while ($row = $result->fetch()) {
        $data[] = $row;
      }
    }
  }
  return $data;
}

/**
* SQL文の挿入編集を実行。
*
* @param object $pdo
* @param string $sql 実行されるSQL文章
* @return boolean 結果セットの配列
*/
function change_sql($pdo, $sql) {
	
    if ($pdo->query($sql)) {
      return true;
    }else {
      return false;
    }
}


function getAppNum($pdo,$id){
	$sql =  'SELECT count(1) from jpt_application INNER JOIN jpt_user ON jpt_user.id = jpt_application.user_id where (jpt_user.supervisor=:id or jpt_user.id=:id) and jpt_application.status=1;';
	$stmt = $pdo->prepare($sql);
	$stmt -> bindParam(':id',$id, PDO::PARAM_INT);
	$stmt -> execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result[0]['count(1)'];
}

function getRepNum($pdo,$id){
	$sql =  'SELECT count(1) from jpt_report INNER JOIN jpt_user ON jpt_user.id = jpt_report.user_id where (jpt_user.supervisor=:id or jpt_user.id=:id) and jpt_report.status=1;';
	$stmt = $pdo->prepare($sql);
	$stmt -> bindParam(':id',$id, PDO::PARAM_INT);
	$stmt -> execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result[0]['count(1)'];
}

function getApplications($pdo,$id){
	$positionSql  = "SELECT position from jpt_user where id=:id";
	$positionStmt = $pdo->prepare($positionSql);
	$positionStmt -> bindParam(':id',$id, PDO::PARAM_INT);
	$positionStmt -> execute();
	$position     = $positionStmt->fetchAll(PDO::FETCH_ASSOC)[0]['position'];
	if ($position == 4){
		$ifSentence = "app.status = 1 and staff.supervisor=:id";
	}else{
		$ifSentence = "app.status = 2 and deputy.supervisor=:id";
	}
	$sql = "SELECT app.id,app.name,app.department,app.pattern,app.time,app.reason from jpt_application as app INNER JOIN jpt_user as staff on app.user_id = staff.id INNER join jpt_user as deputy on deputy.id = staff.supervisor WHERE ".$ifSentence;
	$stmt = $pdo->prepare($sql);
	$stmt -> bindParam(':id',$id, PDO::PARAM_INT);
	$stmt -> execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}



function update_rep($pdo,$id){
	$deputySql = 'SELECT user_name from jpt_user where id=:id';
	$deputyStmt = $pdo->prepare($deputySql);
	$deputyStmt -> bindParam(':id',$id, PDO::PARAM_INT);
	$deputyStmt -> execute();
	$dequtyName = $deputyStmt->fetchAll(PDO::FETCH_ASSOC)[0]['user_name'];

	$reportSql = 'SELECT file_name from jpt_report INNER JOIN jpt_user ON jpt_user.id = jpt_report.user_id where (jpt_user.supervisor=:id or jpt_user.id=:id) and jpt_report.status=1';
	$reportStmt = $pdo->prepare($reportSql);
	$reportStmt -> bindParam(':id',$id, PDO::PARAM_INT);
	$reportStmt -> execute();
	$result = $reportStmt->fetchAll(PDO::FETCH_ASSOC);
	$reportnames = [];
	foreach($result as $row){
		array_push($reportnames,$row['file_name']);
	}

	if(count($reportnames) == 0){
		return false;
	}

	try{
		$pdo->beginTransaction();
		$updateSql = "UPDATE jpt_report INNER JOIN jpt_user ON jpt_report.user_id = jpt_user.id SET jpt_report.status=2,jpt_report.update_date=CURRENT_TIMESTAMP where (jpt_user.supervisor=:id or jpt_user.id=:id) and jpt_report.status=1";
		$stmt      = $pdo->prepare($updateSql);
		$stmt      -> bindParam(':id',$id, PDO::PARAM_INT);
		$stmt -> execute();
		
	}catch(PDOException $e){
		$pdo->rollback();
		return false;
	}

	//zipの圧縮開始
	$monthYear = explode('_',$reportnames[0])[1];
	$dist = $dequtyName.'_'.$monthYear.'_'.'残業書類'.'.zip';
	$path = '../../htdocs/attendance/reports/';

	$reportZip = new ZipArchive();
	$reportZip->open($dist, ZipArchive::CREATE | ZipArchive::OVERWRITE);

	if (is_dir($path)) {
		$files = array_diff(scandir($path), ['.', '..']);
		foreach ($files as $file){
			if(in_array($file,$reportnames)){
				$reportZip->addFile($path.$file,$file);
			}
		}
	}else{
		$pdo->rollback();
		return false;
	}


	$reportZip->close();
	mb_http_output("pass");
	// ストリームに出力
	header('Content-Type: application/zip; name="' . $dist . '"');
	//header('X-Content-Type-Options: nosniff');
	header('Content-Disposition: attachment; filename="' . $dist . '"');
	header("Content-Length: ".filesize($dist));
	ob_end_clean();
	readfile($dist);
	unlink($dist);
	$pdo->commit();
	
	return true;
}

function getPdfBool($pdo,$id){
	$appSql = 'SELECT count(1) from jpt_application INNER JOIN jpt_user ON jpt_user.id = jpt_application.user_id  where (jpt_user.supervisor=:id or jpt_user.id=:id) and jpt_application.status=3';
	$appStmt = $pdo->prepare($appSql);
	$appStmt -> bindParam(':id',$id, PDO::PARAM_INT);
	$appStmt -> execute();
	$count = $appStmt->fetchAll(PDO::FETCH_ASSOC)[0]['count(1)'];
	if ($count > 0){
		return false;
	}else{
		return true;
	}
}

require_once 'tcpdf/tcpdf.php';
function download_pdf($pdo,$id){
	$appSql = 'SELECT name,jpt_application.department,pattern,time,reason from jpt_application INNER JOIN jpt_user ON jpt_user.id = jpt_application.user_id  where (jpt_user.supervisor=:id or jpt_user.id=:id) and jpt_application.status=3';
	$appStmt = $pdo->prepare($appSql);
	$appStmt -> bindParam(':id',$id, PDO::PARAM_INT);
	$appStmt -> execute();
	$appList = $appStmt->fetchAll(PDO::FETCH_ASSOC);

	$deputySql = 'SELECT user_name from jpt_user where id=:id';
	$deputyStmt = $pdo->prepare($deputySql);
	$deputyStmt -> bindParam(':id',$id, PDO::PARAM_INT);
	$deputyStmt -> execute();
	$dequtyName = $deputyStmt->fetchAll(PDO::FETCH_ASSOC)[0]['user_name'];

	$positionSql  = 'SELECT position from jpt_user where id=:id';
	$positionStmt = $pdo->prepare($positionSql);
	$positionStmt -> bindParam(':id',$id, PDO::PARAM_INT);
	$positionStmt -> execute();
	$position     = $positionStmt->fetchAll(PDO::FETCH_ASSOC)[0]['position'];

	if($position ==4){
		$superVisorSql = 'SELECT user_name from jpt_user where id=:id';
	}else{
		$superVisorSql = 'SELECT  supervisor.user_name FROM jpt_user as supervisor INNER join jpt_user as user ON supervisor.id = user.supervisor where  user.id = :id';

	}

	$superVisorStmt = $pdo->prepare($superVisorSql);
	$superVisorStmt -> bindParam(':id',$id, PDO::PARAM_INT);
	$superVisorStmt -> execute();
	$superVisorName = $superVisorStmt->fetchAll(PDO::FETCH_ASSOC)[0]['user_name'];

	$pdf = new TCPDF('P', 'mm', 'A4',true, 'UTF-8',false,false);
	$pdf ->setFont("kozgopromedium", "", 10);
	$pdf ->AddPage();
	$html = "<style>
		h1{
			text-align:center;
		}
		table th{
			border: solid 1px #333;
			width:22%;
		}
		table td{
			border: solid 1px #333;
			width:80%;
		}
		.signature{
			text-align:right;
		}
		.reason{
			height: 150px;
		}</style>
		<h1>残業申請書</h1>";
	foreach ($appList as $app){
		$html = $html. addHtml($app,$superVisorName);
	}

	if(count($appList) == 0){
		return false;
	}

	try{
		$pdo->beginTransaction();
		$updateSql = "UPDATE jpt_application INNER JOIN jpt_user ON jpt_user.id = jpt_application.user_id set jpt_application.status=5 where (jpt_user.supervisor=:id or jpt_user.id=:id) and jpt_application.status=3";
		$stmt      = $pdo->prepare($updateSql);
		$stmt      -> bindParam(':id',$id, PDO::PARAM_INT);
		$stmt -> execute();
		$pdo->commit();
	}catch(PDOException $e){
		$pdo->rollback();
		return false;
	}
	$pdf->writeHTML($html); // 表示htmlを設定
	$date = explode('-',$appList[0]['time']);
	$name = $dequtyName.'_'.$date[0].$date[1].'_残業申請書.pdf';
	$pdf->Output($name, 'D'); // pdf表示設定
	return true;
}

function addHtml($app,$superVisorName){
	$startEnd  = explode('~',$app['time']);
	$start     = explode('T',$startEnd[0]);
	$end       = explode('T',$startEnd[1]);
	$startDate = explode('-',$start[0]);
	$endDate   = explode('-',$end[0]);
	$startTime = explode(':',$start[1]);
	$endTime   = explode(':',$end[1]);
	return '<table class="printApp">
		<tr>
			<th>部署名</th>
			<td class="value">'.$app['department'].'</td>
		</tr>
		<tr>
			<th>氏名</th>
			<td class="value">'.$app['name'].'</td>
		</tr>
		<tr>
			<th>勤務パターン</th>
			<td class="value">'.$app['pattern'].'</td>
		</tr>
		<tr>
			<th>残業予定時間</th>
			<td class="value">'
				.$startDate[0].'年'.$startDate[1].'月'.$startDate[2].'日'.$startTime[0].'時'.$startTime[1].'分'.
				'~'.$endDate[0].'年'.$endDate[1].'月'.$endDate[2].'日'.$endTime[0].'時'.$endTime[1].'分'.
			'</td>
		</tr>
		<tr class="highTr">
			<th>残業理由</th>
			<td class="value reason">'.$app['reason'].'</td>
		</tr>
		<tr>
			<th >上記の残業を命令します</th>
			<td class="value signature">
				<span class="underline">承認者　　　　　　'.$superVisorName.'</span>
			</td>
		</tr>
	</table>
	<div height:40px;></div>';
	
}