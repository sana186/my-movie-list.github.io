<?php
$delete = $_GET['delete'];
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
   header("Location:delete.php");
   exit;
} else
 
foreach($textFile as $key => $val) {
$line = @$line . $val . "<a href =?delete=$key> Delete </a><br />";
}
echo $line;
?>
