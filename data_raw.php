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
  <link rel="stylesheet" href="data_raw.css">

  <title>Data Rating</title>

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
              <!-- <form class="d-flex" role="search">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                      </form> -->
            </div>
          </div>
        </div>
      </nav>

    </div> <!-- main form -->

    <div class="middle">
      <h4 style="padding-bottom:20px;" class="judul_rek text-center">Data Rating</h4>

      <div class="data">
        <table class="table table-hover table-danger">

          <thead class="thead-dark">
            <tr>
              <th>User Id</th>
              <th>xiaomi</th>
              <th>realme</th>
              <th>samsung</th>
              <th>vivo</th>
              <th>oppo</th>
            </tr>
          </thead>

          <?php

          $url_all = "http://127.0.0.1:8000/api/ratings/";

          $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

          $per_page = 25;

          $start = ($page - 1) * $per_page;

          $result_all = file_get_contents($url_all);

          error_reporting(0);

          // $result_all = file_get_contents($url_ratings, false, $context_all);

          if ($result_ratings === FALSE) {

            $message = "error, tidak ada data!";
            echo "<script type='text/javascript'>alert('$message');</script>";
          } else {

            $ratings_get = json_decode($result_all);

            $page_data = array_slice($ratings_get, $start, $per_page);

            // $no = 1;
            $table = '<tbody>';
            foreach ($page_data as $row) {
              $table .= '<tr>';
              $table .= '<td>' . $row->user_id . '</td>';
              $table .= '<td>' . $row->merk_id1 . '</td>';
              $table .= '<td>' . $row->merk_id2 . '</td>';
              $table .= '<td>' . $row->merk_id3 . '</td>';
              $table .= '<td>' . $row->merk_id4 . '</td>';
              $table .= '<td>' . $row->merk_id5 . '</td>';
              $table .= '</tr>';
            }

            $table .= '</tbody>';

            echo $table;

            $total_records = count($ratings_get);
            $total_pages = ceil($total_records / $per_page);

            if ($total_pages > 1) {
              // echo '<div class="pagination>';
              echo '<nav aria-label="Page navigation example">';
              echo '<ul class="pagination">';
              for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $page) {

                  // echo '<span>' . $i . '</span>';
                  echo '<li class="page-item active"><a class="page-link" href="#">' . $i . '</a></li>';
                } else {
                  // echo '<a href="? page=' . $i . '">' . $i .  '</a>';
                  echo '<li class="page-item"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                }
              }
            }
            echo '</ul>';
            echo '</nav>';
          }

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