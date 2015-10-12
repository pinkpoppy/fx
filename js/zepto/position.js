
var LS = {
    item : function(name,value){
        var val = null;
        if(LS.isSupportLocalStorage()){
            if(value){
                localStorage.setItem(name,value);
                val = value;
            }else{
                val = localStorage.getItem(name);
            }
        }else{
            console.log('浏览器不支持html5的本地存储！');
        }
        return val;
    },
    removeItem : function(name){
        if(LS.isSupportLocalStorage()){
            localStorage.removeItem(name);
        }else{
            console.log('浏览器不支持html5的本地存储！');
            return false;
        }
        return true;
    },
    isSupportLocalStorage : function(){
        var ls = LS,is = ls.IS_HAS_LOCAL_STORAGE;
        if(is == null){
            if(window.localStorage){
                is = ls.IS_HAS_LOCAL_STORAGE = true;
            }
        }
        return is;
    },
    IS_HAS_LOCAL_STORAGE : null
};

function getLocation(options) {
    if (navigator.geolocation) {
        var popupTip ='<div class="popupTip"><div class="popupTipCon"><p class="txt">定位中......</p><a href="#" class="close">关闭</a></div></div>',
            popupTxt, popupClose,
            showPosition = function(position){
                if(!LS.isSupportLocalStorage())return;
                var txt = "定位成功";

                LS.item('position', 'success');
                LS.item('lat', position.coords.latitude);
                LS.item('lng', position.coords.longitude);

                opts.success({
                    lat:position.coords.latitude,
                    lng:position.coords.longitude
                });

                if(opts.showSuccessTip){
                    popupTip.show();
                    popupTxt.text(txt).one('click', function(event) {
                        popupTip.remove();
                    });

                    setTimeout(function(){
                        popupTip.remove();
                    },3000)
                }
            },
            showError = function(error){
                var txt = "定位失败，点击重新定位";
                if(LS.isSupportLocalStorage()){
                    LS.item('position', 'failed');
                    LS.item('lat', '');
                    LS.item('lng', '');
                };

                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        console.log("您拒绝了请求地理定位。");
                        break;
                    case error.POSITION_UNAVAILABLE:
                        console.log("位置信息是不可用的。");
                        break;
                    case error.TIMEOUT:
                        console.log("请求获取用户位置超时。");
                        break;
                    case error.UNKNOWN_ERROR:
                        console.log("未知的错误...");
                        break;
                }

                opts.failed(error.code);

                if(opts.showFailedTip){
                    popupTip.show();
                    popupTxt.text(txt).one('click',function(event) {
                        popupTip.remove();
                        getLocation();
                    });

                    setTimeout(function(){
                        popupTip.hide();
                    },3000)
                }
            },
            opts = {
                showSuccessTip:1,
                showFailedTip:1,
                success: function(){},
                failed : function(){}
            }

        $.extend(opts, options);

        popupTip = $(popupTip).appendTo("body").fadeIn();
        popupTxt = popupTip.find('.txt');
        popupClose = popupTip.find('.close');

        popupClose.click(function(event) {
            popupTip.fadeOut();

            return false;
        });


        navigator.geolocation.getCurrentPosition(showPosition, showError);
    }
}

