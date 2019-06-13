<?php
	session_start();
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

	<link rel="stylesheet" href="./css/bootstrap.min.css">
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

					<li class="nav-item active">
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
	</nav>
    <main>
        <article class="categories">

            <header>
                <h1>Wybierz kategorię</h1>
            </header>

            <div class="container">
                <div class="row">

                        <div class="col-sm-6 col-md-5 offset-md-1 ">
                            <figure>
																<?php
																$kat = 'elektronika';
																echo '<a href="elektronika-ogloszenia.php?kat='.$kat.'">'?><img src="./img/elektronika.png"   alt="elektronika"></a>
                                <figcaption>Elektronika</figcaption>
                            </figure>
                        </div>

                        <div class="col-sm-6 col-md-5 ">
													<figure>
															<?php
															$kat = 'motoryzacja';
															echo '<a href="elektronika-ogloszenia.php?kat='.$kat.'">'?><img src="./img/motoryzacja.png"   alt="motoryzacja"></a>
															<figcaption>Motoryzacja</figcaption>
													</figure>
                        </div>

                        <div class="col-sm-6 col-md-5 offset-md-1">
													<figure>
															<?php
															$kat = 'Domiogród';
															echo '<a href="elektronika-ogloszenia.php?kat='.$kat.'">'?><img src="./img/domiogrod.png"   alt="Dom o ogród"></a>
															<figcaption>Dom i ogród</figcaption>
													</figure>
                        </div>



                        <div class="col-sm-6 col-md-5">
													<figure>
															<?php
															$kat = 'praca';
															echo '<a href="elektronika-ogloszenia.php?kat='.$kat.'">'?><img src="./img/praca.png"   alt="praca"></a>
															<figcaption>Praca</figcaption>
													</figure>
                        </div>

                        <div class="col-sm-6 col-md-5 offset-md-1">
													<figure>
															<?php
															$kat = 'moda';
															echo '<a href="elektronika-ogloszenia.php?kat='.$kat.'">'?><img src="./img/moda.png"   alt="moda"></a>
															<figcaption>Moda</figcaption>
													</figure>
                        </div>

                        <div class="col-sm-6 col-md-5">
													<figure>
															<?php
															$kat = 'inne';
															echo '<a href="elektronika-ogloszenia.php?kat='.$kat.'">'?><img src="./img/pozostale.png"   alt="Pozostale"></a>
															<figcaption>Pozostałę</figcaption>
													</figure>
                        </div>

                </div>
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
