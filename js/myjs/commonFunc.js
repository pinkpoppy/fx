function showFavNum(n) {
	var res = "";
	var a,left,b;
	if (n < 1000) {
		res += String(n);
	} else {
		a = parseInt(n / 1000);
		res += String(a);
		left = n % 1000;
		if (left > 100) {
			b = parseInt(left / 100);
			res += ".";
			res += String(b) + "k";
		} else {
			res += "k";
		}
	}
	return res;
}

//收藏时本地红心的变化
function checkFavInAdd(favText) {
	if (favText.search("k") != -1) {
		//不改红心文本
		return favText;
	} else {
		var num = parseFloat(favText);
		return showFavNum(num + 1);
	}
}

//取消收藏时本地红心的变化
function checkFavInCancle(favText) {
	if (favText.search("k") != -1) {
		//不改红心文本
		return favText;
	} else {
		var num = parseFloat(favText);
		return showFavNum(num - 1);
	}
}

//读取 cookie
function loadCookie() {
	var myCookie = document.cookie;
	var splitedCookieContents = myCookie.split(";");
	var keysArr = ["userId","userType","province","city","area","road","lng","lat","width","activityID","title","width","activityID"];
	var resArr = [];
	for (var i = 0; i < keysArr.length; i++) {
		for (var j = 0; j < splitedCookieContents.length; j++) {
			var targetString = splitedCookieContents[j];
			if (targetString.search(keysArr[i]) != -1) {
				var targetArr = splitedCookieContents[j].split("=");
				var key = targetArr[0].trim();
				var value = targetArr[1].trim();
				resArr[key] = value;
				console.log(resArr);
				break;
			}
		}
	}
	//return resArr;
	//console.log(resArr);
	return resArr;
}


//日期函数
function getCreateTime() {
	var create_time = new Date();
	var preString = "" + "0";

	var year = create_time.getFullYear();
	var month = String(parseInt(create_time.getMonth()) + 1);

	if (month.length < 2) {
		month = preString + month;
	}

	var day = String(create_time.getDate());
	if (day.length < 2) {
		day = preString + day;
	}
	
	var hour = String(create_time.getHours());
	if (hour.length < 2) {
		hour = preString + hour;
	}

	var min = String(create_time.getMinutes());
	if (min.length < 2) {
		min = preString + min;
	}

	var res = year + "-" + month + "-" + day + " " + hour + ":" + min;
	return res;
}

function setSpin() {
	//配置spin.js数组
	var opts={
		lines:11,
		length:5,
		width:3,
		radius:10,
		corners:1,
		rotate:0,
		direction:1,
		color:'#000',
		speed:1,
		trail:60,
		shadow:false,
		hwaccesl:false,
		className:'spinner',
		zIndex:2e9,
		left:'50%',
		top:'auto'
	};
	return opts;
}

function getShortTime(completeTime) {
	return completeTime.substr(5,5);
}

