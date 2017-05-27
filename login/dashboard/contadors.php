<?php
ini_set('memory_limit', '-1');
$servername = "127.0.0.1";
$username = "admin";
$password = "1234";
$dbname = "vehicle_counter";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//echo "connected";
$sql = "SELECT * FROM sensors";
$result = $conn->query($sql);
$dataArray = array();
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        //echo $row["IMSI"] .",". $row["fecha"] .",". $row["latitud"] .",". $row["longitud"]. "<br>";
        $data = array(
          'type' => 'Feature',
          'geometry' => array(
            'type'=>'Point',
            'coordinates' =>array((float) $row['longitud'],(float)$row['latitud'])
          ),
          'properties'=> array(
            'hora' => $row['last_offline'],
            'status' => $row['status']
          )
        );
        //echo json_encode($data);
        array_push($dataArray, $data);
    }
} else {
    echo "0 results";
}
$conn->close();
?>
<html>

<head>
    <meta charset='utf-8' />
    <title></title>
    <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
    <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.36.0/mapbox-gl.js'></script>
    <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.36.0/mapbox-gl.css' rel='stylesheet' />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="test.js"></script>

    <style>
        body {
            margin: 0;
            padding: 0;
        }

        #map {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 100%;
        }
    </style>

    <div id='map'></div>

    <script>
        var data = <?php echo json_encode($dataArray); ?>;
        //console.log(data);
        //console.log(data[0]["properties"]);
        var sensoresOn = []
        var sensoresOff = []
        for (var i=0; i < data.length; i++){
          //console.log(data[i]["properties"]["status"]);
          //console.log(data[i]);
          var activo = data[i]["properties"]["status"];
          if (activo == '1') {
            sensoresOn.push(data[i])
          }else {
            sensoresOff.push(data[i])
          }
        }
        //console.log(sensoresOn);
        //console.log(sensoresOff);

        mapboxgl.accessToken = 'pk.eyJ1IjoiamF2aWVyYXpkIiwiYSI6ImNqMHh4OGRwbTAwMTAzM3BiZjdya2k3ZjYifQ.8srntVtXZZF538OjGBQH0g';
        var map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/dark-v9',
            center: [1.521835, 42.506317],
            zoom: 11
        });
        map.on('load', function() {
            if (sensoresOff.length>0) {
              map.addSource("jeje", {
                  "type": "geojson",
                  "data": {
                      "type": "FeatureCollection",
                      "features": sensoresOff
                  }
              });

              map.addLayer({
                  "id": "point-1",
                  "type": "circle",
                  "source": "jeje",
                  "paint": {
                      "circle-radius": 3,
                      "circle-color": "#ff6849"
                  }
              });
            }

            if (sensoresOn.length>0) {
                  map.addSource("jaja", {
                      "type": "geojson",
                      "data": {
                          "type": "FeatureCollection",
                          "features": sensoresOn
                      }
                  });
                  map.addLayer({
                      "id": "point-2",
                      "type": "circle",
                      "source": "jaja",
                      "paint": {
                          "circle-radius": 3,
                          "circle-color": "#49b6ff"
                      }
                  });
            }


        });




    </script>

</body>

</html>
