<?php
    $a=$_POST['liczba1'];
    $b=$_POST['liczba2'];
    $dodawanie=$a+$b;
    echo $dodawanie,"<br>";
    $odejmowanie=$a-$b;
    echo $odejmowanie,"<br>";
    $mnozenie=$a*$b;
    echo $mnozenie,"<br>";
    if($_POST['liczba2'] == 0){ echo "Nie dziel przez 0";}
        else {
        $dzielenie = $a / $b;
        echo $dzielenie,"<br>";
        }
?>