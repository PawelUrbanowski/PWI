<?php
session_start();
echo $_SESSION['$_sesja'] = "Hello World";
echo '<a href="12.php">12</a>';
session_destroy();
?>