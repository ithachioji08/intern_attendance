<?php 
$title = '残業申請管理_ユーザー登録';
$is_home = true; //トップページの判定用の変数
$sclipt = "<script src='./register.js'></script><script src='./patternEnable.js'></script>";
include "attendance_header.php"
?>
	<div class="center">
		<h1>ユーザー登録</h1>
		<?php if($resultMessage!=''){?>
			<p class="error">
				<?php print htmlspecialchars($resultMessage, ENT_QUOTES, 'UTF-8');?>
			</p>
		<?php }?>
		<form method="post">
			<p>ユーザーID:<input type="text" name="user_id"></p>
			<p>パスワード:<input type="password" name="password"></p>
			<p>氏名:<input type="text" name="user_name"></p>
			<p>部署:<input type="text" name="department"></p>
			<div>
				<p>役職</p>
				<div>
					<input type="radio" name="position" value="4">部長
					<input type="radio" name="position" value="1">課長
					<input type="radio" name="position" value="2">次長
					<input type="radio" name="position" value="3">社員
				</div>
			</div>
			<div>
				<p><span class="svChar">上司</span><input type="text" name="supervisor"></p>
			</div>
			<div id="pattern">
				<p>勤務パターン</p>
				<div>
					<input class="staffOnly" type="radio" name="patternChar" value="通常">
					<span class="staffOnlyChar">通常</span>
					<input class="staffOnly" type="radio" name="patternChar" value="早出">
					<span class="staffOnlyChar">早出</span>
					<input class="staffOnly" type="radio" name="patternChar" value="遅出">
					<span class="staffOnlyChar">遅出</span>
				</div>
				<div>
					<input class="staffOnly" type="radio" name="patternAlb" value="A">
					<span class="staffOnlyChar">A</span>
					<input class="staffOnly" type="radio" name="patternAlb" value="B">
					<span class="staffOnlyChar">B</span>
					<input class="staffOnly" type="radio" name="patternAlb" value="C">
					<span class="staffOnlyChar">C</span>
					<input class="staffOnly fastAble" type="radio" name="patternAlb" value="D">
					<span class="staffOnlyChar fastAbleChar">D</span>
					<input class="staffOnly fastAble" type="radio" name="patternAlb" value="E">
					<span class="staffOnlyChar fastAbleChar">E</span>
					<input class="staffOnly fastAble" type="radio" name="patternAlb" value="F">
					<span class="staffOnlyChar fastAbleChar">F</span>
					<input class="staffOnly slowAble" type="radio" name="patternAlb" value="G">
					<span class="staffOnlyChar slowAbleChar">G</span>
				</div>
			</div>
			<input class="btn" type="submit" value= "登録"/>
		</form>
		<div class="link"><a href="index.php">ログインページへ</a></div>
	</div>
</body>