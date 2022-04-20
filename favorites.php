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
<a href="index.html">Home</a>
  <a href="movie.php">Movies</a>
  <a href="signup.html">My Account</a>
  <a href="favorites.php">My Favorites</a>
</div>
<!-- Navigation 
<nav class="w3-bar w3-light-blue">
  <a href="#home" class="w3-button w3-bar-item">Home</a>
  <a href="#band" class="w3-button w3-bar-item">Movies</a>
  <a href="#tour" class="w3-button w3-bar-item">Genres</a>
  <a href="#contact" class="w3-button w3-bar-item">Actors</a>
  <a href="#contact" class="w3-button w3-bar-item">My Account</a>
  <a href="#contact" class="w3-button w3-bar-item">My Favorites</a>
</nav>-->

<!-- Website Description -->
<section class="w3-container w3-center w3-content" style="max-width:600px">
  <h2 class="w3-wide">MY MOVIE LIST</h2>
  <p class="w3-justify">My Movie List is an all-in-one, cross-platform service for tracking the movies you’ve watched, enjoyed, and wish to know more about.
</p>
</section>
<section class="w3-container w3-center w3-content" style="max-width:600px">
<?php
// (B) PROCESS SEARCH WHEN FORM SUBMITTED
// NOTE: API keys for IMDb allow 100 free requests per day - if maximum is reached, switch to another API key.
// Josef - k_u83w1u0o
// Connor - k_1nw7v1rh

if (isset($_GET['delete'])){
  $delete = $_GET['delete'];
}
else{
  $delete = "";
}
$file_name = "favorites.txt";
$size = filesize($file_name);
$textFile = file($file_name);
$lines = count($textFile);
if($size == "0")
{
echo "Nothing to display, Seems like file is empty. Try adding some data to it!!!";
exit;
}
if($delete != "" && $delete >! $lines || $delete === '0') {
    $textFile[$delete] = "";
    $fileUpdate = fopen($file_name, "wb");
    for($a=0; $a< $lines; $a++) {
           fwrite($fileUpdate, $textFile[$a]);
    }
    fclose($fileUpdate);
   header("Location:favorites.php");
   exit;
}

?>
<form method="post" action="">

<table border='1' padding-top='50px'>
        <tr>
          <th>Picture</th> 
          <th>Title</th>
          <th>Year</th>
          <th>Add to favorites</th>
        </tr>
<?php
 
foreach($textFile as $key => $val) {
  $movie = explode(",", $val);

  $curl = curl_init();

  $link = trim("https://imdb-api.com/en/API/Title/k_1nw7v1rh/". $movie[1]. "");

  curl_setopt_array($curl, array(
    CURLOPT_URL => $link,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
  ));
  
  $response = curl_exec($curl);
  
  curl_close($curl);
  //echo $response;

  $response_json = json_decode($response, true);

  $image = $response_json['image'];
  $title = $response_json['title'];
  $year = $response_json['year'];


  echo "<tr>";
  $line = @$line .  "<td width='200' height='100'>" . "<img width='50' src=" . $image . ">" . "</td>" .
                    "<td width='200' height='100'>" . $title . "</td>" .
                    "<td width='200' height='100'>" . $year . "</td>" . 
                    "<td width='200' height='100'>" . "<a href =?delete=$key> Delete </a><br />" . "</td></tr>";
}

echo $line;
echo "</table>";


//$file = file("favorites.txt");



/*
foreach($line as $file){
  $movie = explode(",", $file);

  $movie = explode(",", $line);
  $curl = curl_init();

  $link = trim("https://imdb-api.com/en/API/Title/k_u83w1u0o/". $movie[1]. "");

  curl_setopt_array($curl, array(
    CURLOPT_URL => $link,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
  ));
  
  $response = curl_exec($curl);
  
  curl_close($curl);
  //echo $response;

  $response_json = json_decode($response, true);
  echo "<tr>";
  echo "<td width='200'>" . "<img width='50' src=" . $response_json['image'] . ">". "</td>";
  echo "<td width='200'>" . $response_json['title']. "</td>";
  echo "<td width='200'>" . $response_json['year'] . "</td>";
  echo "<td width='200'>  <input type='submit' name='remove' value='remove'> <input type='hidden' id='removeMovie' name='removeMovie' value=''> </td>";
  echo "</tr>";
*/
    ?>
  
  </form>

  <?php
//}
?>
</section>
<!--Footer-->

<footer   height: 50px; margin-top: -50px;class="w3-container w3-padding-64 w3-center w3-blue w3-xlarge">
<p> Team 2: My Movie List </p>
</footer>
</body>

</html>
<!--<section class="slideshow-container">

<section class="images fade">
  <section class="numbertext">1 / 3</div>
  <img src="lostcity.jpg" style="width:50%">
  <section class="text">The Lost City</div>
</section>

<section class="images fade">
  <section class="numbertext">2 / 3</div>
  <img src="batman.jpg" style="width:50%">
  <section class="text">The Batman</div>
</section>

<section class="images fade">
  <section class="numbertext">3 / 3</div>
  <img src="uncharted.jpg" style="width:50%">
  <section class="text">Uncharted</div>
</section>

</section>
<br>

<div style="text-align:center">
  <span class="dot"></span> 
  <span class="dot"></span> 
  <span class="dot"></span> 
</div> -->
