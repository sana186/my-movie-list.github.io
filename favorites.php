<?php 
session_start();

var_dump($_SESSION);

require_once __DIR__ . '/vendor/autoload.php';

use google\appengine\api\users\User;
use google\appengine\api\users\UserService;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

# [START use_cloud_storage_tools]
use google\appengine\api\cloud_storage\CloudStorageTools;
use Google\Cloud\Storage\StorageClient;
# [END use_cloud_storage_tools]
use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Symfony\Component\HttpFoundation\Request;

// create the Silex application
$app = new Application();
$app->register(new TwigServiceProvider());
$app['twig.path'] = [ __DIR__ ];

$storage = new StorageClient();
$storage->registerStreamWrapper();
 
?>
<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<style>
body {
font-family: "Times New Roman", Times, serif;
font-weight: bolder;
font-size: medium;
}
* {
  box-sizing: border-box;
}

/* Style the search field */
form.example input[type=text] {
  padding: 10px;
  font-size: 17px;
  border: 1px solid grey;
  float: left;
  width: 80%;
  background: #f1f1f1;
}

/* Style the submit button */
form.example button {
  float: left;
  width: 20%;
  padding: 10px;
  background: #2196F3;
  color: white;
  font-size: 17px;
  border: 1px solid grey;
  border-left: none; /* Prevent double borders */
  cursor: pointer;
}

form.example button:hover {
  background: #0b7dda;
}

/* Clear floats */
form.example::after {
  content: "";
  clear: both;
  display: table;
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
</style>



<div class="main">
<body style="background-image: linear-gradient(#99d3f0, #318ebd)">
<div class="sidenav">
<a href="index.php">Home</a>
  <a href="movie.php">Movies</a>
  <a href="signin.html">My Account</a>
  <a href="favorites.php">My Favorites</a>
  <a href="signout.php">Logout</a>
</div>

<!-- Website Description -->
<section class="w3-container w3-center w3-content" style="max-width:600px">
  <h2 class="w3-wide">MY FAVORITES</h2>
  <p text-align="center">Here is a list of your current favorites. <br><br> You can add more movies over the movies link</p>
</section>
<section class="w3-container w3-center w3-content" style="max-width:600px">

<?php

if(isset($_SESSION['user'])){
    $userName=$_SESSION['user'];

    try{
      //$db = new pdo('mysql:host=35.229.81.68;port=3306;dbname=guestbook', 'test', 'test');
      $db = new pdo('mysql:host=35.229.81.68:3306;dbname=guestbook',
        'test',
        'test'
        );
    }catch(PDOException $ex){
        echo $ex->getMessage();
    }

    $stmt = $db->prepare("SELECT * from favorites, cache where favorites.userFavorite=cache.movieID and userName=?;");
    $stmt->execute(array($userName));
    $data = $stmt->fetchAll();

    echo "<table border='1' padding-top='50px'><tr><th>Picture</th><th>Title</th><th>Year</th></tr>";

    foreach ($data as $row) {
      $movieTitle = str_replace("_", "" ,$row['movieTitle']);
      echo "<tr><td width='200' height='50'><img width='50' src=" . $row['movieImage'] . "></td><td width='200' height='50'>".$movieTitle."</td><td width='200' height='50'>".$row['movieYear']."</td></tr>";
    }

    echo "</table>";
}
else{
    echo "Please sign in to see your favorites";
}

?>
</section>
<!--Footer-->

<footer   height: 50px; margin-top: -50px;class="w3-container w3-padding-64 w3-center w3-blue w3-xlarge">
<p> Team 2: My Movie List </p>
</footer>
</body>

</html>