<?php
	include "connectDatabase.php";
	$userID = $_POST["userID"];
	$userType = $_POST["userType"];
	$activityID = $_POST["activityID"];
	$infoType = $_POST["infoType"];

	
	$sql_update_favNum = "UPDATE jobs_activity SET fav_num = '' WHERE ";
	$sql = "INSERT INTO jobs_activity_comment (
						activity_id,
						user_id,
						user_type,
						comment_cont,
						create_time
						) VALUES (
						'10',
						'2311474561',
						'3',
						'{$_POST['comment']}',
						'2015-01-20'
						);";
	$conn->query($sql);
	echo mysql_errno();	
	$conn->close();

?>