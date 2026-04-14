<?php 
$title = '残業申請管理_課長';
$is_home = true; //トップページの判定用の変数
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
	
	<div class="link"><a href="staff.php">自身の残業申請へ</a></div>
	<div class="link"><a href="index.php">ログインページへ</a></div>
</div>
</body>
</html>