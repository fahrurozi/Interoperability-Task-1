<!DOCTYPE html>
<html lang="en">

<head>
  <title>URL SCAN VIRUS TOTAL</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

  <div class="jumbotron text-center">
    <h1>URL SCAN</h1>
    <a href="index.php">
      <h5>Go Home</h5>
    </a>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <form action="url-report.php" method="post" class="d-flex" style="justify-content: center;">
          <input type="text" name="link" placeholder="link">
          <input type="submit" name="action" value="submit">
        </form>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        


</body>

</html>

<?php
if (isset($_POST['link'])) {
  $link = $_POST['link'];
  // var_dump($link);

  if ($link != null) {
    $post = array('apikey' => 'f5729768d43686e2a0ec594aebfce9cc24501f6eea6f0bda9ad0a814582d78e9', 'resource' => $link);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.virustotal.com/vtapi/v2/url/report');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 1); // remove this if your not debugging
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate'); // please compress data
    curl_setopt($ch, CURLOPT_USERAGENT, "gzip, My php curl client");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

    $result = curl_exec($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    

    if ($status_code == 200) { // OK

      $js = json_decode($result, true);

      // print_r($js["scans"]);
      if ($js['response_code'] == 1) {
        echo "<div class='table-responsive'>";
        echo "<table border=1 class='table'>";
        echo "<tr>";
        echo "<th>scan_id</th>";
        echo "<td>". $js['scan_id'] ."</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<th>resource</th>";
        echo "<td>". $js['resource'] ."</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<th>url</th>";
        echo "<td>". $js['url'] ."</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<th>response_code</th>";
        echo "<td>". $js['response_code'] ."</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<th>scan_date</th>";
        echo "<td>". $js['scan_date'] ."</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<th>permalink</th>";
        echo "<td>". $js['permalink'] ."</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<th>verbose_msg</th>";
        echo "<td>". $js['verbose_msg'] ."</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<th>positives</th>";
        echo "<td>". $js['positives'] ."</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<th>total</th>";
        echo "<td>". $js['total'] ."</td>";
        echo "</tr>";
        echo "</table>";
        echo "</div>";
       

        echo "<div class='table-responsive'>";
        echo "<table border=1 class='table'>";
        echo "<tr>";
        echo "<th>Key</th>";
        echo "<th>Value</th>";
        echo "</tr>";
        foreach ($js["scans"] as $key => $value) {
          echo "<tr>";
          echo "<td>" . $key . "</td>";
          echo "<td>" . $value["result"] . "</td>";
          echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
      } else {
        echo "wrong URL";
      }
    } else {  // Error occured
      print($result);
    }
    curl_close($ch);
  }
}

?>