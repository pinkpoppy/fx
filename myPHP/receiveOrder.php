<?php
	include "./config/connectDatabase.php";
	$sql = "INSERT INTO 
			jobs_activity_user_register
			(name,tel,activity_id,user_id,user_type,create_time)
			VALUES
			('{$_POST['name']}',
			 '{$_POST['tel']}',
			 '{$_POST['activityID']}',
			 '{$_POST['userID']}',
		     '{$_POST['userType']}',
			 '{$_POST['createTime']}'
			);";
	$conn->query($sql);
	mysql_errno();	
	$conn->close();
?>
