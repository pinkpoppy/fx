<?php
	include"./config/connectDatabase.php";


	$cancleFavSql = "DELETE FROM jobs_user_fav 
					WHERE activity_id = '{$_POST['activityID']}' 
					AND activity_type = '{$_POST['activityType']}'
					AND user_id = '{$_POST['userID']}'
					AND type = '{$_POST['userType']}';";				
	$conn->query($cancleFavSql);

	$getFavNumSql = "SELECT fav_num 
					from jobs_activity 
					WHERE id = '{$_POST['activityID']}';";
    $favNumResult = $conn->query($getFavNumSql);
   	$favNum = $favNumResult->fetch_row();

   	$newFavNum = MAX($favNum[0] - 1,0);
   	$favNumDecreaseSql = "UPDATE jobs_activity 
                            SET fav_num = ".$newFavNum.
                           " WHERE id = '{$_POST['activityID']}';";
    $conn->query($favNumDecreaseSql);

    
	mysql_errno();	
	$conn->close();
?>