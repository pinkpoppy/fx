<?php
   include "../myPHP/config/connectDatabase.php";
    if(isset($_GET["activityID"])) {
        $id = $_GET["activityID"];
    }

    $sql = "SELECT lng,lat 
            FROM jobs_activity 
            WHERE id = ".$id.";";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $lng = $row["lng"];
        $lat = $row["lat"];
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta name="viewport" content="initial-scale=1.0,user-scalable=no">
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=CmNnl8txr6kNtNi6tyZ0SZWS"></script>

    <style type="text/css">
        html{height:100%}
        body{height:100%;margin:0px;padding:0px}
        #map_container{height:100%}
    </style>  

    <script type="text/javascript">
       
        //用纬度设置地图中心点
        function theLocation() {
            var map = new BMap.Map("map_container");
            var lng = <?php echo $lng; ?>;
            var lat = <?php echo $lat; ?>;
            map.centerAndZoom(new BMap.Point(lng,lat),20);
            if (lng != "" && lat != "") {
                map.clearOverlays();
                var new_point = new BMap.Point(lng,lat);
                var marker = new BMap.Marker(new_point);
                map.addOverlay(marker);
                map.panTo(new_point);
            } 
        }
    </script>

</head>

<body onload="theLocation()">
    <div id="map_container"></div>
</body>

