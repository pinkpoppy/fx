<?php 
	include "../myPHP/config/setCookie.php";
	include "../myPHP/config/connectDatabase.php";
	
	if (isset($_COOKIE["activityID"])) {
		if (isset($_GET["activityID"])) {
			setCookie("activityID",$_GET["activityID"],time() + (86400 * 30),"/");
			$_COOKIE["activityID"] = $_GET["activityID"];
			$activityID = $_COOKIE["activityID"];
		}
		$activityID = $_COOKIE["activityID"];
	} else {
		$id = $_GET["activityID"];
		setCookie("activityID",$id,time() + (86400 * 30),"/");
		$_COOKIE["activityID"] = $id;
		$activityID = $_COOKIE["activityID"];
	}

	if (isset($_GET["title"])) {
		$title = $_GET["title"];
		setCookie("title",$title,time() + (86400 * 30),"/");
		$_COOKIE["title"] = $title;
	} else {
        $sql = 'SELECT title FROM jobs_activity WHERE id=\'' . $activityID . '\';';
        $result = $conn->query($sql);
        $titleRow = $result->fetch_assoc();
        $title = $titleRow["title"];
	}
	
	if(isset($_COOKIE["userId"])) {    		
		if (isset($_GET["userId"])) {
			setCookie("userId",$_GET["userId"],time() + (86400 * 30),"/");
			$_COOKIE["userId"] = $_GET["userId"];
			$userID = $_COOKIE["userId"];
		}
		$userID = $_COOKIE["userId"];
	} else {
		$u = $_GET["userId"];
		setCookie("userId",$u,time() + (86400 * 30),"/");
		$_COOKIE["userId"] = $u;
		$userID = $_COOKIE["userId"];
	}
	if(isset($_COOKIE["userType"])) {
		if (isset($_GET["userType"])) {
			setCookie("userType",$_GET["userType"],time() + (86400 * 30),"/");
			$_COOKIE["userType"] = $_GET["userType"];
			$userType = $_COOKIE["userType"];
		}
		$userType = $_COOKIE['userType'];
	} else {
		$userType = $_GET["userType"];
		setCookie("userType",$userType,time() + (86400 * 30),"/");
		$_COOKIE["userType"] = $userType;
		$userType = $_COOKIE["userType"];
	}

	session_start();
	$_SESSION["ID"] = $activityID;
?>
<?php
	define("DEFAULT_SHOW", 5); /*默认显示评论数不超过5条*/
?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="format-detection" content="telephone=phone">
	<meta name="keywords" content="上海-上海品酒活动,品酒会,红酒,酒庄活动,红酒爱好者" /> 
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">   
	<script type="text/javascript" src="../js/myjs/commonFunc.js"></script>
	<title><?php echo $title;?></title>

	<!-- 链入css 开始-->
	<link rel="stylesheet" href="../css/layout.css">
	<!-- 链入css 结束-->

	<!-- 链入脚本 开始-->
	<script src="../js/opensource/jquery-1.11.2.min.js"></script>
	<script src="../js/opensource/bootstrap.min.js"></script> 
	
	<script src="../js/myjs/global.js"></script>
	<script src="../js/opensource/spin.min.js"></script>
	<!-- 链入脚本 结束-->

<?php 
	$sql = 'SELECT * FROM jobs_activity WHERE id=\'' . $activityID . '\';';
	$result = $conn->query($sql);
	$activityDetail = $result->fetch_assoc();
	$favNum = $activityDetail["fav_num"];

	if ($userID != 'x') {
		$sqlUserInfo = "SELECT nickname,head_pic 
						FROM jobs_userinfo
						WHERE user_id = '".$userID.
						"' AND type = " . $userType.
						";";
		$userInfoResult = $conn->query($sqlUserInfo);
		$infoRow = $userInfoResult->fetch_assoc();
		$nickname = $infoRow["nickname"];
		$headPic = $infoRow["head_pic"];
	}

	function getShowDetailTime($beginDate,$endDate) {
		$res = "";
		$beginDateString = substr($beginDate,5,5);
		$endDateString = substr($endDate,5,5);

		$beginDateTime = substr($beginDate,11,5);
		$endDateTime = substr($endDate, 11,5);

		if ($beginDateString==$endDateString) {
			$res .= $beginDateString . " " . $beginDateTime . "至" . $endDateTime;
		} else {
			$res .= $beginDateString . "至" . $endDateString;
		}
		return $res;
	}
