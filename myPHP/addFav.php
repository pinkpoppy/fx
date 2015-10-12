<?php
	include'./config/connectDatabase.php';


	$addFavSql = "INSERT INTO jobs_user_fav 
								(activity_id,activity_type,type,user_id,create_time)
					VALUES('{$_POST['activityID']}',
						   '{$_POST['activityType']}',
						   '{$_POST['userType']}',
						   '{$_POST['userID']}',
						   '{$_POST['createTime']}'
						   );";

	$conn->query($addFavSql);
	
	$getFavNumSql = "SELECT fav_num from jobs_activity WHERE id = '{$_POST['activityID']}';";
    $favNumResult = $conn->query($getFavNumSql);
   	$favNum = $favNumResult->fetch_row();

   	$favNumIncreaseSql = "UPDATE jobs_activity 
                            SET fav_num = $favNum[0] + 1 
                            WHERE id = '{$_POST['activityID']}';";
    $conn->query($favNumIncreaseSql);

    
	echo mysql_errno();	
	$conn->close();
?>