<?php
	require_once "config/connectDatabase.php";
?>
<?php

	define("DEFAULT_SHOW", 10);
	function loadMore($page,mysqli_result $result) {
		$pos = 0;
		$cnt = 0;
		$arry = array();
		while ($row = $result->fetch_assoc()) {
			if ($pos != $page * DEFAULT_SHOW) {
				$pos++;
				continue;
			} else if($cnt < DEFAULT_SHOW){
				array_push($arry, $row);
				$cnt++;
			} else {
				break;
				return; 
			}
		}
		return json_encode($arry,JSON_UNESCAPED_UNICODE);
	}
?>

<?php
	 $sql = "SELECT info_id,
	 				caption,
	 				origin,
	 				publish_date,
	 				fav,
	 				pic_link,
	 				origin_link,
	 				create_time,
	 				subhead
            FROM jobs_infomation 
            where subtype = '2' 
            AND flag='0'
            ORDER BY create_time DESC;"; 

	$result = $conn->query($sql);
	echo loadMore($_GET["currentPage"],$result);
?>