?>

<?php
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
			WHERE jobs_activity_comment.activity_id = '.$activityID.
            ' ORDER BY jobs_activity_comment.comment_id DESC;';

	$commentResults = $conn->query($sql);
	$commentResultsCopy = $conn->query($sql);
	$totalCount = $commentResults->num_rows;

	if($totalCount > 0) {
		$firstComment = $commentResultsCopy ->fetch_row();
		$firstCommentID = $firstComment[7];
		$_SESSION["firstCommentID"] = $firstCommentID;
	}
	
	$maxLoadNum = 0;
	if ($totalCount > DEFAULT_SHOW) {
		$maxLoadNum = ceil(($totalCount - DEFAULT_SHOW) / DEFAULT_SHOW);
	}
	define("MAX_LOAD_NUM",$maxLoadNum,false);
?>

<?php 
	function loadDefaultComments($totalCount,mysqli_result $res) {
		if ($totalCount == 0) {
			return;
		} else {
			$cnt = 0;
			while ($row = $res->fetch_assoc()) {
				if ($cnt < DEFAULT_SHOW) {
					echo "<div class=\"conts\">";	
						echo "<div class=\"user_bar\">";
							echo "<div class=\"user_info\">";
								echo "<img src=\"".$row["head_pic"]."\">";
								echo "<p class=\"uname\">";
								echo $row["nickname"];
								echo "</p>";
								echo "<p class=\"date_time\">";
								echo $row["create_time"];
								echo "</p>";
							echo "</div>";
						echo "</div>";
						echo "<div class=\"comment_info\">";
							echo "<p class=\"comment_cont\">";
							echo $row["comment_cont"];
							echo "</p>";
						echo "</div>";
					echo "</div>";
					echo "<hr/>";
					$cnt++;
				} else {
					break;
					return;
				}
			}
		}
	}
?>
</head>

