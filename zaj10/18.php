<?php
$myfile = fopen("plik.txt", "w") or die("Unable to open file!");
fwrite($myfile, $_POST['tekst']);
fclose($myfile);