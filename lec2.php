<?php
$input=234;
$total=0;
echo $input;
$count =0;
while($input>0)
{
    $i = $input %10;

    $total = ($total*10)+ $i;

    $input= floor($input/10);
    $count++;
}
echo "<br>";
echo $total;
echo "<br>";
echo $count;
echo "<br>";

for($row=1; $row <=12; $row++)
{
    echo"Multiplication for ", $row;
    echo "<br>";
    for($col=1; $col<=12; $col++)
    {
        echo $row * $col, "&nbsp;";
    }
    echo"<br>";
    echo"<br>";

}

?>