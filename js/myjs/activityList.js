//全局变量，触摸开始位置
var startX = 0, startY = 0;

$(document).ready(function() {
 	var cookieInfo = loadCookie();
	var user_id = '<?php echo $userID;?>';
	var user_type =  '<?php echo $userType;?>';
	var activity_type = 100;
    
    var dropload = $('.inner').dropload({
        //上拉加载
        domDown : { 
            domClass   : 'dropload-down',
            domRefresh : '<div class="dropload-refresh">↑上拉加载更多</div>',
            domUpdate  : '<div class="dropload-update">↓释放加载</div>',
            domLoad    : '<div class="dropload-load"><span class="loading"></span>加载中...</div>'
        },
        domOver : { 
            domClass   : 'dropload-down',
            domRefresh : '<div class="dropload-refresh">没有更多数据了</div>',
            domUpdate  : '<div class="dropload-update">没有更多数据了</div>',
            domLoad    : '<div class="dropload-load"><span class="loading"></span>没有更多数据了</div>'
        },
        loadDownFn : function(me){
            if (startPostion >= totalActivitiesCount) {
                me.resetload();
                return;
            } 
            $.ajax({
                type: 'POST',
                url: '../myPHP/loadMoreActivities.php',
                timeout:20000,
                data: {
                    start_position:startPostion,
                    max_activity_id:maxActivityID
                },
                dataType: 'json',
                success: function(activitiesData){
                    startPostion += 10;
                    var length = activitiesData.length;
                    for (var i = 0; i < length; i++) {
                        var currentCity = activitiesData[i]['city'];
                        if (isShowCityBar(prevCity,currentCity)) {
                            createCityBar(currentCity);
                            prevCity = currentCity;
                        }
                        createAndInsertActivity(activitiesData[i]);

                    }          
                    $('.lazy').lazyload({ 
                        effect:'fadeIn' ,
                        container:$('.inner')
                    });
                    me.resetload();
                    me.isOver = startPostion >= totalActivitiesCount;
                },
                error: function(xhr, type){
                    if (startPostion>10) {
                        startPostion -= 10;
                    }               
                    me.resetload();
                }
            });
    }});
	

    //创建并插入活动
    function createAndInsertActivity(jsonRow) {
        var items = $("<div class='items'></div>");
        var itemsPic = $("<div class='items_pic'></div>");
        var directLink = jsonRow['direct_link'];
       
        var windowWidth = screen.width;
        var realImageWidth = 800;
        var realImageHeight = 500;
        var imageWidth = windowWidth;
        var imageHeight = realImageHeight * (imageWidth * 1.0 / realImageWidth);      
        var link;

        if (directLink != null) {
            link = $("<a href='"+jsonRow['direct_link']+"' class='activityLink' data-id='"+jsonRow['id'] + "' onclick=count("+jsonRow['id'] + ")></a>");     
        } else {
            link = $("<a href='activityDetail.php?activityID="+jsonRow['id']+"&title="+jsonRow['title']+"'class='activityLink' data-id='"+jsonRow['id']+"'onclick=count(" +jsonRow['id']+")></a>");  
        }   
        var lazyImage = $("<img class='lazy' src='../images/grey.gif' data-original='" +jsonRow['activity_show_pic']+ "' style='width:100%;height:"+imageHeight+"px;'>");
       
        // 倒计时部分 开始
        var timeValidLabel = $("<div class='time_valid_label'></div>");
        timeLabelColor(jsonRow['timeTips'],timeValidLabel);
        var cont = $("<div class='cont'>");
        var timeIcon = $("<img class='time_icon' src='../images/countTime.png'>");
        var main_cont = $("<div class='main_cont'>"+jsonRow['timeTips']+"</div>");

        $(cont).append(timeIcon);
        $(cont).append(main_cont);   
        $(timeValidLabel).append(cont)
        // 倒计时部分 结束


        //阴影条目及内容 开始
        var itemsShadow = $("<div class='items_shadow'></div>");
        var shadowCont = $("<div class='shadow_cont'></div>");
        var shadowContTitle = $("<div class='shadow_cont_title'>"+jsonRow['title'] +"</div>");
        var subhead = $("<p class='activity_subhead'>"+jsonRow['subhead'] +"</p>");
        var shadowContFooter = $("<div class='shadow_cont_footer'></div>");

        var ul = $("<ul></ul>");

        var li01 = $("<li class='li_date li_width_22'></li>");
        var li01Img = $("<img class='li_icon' src='../images/date.png'>");
        var liText01 = $("<span class='li_text'>"+shortCutDateShow(jsonRow['begin_date'])+"</span>");

        var li02 = $("<li class='li_collect li_width_16'></li>");
        var li02Img = $("<img class='li_icon' src='../images/fav.png'>");
        var liText02 = $("<span class='li_collect li_text'>"+jsonRow['fav_num'] + "</span>");

        var li03 = $("<li class='li_price li_width_28'></li>");
        var li03Img = $("<img class='li_icon' src='../images/fee.png'>");
        var liText03 = $("<span class='li_price li_text'>"+jsonRow['fee'] + "</span>");

        var li04 = $("<li class='li_hold li_width_34'></li>");
        var li04Img = $("<img class='li_icon' src='../images/hold.png'>");
        var liText04 = $("<span class='li_hold li_text'>"+jsonRow['company'] + "</span>");

        $(li01).append(li01Img);
        $(li01).append(liText01);
        $(li02).append(li02Img);
        $(li02).append(liText02);
        $(li03).append(li03Img);
        $(li03).append(liText03);
        $(li04).append(li04Img);
        $(li04).append(liText04);
        $(ul).append(li01); 
        $(ul).append(li02);
        $(ul).append(li03);
        $(ul).append(li04);
        $(shadowContFooter).append(ul);
        $(shadowCont).append(shadowContTitle);
        $(shadowCont).append(subhead);

        $(shadowCont).append(shadowContFooter);
        $(itemsShadow).append(shadowCont);
        $(link).append(lazyImage);
        $(link).append(timeValidLabel);
        $(link).append(itemsShadow);
        $(itemsPic).append(link);
        $(items).append(itemsPic);
        $('.inner_lists').append(items);
    }

    function isShowCityBar(lastCityName,currentCityName) {
        if (lastCityName!=currentCityName) {
            return true;
        }
    }

    function createCityBar(currentCityName) {
        var city_bar = $("<div class=\"city_bar\"></div>");
        $(city_bar).text(currentCityName);
        $(".inner_lists").append(city_bar);
    }

    function timeLabelColor(timeLabel,timeValidLabel) {
       
        if (timeLabel.search("开始") != -1) {

            $(timeValidLabel).addClass('time_valid_label_nearly_opening');
        } else if (timeLabel.search("结束") != -1) {

            $(timeValidLabel).addClass('time_valid_label_ended');

        } else if (timeLabel.search("进行") != -1) {

             $(timeValidLabel).addClass('time_valid_label_opening');
        }
    }

    function shortCutDateShow(orginalDate) {
        return orginalDate.substr(5,5);
    }

    //收藏相关的模块,即将废除
 	$('.fav_button').click(function() {
 		var clickTime = getCreateTime();
 		var currentClickedActivityID = $(this).attr('data-activityID');
 		var favButtonBgUrl = $(this).css('background-image');
 		if (favButtonBgUrl.search("whiteHeart.png") != -1) {
 			$(this).css('background-image','url(../images/redHeart.png)');
 			var thisButton = $(this);
 			var temp = checkFavInAdd($(this).text());
 			$.ajax({
	 			url: 'addFav.php',
	 			type: 'POST',
	 			data: {activityID: currentClickedActivityID,
	 				   activityType:'100',
	 				   userType:user_type,
	 				   userID:user_id,
	 				   createTime:clickTime
	 				}
		 		})
		 		.done(function() {	
 					thisButton.text(temp);
		 		})
		 		.fail(function() {
		 			console.log("error");
		 		})
		 		.always(function() {
		 			console.log("complete");
		 		});
 		} else {
 			$(this).css('background-image','url(../images/whiteHeart.png)');
 			var thisButton = $(this);
 			var temp = checkFavInCancle($(this).text());
 			$.ajax({
 				url: 'cancleFav.php',
 				type: 'POST',
 				data: {
 					activityID: currentClickedActivityID,
 				    activityType:'100',
 				   	userType:user_type,
 				   	userID:user_id
 				}
 			})
 			.done(function() {
 				thisButton.text(temp);
 			})
 			.fail(function() {
 				console.log("error");
 			})
 			.always(function() {
 				console.log("complete");
 			});			
 		}
 	});

	//图片延时加载相关
	$('.lazy').lazyload({ 
		effect:'fadeIn' ,
        container:$('.inner')
	});
});

//统计(活动)链接的点击次数
function count(id) {
    var clickTime = getCreateTime();
    $.ajax({
        url: '../myPHP/statistic/countClick.php',
        type: 'POST',
        dataType: 'text',
        data: {
            click_from: '1',
            content_type:'100',
            content_subtype:'0',
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


