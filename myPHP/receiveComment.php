<?php
	include "./config/connectDatabase.php";
	$sql = "INSERT INTO jobs_activity_comment (
						activity_id,
						user_id,
						user_type,
						comment_cont,
						create_time
						) VALUES (
						'{$_POST['activityID']}',
						'{$_POST['userID']}',
						'{$_POST['userType']}',
						'{$_POST['comment']}',
						'{$_POST['publishTime']}'
						);";
	$conn->query($sql);
	echo mysql_errno();	
	$conn->close();
?>