<?php 
$title = '残業申請管理_次長';
$is_home = true; //トップページの判定用の変数
$sclipt  = '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>';
	
include "attendance_header.php"
?>
	<form method="post">
		<div class="center">
			<?php if($resultMessage!=''){?>
				<p class="error">
					<?php print $resultMessage;?>
				</p>
			<?php }?>
			<h1>現在の残業申請は<?php echo $appNum;?>件です</h1>
			<button id="appBtn" name="app" <?php if($appNum ==0) echo "disabled";?>>残業申請の報告</button>
		</div>
		<div class="center">
			<h1>現在の残業報告は<?php echo $repNum;?>件です</h1>
			<button id="repBtn" name="rep" <?php if($repNum ==0) echo "disabled";?>>残業報告書のzipファイル作成</button>
			<button id="pdfBtn" name="pdf" <?php if($pdfBool) echo "disabled";?>>残業申請書のpdfファイル作成</button>
		</div>
	</form>
	<div class="center">
		<div class="link"><a href="index.php">ログインページへ</a></div>
	</div>
	<div class="center">
		<div class="link"><a href="staff.php">自身の残業申請へ</a></div>
	</div>
</body>
</html>