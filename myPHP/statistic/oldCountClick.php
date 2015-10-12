<?php
	include "../config/connectDatabase.php";
	$from = $_POST['click_from'];
	$type = $_POST['content_type'];
	$id = $_POST['content_id'];
	$subtype = $_POST['content_subtype'];
	$time = $_POST['click_time'];

	$isExistedSql = "SELECT * FROM jobs_count_click WHERE content_type=$type 
					AND content_id=$id
					AND subtype=$subtype;";

	$checkResult = $conn->query($isExistedSql);

	if ($checkResult->num_rows > 0) { //被点击的内容条目已存在,进行++操作
		$row = $checkResult->fetch_assoc();

		$webClick = $row["web_click_cnt"];
		$appClick = $row["app_click_cnt"];
		$totalClick = $row["total_click_cnt"];

		if ($from == '1') { //来自app 内网页的点击
			$webClick = $webClick + 1;
			$totalClick = $totalClick + 1; 
			$updateSql = "UPDATE jobs_count_click SET web_click_cnt=$webClick,
							total_click_cnt=$totalClick,
							until_today='$time'
							WHERE content_type=$type
							AND content_id=$id;";
			$conn->query($updateSql);
		} else if ($from == '2') { //来自app 中收藏的点击
			$appClick += 1;
			$totalClick += 1;
			$updateSql = "UPDATE jobs_count_click SET app_click_cnt=$appClick,
							total_click_cnt=$totalClick,
							until_today='$time'
							WHERE content_type=$type 
							AND content_id=$id;";

			$conn->query($updateSql);
		} else { //出错,返回
			return;
		}
	} else { //该条目之前没被点击过,执行插入操作
		$web = 0;$app = 0;
		if ($from=='1') {
			$web = 1;
		} else {
			$app = 1;
		}
		$insertSql = "INSERT INTO jobs_count_click (
									content_type,
									content_id,
									web_click_cnt,
									app_click_cnt,
									total_click_cnt,
									until_today,
									subtype)
									VALUES(
									$type,
									$id,
									$web,
									$app,
									1,
									'$time',
									$subtype);";
		$conn->query($insertSql);
	}


?>