<?php
ini_set('memory_limit', '-1');
$servername = "192.168.0.105";
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js"></script>

    <div class="wrapper">
        <div style="width: 100%;">
            <div id="parent" style="height:22vh; padding-bottom:30px;">
                <canvas id="myChart"></canvas>
                <script src="linechart.js" charset="utf-8">
                </script>
            </div>
            <div style="width: 100%">
                <div id="parent" style="height: 25vh; padding-bottom:30px;">
                    <canvas id="myChart-1"></canvas>
                    <script src="offOn.js" charset="utf-8"> </script>
                    <script>
                        var data = <?php echo json_encode($dataArray); ?>;
                        console.log(data);
                        //console.log(data[0]["properties"]);
                        var sensoresOn = 0;
                        var sensoresOff = 0;
                        var consolidado = []
                        for (var i=0; i < data.length; i++){
                          //console.log(data[i]["properties"]["status"]);
                          //console.log(data[i]);
                          var activo = data[i]["properties"]["status"];
                          if (activo == '1') {
                            sensoresOn +=1;
                          }else {
                            sensoresOff +=1;
                          }
                        }
                        consolidado.push(sensoresOff)
                        consolidado.push(sensoresOn)
                        console.log(consolidado);
                        pieOffOn(consolidado);
                    </script>
                </div>
            </div>

            <div style="width: 100%">
                <div id="parent" style="height:40vh;">
                    <canvas id="myChart-2"></canvas>
                    <script src="polarChart.js" charset="utf-8"></script>
                </div>
            </div>
        </div>
      </div>

</body>

</html>
