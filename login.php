<!doctype html>
<html lang="en">

<?php

// include "include/config.php";
//start session
session_start();

if (isset($_SESSION['access_token'])) {
  header("Location: main.php");
}

if (isset($_POST['submit'])) {

  $email = $_POST['email'];
  $password = $_POST['password'];

  $url = "http://127.0.0.1:8000/api/authentication/login/";
  $data = array('email' => $email, 'password' => $password);

  $headers = array(
    'Content-Type: application/x-www-form-urlencoded',
    'Content-Length: ' . strlen(http_build_query($data))
  );

  $options = array(
    'http' => array(
      'header' => implode("\r\n", $headers),
      'method' => 'POST',
      'content' => http_build_query($data)

    )
  );
  error_reporting(0);
  $context  = stream_context_create($options);
  $result = file_get_contents($url, false, $context);



  if ($result === FALSE) { /* Handle error */
    // die('wrong email or pass!');
    $message = "email atau password salah!";
    echo "<script type='text/javascript'>alert('$message');</script>";
  } else {

    $_SESSION['user'] = $data['email'];
    $_SESSION['password'] = $data['password'];

    $response = json_decode($result);

    $token_a = $response->access_token;
    $token_r = $response->refresh_token;
    $user_id = $response->user_id;

    $_SESSION['access_token'] = $token_a;
    $_SESSION['refresh_token'] = $token_r;
    $_SESSION['user_id'] = $user_id;
    // echo $_SESSION['user_id']
    header("Location: main.php");
  }
}

?>

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <!-- custom css -->
  <link rel="stylesheet" href="login.css">

  <title>login</title>

</head>

<body>
  <div class="global-container">

    <div class="card login-form">
      <div class="card-body">
        <h1 class="card-title text-center">L O G I N</h1>
      </div>

      <div class="card-text">
        <form method="POST" class="user">
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Masukkan Email...">
          </div>

          <div class="mb-3">
            <label for="exampleInputPassword1" type="password" id="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Masukkan Password...">
          </div>

          <div class="d-grid gap-2">
            <input href="main.php" type="submit" class="btn btn-primary" name="submit" value="submit"></input>

            <a style="text-decoration:none" href="register.php">Register!</a>
          </div>

        </form>

      </div>
    </div>

  </div>

</body>

<?php
//end session
if (isset($result)) { /* Handle error */


  // header('location:main.php');
}

// session_unset();
// session_destroy();

?>

</html>