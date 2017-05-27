<?php
    function func1($minute,$hour,$day,$month){
      ini_set('memory_limit', '-1');
      $servername = "127.0.0.1";
      $username = "admin";
      $password = "1234";
      $dbname = "telecom";
      // Create connection
      $conn = new mysqli($servername, $username, $password, $dbname);
      // Check connection
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }
      //echo "connected";

      if ($minute == 0 and $hour == 0) {
        $sql = 'SELECT * FROM registro where day(fecha) = '." $day ".' and month(fecha)= '." $month ".' and year(fecha) = 2016';
      }elseif ($minute ==0){
        $sql = 'SELECT * FROM registro where hour(fecha) = '." $hour ".' and day(fecha) = '." $day ".' and month(fecha)= '." $month ".' and year(fecha) = 2016';
      }elseif ($hour==0) {
        $sql = 'SELECT * FROM registro where minute(fecha) = '." $minute ".' and day(fecha) = '." $day ".' and month(fecha)= '." $month ".' and year(fecha) = 2016';
      }
      else{
        $sql = 'SELECT * FROM registro where minute(fecha) = '." $minute ".' and hour(fecha) = '." $hour ".' and day(fecha) = '." $day ".' and month(fecha)= '." $month ".' and year(fecha) = 2016';
      }


      //$sql = 'SELECT * FROM registro where hour(fecha) = 23 and day(fecha) = 26';
      //$sql = 'SELECT * FROM registro where minute(fecha) = 46 and hour(fecha) = 22 and day(fecha) = 26 and month(fecha)= 7 and year(fecha) = 2016';
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
                  'hora' => $row['fecha']
                )
              );
              //echo json_encode($data);
              array_push($dataArray, $data);
          }
      } else {
          echo "0 results";
      }
      //echo $dataArray;
      //print_r(array_values($dataArray));
      $conn->close();
      return json_encode($dataArray);
      //return $data+1;
    }

    if (isset($_POST['callFunc1'])) {
        echo func1($_POST['minute'],$_POST['hour'],$_POST['day'],$_POST['month']);
    }
?>
