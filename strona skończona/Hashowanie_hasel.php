<?php

require_once "connect.php";

$polaczenie = @new mysqli($host,$db_user,$db_password,$db_name);

if($polaczenie->connect_errno!=0)
{
    echo "Error: ".$polaczenie->connect_errno;//." Opis ".$polaczenie->connect_error;
}
else
{
  $rezultat = $polaczenie->query("SELECT * FROM admin");

  if(!$rezultat)
  {
    throw new Exception($polaczenie->error);
  }
  else
  {

    while($row = mysqli_fetch_array($rezultat))
    {
          $haslo_TEMP = $row['Haslo'];

          if(strlen($haslo_TEMP)>20)
          {
          //echo "rekord ".$row['ID_USER']." ma zamienione haslo z hashem";
          }
          else
          {
          $haslo_TEMP = password_hash($haslo_TEMP,PASSWORD_DEFAULT);

//          echo $row['ID_USER']." --- ".$haslo_TEMP. "</br>";

          if($polaczenie->query("UPDATE `admin` SET `Haslo` = '$haslo_TEMP' WHERE `Id` ='".$row['Id']."';"))
          {
  //            echo "rekord ".$row['ID_USER']." zostal zamieniony ! </br>";
          }
      }
    }

  }




}



?>
