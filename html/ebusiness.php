<?php
	include "../myPHP/config/connectDatabase.php";
        define("DEFAULT_SHOW", 10);
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

        $totalCount = $result->num_rows;
        define("MAX_LOAD_NUM", $totalCount <= 0 ? 0 :($totalCount > 0 && $totalCount <= 10 ? 0 : ceil(($totalCount - DEFAULT_SHOW) / DEFAULT_SHOW) ));
?>

<!DOCTYPE html>
<html>
<head>
    <title>电商精选</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<link rel="stylesheet" type="text/css" href="../css/layout.css">
	<script type="text/javascript" src="../js/opensource/jquery-1.11.2.min.js"></script>
	<script src="../js/opensource/spin.min.js"></script>
	<script type="text/javascript" src="../js/myjs/commonFunc.js"></script>
	<script type="text/javascript" rel="global.js"></script>
</head>

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
			return;
		} else if ($totalCount > 0) {
			$cnt = 0;
			while ($row = $result->fetch_assoc()) {
				if ($cnt < DEFAULT_SHOW) {
					$list_id = $row["info_id"];
					echo "<div class=\"list\">";
						echo "<a href=\"" . $row["origin_link"]."\" class=\"e_wine_list\" data-id=$list_id onclick=\"count($list_id)\">";
							echo "<p class=\"wine_caption\">";
								echo $row["caption"];
							echo "</p>";
							echo "<p class='wine_subhead'>";
								echo $row['subhead'];
							echo "</p>";
							echo "<div class=\"wine_footer\">";
								echo "<span class=\"wine_orgin\">";
									echo $row["origin"];
								echo "</span>";
								echo "&nbsp";
								echo "&nbsp";
								echo "<span class=\"wine_price\">";
									echo $row["publish_date"];
								echo "</span>";
								echo "&nbsp";
								echo "&nbsp";
								echo "<div class=\"wine_fav\">";
									echo "<img src=\"../images/grayheart.png\" class=\"icon\">";
									echo "<span class=\"wine_fav_num\">";
										echo showFavNum($row["fav"]);
									echo "</span>";
								echo "</div>";
							echo "</div>";					
						echo "<img src=\"".$row["pic_link"]."\" class=\"wine_show_pic\">";
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
			async:false, 
			timeout:100000,  
			data: {
				click_from: '1',
				content_type:'200',
				content_subtype:'2',
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
			url: '../myPHP/loadMoreWine.php?currentPage='+pageNum,
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
					a.setAttribute('class','e_wine_list');
					a.setAttribute('href',receiveArray[i]["origin_link"]);
					a.setAttribute('data-id',receiveArray[i]["info_id"]);

					var caption = document.createElement("p");
					caption.setAttribute('class','wine_caption');
					caption.innerHTML = receiveArray[i]["caption"];

					var subhead = document.createElement("p");
					subhead.setAttribute('class','wine_subhead');
					subhead.innerHTML = receiveArray[i]['subhead'];

					var footer = document.createElement("div");
					footer.setAttribute('class','wine_footer');

					var origin = document.createElement("span");
					origin.setAttribute('class','wine_orgin');
					origin.innerHTML = receiveArray[i]["origin"];

					var date = document.createElement("span");
					date.setAttribute('class','wine_price');
					date.innerHTML = receiveArray[i]["publish_date"];

					var like = document.createElement("div");						
					like.setAttribute('class','wine_fav');
					


					var iconHeart = document.createElement("img");
					iconHeart.setAttribute('class','icon');
					iconHeart.setAttribute('src','../images/grayheart.png');

					var likeNum = document.createElement("span");
					likeNum.setAttribute('class','wine_fav_num');
					likeNum.innerHTML = showFavNum(receiveArray[i]["fav"]);
					//likeNum.innerHTML = receiveArray[i]["fav"];

					var pic = document.createElement("img");
					pic.setAttribute('class','wine_show_pic');
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
	
	var lastPos = 0;
	var interval = null;//定时器	

	$(document).ready(function() {
		var range = 80;		
		var totalHeight;
		var maxPage = "<?php echo MAX_LOAD_NUM ?>";
		$(window).scroll(function() {
			totalHeight = parseFloat($(window).height()
						 + parseFloat($(document).scrollTop()));
			if (pageNum > maxPage) { 
				$("#loading").text("没有酒款了...");
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
