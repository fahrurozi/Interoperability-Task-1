<!DOCTYPE html>
<html lang="en">

<head>
    <title>FILE SCAN VIRUS TOTAL</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="jumbotron text-center">
        <h1>FILE SCAN</h1>
        <a href="index.php"><h5>Go Home</h5></a>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <form action="inputfile.php" method="post" class="d-flex" style="justify-content: center;" enctype="multipart/form-data">
                    <!-- <input type="file" name="fileToUpload" placeholder="file" id="fileToUpload">
        <input type="submit" name="action" value="submit"> -->
                    <form action="upload.php" method="post" enctype="multipart/form-data">
                        Pilih file: <input type="file" name="berkas" />
                        <input type="submit" name="upload" value="upload" />
                    </form>
                </form>
            </div>
        </div>
    </div>


</body>

</html>


<?php
if (isset($_FILES['berkas'])) {

    $namaFile = $_FILES['berkas']['name'];
    $namaSementara = $_FILES['berkas']['tmp_name'];

    // tentukan lokasi file akan dipindahkan
    $dirUpload = "file/";

    // pindahkan file
    $terupload = move_uploaded_file($namaSementara, $dirUpload . $namaFile);

    if ($terupload) {
        echo "<div class='container'>";
        echo "<br>";
        echo "<div class='alert alert-success' role='alert'>Upload berhasil!</div>";
        echo "<a href='" . $dirUpload . $namaFile . "'>" . $namaFile . "</a>";

        // 
        $file_name_with_full_path = realpath('file/' . $namaFile);
        $api_key = getenv('VT_API_KEY') ? getenv('VT_API_KEY') : 'f5729768d43686e2a0ec594aebfce9cc24501f6eea6f0bda9ad0a814582d78e9';
        // $cfile = curl_file_create('bin/cp/tes.pdf');
        $cfile = curl_file_create($file_name_with_full_path);
        // var_dump($cfile);

        $post = array('apikey' => $api_key, 'file' => $cfile);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.virustotal.com/vtapi/v2/file/scan');
        curl_setopt($ch, CURLOPT_POST, True);
        curl_setopt($ch, CURLOPT_VERBOSE, 1); // remove this if your not debugging
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate'); // please compress data
        curl_setopt($ch, CURLOPT_USERAGENT, "gzip, My php curl client");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $result = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // print("status = $status_code\n");
        if ($status_code == 200) { // OK
            $js = json_decode($result, true);
            // print_r($js);
            unlink($dirUpload . $namaFile);
            echo "<br>";
            echo "<a class='btn btn-primary' href='result.php?resource=" . $js["resource"] . "&permalink=" . $js["permalink"] . "?'' role='button'>Show Result</a>";
            echo "<div class='table-responsive'>";
            echo "<table border=1 class='table'>";
            echo "<tr>";
            echo "<th>Key</th>";
            echo "<th>Value</th>";
            echo "</tr>";
            foreach ($js as $key => $value) {
                echo "<tr>";
                echo "<td>$key</td>";
                echo "<td>$value</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
            //echo a href
            // echo "<a href='result.php?resource=" . $js["resource"] . "&permalink=" . $js["permalink"] . "?'>Link</a>";
            
            echo "</div>";
            // echo "<a href='result.php?id='".$js['resource'].">Link</a>";
        } else {  // Error occured
            print($result);
        }
        curl_close($ch);
        // 
    } else {
        echo "Upload Gagal!";
    }
}

?>