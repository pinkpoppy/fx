<?php

	$maxActivityID = 0;
	$startPostion = 0; 
	$resultsNum = 0;
	$preCity = -1;
	

	function getUserCity() {
		if (!isset($_COOKIE['city'])) {
			return null;
		}
		$userCity = $_COOKIE['city'];
		if ($userCity == 'x') { //x是和客户端约定好的值，用户禁用了位置功能后传输的值
			return null;
		}
		return $userCity;
	}

	function timeLabelColor($timeTip) {
		if (strstr($timeTip, "开始")) {
			return 'time_valid_label_nearly_opening';
		} else if (strstr($timeTip, "结束")) {
			return 'time_valid_label_ended';
		} else if (strstr($timeTip, "进行")) {
			return 'time_valid_label_opening';
		}
	}

	function printActivityContent($row) {
		$windowWidth = $_COOKIE["width"];
		$realImageWidth = PIC_WIDTH;
		$realImageHeight = PIC_HEIGHT;
		$imagesWidth = $windowWidth;
		$imagesHeight = $realImageHeight * ($imagesWidth * 1.0 / $realImageWidth);


		// 调用活动开始时间
		$beginTimeTips = activityBeginTime($row['begin_date'],$row['end_date']);

		$ex = $row['direct_link'];
		$list_id = $row["id"];

		echo "<div class=\"items\">";
			echo "<div class=\"items_pic\">";
				if ($ex != null) {
					echo "<a href=\"$ex\" class=\"activityLink\" data-id=$list_id onclick=\"count($list_id)\">";
				} else {
					echo "<a href=\"activityDetail.php?activityID=".$row["id"]."&title=".urlencode($row["title"])."\" class=\"activityLink\" data-id=$list_id onclick=\"count($list_id)\">";
				}
				
				echo "<img class=\"lazy\" src=\"../images/grey.gif\" data-original=\"" . $row["activity_show_pic"] . "\" style=\"width:100%; height:".$imagesHeight."px;\">";

				$colorClass = timeLabelColor($beginTimeTips);
				echo "<div class=\"time_valid_label $colorClass\">";
					echo "<div class=\"cont\">";
						echo "<img class='time_icon' src='../images/countTime.png'>";
						echo "<p class=\"main_cont\">";
							 echo $beginTimeTips;
						echo "</p>";
					echo "</div>";
				echo "</div>";

				echo "<div class=\"items_shadow\">";
					echo "<div class=\"shadow_cont\">";
						echo "<p class=\"shadow_cont_title\">";
							echo $row['title'];
						echo "</p>";

						echo "<p class=\"activity_subhead\">";
							echo $row['subhead'];
						echo "</p>";

						echo "<div class=\"shadow_cont_footer\">";


							echo "<ul>";
								echo "<li class=\"li_date li_width_22\">";
									echo "<img class=\"li_icon\" src=\"../images/date.png\">";
									echo "<span class='li_text'>";
										echo substr($row['begin_date'], 5,5);
									echo "</span>";				
								echo "</li>";

								echo "<li class=\"li_collect li_width_16\">";
									echo "<img class=\"li_icon\" src=\"../images/fav.png\">";
									echo "<span class='li_text'>";
										echo showFavNum($row['fav_num']);
									echo "</span>";							
								echo "</li>";

								echo "<li class=\"li_price li_width_28\">";
									echo "<img class=\"li_icon\" src=\"../images/fee.png\">";
									echo "<span class='li_text'>";
										echo $row['fee']; 
									echo "</span>";
								echo "</li>";

								echo "<li class=\"li_hold li_width_34\">";
									echo "<img class=\"li_icon\" src=\"../images/hold.png\">";
									echo "<span class='li_text'>";
										echo $row['company'];
									echo "</span>";
								echo "</li>";
							echo "</ul>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
				echo "</a>";
			echo "</div>";
		echo "</div>";
	}
	
	function showCityBar($cityName) {
		echo "<div class=\"city_bar\">";
				echo $cityName;
		echo "</div>";
	}

	function getMaxActivityID($conn) {
		$sql = "SELECT id 
				FROM jobs_activity
				WHERE flag=0";

		$results = $conn->query($sql);

		$idArray = array();

		if ($results->num_rows > 0) {

			while ($row = $results->fetch_assoc()) {
				array_push($idArray, $row['id']);
			}

			rsort($idArray);

			return $idArray[0];
		}
		return -1; //出错
	}


	function showActivitiesByPostion($begin,$end,$results,$conn) {
		$cnt = 0;
		foreach ($results as $key => $value) {
			if ($cnt < $begin) {
				echo "test";
				$cnt ++;
				continue;
			}
			if ($cnt==$end)
				break;
			global $preCity;
			if ($value['city']!=$preCity) {
				showCityBar($value['city']);
				$preCity = $value['city'];
			}

			printActivityContent($value);

			$cnt++;
		}
	}

	function showUserActivities($uCity) {
		if ($uCity != null && $uCity != 'x') {
			return true;
		}
		return false;
	}

	function saveTotalActivitiesCount($startPos,$activitiesArr) {
		if ($startPos == 0) {
			global $resultsNum;
			$resultsNum = count($activitiesArr);
		}
	}

	function showActivities($startPos,$uCity,$conn) {

		global $maxActivityID; //这里要加上这句.不然 loadMoreActivities.php 中会报错
		//说 maxActivityID 未定义
		$maxActivityID = getMaxActivityID($conn);
		$resultsArr = array();
		if (showUserActivities($uCity)) {
			$userCitySql = "SELECT * FROM 
							jobs_activity
							WHERE id<=$maxActivityID
							AND city='$uCity'
							AND flag=0
							ORDER BY begin_date ASC;";
			$userCityActivitiesResults = $conn->query($userCitySql);
			if ($userCityActivitiesResults->num_rows > 0) {
				while ($row = $userCityActivitiesResults->fetch_assoc()) {
					array_push($resultsArr, $row);
				}
			}	
		}

		$sql = "SELECT * FROM 
				jobs_activity
				WHERE id<=$maxActivityID
				AND city!='$uCity'
				AND flag=0
				ORDER BY city_flag ASC,
				begin_date ASC;";
		$results = $conn->query($sql);
				
		if ($results->num_rows > 0) {
			while ($row=$results->fetch_assoc()) {
				array_push($resultsArr, $row);
			}
		}
		global $startPos;
		saveTotalActivitiesCount($startPos,$resultsArr);
		showActivitiesByPostion($startPos,$startPos + DEFAULT_SHOW,$resultsArr,$conn);
	}

?>