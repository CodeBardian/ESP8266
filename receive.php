<?php
    $servername = "localhost";  
    $dbusername = "username";  //change to your mysql username
    $dbpassword = "password";  //change to your mysql password
    $dbname = "sensor_data"; 

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if(isset($_GET['temp']) && !empty($_GET['temp']) && isset($_GET['hum']) && !empty($_GET['hum'])) {
        $temp = $conn->escape_string($_GET['temp']);
        $hum = $conn->escape_string($_GET['hum']);
        $conn->query("INSERT INTO sensor_data(temp,humidity,time,date) VALUES ('$temp','$hum',CURTIME(),CURDATE())");
    }
    $conn->close();
?>