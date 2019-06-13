<?php
    session_start();

    if((!isset($_POST['email'])) || (!isset($_POST['haslo'])))
    {
        header('Location: tablica.php');
        exit();
    }
    require_once "connect.php";
    $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
    if($polaczenie->connect_errno!=0)
    {
        echo "Error: ".$polaczenie->connect_errno;
    }
    else
    {
        $email = $_POST['email'];
        $haslo = $_POST['haslo'];
        $email = htmlentities($email, ENT_QUOTES, "UTF-8");
        $haslo = htmlentities($haslo, ENT_QUOTES, "UTF-8");

        if($rezultat = @$polaczenie->query(sprintf("SELECT * FROM admin WHERE Email='%s'", mysqli_real_escape_string($polaczenie, $email))))
        {
            $ilu_userow = $rezultat->num_rows;
            if($ilu_userow>0)
            {
              $wiersz = $rezultat->fetch_assoc();
              if(password_verify($haslo, $wiersz['Haslo']))
              {
                $_SESSION['zalogowany'] = true;
                $_SESSION['Id'] = $wiersz['Id'];
                $_SESSION['Imie'] = $wiersz['Imie'];
                $_SESSION['Email'] = $wiersz['Email'];
                $_SESSION['Haslo'] = $wiersz['Haslo'];                
              }
                unset($_SESSION['blad']);
                $rezultat->free_result();
                header('Location: akceptacja.php');
            }
            else
            {
                $_SESSION['blad'] = '<span style="color:red">Nieprawidłowy email lub hasło!</span>';
                header('Location: zaloguj.php');
            }
        }

        $polaczenie->close();
    }
?>
