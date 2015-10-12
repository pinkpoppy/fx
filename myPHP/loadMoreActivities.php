<?php

	include "./config/connectDatabase.php";
	include "./produceDefaultActivities.php";
	include "./config/const.php";
	include "./date.php";
	
	$maxActivityID = $_POST['max_activity_id'];
	$startPostion = $_POST['start_position'];


	$uCity = getUserCity();
	requestCurrentPageActivities($startPostion,$uCity,$conn);

	function requestCurrentPageActivities($startPostion,$uCity,$conn) {	
		global $maxActivityID; 
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
		global $startPostion;

		getActivities($startPostion,$startPostion + DEFAULT_SHOW,$resultsArr,$conn);
	}

	function getActivities($begin,$end,$results,$conn) {
		$cnt = 0;
		$resArr = array();
		foreach ($results as $key => $value) {
			if ($cnt < $begin) {
				$cnt++;
				continue;
			}
			if ($cnt==$end) 
				break;
			$timeTips = activityBeginTime($value['begin_date'],$value['end_date']);
			$value['timeTips'] = $timeTips;
			array_push($resArr, $value);
			$cnt++;
		}
		$row_json = json_encode($resArr,JSON_UNESCAPED_UNICODE);	
		echo $row_json;
	}
?>