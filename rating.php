<!doctype html>
<html lang="en">

<?php


// $has_submitted = false;

ob_start();
session_start();

if (!isset($_SESSION['access_token'])) {
  header("Location: login.php");
}
// if (isset($_POST['user_id'])) {

//   $user_id = $_POST['user_id'];
//   $url_get_user = "http://127.0.0.1:8000/api/authentication/user_id";

//   $data_get_user = array(
//     'user_id' => $user_id
//   );

//   $headers_get_user = array(
//     'Content-Type: application/x-www-form-urlencoded',
//     'Content-Length: ' . strlen(http_build_query($data_get_user))
//   );

//   $options_get_user = array(
//     'http' => array(
//       'header' => implode("\r\n", $headers_get_user),
//       'method' => 'POST',
//       'content' => http_build_query($data_get_user),
//       'Authorization' => "Bearer <$_SESSION.['access_token']>"
//     )
//   );

//   $context_get_user  = stream_context_create($options_get_user);
//   $result_get_user = file_get_contents($url_get_user, false, $context_get_user);

//   $response_get_user = json_decode($result_get_user);

//   $token_a = $response_get_user->access_token;
//   $token_r = $response_get_user->refresh_token;

//   $_SESSION['access_token'] = $token_a;
//   $_SESSION['refresh_token'] = $token_r;
// }



if (isset($_POST['save'])) {

  $merk1 = $_POST['merk_id1'];
  $merk2 = $_POST['merk_id2'];
  $merk3 = $_POST['merk_id3'];
  $merk4 = $_POST['merk_id4'];
  $merk5 = $_POST['merk_id5'];

  $url_ratings = "http://127.0.0.1:8000/api/ratings/";
  $data_ratings = array(
    'user_id' => $_SESSION['user_id'],
    'merk_id1' => $merk1, 'merk_id2' => $merk2,
    'merk_id3' => $merk3, 'merk_id4' => $merk4, 'merk_id5' => $merk5
  );
  // var_dump($data_ratings);

  $headers_ratings = array(
    'Content-Type: application/x-www-form-urlencoded',
    'Content-Length: ' . strlen(http_build_query($data_ratings)),
    'Authorization' => "Bearer " . $_SESSION['access_token']
  );

  $options_ratings = array(
    'http' => array(
      'header' => implode("\r\n", $headers_ratings),
      'method' => 'POST',
      'content' => http_build_query($data_ratings),
    )
  );

  error_reporting(0);

  $context_ratings  = stream_context_create($options_ratings);
  $result_ratings = file_get_contents($url_ratings, false, $context_ratings);
  // var_dump($result_ratings);

  // echo $response;  
  if ($result_ratings === FALSE) { /* Handle error */
    // if (http_response_code(400)) {
    // die('you already submitted!');
    $message = "You already submitted!";
    // trigger_error($message, E_USER_WARNING);
    // echo "<script type='text/javascript'>alert('$message');</script>" . $http_response_header[0];
    echo "<script type='text/javascript'>alert('$message');</script>";
    // }
  }
  // var_dump($result_ratings);


}

if (isset($_POST['edit'])) {

  $merk1 = $_POST['merk_id1'];
  $merk2 = $_POST['merk_id2'];
  $merk3 = $_POST['merk_id3'];
  $merk4 = $_POST['merk_id4'];
  $merk5 = $_POST['merk_id5'];

  $url_ratings_update = "http://127.0.0.1:8000/api/ratings_edit/" . $_SESSION['user_id'] . "/";
  $data_ratings_update = array(
    'user_id' => $_SESSION['user_id'],
    'merk_id1' => $merk1, 'merk_id2' => $merk2,
    'merk_id3' => $merk3, 'merk_id4' => $merk4, 'merk_id5' => $merk5
  );
  // var_dump($data_ratings_update);

  $headers_ratings_update = array(
    'Content-Type: application/x-www-form-urlencoded',
    'Content-Length: ' . strlen(http_build_query($data_ratings_update)),
    'Authorization' => "Bearer " . $_SESSION['access_token']
  );

  $options_ratings_update = array(
    'http' => array(
      'header' => implode("\r\n", $headers_ratings_update),
      'method' => 'PUT',
      'content' => http_build_query($data_ratings_update),
    )
  );

  $context_ratings_update  = stream_context_create($options_ratings_update);
  $result_ratings_update = file_get_contents($url_ratings_update, false, $context_ratings_update);
  // var_dump($result_ratings_update);

  if ($result_ratings_update === FALSE) { /* Handle error */
    // $message = "You already submitted!";
    // die('you already submitted!');
    echo "<script type='text/javascript'>alert('$message');</script>";
  }
}

if (isset($_POST['analyze'])) {

  header("Location: rekomendasi.php");
}


?>

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <!-- custom css -->
  <link rel="stylesheet" href="rating.css">
  <!-- <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> -->

  <title>Rating Smartphone</title>

</head>

