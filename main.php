<!doctype html>
<html lang="en">

<?php
//untuk mengarahkan ke login.php kalau belum login (session belum aktif)
ob_start();
session_start();



// $url = "http://127.0.0.1:8000/api/authentication/";

// $response = file_get_contents($url);

// $response_data = json_decode($response, true);

// var_dump($response_data);

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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- custom css -->
  <link rel="stylesheet" href="main.css">

  <title>Main</title>

</head>

<body>
  <div class="global-container">
    <div class="card main-form">
      <!-- <div class="card-body">
          <h1 class="card-title text-left">M A I N</h1>
        </div> -->


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
                  <a class="nav-link active" aria-current="page" href="#">Home</a>
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

      <h1 class="judul">Aplikasi Rekomendasi Smartphone</h1>
      <br>
      <h4 class="judul2">Welcome!</h4>
      <br>
      <p class="paragraf">GawaiKu merupakan sebuah website yang akan memberikan rekomendasi merek smartphone,<br> berdasarkan rating user lain yang menggunakan metode Collaborative Filtering.</p>
      <h5 class="copyright text-center">&copy<script>
          document.write(new Date().getFullYear())
        </script> 825180011 - Davin Sebastian</h5>


    </div>


  </div> <!-- global container -->

</body>
<?php

//END OB

// mysqli_close($connection);
// session_destroy();
ob_end_flush();

?>

</html>