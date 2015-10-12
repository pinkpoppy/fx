<?php
include "../myPHP/config/connectDatabase.php";

?>
<!DOCTYPE html>
<html>
<head>
    <title>资讯</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<link rel="stylesheet" type="text/css" href="../css/layout.css">
	<script type="text/javascript" src="../js/opensource/jquery-1.11.2.min.js"></script>
	<script src="../js/opensource/spin.min.js"></script>
	<script type="text/javascript" src="../js/myjs/commonFunc.js"></script>
	<script type="text/javascript" rel="global.js"></script>
</head>
<?php
    define("DEFAULT_SHOW", 10);
    $sql = "SELECT info_id,
    				caption,
    				origin,
    				publish_date,
    				fav,
    				pic_link,
    				origin_link,
    				subhead
            FROM jobs_infomation 
            where subtype = '1'
            AND flag='0'
            ORDER BY publish_date DESC,info_id desc;";

    $result = $conn->query($sql);
    $totalCount = $result->num_rows;
    define("MAX_LOAD_NUM", $totalCount <= 0 ? 0 :($totalCount > 0 && $totalCount <= 10 ? 0 : ceil(($totalCount - DEFAULT_SHOW) / DEFAULT_SHOW) ));
?>

<body>
	<div class="info_wrap">			
		<div class="info_list">
			<?php loadDefaultData($totalCount,$result);?>
		</div>
		<div id="loading"></div>
	</div>
<?php
	function loadDefaultData($totalCount,mysqli_result $result) {
		if ($totalCount == 0) {
			echo "暂无资讯";
			return;
		} else if ($totalCount > 0) {
			$cnt = 0;
			while ($row = $result->fetch_assoc()) {
				if ($cnt < DEFAULT_SHOW) {
					$list_id = $row["info_id"];
					echo "<div class=\"list\">";
						echo "<a href=\"" . $row["origin_link"]."\" class=\"nav_list\" data-id=$list_id onclick=\"count($list_id)\">";
							echo "<p class=\"caption\">";
								echo $row["caption"];
							echo "</p>";
							echo "<p class=\"subhead\">";
								echo $row["subhead"];
							echo "</p>";

							echo "<div class=\"footer\">";
								echo "<span class=\"text_des\">";
									echo $row["origin"];
								echo "</span>";
								echo "&nbsp";
								echo "&nbsp";
								echo "<span class=\"num_des\">";
									echo substr($row["publish_date"],5,5);
								echo "</span>";
								echo "&nbsp";
								echo "&nbsp";
								echo "<div class=\"like\">";
									echo "<img src=\"../images/grayheart.png\" class=\"icon\">";
									echo "<span class=\"info_like\">";
										echo showFavNum($row["fav"]);
									echo "</span>";
								echo "</div>";
							echo "</div>";					
						echo "<img src=\"".$row["pic_link"]."\" class=\"show_pic\">";
						echo "</a>";
					echo "</div>";
					echo "<hr/>";
					$cnt++;
				} else{
					break;
					return;
				}
			}
		}
	}
?>	
<script type="text/javascript">
	function count(id) {
		var clickTime = getCreateTime();
		$.ajax({
			url: '../myPHP/statistic/countClick.php',
			type: 'POST',
			dataType: 'text',
			data: {
				click_from: '1',
				content_type:'200',
				content_subtype:'1',
				content_id: id,
				click_time: clickTime
			},
		})
		.done(function() {
		})
		.fail(function() {
		})
		.always(function() {
		});
	}

</script>		
<script>	
	var spinner = new Spinner(setSpin());
	var pageNum = 1;
	function fetchData(pageNum) {
		$.ajax({
			type:'GET',
			url: '../myPHP/loadMoreInfomation.php?currentPage='+pageNum,
			dataType:'json',
			contentType: "application/x-www-form-urlencoded",
			timeout:20000,
			beforeSend:function() {
				//spinner 出现
				var target = document.getElementById("loading");
				spinner.spin(target);
			},
			success:function(receiveArray) {
				for (var i = 0; i < receiveArray.length; i++) {
					var list = document.createElement("div");
					list.setAttribute('class','list');

					var a = document.createElement("a");
					a.setAttribute('class','nav_list');
					a.setAttribute('href',receiveArray[i]["origin_link"]);
					a.setAttribute('data-id',receiveArray[i]["info_id"]);

					var caption = document.createElement("p");
					caption.setAttribute('class','caption');
					caption.innerHTML = receiveArray[i]["caption"];

					
					var subhead = document.createElement("p");
					subhead.setAttribute('class','subhead');
					subhead.innerHTML = receiveArray[i]['subhead'];

					var footer = document.createElement("div");
					footer.setAttribute('class','footer');

					var origin = document.createElement("span");
					origin.setAttribute('class','text_des');
					origin.innerHTML = receiveArray[i]["origin"];

					var date = document.createElement("span");
					date.setAttribute('class','num_des');
					date.innerHTML = getShortTime(receiveArray[i]["publish_date"]);

					var like = document.createElement("div");						
					like.setAttribute('class','like');
					

					var iconHeart = document.createElement("img");
					iconHeart.setAttribute('class','icon');
					iconHeart.setAttribute('src','../images/grayheart.png');

					var likeNum = document.createElement("span");
					likeNum.setAttribute('class','info_like');
					likeNum.innerHTML = showFavNum(receiveArray[i]["fav"]);

					var pic = document.createElement("img");
					pic.setAttribute('class','show_pic');
					pic.setAttribute('src',receiveArray[i]["pic_link"]);


					like.appendChild(iconHeart);
					like.appendChild(likeNum);

					footer.appendChild(origin);
					footer.appendChild(date);
					footer.appendChild(like);

					a.appendChild(caption);
					a.appendChild(subhead);

					a.appendChild(footer);
					a.appendChild(pic);

					$(a).click(function(){
						count($(a).data("id"));
					});
					list.appendChild(a);

					$(".info_list").append(list);
					$(".info_list").append("<hr/>");
				}
				spinner.spin();	
			}
		})
		.done(function() {
			spinner.spin();
		})
		.fail(function() {
			spinner.spin();
			$("#loading").text("加载失败,请重新加载...");
		})
		.always(function() {
		});
		
	}
		
	
	$(document).ready(function() {
		var range = 80;		
		var totalHeight;
		var maxPage = "<?php echo MAX_LOAD_NUM ?>";

		var lastPos = 0;
		$(window).scroll(function() {			
			totalHeight = parseFloat($(window).height()
						 + parseFloat($(document).scrollTop()));
			if (pageNum > maxPage) { 
				$("#loading").text("没有资讯啦...");
			} else {
				if (totalHeight >= $(document).height() - range) {
					fetchData(pageNum);
					pageNum++;
				}
			}
		});
	});


</script>
</body>
</html>
