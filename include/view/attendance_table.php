<table>
	<tr>
		<th class="department">部署</th>
		<th class="name">氏名</th>
		<th class="pattern">勤務パターン</th>
		<th class="time">残業予定時間</th>
		<th class="reason">残業理由</th>
		<th class="vote">採決</th>
	</tr>
	<?php foreach ($appList as $app){?>
		<tr>
			<td class="department"><?php echo $app['department']; ?></td>
			<td class="name"><?php echo $app['name']; ?></td>
			<td class="pattern"><?php echo $app['pattern']; ?></td>
			<td class="time">
				<?php 
					$startEnd  = explode("~" ,$app['time']);
					$start     = explode("T" ,$startEnd[0]);
					$end       = explode("T" ,$startEnd[1]);
					$startDate = explode("-" ,$start[0]);
					$endDate   = explode("-" ,$end[0]);
					echo $startDate[0]."年".$startDate[1]."月".$startDate[2]."日 ".$start[1]."<br>"."~"."<br>"
						.$endDate[0]."年".$endDate[1]."月".$endDate[2]."日 ".$end[1];
				?>
			</td>
			<td class="reasonTd"><?php echo $app['reason']; ?></td>
			<td class="vote">
				<form method ="post">
					<input type="hidden" name="appId" value="<?php echo ($app['id'])?>">
					<button class="settleBtn" name="settle">決済</button>
					<button class="cancelBtn" name="cancel">差し戻し</button>
				</form>
			</td>
		</tr>
	<?php }?>
</table>