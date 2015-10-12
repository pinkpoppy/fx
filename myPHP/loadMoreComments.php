<?php
	include_once "./config/connectDatabase.php";
?>
<?php
	define("DEFAULT_SHOW", 5);
	function loadMore($page,mysqli_result $result,$firstCommentID) {
		$pos = 0;
		$cnt = 0;
		$arry = array();
		while ($row = $result->fetch_assoc()) {
			if ($row["comment_id"] <= $firstCommentID) {
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
		}
		return json_encode($arry,JSON_UNESCAPED_UNICODE);
	}
?>

<?php
	session_start();
	$id = $_SESSION["ID"];
	$firstCommentID = $_SESSION["firstCommentID"];
	
  	$sql = 'SELECT jobs_activity_comment.comment_cont,
				jobs_activity_comment.create_time,
				jobs_userinfo.head_pic,
				jobs_userinfo.nickname,
				jobs_activity_comment.user_id,
				jobs_activity_comment.user_type,
				jobs_activity_comment.activity_id,
				jobs_activity_comment.comment_id
				FROM jobs_activity_comment
				INNER JOIN  jobs_userinfo 
				ON jobs_activity_comment.user_id = jobs_userinfo.user_id
				AND jobs_activity_comment.user_type = jobs_userinfo.type
				WHERE jobs_activity_comment.activity_id = '.$id.
                ' ORDER BY jobs_activity_comment.comment_id DESC;';
	$result = $conn->query($sql);
	echo loadMore($_GET["currentPage"],$result,$firstCommentID);
?>