<body>
  <div class="global-container">
    <div class="card rating-form">
      <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">

          <div class="main">
            <a class="nav-link active" aria-current="page" href="main.php">
              <h1 class="card-title text-left" href="main.php">GawaiKu</h1>
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
      <h4 class="judul_rating">Rating Merek Smartphone:</h4>

      <form method="POST">
        <!--penggunaan maxlength="1", artinya batas input max = 1-->
        <!--required ="", kolom tidak boleh kosong-->
        <div class="form-group row">
          <label for="xiaomi" class="col-sm-2 col-form-label">Xiaomi</label>
          <div class="col-sm-10">
            <input type="hidden" name="merk_id1" value="0">
            <label><input type="radio" id="xiaomi" name="merk_id1" value="1"> 1</label>
            <label><input type="radio" id="xiaomi" name="merk_id1" value="2"> 2</label>
            <label><input type="radio" id="xiaomi" name="merk_id1" value="3"> 3</label>
            <label><input type="radio" id="xiaomi" name="merk_id1" value="4"> 4</label>
            <label><input type="radio" id="xiaomi" name="merk_id1" value="5"> 5</label>
            <!--for dan id harus sama-->
          </div>
        </div>

        <div class="form-group row">
          <label for="realme" class="col-sm-2 col-form-label">Realme</label>
          <div class="col-sm-10">
            <input type="hidden" name="merk_id2" value="0">

            <label><input type="radio" id="realme" name="merk_id2" value="1"> 1</label>
            <label><input type="radio" id="realme" name="merk_id2" value="2"> 2</label>
            <label><input type="radio" id="realme" name="merk_id2" value="3"> 3</label>
            <label><input type="radio" id="realme" name="merk_id2" value="4"> 4</label>
            <label><input type="radio" id="realme" name="merk_id2" value="5"> 5</label>
            <!--for dan id harus sama-->
          </div>
        </div>

        <div class="form-group row">
          <label for="samsung" class="col-sm-2 col-form-label">Samsung</label>
          <div class="col-sm-10">
            <input type="hidden" name="merk_id3" value="0">
            <label><input type="radio" id="samsung" name="merk_id3" value="1"> 1</label>
            <label><input type="radio" id="samsung" name="merk_id3" value="2"> 2</label>
            <label><input type="radio" id="samsung" name="merk_id3" value="3"> 3</label>
            <label><input type="radio" id="samsung" name="merk_id3" value="4"> 4</label>
            <label><input type="radio" id="samsung" name="merk_id3" value="5"> 5</label>
            <!--for dan id harus sama-->
          </div>
        </div>

        <div class="form-group row">
          <label for="vivo" class="col-sm-2 col-form-label">Vivo</label>
          <div class="col-sm-10">
            <input type="hidden" name="merk_id4" value="0">
            <label><input type="radio" id="vivo" name="merk_id4" value="1"> 1</label>
            <label><input type="radio" id="vivo" name="merk_id4" value="2"> 2</label>
            <label><input type="radio" id="vivo" name="merk_id4" value="3"> 3</label>
            <label><input type="radio" id="vivo" name="merk_id4" value="4"> 4</label>
            <label><input type="radio" id="vivo" name="merk_id4" value="5"> 5</label>
            <!--for dan id harus sama-->
          </div>
        </div>

        <div class="form-group row">
          <label for="oppo" class="col-sm-2 col-form-label">Oppo</label>
          <div class="col-sm-10">
            <input type="hidden" name="merk_id5" value="0">
            <label><input type="radio" id="oppo" name="merk_id5" value="1"> 1</label>
            <label><input type="radio" id="oppo" name="merk_id5" value="2"> 2</label>
            <label><input type="radio" id="oppo" name="merk_id5" value="3"> 3</label>
            <label><input type="radio" id="oppo" name="merk_id5" value="4"> 4</label>
            <label><input type="radio" id="oppo" name="merk_id5" value="5"> 5</label>
            <!--for dan id harus sama-->
          </div>
        </div>


        <div class="button">
          <!-- <div class="d-grid gap-2"> -->
          <input type="submit" class="btn btn-primary" name="save" value="Save"></button>
          <input type="submit" class="btn btn-primary" name="edit" value="Edit"></button>

        </div>
      </form>

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
          <tbody>
            <?php
            // untuk get data

            $url_get = "http://127.0.0.1:8000/api/ratings_data/" . $_SESSION['user_id'] . "/";
            error_reporting(0);
            $results_get = file_get_contents($url_get);
            // var_dump($results_get);

            if ($results_get === FALSE) { /* Handle error */
              $message = "error, tidak ada data atau anda belum memasukkan data rating!";
              // die('you already submitted!');
              echo "<script type='text/javascript'>alert('$message');</script>";
            } else {
              $data_get = json_decode($results_get);
              // var_dump($results_get);
              // echo '.<br>';
              // var_dump($data_get);
              // echo $data_get->user_id . '<br>';
              // echo $data_get->merk_id1;

            ?>

              <tr>
                <td><?php echo $data_get->user_id; ?></td>
                <td><?php echo $data_get->merk_id1; ?></td>
                <td><?php echo $data_get->merk_id2; ?></td>
                <td><?php echo $data_get->merk_id3; ?></td>
                <td><?php echo $data_get->merk_id4; ?></td>
                <td><?php echo $data_get->merk_id5; ?></td>
              </tr>

            <?php
            }

            ?>




          </tbody>


        </table>
      </div>
      <!-- table -->

      <div class="analyze">
        <a style="text-decoration:none" href="rekomendasi.php">Analyze</a>
      </div>


      <h5 class="copyright text-center">&copy<script>
          document.write(new Date().getFullYear())
        </script> 825180011 - Davin Sebastian</h5>

    </div> <!-- middle -->

  </div> <!-- global container -->
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>

</html>