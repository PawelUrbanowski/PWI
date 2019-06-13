<?php
// nie dziala wpisanie katego ri  i ceny
    session_start();
    if(isset($_POST['tytul']))
    {
        // walidacja udana?
        $wszystko_OK=true;

    		//Sprawdz poprawnosc tytulu
    		$tytul = $_POST['tytul'];
        //spr dlg tytulu
        if((strlen($tytul)<3) || (strlen($tytul)>50))
    		{
    			$wszystko_OK=false;
    			$_SESSION['e_tytul']="Tytuł musi posiadać od 3 do 50 znaków!";
    		}
        //spr poprawnosc znakow
        if(ctype_alnum($tytul)==false)
        {
            $wszystko_OK=false;
            $_SESSION['e_tytul']="Tytuł może składać się tylko z liter i cyfr (bez polskich znaków).";
        }
        $tytul = htmlentities($tytul, ENT_QUOTES, "UTF-8"); // inject protection

        $kategorie = $_POST['kategorie'];

        //Sprawdz poprawnosc opisu
		    $opis = $_POST['opis'];
        if((strlen($opis)<10) || (strlen($opis)>300))
    		{
    			$wszystko_OK=false;
    			$_SESSION['e_opis']="Opis musi posiadać od 10 do 300 znaków!";
    		}
        $opis = htmlentities($opis, ENT_QUOTES, "UTF-8"); // inject protection




        //Sprawdzenie ceny
        $cena = $_POST['cena'];
        if($cena < 0)
        {
          $wszystko_OK = false;
          $_SESSION['e_cena']="Cena nie może być ujemna.";
        }
        if(ctype_alnum($cena)==false)
        {
            $wszystko_OK=false;
            $_SESSION['e_cena']="Cena może składać się tylko z cyfr";
        }
        $cena = htmlentities($cena, ENT_QUOTES, "UTF-8"); // inject protection



            //Sprawdz poprawnosc imie
    		$imie = $_POST['imie'];
        if((strlen($imie)<3) || (strlen($imie)>15))
    		{
    			$wszystko_OK=false;
    			$_SESSION['e_imie']="Podaj prawidłowe imię (3 do 15 znaków).";
        }
        if(ctype_alnum($imie)==false)
        {
            $wszystko_OK=false;
            $_SESSION['e_imie']="Tytuł może składać się tylko z liter i cyfr (bez polskich znaków).";
        }
        $imie = htmlentities($imie, ENT_QUOTES, "UTF-8"); // inject protection


         //Sprawdz poprawnosc nazwisko
    		$nazwisko = $_POST['nazwisko'];
            if((strlen($nazwisko)<3) || (strlen($nazwisko)>20))
    		{
    			$wszystko_OK=false;
    			$_SESSION['e_nazwisko']="Podaj prawidłowe nazwisko (3 do 20 znaków).";
    		}
        if(ctype_alnum($nazwisko)==false)
        {
            $wszystko_OK=false;
            $_SESSION['e_nazwisko']="Nazwisko może składać się tylko z liter (bez polskich znaków).";
        }
        $nazwisko = htmlentities($nazwisko, ENT_QUOTES, "UTF-8"); // inject protection

        //sprawdz email
        $email = $_POST['email'];
        $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
        if((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
        {
            $wszystko_OK=false;
            $_SESSION['e_email']="Podaj poprawny adres e-mail.";
        }
        $emailB = htmlentities($emailB, ENT_QUOTES, "UTF-8"); // inject protection

        //Sprawdz poprawnosc telefonu
    		$telefon = $_POST['telefon'];
            if(strlen($telefon)!=9)
    		{
    			$wszystko_OK=false;
    			$_SESSION['e_telefon']="Podaj prawidłowy numer telefonu (9 cyfr).";
    		}
        if(ctype_alnum($telefon)==false)
        {
            $wszystko_OK=false;
            $_SESSION['e_telefon']="Numer telefonu może składać się tylko z cyfr.";
        }
        $telefon = htmlentities($telefon, ENT_QUOTES, "UTF-8"); // inject protection

        //Sprawdz poprawnosc lokalizacji
    		$lokalizacja = $_POST['lokalizacja'];
            if((strlen($lokalizacja)<3) || (strlen($lokalizacja)>30))
    		{
    			$wszystko_OK=false;
    			$_SESSION['e_lokalizacja']="Podaj prawidłową nazwę lokalizacji.";
    		}
        if(ctype_alnum($lokalizacja)==false)
        {
            $wszystko_OK=false;
            $_SESSION['e_lokalizacja']="Nazwa lokalizacji może składać się tylko z liter (bez polskich znaków).";
        }
        $lokalizacja = htmlentities($lokalizacja, ENT_QUOTES, "UTF-8"); // inject protection
// zapamietywanie pol

$_SESSION['Tytul'] = $tytul;
$_SESSION['Kategorie'] = $kategorie;
$_SESSION['Opis'] = $opis;
$_SESSION['Cena'] = $cena;
$_SESSION['Imie'] = $imie;
$_SESSION['Nazwisko'] = $nazwisko;
$_SESSION['Email'] = $emailB;
$_SESSION['Telefon'] = $telefon;
$_SESSION['Lokalizacja'] = $lokalizacja;

// polaczenie z baza i dodawanie




        require_once"connect.php";
        mysqli_report(MYSQLI_REPORT_STRICT);
        try
        {
            $polaczenie = new mysqli($host,$db_user, $db_password, $db_name);
            if($polaczenie->connect_errno!=0)
            {
                throw new Exception(mysqli_connect_errno());
            }

            if($wszystko_OK==true)
            {
                //testy zaliczone
                if($polaczenie->query("INSERT INTO kontakt VALUES(NULL, '$imie', '$nazwisko', '$email', '$telefon', '$lokalizacja')")) //dziala
                {
                    $rezultat = $polaczenie->query("Select Id FROM kontakt WHERE Imie = '$imie' AND Nazwisko = '$nazwisko' AND Email = '$email' AND Nr_tel = '$telefon' AND Lokalizacja = '$lokalizacja' ");

                    if(!$rezultat){ throw new Exception($polaczenie->error); }

                    $liczbazwrotow = $rezultat->num_rows;

                    if($liczbazwrotow == 1)
                    {
                        $row = mysqli_fetch_array($rezultat);
                        $id_kontaktu = $row['Id'];
                    }
                    else
                    {
                        throw new Exception(mysqli_fetch_array($rezultat));
                    }

                }
                else
                {
                    throw new Exception($polaczenie->error);
                }


                if($polaczenie->query("INSERT INTO ogloszenie VALUES(NULL,'$tytul',  '$kategorie', '$opis', '$cena', '$id_kontaktu', 'oczekujace', '1')"))
                {
                  $id_ogloszenia = mysqli_insert_id($polaczenie);
                  foreach($_FILES["obraz"]["tmp_name"] as $image)
                  {

                    $imgData = addslashes(file_get_contents($image));
                    $imageProperties = getimageSize($image);

                    $polaczenie = new mysqli($host,$db_user, $db_password, $db_name);
                    $polaczenie->query("INSERT INTO zd(sciezka, id_ogloszenia) VALUES('{$imgData}', '{$id_ogloszenia}')");
                  }
                }
                else
                {
                    throw new Exception($polaczenie->error);
                }

            }

            $polaczenie->close();
        }
        catch(Exception $e)
        {
            echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności, spróbuj ponownie później.</span>';
            //echo '<br />Informacja developerska: '.$e;
        }
        if($wszystko_OK==true)
        {
              unset($_SESSION['Tytul']);
              unset($_SESSION['Kategorie']);
              unset($_SESSION['Opis']);
              unset($_SESSION['Cena']);
              unset($_SESSION['Imie']);
              unset($_SESSION['Nazwisko']);
              unset($_SESSION['Email']);
              unset($_SESSION['Telefon']);
              unset($_SESSION['Lokalizacja']);
        }
    }
?>

<!DOCTYPE html>
<html lang="pl">
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<title>Tablica ogłoszeń</title>
	<meta name="description" content="Ogłoszenia dla każdego.">
	<meta name="keywords" content="tablica, ogłoszenia,kupie, oddam, zamienię">
	<meta name="author" content="Paweł Urabanowski">
	<meta http-equiv="X-Ua-Compatible" content="IE=edge">

	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="main2.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">

	<!--[if lt IE 9]>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
	<![endif]-->
	<style>
        .error{
            color:red;
            font-size: 10px;
            margin-top: 10px;
            margin-bottom: 10px;
        }

    </style>
</head>

<body>

	<header>

		<nav class="navbar navbar-dark bg-pasek navbar-expand-lg">

			<a class="navbar-brand" href="tablica.php"> Tablica.pl</a>

			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainmenu" aria-controls="mainmenu" aria-expanded="false" aria-label="Przełącznik nawigacji">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="mainmenu" >

				<ul class="navbar-nav mr-auto">

					<li class="nav-item">
						<a class="nav-link" href="tablica.php"> Strona startowa </a>
					</li>

					<li class="nav-item">
						<a class="nav-link" href="cennik.php" > Cennik </a>

					</li>

          <?php
        if((isset($_SESSION['zalogowany']))&&($_SESSION['zalogowany']==true))
                 {
        ?>
                   <li class="nav-item">
                    <a class="nav-link" href="akceptacja.php"> Akceptacja ogłoszeń </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="wyloguj.php"> Wyloguj się </a>
                  </li>
        <?php

                 }
                 else
                 {
        ?>
                   <li class="nav-item">
                    <a class="nav-link" href="dodaj.php"> Dodaj ogłoszenie </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="zaloguj.php"> Zaloguj się </a>
                  </li>
        <?php
                 }
        ?>



				</ul>

        <form class="form-inline" action="elektronika-ogloszenia.php" method="post">

					<input class="form-control mr-1" type="search" placeholder="Wyszukaj" aria-label="Wyszukaj" name="search">
					<button class="btn btn-light" type="submit">Znajdź</button>

				</form>

			</div>

		</nav>

	</header>

  <nav>
    <form action="elektronika-ogloszenia.php" method="post">
      <div class="wyszukiwarka">

              <label>Czego szukasz: <input size="17" maxlength="256" style="margin-right: 10px" type="search" name="w_tytul"></label>

              <label for="kategoria" style="margin-right: 10px"> Wybierz kategorie: </label>

              <select id="kategoria" style="margin-right: 10px" name="w_kategoria" >

                  <option selected>Wszystkie kategorie</option>
                  <option >elektronika</option>
                  <option >dom i ogród</option>
                  <option >motoryzacja</option>
                  <option >praca</option>
                  <option >moda</option>
                  <option >inne</option>

              </select>

              <label>Lokalizacja: <input style="margin-right: 10px" placeholder="Miejscowość" type="search" name="w_lokalizacja"></label>

              <label>Cena od: <input type="search" size="4" name="w_od"> </label>
              <label>do: <input type="search" size="4" name="w_do"> </label>

              <input type="submit" value="Ok">
              <input type="reset" value="Wyczyść">

      </div>
    </form>
    <?php
      if(isset($wszystko_OK) && $wszystko_OK==true)
      {
        echo '<h3>Twoje ogłoszenie zostało przyjęte do akceptacji.</br> Dziękujemy za zaufanie !</h3>';
      }

     ?>
  </nav>
    <main>
        <article>
            <form method="post" enctype="multipart/form-data" action="dodaj.php">
                <article>
                    <div class="dodawanie">
                        <header>
                            <h1>Dane ogłoszenia</h1>
                        </header>

                        <div class="row">
                            <label>Tytuł ogłoszenia: *<input type="text" name="tytul"
                              <?php
                                if(isset($_SESSION['Tytul']))
                                {
                                  echo 'value="'.$_SESSION['Tytul'].'"';
                                  unset ($_SESSION['Tytul']);
                                }
                              ?> required> </label>
							<?php
								if(isset($_SESSION['e_tytul']))
								{
									echo '<div class="error">'.$_SESSION['e_tytul'].'</div>';
									unset($_SESSION['e_tytul']);
								}
							?>
                        </div>

                        <div class="row">
                            <label for="kategorie"> Wybierz kategorię:  </label>
                            <select id="kategorie" name="kategorie">
                                <option >elektronika</option>
                                <option >dom i ogród</option>
                                <option>motoryzacja</option>
                                <option >praca</option>
                                <option >moda</option>
                                <option >inne</option>
                                <?php if(isset($_SESSION['Kategorie']))
                                {
                                  echo '<option selected >'.$_SESSION['Kategorie'].'</option>';
                                }
                                ?>
                            </select>
                            <?php
                              if(isset($_SESSION['Kategorie'])) $wybrana_kat = $_SESSION['Kategorie'];
                            ?>
                            <?php
                                if(isset($_POST['kategorie']))
                                {
                                    $kategorie = $_POST['kategorie'];
                                }
                            ?>
                        </div>

                        <div class="row">
                            <div><label for="opis"> Opis: * </label></div>
                            <textarea name="opis" id="opis" rows="6" cols="55" minlength="10" maxlength="300" required><?php
                              if(isset($_SESSION['Opis']))
                              {
                                echo $_SESSION['Opis'];
                                unset($_SESSION['Opis']);
                              }
                              ?></textarea>

                            <?php
              								if(isset($_SESSION['e_opis']))
              								{
              									echo '<div class="error">'.$_SESSION['e_opis'].'</div>';
              									unset($_SESSION['e_opis']);
              								}
              							?>
                        </div>
                        <input type="hidden" id="id_ogloszenia" name="id_ogloszenia" value="<?php   ?>">
                        <div class="row">
                            <label>Dodaj zdjęcia: </label>

                            <input type="file" name="obraz[]" accept="image/x-png,image/gif,image/jpeg" multiple required> <!-- potrzebne w formie enctype="multipart/form-data" -->

                        </div>

                        <div class="row">
                            <label> Cena:  <input type="number" name="cena" step="1" min="0" size="5"
                              <?php
                              if(isset($_SESSION['Cena']))
                              {
                                echo 'value="'.$_SESSION['Cena'].'"';
                                unset($_SESSION['Cena']);
                              }
                              ?>
                              ></label>
                                <?php
                                  if(isset($_SESSION['e_cena']))
                                  {
                                    echo '<div class="error">'.$_SESSION['e_cena'].'</div>';
                                    unset($_SESSION['e_cena']);
                                  }
                                ?>
                        </div>
                    </div>
                </article>

                <article>
                    <div class="dodawanie">
                        <header>
                            <h1>Twoje dane Kontaktowe </h1>
                        </header>

                        <div class="row">
                            <label>Podaj swoje imię: *<input type="text" name="imie"
                              <?php
                                if(isset($_SESSION['Imie']))
                                {
                                  echo'value="'.$_SESSION['Imie'].'"';
                                  unset($_SESSION['Imie']);
                                }
                                ?>
                              required> </label>
                            <?php
              								if(isset($_SESSION['e_imie']))
              								{
              									echo '<div class="error">'.$_SESSION['e_imie'].'</div>';
              									unset($_SESSION['e_imie']);
              								}
              							?>
                        </div>

                        <div class="row">
                            <label>Podaj swoje nazwisko: *<input type="text" name="nazwisko"
                              <?php
                                if(isset($_SESSION['Nazwisko']))
                                {
                                  echo'value="'.$_SESSION['Nazwisko'].'"';
                                  unset($_SESSION['Nazwisko']);
                                }
                                ?>

                              required> </label>
                            <?php
                  								if(isset($_SESSION['e_nazwisko']))
                  								{
                  									echo '<div class="error">'.$_SESSION['e_nazwisko'].'</div>';
                  									unset($_SESSION['e_nazwisko']);
                  								}
                  							?>
                        </div>

                        <div class="row">
                            <label>Adres e-mail: * <input type="email" name="email" placeholder="adres@domena.pl"
                              <?php
                                if(isset($_SESSION['Email']))
                                {
                                  echo'value="'.$_SESSION['Email'].'"';
                                  unset($_SESSION['Email']);
                                }
                                ?>
                               required> </label>
                            <?php
                  								if(isset($_SESSION['e_emial']))
                  								{
                  									echo '<div class="error">'.$_SESSION['e_email'].'</div>';
                  									unset($_SESSION['e_email']);
                  								}
                  							?>
                        </div>

                        <div class="row">
                            <label> Numer telefonu: * <input type="tel" name="telefon"
                              <?php
                                if(isset($_SESSION['Telefon']))
                                {
                                  echo'value="'.$_SESSION['Telefon'].'"';
                                  unset($_SESSION['Telefon']);
                                }
                                ?>

                              required></label>
                            <?php
                  								if(isset($_SESSION['e_telefon']))
                  								{
                  									echo '<div class="error">'.$_SESSION['e_telefon'].'</div>';
                  									unset($_SESSION['e_telefon']);
                  								}
                  							?>
                        </div>

                        <div class="row">
                            <label>Lokalizacja: *<input type="text" name="lokalizacja"
                              <?php
                                if(isset($_SESSION['Lokalizacja']))
                                {
                                  echo'value="'.$_SESSION['Lokalizacja'].'"';
                                  unset($_SESSION['Lokalizacja']);
                                }
                                ?>


                               required> </label>
                            <?php
                  								if(isset($_SESSION['e_lokalizacja']))
                  								{
                  									echo '<div class="error">'.$_SESSION['e_lokalizacja'].'</div>';
                  									unset($_SESSION['e_lokalizacja']);
                  								}
                  							?>
                        </div>

                  </div>
                </article>

                <div class="row">
                    <input type="submit" value="Dodaj"  >
                    <input type="reset" value="Wyczyść" >
                </div>

            </form>
        </article>
    </main>

	<footer class="stopka">
        <p>2019 &copy; - Tablica.pl - miejsce na Twoje ogłoszenia! - Paweł Urbanowski </p>
    </footer>




	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>

	<script src="js/bootstrap.min.js"></script>

	<script>

	$( document ).ready( function () {
		$( '.dropdown' ).on( 'click', function ( e ) {
			var $el = $( this );
			var $parent = $( this ).offsetParent( ".dropdown-menu" );
			if ( !$( this ).next().hasClass( 'show' ) ) {
				$( this ).parents( '.dropdown-menu' ).first().find( '.show' ).removeClass( "show" );
			}
			var $subMenu = $( this ).next( ".dropdown-menu" );
			$subMenu.toggleClass( 'show' );

			$( this ).parent( "li" ).toggleClass( 'show' );

			$( this ).parents( 'li.nav-item.dropdown.show' ).on( 'hidden.bs.dropdown', function ( e ) {
				$( '.dropdown-menu .show' ).removeClass( "show" );
			} );

			 if ( !$parent.parent().hasClass( 'navbar-nav' ) ) {
				$el.next().css( { "top": $el[0].offsetTop, "left": $parent.outerWidth() - 4 } );
			}

			return false;
		} );
	} );

	</script>


</body>
</html>
