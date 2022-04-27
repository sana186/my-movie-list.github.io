<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create an Account</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">

    <style>
    body {
      font-family: "Times New Roman", Times, serif;
      font-weight: bolder;
      font-size: medium;
    }
    .sidenav {
      height: 100%;
      width: 200px;
      position: fixed;
      z-index: 1;
      top: 0;
      left: 0;
      background-color: #318ebd;
      overflow-x: hidden;
      border: 2px solid #111;
      padding-top: 20px;
    }

    .sidenav a {
      padding: 6px 6px 6px 32px;
      text-decoration: none;
      font-size: 25px;
      color: #111;
      display: block;
    }

    .sidenav a:hover {
      color: #f1f1f1;
    }

    .main {
      margin-left: 200px; /* Same as the width of the sidenav */
    }

    @media screen and (max-height: 450px) {
      .sidenav {padding-top: 15px;}
      .sidenav a {font-size: 18px;}
    }

    .container {
      background-image: linear-gradient(#99d3f0, #318ebd);
      padding: 500px;
    }

    </style>
</head>
<body style="background-image: linear-gradient(#99d3f0, #318ebd)">
  <div class="main">
    <!--Navigation Menu-->
    <div class="sidenav">
      <a href="index.php">Home</a>
      <a href="movie.php">Movies</a>
      <a href="signup.html">My Account</a>
      <a href="favorites.php">My Favorites</a>
      <a href="signout.php">Logout</a>
    </div>


    <?php
      session_start();
      session_unset();
      session_destroy();
      header("location:index.php");
    ?>
</body>
<footer class="w3-container w3-padding-64 w3-center w3-blue w3-xlarge">

</footer>
</html>
