<!doctype html>
<html lang="en">

<?php
ob_start();
session_start();

if (!isset($_SESSION['access_token'])) {
  header("Location: login.php");
}


?>



<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <!-- custom css -->
  <link rel="stylesheet" href="rekomendasi.css">

  <title>Rekomendasi</title>

</head>

<body>
  <div class="global-container">
    <div class="card rating-form">
      <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">

          <div class="main">
            <a class="nav-link active" aria-current="page" href="main.php">
              <h1 class="card-title text-left" href="#">GawaiKu</h1>
            </a>
          </div>

          <div class="menu">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <!-- home -->
                <li class="nav-item">
                  <a class="nav-link active" aria-current="page" href="main.php">Home</a>
                </li>
                <!-- rating -->
                <li class="nav-item">
                  <a class="nav-link" href="rating.php">Rating Smartphone</a>
                </li>
                <!-- rekomendasi -->
                <li class="nav-item">
                  <a class="nav-link" href="data_raw.php">Data Rating</a>
                </li>
                <!-- logout -->
                <li class="nav-item">
                  <a class="nav-link" href="logout.php">Logout</a>
                </li>

              </ul>

            </div>
          </div>
        </div>
      </nav>

    </div> <!-- main form -->

    <div class="middle">
      <h4 class="judul_rek text-center">Rekomendasi</h4>
      <?php

      $url_analyze = "http://127.0.0.1:8000/api/user_cf/";

      // var_dump($result_analyze);

      $data_analyze = array(
        'user_id' => $_SESSION['user_id']
      );

      $headers_analyze = array(
        'Content-Type: application/x-www-form-urlencoded',
        'Content-Length: ' . strlen(http_build_query($data_analyze)),
        'Authorization' => "Bearer " . $_SESSION['access_token']
      );

      $options_analyze = array(
        'http' => array(
          'header' => implode("\r\n", $headers_analyze),
          'method' => 'POST',
          'content' => http_build_query($data_analyze),
        )
      );
      error_reporting(0);
      $context_analyze  = stream_context_create($options_analyze);
      $result_analyze = file_get_contents($url_analyze, false, $context_analyze);
      // var_dump($result_analyze);
      if ($result_analyze === FALSE) {

        $message = "error, tidak ada data atau anda belum memasukkan data rating!";
        echo "<script type='text/javascript'>alert('$message');</script>";
      }
      $analyzer_get = json_decode($result_analyze);
      // var_dump($response_analyzer);
      // var_dump($result_analyze);
      // var_dump($analyzer_get);
      // echo $analyzer_get->score_brand->brand;

      $obj1 = new stdClass();
      $obj1->{'Similarity_User'} = $analyzer_get->Similarity_User;
      // var_dump($obj1);

      $obj2 = new stdClass();
      $obj2->brand = $analyzer_get->{'score_brand'}->{'brand'};
      // var_dump($obj2);

      $obj3 = new stdClass();
      $obj3->brand_Score = $analyzer_get->{'score_brand'}->{'brand_score'};
      // var_dump($obj3);

      // echo $response_analyzer;
      // $result_analyze = file_get_contents($url_analyze);

      ?>

      <div class="table">
        <div class="dataSim">
          <table class="table table-hover table-danger">
            <h4 style="color:white;">Similarity User</h4>

            <thead>
              <tr>
                <th>User Id</th>
                <th>Similar User Id</th>
                <th>Skor Similarity</th>
              </tr>

            </thead>
            <?php
            $html = '<tbody>';
            foreach ($obj1 as $key1 => $value1) {
              foreach ($value1 as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                  foreach ($value3 as $key4 => $value4) {
                    $html .= '<tr>';
                    $html .= '<td>' . $key2 . '</td>';
                    $html .= '<td>' . $key4 . '</td>';
                    $html .= '<td>' . $value4 . '</td>';
                    $html .= '</tr>';
                  }
                }
              }
            }
            $html .= '</tbody>';

            echo $html;

            //key2 = 77
            //key4 = similar id
            //value4 = score similarity

            ?>


        </div>

        <div class="scoreBrand">
          <table class="table table-hover table-danger">
            <h4 style="color:white;">Brand</h4>
            <thead>
              <tr>
                <td>No</td>
                <td>Nama Merek</td>
              </tr>
            </thead>
            <?php

            $html = '<tbody>';
            foreach ($obj2 as $key1 => $value1) {
              foreach ($value1 as $key2 => $value2) {
                // foreach ($obj3 as $key3 => $value3) {
                $html .= '<tr>';
                $html .= '<td>' . $key2 . '</td>';
                $html .= '<td>' . $value2 . '</td>';
                $html .= '</tr>';
                // }
              }
            }
            $html .= '</tbody>';

            echo $html;
            ?>

          </table>
          <table class="table table-hover table-danger">
            <thead>
              <h4 style="color:white;">Brand Score</h4>
              <tr>
                <th>ID</th>
                <th>Skor Merek</th>
              </tr>
            </thead>
            <?php
            $html = '<tbody>';
            foreach ($obj3 as $key1 => $value1) {
              foreach ($value1 as $key2 => $value2) {
                $html .= '<tr>';
                $html .= '<td>' . $key2 . '</td>';
                $html .= '<td>' . $value2 . '</td>';
                $html .= '</tr>';
              }
            }
            $html .= '</tbody>';
            echo $html;
            ?>
          </table>

          <h5 class="copyright text-center">&copy<script>
              document.write(new Date().getFullYear())
            </script> 825180011 - Davin Sebastian</h5>

        </div>
      </div><!-- class_table -->

    </div>
  </div>

  </div>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>

</html>