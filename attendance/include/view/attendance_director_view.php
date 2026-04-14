<?php 
$title = '残業申請管理_部長';
$is_home = true; //トップページの判定用の変数
$sclipt  = '';
include "attendance_header.php"
?>

<div class="manager">
	<?php if($resultMessage!=''){?>
			<p class="error">
				<?php print $resultMessage;?>
			</p>
		<?php }?>
	<h1>現在の残業申請の一覧</h1>
	<?php 
		include "attendance_table.php";
	?>
	<div class="center">
		<form method="post">
			<h1>現在の残業報告は<?php echo $repNum;?>件です</h1>
			<button id="repBtn" name="rep" <?php if($repNum ==0) echo "disabled";?>>残業報告書のzipファイル作成</button>
			<button id="pdfBtn" name="pdf" <?php if($pdfBool) echo "disabled";?>>残業申請書のpdfファイル作成</button>
		</form>

	</div>
	<div class="link"><a href="index.php">ログインページへ</a></div>
</div>
</body>
</html>