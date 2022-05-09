<?php
session_start();


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
  <h2 class="w3-wide">MY MOVIE LIST</h2>
  <p class="w3-justify">My Movie List is an all-in-one, cross-platform service for tracking the movies you’ve watched, enjoyed, and wish to know more about.
</p>
</section>
<!--Search field-->
<section class="w3-container w3-center w3-content" style="max-width:600px">
<!-- (A) SEARCH FORM -->
<form method="post" action="">
  <h1>SEARCH FOR MOVIE TITLES</h1>
  <input type="text" name="search" required/>
  <input type="submit" value="search"/>
</form>

<?php
// (B) PROCESS SEARCH WHEN FORM SUBMITTED
// NOTE: API keys for IMDb allow 100 free requests per day - if maximum is reached, switch to another API key.
// Josef - k_u83w1u0o
// Connor - k_1nw7v1rh
if (isset($_POST["add"])){

  if(isset($_SESSION['user'])){
		$user = $_SESSION['user']; 
    $movieName = $_POST["movieName"];
		$movieImage = $_POST["movieImage"];
		$movieDescription = $_POST["movieDescription"];
    $movieID = $_POST['addMovie'];

    $db = null;

    try{
        //$db = new pdo('mysql:host=35.229.81.68;port=3306;dbname=guestbook', 'test', 'test');
        $db = new pdo('mysql:host=35.229.81.68:3306;dbname=guestbook',
          'test',
          'test'
        );
    }catch(PDOException $ex){
        echo $ex->getMessage();
    }

    $stmt = $db->prepare("SELECT count(*) FROM favorites WHERE userName = ? and userFavorite = ?");
    $stmt->execute(array($user, $movieID));

    $count = $stmt->fetchColumn();

    if ($count > 0) {
      echo "<br>";
      echo "<br>";
      echo "Movie already in your favorites.";
      echo "<br>";
      echo "<br>";
    } else {
      $stmt = $db->prepare('INSERT INTO favorites (userName, userFavorite) VALUES (:name, :content)');
      $stmt->execute(array(':name' => htmlspecialchars($user), ':content' => htmlspecialchars($_POST['addMovie'])));
      $affected_rows = $stmt->rowCount();
    }

    $stmt = $db->prepare("SELECT * FROM cache Where movieID = ?;");
    $stmt->execute(array($movieID));

    $count = $stmt->fetchColumn();

    if ($count > 0) {
    } 
    else {
    $stmt = $db->prepare('INSERT INTO cache (movieID,movieTitle,movieImage, movieYear ) VALUES (:movieID, :movieTitle, :movieImage, :movieYear)');
    $stmt->execute(array(':movieID' => htmlspecialchars($_POST['addMovie']), ':movieTitle' => htmlspecialchars($movieName),':movieImage' => htmlspecialchars($movieImage),':movieYear' => htmlspecialchars($movieDescription) ));
    $affected_rows = $stmt->rowCount();   
    echo 'Movie has been added to favorites!';
    }
    $db = null;

    }
	else{
		echo 'Please sign in first in order to add movies to your favorites!';
	}
}


if (isset($_POST['search'])) {

    $searchString = str_replace(" ", "%20" ,$_POST["search"]); 
    $json=file_get_contents("https://imdb-api.com/en/API/SearchTitle/k_u83w1u0o/" . $searchString . "");
    $json = json_decode($json,true);
    $i = 0;

?>

 
  <table border='1' padding-top='50px'>
        <tr>
          <th>Picture</th> 
          <th>Title</th>
          <th>Year</th>
          <th>Add to favorites</th>
        </tr>

        <?php
    while ($i < count($json)){
        if ($i === 0 || ($i % 2 == 0)){
            $yearJSON = (int) filter_var($json['results'][$i]['description'], FILTER_SANITIZE_NUMBER_INT);
            $movieNameJSON = $json['results'][$i]['title'];
            echo "<tr>";
            echo "<td width='200' height='100'>  " . "<img width='50' src=" . $json['results'][$i]['image'] . ">". "</td>";
            echo "<td width='200' height='100'>  " . $movieNameJSON . "</td>";
            echo "<td width='200' height='100'>  " . $yearJSON . "</td>";
            echo "<td width='200' height='100'> <form method='post' action=''>  <input type='submit' name='add' value='add'>
                                                                      </td> <input type='hidden' id='movieName' name='movieName' value=" . str_replace(" ", "_" ,$movieNameJSON) . ">
                                                                            <input type='hidden' id='movieDescription' name='movieDescription' value=" . $yearJSON . ">
                                                                            <input type='hidden' id='movieImage' name='movieImage' value=" . $json['results'][$i]['image'] . ">
                                                                            <input type='hidden' id='addMovie' name='addMovie' value=" . $json['results'][$i]['id'] . "></form></td>";
            echo "<tr>";
        }
        $i++;
    }
        ?>
  </table>
  
<?php  
}
?>
</section>
<!--Footer-->

<footer class="w3-container w3-padding-64 w3-center w3-blue w3-xlarge">
<p> Team 2: My Movie List </p>
</footer>
</body>
</html>