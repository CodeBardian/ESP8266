<?php
    $servername = "localhost";  
    $dbusername = "username";  //change to your mysql username
    $dbpassword = "password";  //change to your mysql password
    $dbname = "sensor_data"; 

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "SELECT * FROM sensor_data ORDER BY id DESC LIMIT 0, 1 ";
    $test = $conn->query($sql);
    $highestSet = $test->fetch_assoc();
    $to_id = $highestSet['id'];
    $from_id = $to_id-12;
    $cnt=0;
    $value = array();
    $time = array();


    $sql = "SELECT * FROM sensor_data WHERE id BETWEEN '" . $from_id . "' AND ( SELECT MAX(id) FROM sensor_data ) ";
    $result = $conn->query($sql);

    if ($result->num_rows==0){
        die("Couldnt retrieve data");
    }
    else{
        while($temp = $result->fetch_assoc()){
            $value[$cnt] = $temp['temp'];
            $time[$cnt] = $temp['time'];
            $humidity[$cnt] = $temp['humidity'];
            $cnt = $cnt+1 ;
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>
            Sensor Data
        </title>
        <script src="Chart.js"></script>
    </head>
    <body>
        <section>
            <h3>Sensor Data from Arduino Weather Station</h3>
            <div style="padding: 5% 5% 5% 5%;">
                <canvas id="myTempChart" width="400" height="100"></canvas>
                <canvas id="myHumChart" width="400" height="100"></canvas>
            </div>
            <script>
                var val = <?php echo json_encode($value); ?>;
                var time = <?php echo json_encode($time); ?>;
                var humidity = <?php echo json_encode($humidity); ?>;
                var ctxTemp = document.getElementById('myTempChart');
                var myLineChart = new Chart(ctxTemp, {
                    type: 'line',
                    data: {
                        labels: [time[0],time[1],time[2],time[3],time[4],time[5],time[6],time[7],time[8],time[9],time[10],time[11]],
                        datasets: [{ 
                            data: [val[0],val[1],val[2],val[3],val[4],val[5],val[6],val[7],val[8],val[9],val[10],val[11]],
                            label: "Temperature",
                            borderColor: "#3e95cd",
                            fill: false
                        }]
                    },
                    options:{
                        responsive: true,
                    }
                });
                var ctxHum = document.getElementById('myHumChart');
                var myLineChartHum = new Chart(ctxHum, {
                    type: 'line',
                    data: {
                        labels: [time[0],time[1],time[2],time[3],time[4],time[5],time[6],time[7],time[8],time[9],time[10],time[11]],
                        datasets: [{ 
                            data: [humidity[0],humidity[1],humidity[2],humidity[3],humidity[4],humidity[5],humidity[6],humidity[7],humidity[8],humidity[9],humidity[10],humidity[11]],
                            label: "Humidity",
                            borderColor: "#3e95cd",
                            fill: false
                        }]
                    },
                    options:{
                        responsive: true,
                    }
                });
            </script>
        </section>
    </body>
</html>