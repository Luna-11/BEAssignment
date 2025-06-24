<?php
for($j=0; $j<7 ; $j++)
{
    for($num= 1; $num < $j; $num++)
    {
      echo $num;
    }

    echo "<br>";
}
echo "<br>";
echo "<br>";

for($j=1; $j<6 ; $j++)
{
    for($num= 1; $num <= $j; $num++)
    {
      echo $j;
    }

    echo "<br>";
}
echo "<br>";
echo "<br>";

for($j=1; $j<6 ; $j++)
{
    for($space=5; $space < 1; $space--)
    {
        echo"&nbsp;";
    }
    for($num= 1; $num <= $j; $num++)
    {
      echo "1";
    }
    for($n2= 1; $n2 <= $j; $n2++)
    {
      echo "2";
    }

    echo "<br>";
}

echo "<br>";
echo "<br>";


//nested loop 12345iv
$n = 5;  // number of rows
for ($row = 1; $row <= $n; $row++) {
    // 1. Leading spaces
    for ($space = $n - $row; $space > 0; $space--) {
        echo "&nbsp;"; 
    }

    // 2. Print the '2' block before the '1'
    for ($i = 1; $i <= $row - 1; $i++) {
        echo "2";
    }

    // 3. Print the '1'
    echo "1";

    // 4. Print the '2' block after the '1'
    for ($i = 1; $i <= $row - 1; $i++) {
        echo "2";
    }

    echo "<br>";
}

?>