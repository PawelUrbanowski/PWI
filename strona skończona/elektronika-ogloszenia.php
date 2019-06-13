<?php
	session_start();

	require_once "connect.php";

	$polaczenie = @new mysqli($host,$db_user,$db_password,$db_name);

	if($polaczenie->connect_errno!=0)
	{
	    echo "Error: ".$polaczenie->connect_errno;//." Opis ".$polaczenie->connect_error;
	}
	else
	{
		if(isset($_POST['search']))
		{
				$search = $_POST['search'];
				$search2 = '%'.$search.'%';
				$w_od = "0";
				$w_do = "100000000";
				$kategoria ='';
				$rezultat = $polaczenie->query(
				sprintf("SELECT * FROM ogloszenie WHERE Stan ='dodano' AND Cena >= '%s' AND Cena <='%s' AND Tytul LIKE '%s'" ,
				mysqli_real_escape_string($polaczenie, $w_od), mysqli_real_escape_string($polaczenie, $w_do), mysqli_real_escape_string($polaczenie, $search2)));
		}

		if(isset($_POST['w_tytul']))
		{
			$w_tytul = $_POST['w_tytul']; // dokończ
			$w_tytul2 = '%'.$w_tytul.'%';
			//echo $w_tytul2;

			if($_POST['w_kategoria'] == 'Wszystkie kategorie')	$w_kategoria = ''; // popraw bo nie dziala do konca wyszukiwanie
				else $w_kategoria = $_POST['w_kategoria'];
				//echo $w_kategoria;
				$kategoria=$w_kategoria;

			$w_lokalizacja = $_POST['w_lokalizacja']; // dokończ
			$w_lokalizacja2 = '%'.$w_lokalizacja.'%';
			//echo $w_lokalizacja2;

			if($_POST['w_od']=='')	$w_od = "0";
				else $w_od = $_POST['w_od'];
			if($_POST['w_do'] == '') $w_do = '1000000';
				else $w_do = $_POST['w_do'];

			//echo "2";
if($w_kategoria == '' ) // gdy nie ustawione kategorie
{
			if($w_lokalizacja =='')
			{
				$rezultat = $polaczenie->query(
				sprintf("SELECT * FROM ogloszenie WHERE Stan ='dodano' AND Cena >= '%s' AND Cena <='%s' AND Tytul LIKE '%s'" ,
				mysqli_real_escape_string($polaczenie, $w_od), mysqli_real_escape_string($polaczenie, $w_do), mysqli_real_escape_string($polaczenie, $w_tytul2)));
			}
			else
			{
					$rezultat = $polaczenie->query(
					sprintf("SELECT * FROM ogloszenie WHERE Stan ='dodano' AND Cena >= '%s' AND Cena <='%s' AND (SELECT Lokalizacja from kontakt WHERE ogloszenie.Id_kontakt = kontakt.Id = '%s' AND Tytul LIKE '%s')" ,
					mysqli_real_escape_string($polaczenie, $w_od), mysqli_real_escape_string($polaczenie, $w_do), mysqli_real_escape_string($polaczenie, $w_lokalizacja), mysqli_real_escape_string($polaczenie, $w_tytul2)));
			}

}
else // gdy ustawione kategorie
{
	if($w_lokalizacja=='')
	{
		$rezultat = $polaczenie->query(
		sprintf("SELECT * FROM ogloszenie WHERE Stan ='dodano' AND Kategoria = '%s' AND Cena >= '%s' AND Cena <='%s' AND Tytul LIKE '%s'" ,
		mysqli_real_escape_string($polaczenie,$w_kategoria),  mysqli_real_escape_string($polaczenie, $w_od), mysqli_real_escape_string($polaczenie, $w_do), mysqli_real_escape_string($polaczenie, $w_tytul2)));
	}
	else
	{  $rezultat = $polaczenie->query(
		sprintf("SELECT * FROM ogloszenie WHERE Stan ='dodano' AND Kategoria = '%s' AND Cena >= '%s' AND Cena <='%s' AND (SELECT Lokalizacja from kontakt WHERE ogloszenie.Id_kontakt = kontakt.Id) = '%s' AND Tytul LIKE '%s'" ,
		mysqli_real_escape_string($polaczenie,$w_kategoria),  mysqli_real_escape_string($polaczenie, $w_od), mysqli_real_escape_string($polaczenie, $w_do), mysqli_real_escape_string($polaczenie, $w_lokalizacja), mysqli_real_escape_string($polaczenie, $w_tytul2)));
	}
}
		}
		else
		{
				if(isset($_GET['kat'])){

					$kategoria = $_GET['kat'];

					if(isset($kategoria))
					{
							$rezultat = $polaczenie->query(
							sprintf("SELECT * FROM ogloszenie WHERE Stan ='dodano' AND kategoria = '%s'",
							mysqli_real_escape_string($polaczenie, $kategoria)));
					}
					else
					{
						$kategorie = '';
						$rezultat = $polaczenie->query(
							sprintf("SELECT * FROM ogloszenie WHERE Stan ='dodano' AND kategoria = '%s'",
							mysqli_real_escape_string($polaczenie, $kategoria)));
					}
				}
		}
	}

 ?>
