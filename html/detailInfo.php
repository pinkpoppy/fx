<?php
	require_once "../myPHP/config/connectDatabase.php";

	$id = "";
	$filePath = "../../bs/";
	if (isset($_GET['id'])) {
		$id = $_GET['id'];
		$filePath .= $id . ".html";
	}

	$isShare = false;
	if (isset($_GET['type'])=="share") {
		//网页是分享出去的网页
		$isShare = true;
	}

	$sql = "SELECT *
            FROM jobs_infomation 
            where info_id='$id';";

    $results = $conn->query($sql);
    $info = "";
    if($results->num_rows > 0) {
    	while ($row = $results->fetch_assoc()) {
    		$info = $row;
    	}
    } else {
    	echo "不存在此条资讯..";
    }
   
    $writer = $info['origin'];
	
	$sql = "SELECT total_click_cnt
			FROM jobs_count_click
			where content_id='$id'
			AND content_type='200'
			AND subtype='1';";
	$res = $conn->query($sql);
	$row = "";
	if ($res->num_rows > 0) {
		while ($r = $res->fetch_assoc()) {
			$row = $r;
		}
	} else {

	}
	$read_num = $row['total_click_cnt'];
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo "$info[caption]";?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<link rel="stylesheet" type="text/css" href="../css/layout.css">
	<script type="text/javascript" src="../js/opensource/jquery-1.11.2.min.js"></script>
	<script src="../js/opensource/spin.min.js"></script>
	<script type="text/javascript" src="../js/myjs/commonFunc.js"></script>
	<script type="text/javascript" rel="global.js"></script>
</head>

<body id="parent_body">
	<div class="cont_wrap">
		<header class="detail_header">
			<div class="article_caption">
				<?php echo "$info[caption]"; ?>
			</div>
			<div class="date">
				<?php echo substr($info['publish_date'],0,10) . "      " . $info['origin']; ?>
			</div>
			<div class="article_subhead">
				<span class="subhead_flag"></span>
				<div class="subhead_cont">
					<?php echo "$info[subhead]"; ?>
				</div>
			</div>
		</header>

		<article class="detail_article" id="detail_article">
			<iframe src='<?php echo $filePath;?>' id="info_iframe" frameborder="1" scrolling="no">
				
			</iframe>
		</article>
	</div>

	<script>
		$('iframe').load(function() {
			this.style.height =  this.contentWindow.document.body.scrollHeight + 'px';	
		});
		function count(id) {
			var clickTime = getCreateTime();
			$.ajax({
				url: '../myPHP/statistic/countClick.php',
				type: 'POST',
				dataType: 'text',
				data: {
					click_from: '3',
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

		$(document).ready(function() {
			var isShare = '<?php echo $isShare;?>';

			if (isShare) {
				var read_num = "<?php echo $read_num; ?>";
				var div = $("<div class='read_num'>"+ "阅读 " + read_num +"</div>");
				$("#detail_article").append(div);
				
				var writer_name = '<?php echo $writer;?>';

				var bottom = $("<div class='bottom'></bottom>");
				var img = $("<img src='../images/writer.png'>");
				var left_cont = $("<div class='left_cont'></div>");
				var p1 = $("<p class='writer_name'>"+ writer_name +"</p>");
				var p2 = $("<p class='second'>喜欢的话，来拍酒和我交流吧</p>");
				var a = $("<a href='http://um0.cn/42SI1F'>立即下载</a>");

				
				$(left_cont).append(p1);
				$(left_cont).append(p2);
				$(left_cont).append(a);
				
				bottom.append(img);
				bottom.append(left_cont);

				$("#detail_article").append(bottom);
				count('<?php echo $id;?>');

			} else {
				var read_num = "<?php echo $read_num; ?>";
				var div = $("<div class='read_num'>"+ "阅读 " + read_num +"</div>");
				$("#detail_article").append(div);
			}
		});
	</script>
</body>