<?php
ini_set('memory_limit', '-1');
$servername = "127.0.0.1";
$username = "admin";
$password = "1234";
$dbname = "vehicle_counter";
$total = array();
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
$sql_1 = "SELECT type, COUNT(DISTINCT id) as counts from  streets where id>0 group by type limit 5";
$result_1 = $conn->query($sql_1);
$dataArray_1 = array();
if ($result_1->num_rows > 0) {
    // output data of each row
    while($row = $result_1->fetch_assoc()) {
        //echo $row["IMSI"] .",". $row["fecha"] .",". $row["latitud"] .",". $row["longitud"]. "<br>";
        array_push($dataArray_1, $row);
    }
} else {
    echo "0 results";
}


$sql_2 = "SELECT count_date, count(count_total) AS count_total FROM counts GROUP BY count_date";
$result_2 = $conn->query($sql_2);
$dataArray_2 = array();
if ($result_2->num_rows > 0) {
    // output data of each row
    while($row = $result_2->fetch_assoc()) {
        //echo $row["IMSI"] .",". $row["fecha"] .",". $row["latitud"] .",". $row["longitud"]. "<br>";
        array_push($dataArray_2, $row);
    }
} else {
    echo "0 results";
}

array_push($total, $dataArray);
array_push($total, $dataArray_1);
array_push($total, $dataArray_2);
$conn->close();

?>
<html>
<head>
    <meta charset='utf-8' />
    <title></title>
    <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js"></script>
    <script src="datos.js" charset="utf-8"></script>
    <script src="offOn.js" charset="utf-8"></script>
    <script src="polarChart.js" charset="utf-8"></script>
    <script src="linechart.js" charset="utf-8"></script>
<body>

  <div class="wrapper">
      <div style="width: 100%;">
          <div id="parent" style="height:22vh; padding-bottom:30px;">
              <canvas id="myChart"></canvas>
              <script type="text/javascript">
                var data = <?php echo json_encode($total); ?>;
                var linedatos = LineDatos(data);
                var label = linedatos[0];
                var cantidad = linedatos[1];
                console.log(label);
                console.log(cantidad);
                linechart(label,cantidad);
              </script>

          </div>

          <div style="width: 100%">
              <div id="parent" style="height: 25vh; padding-bottom:30px;">
                  <canvas id="myChart-1"></canvas>
                  <script type="text/javascript">
                      var data = <?php echo json_encode($total); ?>;
                      var piedatos = PieDatos(data);
                      console.log(piedatos);
                      pieOffOn(piedatos)
                  </script>
              </div>
          </div>

          <div style="width: 100%">
              <div id="parent" style="height:40vh;">
                  <canvas id="myChart-2"></canvas>
                  <script type="text/javascript">
                      var data = <?php echo json_encode($total); ?>;
                      var datos = BubbleDatos(data);
                      var labelbubble = datos[0];
                      var countsbubble = datos[1];
                      //console.log("buble");
                      console.log(labelbubble);
                      console.log(countsbubble);
                      bubblechart(labelbubble,countsbubble);
                  </script>
              </div>
          </div>
      </div>
    </div>



</body>
</html>