<body>
	<div class="container">
		<!-- 轮播图片 开始 -->
		<section class="hdDetail">
			<script src="../js/myjs/unslide.js"></script>
			<script src="../js/myjs/move.js"></script>
			<script src="../js/myjs/swipe.js"></script>
			<script>
				$(function() {
					var wrap = $('#banner_detail'),
					slides = wrap.find('li'),
					active = slides.filter('.active'),
					i = slides.index(active),
					width = wrap.width();
					slides
					.on('swipeleft',function(e) {
						if (i === slides.length - 1) {
							return;
						}
						slides.eq(i + 1).trigger('activate');
					})
					.on('swiperight',function(e) {
						if (i === 0) {
							return;
						}
						slides.eq(i - 1).trigger('activate');
					})
					.on('activate',function(e) {
						slides.eq(i).removeClass('active');
						jQuery(e.target).addClass('active');
						i = slides.index(e.target);
					})
					.on('movestart',function(e) {
						if ((e.distX > e.distY && e.distX < -e.distY) ||
							(e.distX < e.distY && e.distX > -e.distY)) {
							e.preventDefault();
						}
						wrap.addClass('notransition');
					}) 
					.on('move',function(e) {
						var left = 100 * e.distX / width;
						if (e.distX < 0) {
							if (slides[i + 1]) {
								slides[i].style.left = left + '%';
								slides[i + 1].style.left = (left + 100) + '%';
							} else {
								slides[i].style.left = left / 4 + '%';
							}
						}
						if (e.distX > 0) {
							if (slides[i - 1]) {
								slides[i].style.left = left + '%';
								slides[i - 1].style.left = (left - 100) + '%';
							} else {
								slides[i].style.left = left / 5 + '%';
							}
						}
					})
					.on('moveend',function(e) {
						wrap.removeClass('notransition');
						slides[i].style.left = '';
						if (slides[i + 1]) {
							slides[i + 1].style.left = '';
						}
						if (slides[i - 1]) {
							slides[i - 1].style.left = '';
						}
					});
				});	

			function slider() {
				$('#banner_detail').unslider({
					fluid:true,
					dots:true
				});
			}

			window.onload = function() {
				slider();
				$('.slide_li').show();
			};			
			</script>

			<div class="banner" id="banner_detail">	
				<ul>
					<?php
						$sql = 'SELECT album_url FROM jobs_activity_album
										WHERE activity_id = \'' . $activityID . '\';';
						$albumResult = $conn->query($sql);
						if ($albumResult->num_rows > 0) {
							while ($album = $albumResult->fetch_assoc()) {
								echo "<li class=\"slide_li\">";
									echo "<img style=\"width:100%\" src=\"".$album['album_url'] ."\">";
								echo "</li>";
							}
						}
					?>
				</ul>
			</div>
		</section>
		<!-- 轮播图片 结束 -->

		<!-- 活动介绍 开始 -->
		<div class="intro">
			<div class="intro_cont">
				<div class="content_head">
					<div class="circle_img">
						<img src="<?php echo $activityDetail['brand_pic']?>" class="default_img">
					</div>				
					<h1><?php echo $activityDetail['company']; ?></h1>
				</div>	
				<div class="updown">
					<div class="cont line_clamp three_lines">
						<p class="paragraph">
							<?php echo $activityDetail['introduction']; ?>
						</p>
						<div class="wine_list">
							<ul>
								<?php
									$sql = 'SELECT wine_description FROM jobs_activity_winelist
													WHERE activity_id = \'' . $activityID . '\';';
									$wineListResult = $conn->query($sql);
									if ($wineListResult->num_rows > 0) {
										while ($wineList = $wineListResult->fetch_assoc()) {
											echo "<li class=\"wine_name\">";
											echo $wineList['wine_description'];
											echo "</li>";
										}
									}
								?>
							</ul>
						</div>
					</div>	
					<div class="cont_funcs">
						<button class="btn_func btn_unfold" type="button">
						</button>
					</div>
				</div>
			</div>	
		</div>
		<!-- 活动介绍 结束 -->

		<!-- 参会信息 开始 -->
		<div class="attend_info">
			<p class="tips">参会信息</p>
			<div class="info_detail">
				<table>
					<tbody>			
						<tr>
							<th class="width_th">地点</th>
							<td class="width_td with_map">
								<?php
									echo $activityDetail['address'];
								?>
							</td>
							<td class="width_map">
								<?php
									$href = "map.php?activityID=". $activityID;
								?>
								<a href="<?php echo $href;?>">
									<img src="../images/mapHeavy.png">
								</a>
							</td>
						</tr>
						<tr>
							<th class="width_th">时间</th>
							<td class="width_td" colspan="2">
								<span class="time">
									<?php 
									echo getShowDetailTime($activityDetail['begin_date'],$activityDetail['end_date']);
									?>
								</span>
							</td>
						</tr>
						<tr>
							<th class="width_th">费用</th>
							<td class="width_td" colspan="2">
								<?php
									echo $activityDetail['fee'];
								?>
							</td>
						</tr>
						<tr class="last_row">
							<th class="width_th">预约</td>
							<td class="width_td" colspan="2">
								<span >
									<?php
										echo $activityDetail['contact_tel'];
									?>
								</span>
								<span class="contact_person">
									<?php
										echo $activityDetail['contact_person'];
									?>
								</span>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<!-- 参会信息 结束 -->

		<!-- 评论区 开始 -->
		<div class="comments" id="comments">
			<p class="tips">
				评论
				<span class="comment_show_num">
					<?php
						echo '(' . $totalCount . ')';						
					?>
				</span>
			</p>
			<div class="reply">
				<dl>
					<dt class="default_user_img">
						<?php
							$src = "../images/hold.png";
							if ($userID != 'x') {
								$src = $headPic;
							} 
						?>
						<img src="<?php echo $src;?>">
					</dt>
					<dd>
						<form action="receiveComment.php">
							<?php
								if ($userID == 'x') {
									echo "<a href = \"http://10.0.0.5/~sszhu/activity/html/activityList.php#Authorization\" onclick=\"requestApp()\">";
										echo "<textarea class=\"reply_box\" id=\"reply_box\" readonly=\"readonly\" placeholder=\"有什么感想，你也来说说吧!(评论不少于5个字符)\" name=\"publish\" rows=\"3\">";
										echo "</textarea>";
									echo "</a>";
								} else {
									echo "<textarea class=\"reply_box\" id=\"reply_box\" placeholder=\"有什么感想，你也来说说吧!(评论不少于5个字符)\" name=\"publish\" rows=\"3\" required>";
									echo "</textarea>";
								}
							?>
						</form>
						
						<div class="reply_submit">
							<div class="fr">
								<input id="publish_comment" value="发表" type="submit" class="reply_btn" disabled>
							</div>
						</div>
					</dd>
				</dl>
			</div>
			<hr/>

			<div class="comment_wrap">
				<div class="lists">
					<?php loadDefaultComments($totalCount, $commentResults); ?>
				</div>
				<div id="loading"></div>
			</div>
		</div>
		<!-- 评论区 结束 -->

		<!-- 预约 开始 -->
		<div class="order">
			<div id="order_page">
				<div class="order_content">	
						<p id="order_tips">登记联系方式,主办方会联系您.</p>
						<div class="enter_info">
							<input type="text" id="form_name" value class="inp" placeholder="姓名">
							<input type="text" id="form_tel" class="inp" placeholder="手机">
							<input type="button" value="确定" class="btn_submit" id="btn_submit" onClick="submitPressed()">
						</div>
						
						<button id="close_order" onclick="onClose()"></button>
						<script type="text/javascript">
							var userInfo = loadCookie();
							console.log(userInfo);
							//判断表单内容是否为空或者 null
							function notNull(content) {
								if (content != "" && content != null) {
									return true;
								}
								return false;
							}
							//判断是否为11为手机号
							function isTel(content) {
								if ((/^1\d{10,10}$/.test(content))) {
									return true;
								} else {
									$("#order_tips").text("手机号有误!");
									return false;
								}
							}	
							//表单验证
							function formCheck() {
								var name = $("#form_name");
								var tel = $("#form_tel");

								if (!notNull( name.val() ) &&
									!notNull(tel.val() )) {
									$("#order_tips").text("没有填入内容噢~");
									return false;
								}

								if (!notNull( name.val() )) {
									$("#order_tips").text("姓名不可为空");
									return false;
								}
								if (!notNull( tel.val() )) {
									$("#order_tips").text("手机号不可为空");
									return false;
								}
								if(notNull( name.val() ) && 
								   notNull( tel.val() ) &&
								   isTel(tel.val()) )
								{
									name.focus();
									tel.focus();
									return true;
								} 

								return false;
							}
							//提交表单		
							function orderSubmit() {
								var create_time = getCreateTime();
								var name = $("#form_name").val();
								var tel = $("#form_tel").val();
								$.ajax({
									url: '../myPHP/receiveOrder.php',
									type: 'POST',
									data: {
										userID: userInfo["userId"],
										userType:userInfo["userType"],
										activityID:userInfo["activityID"],
										name:name,
										tel:tel,
										createTime:create_time
									}
								})
								.done(function() {
									console.log("success");
									$("#form_name").blur();
									$("#form_tel").blur();
									$("#order_tips").text("已提交!");
									$('#order_page').hide(500);
									$('#order_btn').text("已预约");
									$('#order_btn').css("background-color","#d9d9d9");
								})
								.fail(function() {
									console.log("error");
								})
								.always(function() {
									console.log("complete");
								});	
							}
							//提交按钮按下
							function submitPressed() {
								// $("#form_name").blur();
								// $("#form_tel").blur();
								var res = formCheck();

								
								if (res==true) {
									orderSubmit();
								}
							}
					</script>							
				</div>
			</div>

			<?php
				if ($userID == "x") {
					echo "<a href=\"http://10.0.0.5/~sszhu/activity/html/activityList.php#Authorization\" class=\"look\">";
					echo "立即预定";
					echo "</a>";
				} else {
					echo "<div class=\"look\" id=\"order_btn\" onClick=\"onPopUp()\">";
					echo "立即预定";
					echo "</div>";
				}
			?>	
		</div>
		<!-- 预约 结束 -->
