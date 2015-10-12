<?php
	include "../myPHP/config/setCookie.php";
	include "../myPHP/config/connectDatabase.php";
	include "../myPHP/config/const.php";
	include "../myPHP/date.php";
	include "../myPHP/produceDefaultActivities.php";
	setCustomerCookie();	
?>

<!DOCTYPE html>
<html>
<head>
	<title>活动</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../css/layout.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
</head>

<body>
	<div class="home">
		<div class="inner">
			<div class="inner_lists">
				<?php
					$uCity = getUserCity();
					showActivities($startPostion,$uCity,$conn);
					global $preCity,$maxActivityID,$resultsNum,$startPostion;
				?>
			</div>	
		</div>		
	</div>
	
	<script src="../js/opensource/jquery-1.11.2.min.js"></script>	
	<script src="../js/opensource/jquery.lazyload.min.js"></script>

	<script src="../js/opensource/dropload.js"></script>
	<script src="../js/myjs/global.js"></script>
	<script src="../js/myjs/commonFunc.js"></script>	
	<script src="../js/myjs/activityList.js"></script>	
	
	<script>
		 var prevCity = '<?php echo $preCity; ?>';
   		 var maxActivityID = parseInt('<?php echo $maxActivityID;?>');
   		 var startPostion = 10;
   		 var totalActivitiesCount = parseInt('<?php echo $resultsNum; ?>');
	</script>

</body>
</html>