<!DOCTYPE html>
<html lang="pl">
<head>

	<meta charset="UTF-8">
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
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

                <input type="submit" value="Ok")>
                <input type="reset" value="Wyczyść">

        </div>
			</form>
    </nav>

     <aside>
        <div class="nawigacjaB">
            <header>
                <h1>Kategorie</h1>
            </header>

            <ul>
								<li><?php	$kat = 'elektronika'; echo '<a href="elektronika-ogloszenia.php?kat='.$kat.'">'?>Elektronika</a> </li>
								<li><?php	$kat = 'motoryzacja'; echo '<a href="elektronika-ogloszenia.php?kat='.$kat.'">'?>Motoryzacja</a> </li>
								<li><?php	$kat = 'domiogrod'; echo '<a href="elektronika-ogloszenia.php?kat='.$kat.'">'?>Dom i ogród</a> </li>
								<li><?php	$kat = 'praca'; echo '<a href="elektronika-ogloszenia.php?kat='.$kat.'">'?>Praca</a> </li>
								<li><?php	$kat = 'moda'; echo '<a href="elektronika-ogloszenia.php?kat='.$kat.'">'?>Moda</a> </li>
								<li><?php	$kat = 'inne'; echo '<a href="elektronika-ogloszenia.php?kat='.$kat.'">'?>Pozostałe</a> </li>
            </ul>

        </div>
    </aside>

    <main>

         <article>
            <div class="ogloszenia">
                <header>
                    <h1>Kategoria:
											<?php
											if($kategoria =='' || !isset($kat)) $kategoria = "Wszystkie kategorie";
											else if($kategoria == 'domiogrod') $kategoria = 'dom i ogród';
												echo $kategoria;
												?></h1>

                </header>

                <article>
                    <section>
<?php
											while($row = mysqli_fetch_array($rezultat))
    											{
															$_SESSION['id_temp'] = $row['ID'];
          										$_SESSION['tytul_temp'] = $row['Tytul'];
															$_SESSION['opis_temp'] = $row['Opis'];
															$_SESSION['cena_temp'] = $row['Cena'];
															$_SESSION['id_kontaktu_temp'] = $row['Id_kontakt'];

															$lok_rezult = $polaczenie->query("SELECT Lokalizacja FROM kontakt WHERE Id =".$_SESSION['id_temp'] );
															$row2 =  mysqli_fetch_array($lok_rezult);
															$_SESSION['lokalizacja'] = $row2['Lokalizacja'];

															$zdj_rezult = $polaczenie->query("SELECT Sciezka FROM zd WHERE Id =".$_SESSION['id_temp'] );
															$row3 =  mysqli_fetch_array($zdj_rezult);
															$zdjecie = $row3['Sciezka'];


?>
															<div class="pojedyncze">
																	<section class="calosc">

																			<section class="nazwa"><?php echo '<a href="ogloszenie.php?id_ogl='.$row['ID'].'&id_kon='.$row['Id_kontakt'].'">'.$_SESSION['tytul_temp'].' </a>' ;?></section>
																			<section class="zdjecie"><?php echo '<img src="data:image/jpeg;base64,'.base64_encode( $zdjecie ).'"" alt="zdjecie"/>'; ?> </section>
																			<section class="tresc"><?php  echo $_SESSION['opis_temp']; ?></section>

																	</section>
																	<section class="prize">
																			<div class="top"> Cena</div>
																			<div class="zawartosc"><?php echo $_SESSION['cena_temp']; ?> zł</div>
																	</section>
																 <section class="lokalizacja">

																			<div class="top"> Lokalizacja</div>
																			<div class="zawartosc">
																				<?php
																					//echo $rezultat2; // nie dziala
																					echo $_SESSION['lokalizacja'];
																				?>
																			</div>

																	</section>
															</div>
<?php
														}
?>
                    </section>
                 </article>
            </div>
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
