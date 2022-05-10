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
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>My Movie List</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
        <link href="css/stylesheet.css" rel="stylesheet" />

</head>

<body id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
            <div class="container">
                <!--<a class="navbar-brand" href="#page-top"><img src="assets/img/navbar-logo.svg" alt="..." /></a>-->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">

                    <i class="fas fa-bars ms-1"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav text-uppercase ms-auto py-4 py-lg-0">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="movies.php">Movies</a></li>
                        <li class="nav-item"><a class="nav-link" href="signin.html">My Account</a></li>
                        <li class="nav-item"><a class="nav-link" href="favorites.php">My Favorites</a></li>
                        <li class="nav-item"><a class="nav-link" href="signout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Page Description -->
        <header class="masthead" style="background-image: url('images/header.jpg');">
            <div class="container">
                <div class="masthead-heading text-uppercase">MOVIES</div>
                <div class="masthead-subheading">Search and discover movie titles. <br><br> Add your favorites to your account for easy access.</div>
            </div>
        </header>

          <!--Search field-->
          <div class="movieContainer">
            <!-- (A) SEARCH FORM -->
            <form method="post" action="">
              <h1>SEARCH FOR MOVIE TITLES</h1>
              <input type="text" name="search" placeholder="Title" required/><br/>
              <button class="submitButton" type="submit" value="search">SUBMIT</button>
            </form>
          </div>

            <div style="align-items:center; justify-content:center; display:flex; background-color:#f7f9fa; padding-bottom:100px;">
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
                        }
                        catch(PDOException $ex){
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


                    if( isset($json['errorMessage'] ) ){
                        echo "Your search returned an error. Please refer to the message below: <br><br>";
                        echo "Error message: " . $json['errorMessage'] . "<br><br>";
                        echo "Please try at a later time or contact the site admin";
                    }
                    else{

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
                }
                ?>
          </div>


        <!-- Footer-->
        <footer class="footer py-4">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-4 text-lg-start">Copyright &copy; My Movie List 2022</div>
                    <div class="col-lg-4 my-3 my-lg-0">
                        <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a class="link-dark text-decoration-none me-3" href="#!">Privacy Policy</a>
                        <a class="link-dark text-decoration-none" href="#!">Terms of Use</a>
                    </div>
                </div>
            </div>
        </footer>
 <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <!-- * *                               SB Forms JS                               * *-->
        <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
    </body>

</html>
