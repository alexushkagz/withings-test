<?php
require_once('config.php');

$rsp = $api->getMeas($_SESSION['access_token']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Measurements</title>
    <style>
        table {
            position: relative;
            width: 100%;
        }
        th, td {
            border-bottom: 1px solid #777;
            border-right: 1px solid #777;
            padding: 10px 15px;
        }
    </style>
</head>
<body>
    <table id="measurementsTable">
        <thead>
            <tr>
                <th>N°</th>
                <th>Date</th>
                <th>Device ID</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>
    <script>
        const measurements = <?= $rsp ?>;
        let tableData = measurements.body.measuregrps.map(elem => {
            return {date: elem.date, deviceid: elem.deviceid, value: elem.measures[0].value};
        })
        console.log(tableData);
        const table = document.getElementById('measurementsTable');
        tableData.forEach((measurement, index) => {
            const row = table.tBodies[0].insertRow();
            row.insertCell().innerText = index+1;
            row.insertCell().innerText = new Date(measurement.date).toDateString();
            row.insertCell().innerText = measurement.deviceid;
            row.insertCell().innerText = measurement.value;
        })
    </script>
</body>
</html>