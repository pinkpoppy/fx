<?php
	//日期处理模块
	date_default_timezone_set("Asia/Shanghai");

	// 将一个YY-mm-dd HH:mm:ss 类型的 date 对象解析为 YYYY-MM-DD 的字符串
	function dateObjToStr($date) {
		$res="";
		$parseRes = date_parse(date_format($date,'Y-m-d'));
		$res .= $parseRes["year"]."-".$parseRes["month"]."-".$parseRes["day"];
		return $res;
	}

	//判断日期是否合法
	function isLegalDate($date) {
		$parseArr = date_parse($date);
		if (checkdate($parseArr["month"], $parseArr["day"], $parseArr["year"])) {
			return true;
		}
		return false;
	}

	// 计算日期模块
	function activityBeginTime($begin,$end) {
		$beginOnlyDate = date_create(dateObjToStr(date_create($begin))) ;
		$endOnlyDate = date_create(dateObjToStr(date_create($end)));

		if (!isLegalDate($begin) || !isLegalDate($begin))
			return;

		$tipsArray = array(
			"beginTimeTips" => "",
			"suffix" => "天后开始",
			"tomorow" => "明天开始",
			"opening" => "正在进行",
			"nearly" => "即将开始",
			"ending" => "刚刚结束",
			"invalid" => "已结束",
		);	
		$test=date_diff($beginOnlyDate,$endOnlyDate)->format("%a");
		$isBeginEqualsEnd = intval( date_diff($beginOnlyDate,$endOnlyDate)->format("%a") );
		if ($isBeginEqualsEnd == 0) {//开始日期=结束日期
			$diff = intval(date_diff(date_create(dateObjToStr(date_create())),$endOnlyDate)->format("%r%a")) ;
			if ($diff == 0) {
			} else if ($diff == 1) {
				$tipsArray["beginTimeTips"] = $tipsArray["tomorow"];
			} else if($diff > 1) {
				$tipsArray["beginTimeTips"] = $diff.$tipsArray["suffix"];
			} else if ($diff < 0) {
				$tipsArray["beginTimeTips"] = $tipsArray["invalid"];
			}
		} else {		
			$parseRes = dateObjToStr(date_create());
			$todayToBegin = date_diff(date_create($parseRes),$beginOnlyDate)->format("%r%a");
			$todayToEnd = date_diff(date_create($parseRes),$endOnlyDate)->format("%r%a");

			if ($todayToBegin > 0) {//未开始
				if (intval($todayToBegin) == 1) {
					$tipsArray["beginTimeTips"] = $tipsArray["tomorow"];
				} else if (intval($todayToBegin) > 1) {
					$tipsArray["beginTimeTips"] = $todayToBegin.$tipsArray["suffix"];
				}
			} else if ($todayToBegin <= 0 && $todayToEnd >= 0) {//进行中 
				$tipsArray["beginTimeTips"] =$tipsArray["opening"];
			} else if ($todayToEnd < 0) {
				$tipsArray["beginTimeTips"] = $tipsArray["invalid"];
			}	
		}

		return $tipsArray["beginTimeTips"];
	}
?>