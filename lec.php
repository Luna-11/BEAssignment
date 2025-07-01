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

    for($row=0;$row<=4;$row++){
    for($space=5;$space>$row;$space--){
        echo "&nbsp;&nbsp;";
    }
    for($first=1;$first<=$row;$first++){
        echo "2";
    }
    for($i=1;$i<=1;$i++){
        echo $i;
    }
    for($first=1;$first<=$row;$first++){
        echo "2";
    }
    echo "<br>";
}
echo "<br>";

for($row = 1; $row <= 5; $row++) {
   
    for($space = 5; $space > $row; $space--) {
        echo "&nbsp;&nbsp;";
    }

    for($num = $row; $num >= 1; $num--) {
        echo $num;
    }

    for($num = 2; $num <= $row; $num++) {
        echo $num;
    }

    echo "<br>";
    }
    }
 echo "<br>"; echo "<br>";

$num=5;
$total =1;
echo $num."!=";
for ( $i = $num; $i >=1; $i--)
    {
        $total = $total*$i;
        echo $i;
        if ($i>1)
        {
            echo "x";
        }
    }
    echo "= ";
    echo $total;

echo "<br>";
$a=0;
$b=1;
$to=0;
echo " Fibonacci sequence: ";
echo $a."&nbsp;".$b."&nbsp;";
for ($i =1; $i <=10; $i++)
{
    $to=$a+$b;
    $a=$b;
    $b=$to;

    echo $to."&nbsp;";    
}

$num = 5;
$count =0;
for($j=2; $j>$num; $j++)
{
    if($num % $j == 0)
    {
        $count++;
    } 
}
if($count !=0)
{
    echo " this is not prime";
}
else
{
    echo " this is prime";
}

for($i =0; $i<10; $i++)
{
    if($i ==4)
    break;
    echo " this is end";
}


echo "<br>";
echo "<br>";

$input=23567;
$tot=0;
while($input>0)
{
    $tot=($input%10)+$tot;
    $input= $input/10;
}
echo $tot;



echo "<br>";
echo "<br>";



?>