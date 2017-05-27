<?php
  if($_SERVER["REQUEST_METHOD"] == "POST") {
     $servername = "127.0.0.1";
     $username = "admin";
     $password = "1234";
     $dbname = "userPage";
     $entra = 0;
     // Create connection
     $conn = new mysqli($servername, $username, $password, $dbname);
     // Check connection
     if ($conn->connect_error) {
         die("Connection failed: " . $conn->connect_error);
     }
    $mypassword = $_POST['password'];
    $myusername = $_POST['username'];
    $sql = "SELECT * FROM dataUser WHERE username = '$myusername' and pass = '$mypassword'";
    echo $mypassword;
    echo $myusername;
    $result = $conn->query($sql);
    if ($result) {
        $row_cnt = $result->num_rows;
        if ($row_cnt==1) {
          header('Location: dashboard/index.html');
        }
        else{
          header('Location: login.php');
        }
        $result->close();

    }else {
      echo "error";
    }

    $conn->close();
  }
  ?>