</body>

<script type="text/javascript">
	var spinner = new Spinner(setSpin());
	
	//显示预定页面
	function onPopUp(){
		$('#order_page').show(500);
	}
	//关闭预定页面
	function onClose(){
		$('#order_page').hide(500);
		$("#order_tips").text("登记联系方式,主办方会联系您.");
	}

	//评论提交后，本地生成刚提交的评论
	function addComment(nickname,headPic,commentCont,time) {
		var divConts = document.createElement("div");
		divConts.setAttribute("class","conts");

		var divUserBar = document.createElement("div");
		divUserBar.setAttribute("class","user_bar");

		var divUserInfo = document.createElement("div");
		divUserInfo.setAttribute("class","user_info");

		var imgHeadPic = document.createElement("img");
		imgHeadPic.setAttribute("src",headPic);

		var pUname = document.createElement("p");
		pUname.innerHTML = nickname;
		pUname.setAttribute("class","uname");

		var pDateTime = document.createElement("p");
		pDateTime.innerHTML = time;
		pDateTime.setAttribute("class","date_time");

		var divCommentInfo = document.createElement("div");
		divCommentInfo.setAttribute("class","comment_info");

		var pCommentCont = document.createElement("p");
		pCommentCont.innerHTML = commentCont;
		pCommentCont.setAttribute("class","comment_cont");


		var hr = document.createElement("hr");

		divUserInfo.appendChild(imgHeadPic);
		divUserInfo.appendChild(pUname);
		divUserInfo.appendChild(pDateTime);
		divUserBar.appendChild(divUserInfo);

		divCommentInfo.appendChild(pCommentCont);
		divConts.appendChild(divUserBar);
		divConts.appendChild(divCommentInfo);


		$(".lists").prepend(hr);
		$(".lists").prepend(divConts);
	}

	//评论提交后,更新评论条数
	function updateCommentShownNum() {
		var pointer = $(".comment_show_num");
		var orginText = pointer.text();
		var start;
		var end;
		for (var i = 0; i < orginText.length; i++) {
			if (orginText[i] == '(') {
				start = i + 1;
			}
			if (orginText[i] == ')') {
				end = i;
			}
		}
		var cleanText = orginText.substring(start,end);
		var writeStr = "(";
		if(cleanText == "") {
			pointer.text(writeStr + "1" + ")");
		} else {
			var lastCommentNum = parseInt(cleanText);
			console.log("last comment nun is : " + lastCommentNum);
			pointer.text(writeStr + String(parseInt(cleanText) + 1) + ")");
		}
		
	}

	//获取下一页数据
	function fetchData(pageNum) {
		$.ajax({
			type:'GET',
			url: '../myPHP/loadMoreComments.php?currentPage='+pageNum,
			dataType:'json',
			contentType: "application/x-www-form-urlencoded",
			timeout:10000,
			beforeSend:function () {
				//spinner 出现
				var target = document.getElementById("loading");
				spinner.spin(target);
			},
			success:function(receiveArray) {
				for (var i = 0; i < receiveArray.length; i++) {
					var divConts = document.createElement("div");
					divConts.setAttribute("class","conts");

					var divUserBar = document.createElement("div");
					divUserBar.setAttribute("class","user_bar");

					var divUserInfo = document.createElement("div");
					divUserInfo.setAttribute("class","user_info");

					var imgHeadPic = document.createElement("img");
					imgHeadPic.setAttribute("src",receiveArray[i]["head_pic"]);
					var pUname = document.createElement("p");
					pUname.innerHTML = receiveArray[i]["nickname"];
					pUname.setAttribute("class","uname");

					var pDateTime = document.createElement("p");
					pDateTime.innerHTML = receiveArray[i]["create_time"];
					pDateTime.setAttribute("class","date_time");

					var divCommentInfo = document.createElement("div");
					divCommentInfo.setAttribute("class","comment_info");

					var pCommentCont = document.createElement("p");
					pCommentCont.innerHTML = receiveArray[i]["comment_cont"];
					pCommentCont.setAttribute("class","comment_cont");
					var hr = document.createElement("hr");

					divUserInfo.appendChild(imgHeadPic);
					divUserInfo.appendChild(pUname);
					divUserInfo.appendChild(pDateTime);
					divUserBar.appendChild(divUserInfo);

					divCommentInfo.appendChild(pCommentCont);
					divConts.appendChild(divUserBar);
					divConts.appendChild(divCommentInfo);

					$(".lists").append(divConts);
					$(".lists").append(hr);
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
		var user_id = '<?php echo $userID;?>'; 
		var user_type = '<?php echo $userType;?>';	
		var activity_id = '<?php echo $activityID;?>';
		var activity_type = '100';
		var nickname = '<?php echo $nickname;?>';
		var head_pic = '<?php echo $headPic;?>';	
		
		var comment;

		$('#reply_box').bind("input propertychange",function() {
			console.log($(this).val().length);
			if($(this).val().length > 5) {
				$('#publish_comment').css({"background-color":"#cc0000","color":"#fff"});
				$('#publish_comment').attr({"disabled":false});
			} else {
				$('#publish_comment').css({"background-color":"gray","color":"#000"});
				$('#publish_comment').attr({"disabled":true});
			}
		});

		$('#reply_box').focus(function() {
			$("#order_immediate").hide();
		});

		$('#reply_box').blur(function() {
			$("#order_immediate").show();
		});		
	
		//发布评论
		$('#publish_comment').click(function() {
			var publish_time = getCreateTime();
			var textarea = $('.reply_box').val();
			comment = textarea;
			if(textarea.length != 0) {
				//textarea文本非空,提交评论内容
				$.ajax({
					url: '../myPHP/receiveComment.php',
					type: 'POST',
					data:{comment:textarea,
						userID:user_id,
						userType:user_type,
						activityID:activity_id,
						publishTime:publish_time
					}
				})
				.done(function() {
					$('.reply_box').val('');
					addComment(nickname,head_pic,comment,publish_time);
					updateCommentShownNum();
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					$('.reply_box').val('');
				});			
			} else {
				$("#publish_comment").attr("disabled",true);
			}		

			$('#publish_comment').css({"background-color":"gray","color":"#000"});
			$('#publish_comment').attr({"disabled":true});
		});	


		//上拉加载
		var loadRange = 80;
		var maxPage = "<?php echo MAX_LOAD_NUM ?>";
		var totalHeight;
		var pageNum = 1;

		$(window).scroll(function() {
			totalHeight = $(window).height() + $(document).scrollTop();
			console.log("totalHeight is : " + totalHeight);
			console.log("文档高度为: " + $(document).height());

			if (pageNum > maxPage && maxPage != 0) { 
				$("#loading").text("没有评论啦...");

			} else {	
				if (totalHeight >= $(document).height() - loadRange) {
					fetchData(pageNum);
					pageNum++;
				}
			}
		});
	});
</script>

</html>
