<?php

$sub1 = 45;
$sub2 = 40;
$sub3 = 40;
$total = ( $sub1+$sub2+$sub3);

if($total/3 >=80)
  {
    echo "A";
  }
else if($total/3 >=60)
  {
    echo "B";
  }
else if($total/3 >= 50)
  {
    echo "C";
  }
else
  {
    echo "D";
  }

?> 