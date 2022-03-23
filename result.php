<!DOCTYPE html>
<html lang="en">

<head>
    <title>RESULT FILE SCAN VIRUS TOTAL</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="jumbotron text-center">
        <h1>FILE SCAN RESULT</h1>
        <a href="index.php"><h5>Go Home</h5></a>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">


<?php

if (!isset($_GET['resource'])) {
    echo "Go back to homepage";
} else {
    // echo $_GET['resource'];
    echo "<br>";
    echo "<a class='btn btn-primary' href='" . $_GET['permalink'] . "' role='button'>Show Result On Virus Total Page</a>";
  
    
    $resource = $_GET['resource'];

    $post = array('apikey' => 'f5729768d43686e2a0ec594aebfce9cc24501f6eea6f0bda9ad0a814582d78e9', 'resource' => $resource);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.virustotal.com/vtapi/v2/file/report');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 1); // remove this if your not debugging
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate'); // please compress data
    curl_setopt($ch, CURLOPT_USERAGENT, "gzip, My php curl client");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

    $result = curl_exec($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    // print("status = $status_code\n");
    if ($status_code == 200) { // OK
        // print_r($result);
        $js = json_decode($result, true);
        // print_r($js["scans"]);
        echo "<div class='table-responsive'>";
        echo "<table border=1 class='table'>";
        echo "<tr>";
        echo "<th>Key</th>";
        echo "<th>Detected</th>";
        echo "<th>Version</th>";
        echo "<th>Result</th>";
        echo "<th>Update</th>";
        echo "</tr>";
        if ($js["response_code"] == -2) {
            echo "<tr>";
            echo "<th colspan='5'>Waiting for the result</th>";
            echo "</tr>";
        } else {
            foreach ($js["scans"] as $key => $value) {

                $converted_res = $value["detected"] ? 'true' : 'false';
                echo "<tr>";
                echo "<th>" . $key . "</th>";
                // echo "<td>" . $value["detected"] . "</td>";
                echo "<td>" . $converted_res . "</td>";
                echo "<td>" . $value["version"] . "</td>";
                echo "<td>" . $value["result"] . "</td>";
                echo "<td>" . $value["update"] . "</td>";
                echo "</tr>";
            }
        }
        echo "</table>";
        echo "</div>";
    } else {  // Error occured
        print($result);
    }
    curl_close($ch);
}
?>

            </div>
        </div>
    </div>