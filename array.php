<?php
$car = array(  // ⚠️ your array variable is named `$car`
    array("volvo",22,18),
    array("tesla",22,18),
    array("BMW",22,18),
    array("LandRover",17,15),
    
);

for ($row = 0; $row < 4; $row++) 
{
  echo "<p><b>Row number $row</b></p>";
  echo "<ul>";
  for ($col = 0; $col < 3; $col++) {
    echo "<li>".$car[$row][$col]."</li>"; 
  }
  echo "</ul>";
}
?>
