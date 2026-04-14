<?php 
$title = '残業申請管理_社員';
$is_home = true; //トップページの判定用の変数
$sclipt = "<script src='./holiday_jp.min.js'></script><script src='staff.js'></script>
<script src='./patternEnable.js'></script>";
include "attendance_header.php"
?>
	<div class="center" id="nonPrintArea">
		<?php if($canceledApp){?>
			<p class="error">
				<?php print '残業申請が却下されました。再度申請してください';?>
			</p>
		<?php }?>
		<?php if($resultMessage!=''){?>
			<p id="message" class="error">
				<?php print $resultMessage;?>
			</p>
		<?php }?>
		<div class="staffDiv">
			<h1>残業申請</h1>
			<div>
				部署名
				<input type="text" name="department" value="<?php echo $staffData['department'];?>">
				<span class="must">※必須</span>
			</div>
			<div>
				名前　
				<input type="text" name="person" value="<?php echo $staffData['user_name'];?>">
				<span class="must">※必須</span>
			</div>
			<div>
				<p>勤務パターン<span class="must">※必須</span></p>
				<div>
					<input type="radio" name="patternChar" value="通常" 
						<?php if($staffData['pattern_char'] =="通常"){
							echo "checked";
						} ?>
					>通常
					<input type="radio" name="patternChar" value="早出"
						<?php if($staffData['pattern_char'] =="早出"){
							echo "checked";
						} ?>
					>早出
					<input type="radio" name="patternChar" value="遅出"
						<?php if($staffData['pattern_char'] =="遅出"){
							echo "checked";
						} ?>
					>遅出
				</div>
				<div>
					<input type="radio" name="patternAlb" value="A"
						<?php if($staffData['pattern_alphabet'] =="A"){
							echo "checked";
						} ?>
					>A
					<input type="radio" name="patternAlb" value="B"
						<?php if($staffData['pattern_alphabet'] =="B"){
							echo "checked";
						} ?>
					>B
					<input type="radio" name="patternAlb" value="C"
						<?php if($staffData['pattern_alphabet'] =="C"){
							echo "checked";
						} ?>
					>C
					<input class="fastAble" type="radio" name="patternAlb" value="D"
						<?php if($staffData['pattern_alphabet'] =="D"){
							echo "checked ";
						} 
						if($staffData['pattern_char'] =="通常"){
							echo "disabled";
						} ?>
					><span class="fastAbleChar"
					<?php 
						if($staffData['pattern_char'] =="通常"){
							echo "style='color:gray'";
						} ?>>D</span>
					<input class="fastAble" type="radio" name="patternAlb" value="E"
					<?php if($staffData['pattern_alphabet'] =="E"){
							echo "checked ";
						} 
						if($staffData['pattern_char'] =="通常"){
							echo "disabled";
						} ?>
					><span class="fastAbleChar"
					<?php 
						if($staffData['pattern_char'] =="通常"){
							echo "style='color:gray'";
						} ?>
					>E</span>
					<input class="fastAble" type="radio" name="patternAlb" value="F"
					<?php if($staffData['pattern_alphabet'] =="F"){
							echo "checked ";
						} 
						if($staffData['pattern_char'] =="通常"){
							echo "disabled";
						} ?>
					><span class="fastAbleChar"
					<?php 
						if($staffData['pattern_char'] =="通常"){
							echo "style='color:gray'";
						} ?>
					>F</span>
					<input class="slowAble" type="radio" name="patternAlb" value="G"
					<?php if($staffData['pattern_alphabet'] =="G"){
							echo "checked ";
						} 
						if($staffData['pattern_char'] =="通常" || $staffData['pattern_char'] =="早出"){
							echo "disabled";
						} ?>
					><span class="slowAbleChar"
					<?php 
						if($staffData['pattern_char'] =="通常" || $staffData['pattern_char'] =="早出"){
							echo "style='color:gray'";
						} ?>
					>G</span>
				</div>
			</div>
			
			<div>
				<p>残業予定時間<span class="must">※必須</span></p>
				開始<input type="datetime-local" name="start">
				終了<input type="datetime-local" name="end">
			</div>
			<div>
				<p>残業理由<span class="must">※必須</span></p>
				<textarea  name="reason" cols="40" rows="5"></textarea>
			</div>
			<input type="submit" value="表示" id="visible">
			
			<div id="back-layer">
				<div id="modal">
					<form method="post">
						<p>部署名　　　　<input class="modalInput" type="text" id="modal-dep" name="modal-dep" readonly></p>
						<p>名前　　　　　<input class="modalInput" type="text" id="modal-per" name="modal-per" readonly></p>
						<p>勤務パターン　<input class="modalInput" type="text" id="modal-ptn" name="modal-ptn" readonly ></p>
						<p>残業予定時間　</p>
						<input class="modalInput" type="text" id = "modal-time" name="modal-time" readonly>
						<p>残業理由　　　<input class="modalInput" id="modal-rsn" type="text" name="modal-rsn" readonly></p>
						<p>以上の内容で印刷します。よろしいですか。</p>
						<div class="btns">
							<input id="print" type="button" name="print" value= "印刷"/>
							<input id="post" type="submit" name="post" value= "送信" disabled/>
							<button id="close">閉じる</button>
						</div>
					</form>
				</div>
			</div>
			
		</div>
		<div class="staffDiv">
			<form method="post" enctype="multipart/form-data">
				<h1>残業報告</h1>
				<input type="file" name="rep">
				<input id="repBtn" type="submit">
			</form>
		</div>
		<?php
			if(intval($_COOKIE['position']) ==2){?>
				<div class="link"><a href="deputy.php">とりまとめページへ</a></div>
		<?php
		}if (intval($_COOKIE['position'])==1){?>
			<div class="link"><a href="manager.php">決済ページへ</a></div>
		<?php
		} ?>
		<div class="link"><a href="index.php">ログインページへ</a></div>
	</div>
	
	<div id="printArea">
		<h1>残業申請書</h1>
		<table class="printApp">
			<tr>
				<th>部署名</th>
				<td class="value" id ="printDep"></td>
			</tr>
			<tr>
				<th>氏名</th>
				<td class="value" id ="printPer"></td>
			</tr>
			<tr>
				<th>勤務パターン</th>
				<td class="value" id ="printPtn"></td>
			</tr>
			<tr>
				<th>残業予定時間</th>
				<td class="value" id ="printTime"></td>
			</tr>
			<tr class="highTr">
				<th>残業理由</th>
				<td class="value" id ="printReason"></td>
			</tr>
			<tr>
				<th rowspan="2" class="highTr">上記の残業を命令します</th>
				<td class="value signature">年　　月　　日</td>
			</tr>
			<tr>
				<td class="value signature">
					<span class="underline">承認者　　　　　　　　　印</span>
				</td>
			</tr>
			<tr>
				<th>実残業時間</th>
				<td class="value"></td>
			</tr>
			<tr>
				<th>うち休憩時間</th>
				<td class="value"></td>
			</tr>
			<tr class="highTr">
				<th>残業報告</th>
				<td class="value"></td>
			</tr>
			<tr>
				<th rowspan="2" class="highTr">上記の残業を報告します</th>
				<td class="value signature">年　　月　　日</td>
			</tr>
			<tr>
				<td class="value signature">
					<span class="underline">申請者　　　　　　　　　印</span>
				</td>
			</tr>
		</table>
	</div>	
</body>
</html>
