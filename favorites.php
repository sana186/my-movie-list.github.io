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

<!-- Website Description -->
<section class="w3-container w3-center w3-content" style="max-width:600px">
  <h2 class="w3-wide">MY FAVORITES</h2>
  <p text-align="center">Here is a list of your current favorites. <br><br> You cann add more movies over the movies link</p>
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
<?php
 
foreach($textFile as $key => $val) {

  $movie = explode(",", $val);


  $jsonFile = file_get_contents("cache.json");
  $movieJSON = json_decode($jsonFile);

  foreach($movieJSON as $movieCache => $values) {
    if($movieCache == trim($movie[1])){
      $titleCache = $values->Title;
      $yearCache = $values->Description;
      $imageCache = $values->Image;

      $line = @$line .  "<table border='1'><tr><td width='200' height='100'>" . "<img width='50' src=" . $imageCache . ">" . "</td>" .
                    "<td width='200' height='100'>" . $titleCache . "</td>" .
                    "<td width='200' height='100'>" . $yearCache . "</td>" . 
                    "<td width='200' height='100'>" . "<a href =?delete=$key> Delete </a><br />" . "</td></tr></table>";
    echo $line; 
    }
  }

    ?>
  
  </form>

  <?php
}
?>
</section>
<!--Footer-->

<footer   height: 50px; margin-top: -50px;class="w3-container w3-padding-64 w3-center w3-blue w3-xlarge">
<p> Team 2: My Movie List </p>
</footer>
</body>

</html>