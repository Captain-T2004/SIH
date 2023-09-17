<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAP</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
     <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>
     <style>
        body{
            font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            background-color:honeydew;
        }
        .custom-icon {
            text-align: center;
        }

        .marker {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            color: #fff; /* Text color inside the marker */
            font-size: 18px; /* Adjust the font size as needed */
            opacity: 0.3;
        }
        *{
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }
        #container{
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;        
        }
        #map{
            height: 350px;
            width: 75%; 
        }
        h1{
            margin: 10px auto 10px auto;
            font-size: 50px;
        }
        #box{
            display: flex;
            justify-content: space-between;
            padding: 20px;
        }
        #box1,#box2{
            padding: 70px;
            background-color:skyblue;
            color: azure;
        }
     </style>
</head>
<body>
    <?php
        $servername = "localhost";
        $usern = "root";
        $passwd = "captaint";
        $con = mysqli_connect($servername, $usern, $passwd, "buses", 3310);
        if(mysqli_connect_errno()){
            echo "Failed";
            exit();
        }
        $sql = "SELECT * FROM location";
        $result=mysqli_query($con,$sql);
        $sql2 = "SELECT * FROM buses";
        $result2 = mysqli_query($con,$sql2);
        mysqli_data_seek($result2,4);
        $row2 = mysqli_fetch_row($result2);
        $pltNo=$row2[1];
        $color=$row2[4];
        $fuelType=$row2[5];
        $fair=$row2[8];
        $count = 0;
        mysqli_data_seek($result,$count);
        // Fetch row
        $row=mysqli_fetch_row($result);
        $longitude = $row[0];
        $latitude = $row[1];
    ?>
    <div id="container">
    <h1 style="color:green">Buses Near you</h1>
    <div id="map"></div>
    <div id="box">
        <div id="box1">
            <h3>
                Bus's Number Plate : <?php echo " ".$pltNo?>
            </h3>
            <br>
            <h3>
                Bus's Color : <?php echo " ".$color?>
            </h3>
        </div>
        <div id="box1">
            <h3>
                Bus's Fuel Type : <?php echo " ".$fuelType?>
            </h3>
            <br>
            <h3>
                Bus's Fair: <?php echo " ".$fair?>
            </h3>
        </div>
    </div>
    </div>
</body>
<script>
    var map = L.map('map');
    <?php 
        echo "let longX =".$longitude.";";
        echo "let latX =".$latitude.";"; 
    ?>

    let userLatitude = 31.104156;
    let userLongitude = 77.172222;
    map.setView([userLongitude,userLatitude],50);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 50,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    var customIcon = L.divIcon({
        className: 'custom-icon',
        iconSize: [30, 30], // Adjust the size as needed
        html: '<div class="marker" style="background-color: purple;"></div>' // Change the background-color to your desired color
    });
    navigator.geolocation.watchPosition(userSuccess,error);
    function userSuccess(pos) {
        let circle , marker , zoomed;
        const lat = userLatitude;
        const lon = userLongitude;
        const accuracy = pos.coords.accuracy;
        if(marker){
            map.removeLayer(marker);
            map.removeLayer(circle);
        }
        marker = L.marker([lat,lon], {icon: customIcon}).addTo(map);
        circle = L.circle([lat,lon],{ radius : 150 }).addTo(map);
        if(!zoomed){
        zoomed = map.fitBounds(circle.getBounds());
        }
    }
    <?php
    while($count<7){
        mysqli_data_seek($result,$count);
        $row=mysqli_fetch_row($result);
        $longitude = $row[0];
        $latitude = $row[1];
        echo 'navigator.geolocation.watchPosition((pos)=>{
                    let userLatitude = 31.104156;
                    let userLongitude = 77.172222;
                    let circle, marker, zoomed;
                    let lat ='.$longitude.';
                    let lon ='.$latitude.';
                    console.log(lat);
                    console.log(lon);
                    const accuracy = pos.coords.accuracy;
                    if(marker){
                        map.removeLayer(marker);
                        map.removeLayer(circle);
                    }
                    marker = L.marker([lat,lon]).addTo(map);
                    circle = L.circle([lat,lon],{ radius : 10 }).addTo(map);
                    if(!zoomed){
                    zoomed = map.fitBounds(circle.getBounds());
                    map.setView([userLatitude,userLongitude]);
                }
            },error); ';
        echo " ";
        
        $count++;
    }
    ?>
    //map.setView([userLongitude,userLatitude],30);
    function error(err) {
        if(err.code === 1){
            alert("Please allow geolocation access")
        }
    }
</script>
</html>
