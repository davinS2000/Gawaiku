<!doctype html>
<html lang="en">
<?php

session_start();


if (isset($_POST['cancel'])) {
  header('location:login.php');
}

if (isset($_POST['submit'])) {

  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  $url = "http://127.0.0.1:8000/api/authentication/register/";
  $data = array(
    'name' => $name,
    'email' => $email,
    'password' => $password
  );

  $headers = array(
    'Content-Type: application/x-www-form-urlencoded',
    'Content-Length: ' . strlen(http_build_query($data))
  );



  // use key 'http' even if you send the request to https://...
  $options = array(
    'http' => array(
      'header'  => implode("\r\n", $headers),
      'method'  => 'POST',
      'content' => http_build_query($data)
    )
  );
  error_reporting(0);
  $context  = stream_context_create($options);
  $result = file_get_contents($url, false, $context);

  // var_dump($result);
  // echo $response;

  if ($result === FALSE) {
    $message = "email sudah terpakai atau password harus > 8!";
    echo "<script type='text/javascript'>alert('$message');</script>"; /* Handle error */
  } else {
    header('location:login.php');
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
  <link rel="stylesheet" href="register.css">

  <title>Register</title>

</head>

<body>
  <div class="global-container">

    <div class="card login-form">
      <div class="card-body">
        <h1 class="card-title text-center">R E G I S T E R</h1>
      </div>

      <div class="card-text">
        <form method="POST" class="user">

          <div class="mb-3">
            <label class="form-label">Nama</label>
            <input for="exampleInputName" type="text" class="form-control" id="exampleInputName" name="name" placeholder="Nama">
          </div>


          <div class=" mb-3">
            <label class="form-label">Email</label>
            <input for="exampleInputEmail1" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email" placeholder="Email harus mengandung '@'">
          </div>

          <div class="mb-3">
            <label class="form-label">Password</label>
            <input for="exampleInputPassword1" type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="minimal 8 karakter">
          </div>

          <div class="button">
            <!-- <div class="d-grid gap-2"> -->
            <input type="submit" class="btn btn-primary" name="submit" value="submit"></input>
            <input type="submit" class="btn btn-secondary" name="cancel" value="cancel"></input>

          </div>

        </form>

      </div>
    </div>



  </div>

</body>


</html>