<?php
	// Pfad am Server: /macoffice/wp2021/wp-content/themes/macoffice2021
	// REQUIRED
	// Set your default time zone (listed here: https://php.net/manual/en/timezones.php)
	date_default_timezone_set('Europe/Vienna');

	// Include the store hours class
	require_once __DIR__ . '/classes/storeHours/StoreHours.class.php';

	// Include the Store Hours SETTINGS
	require_once __DIR__ . '/classes/storeHours/store-hours-settings.php';

	// OPTIONAL
	// Place HTML for output below. This is what will show in the browser.
	// Use {%hours%} shortcode to add dynamic times to your open or closed message.
	$template = array(
		'open'	=>	"jetzt geöffnet",
          			'closed'         => "jetzt geschlossen",
          			'closed_all_day' => "heute geschlossen",
          			'separator'      => " bis ",
          			'join'           => " and ",
          			'format'         => "H", // options listed here: https://php.net/manual/en/function.date.php
          			'hours'          => "{%open%}{%separator%}{%closed%}"
		);

	$template = array(
		'open'           => "<div class=jetzt_geoeffnet>Heute ab {%hours%} geöffnet.</div>",
		'closed'         => "<div class=jetzt_geschlossen>Momentan geschlossen.</div><div class=morgen_geoeffnet>Morgen ab {%hours%} wieder geöffnet.</div>",
		'closed_all_day' => "Heute geschlossen.<br/><div class=morgen_geoeffnet>Morgen ab {%hours%} wieder geöffnet.</div>",
		'separator'      => " bis ",
		'join'           => " and ",
		'format'         => "H", // options listed here: https://php.net/manual/en/function.date.php
		'hours'          => "{%open%}"
	);

	$hours_open = array(
		'open'           => "Heute ab {%hours%} geöffnet.",
		'closed'         => "Jetzt geschlossen.<br/>Morgen ab {%hours%} geöffnet.",
		'closed_all_day' => "Heute geschlossen.",
		'separator'      => " - ",
		'join'           => " and ",
		'format'         => "G:i", // options listed here: https://php.net/manual/en/function.date.php
		'hours'          => "{%open%}"
	);


	// Instantiate class
	$store_hours = new StoreHours($hours, $exceptions, $template);
	$store_hours->is_open();
	$status_today = date("D");

	// CHECKT DEN AKTUELLEN ZUSTAND
	$store_hours_NOW = new StoreHours($hours, $exceptions, $template);
	$store_hours_NOW->is_open();

	//Belege Variablen mit den entsprechenden Zeiten
	$startzeit = time();
	$endzeit = strtotime("10:00");

	//Subtrahiere die Endzeit von der Startzeit und Teile durch 60 um den Wert in Minuten zu bekommen
	//Ergebnis zeigt die Zeit bis zum Aufsperren --> Variable > 0 = geschlossen, Variable < 0 = geöffnet.
	$macoffice_opening = ($endzeit - $startzeit)/60;
	$time_check = $macoffice_opening*(-1);
	$sa_status = $store_hours->is_open();
?>


<?php
	if  ($status_today == 'Sat' and $macoffice_opening > 0) {
		echo '<div id="container-closed">';
	} elseif ($status_today != 'Sun' and $macoffice_opening > 0) {
		echo '<div id="container-closed">';
	} elseif ($status_today != 'Sun' and $store_hours->is_open() and $macoffice_opening < 0) {
		echo '<div id="container-open">';
	} else {
		echo '<div id="container-closed">';
	}
?>


<div id="header">
	<section class="
		<?php
    	if ($status_today == 'Sat' and $macoffice_opening > 0) {
				echo 'logo-geschlossen">';
				echo '<img src="';?><?php echo '/wp-content/themes/macoffice/assets/images/aussendisplay/macoffice-logo_negativ.png">';
			} elseif ($status_today != 'Sun' and $macoffice_opening > 0) {
				echo 'logo-geschlossen">';
				echo '<img src="';?><?php echo '/wp-content/themes/macoffice/assets/images/aussendisplay/macoffice-logo_negativ.png">';
			} elseif ($status_today != 'Sun' and $store_hours->is_open() and $macoffice_opening < 0) {
				echo 'logo-geoeffnet">';
				echo '<img src="';?><?php echo '/wp-content/themes/macoffice/assets/images/aussendisplay/macoffice-logo_positiv.png">';
			} else {
				echo 'logo-geschlossen">';
				echo '<img src="';?><?php echo '/wp-content/themes/macoffice/assets/images/aussendisplay/macoffice-logo_negativ.png">';
			}
		?>"
	</section>

</div>

<div class="content">

	<div id="center" class="

		<?php
		  if ($status_today == 'Sat' and $macoffice_opening > 0) {
				echo 'col-geschlossen">';
				echo '<section class="bg-black">';
			} elseif ($status_today != 'Sun' and $macoffice_opening > 0) {
				echo 'col-geschlossen">';
				echo '<section class="bg-black">';
			} elseif ($status_today != 'Sun' and $store_hours->is_open() and $macoffice_opening < 0) {
				echo 'col-geoeffnet">';
				echo '<section class="bg-white">';
			} else {
				echo 'col-geschlossen">';
				echo '<section class="bg-black">';
			}
		?>

		<div class="

			<?php
				if ($status_today == 'Sat' and $time_check > 180) {
					echo 'status-geschlossen">';
					//echo '<h2 class="jetzt_geschlossen">Am Montag ab 10 Uhr wieder geöffnet.</h2>';
				} elseif ($status_today != 'Sun' and $macoffice_opening > 0) {
					echo 'status-geschlossen">';
					//echo '<h2 class="jetzt_geschlossen">Heute ab 10 Uhr geöffnet.</h2>';
				} elseif ($status_today != 'Sun' and $store_hours->is_open() and $macoffice_opening < 0) {
					echo 'status-geoeffnet">';
					//echo '<h2 class="jetzt_geoeffnet">Jetzt geöffnet.</h2>';  //  <-- BEI NORMALBETRIEB DIESE ZEILE WIEDER AKTIVIEREN !!!
					// ------
					// BEI NORMALBETRIEB FOLGENDE ZEILE WIEDER AUSKOMMENTIEREN UND DIE SCREEN.CSS EBENFALLS BZGL. SCHRIFTGRÖSSE ANPASSEN!!!
					echo '<h2 class="jetzt_geoeffnet mach-mich-kleiner">Jetzt geöffnet!</h3>';
				} else {
					echo 'status-geschlossen">';
					// Standardmeldung
					//echo '<h2 class="morgen_geoeffnet">Morgen ab 10 Uhr geöffnet.</h2>';

					// Spezialmeldung
					echo '<h2 class="morgen_geoeffnet"><span class="exception-text">Geschlossen: Urlaub bis 06.01.</span></h2>';

				}
			?>

		</div>

		<div class="

			<?php

          		if ($status_today == 'Sat' and $macoffice_opening > 0) {
					echo 'hotline-geschlossen">';
				} elseif ($status_today != 'Sun' and $macoffice_opening > 0) {
					echo 'hotline-geschlossen">';
				} elseif ($status_today != 'Sun' and $store_hours->is_open() and $macoffice_opening < 0) {
					echo 'hotline-geoeffnet">';
				} else {
					echo 'hotline-geschlossen">';
				}

			?>

			<span class="text-light">Technische Hotline</span> <span class="text-bold">0900 888 345 </span> <span class="text-light">(€ 1,81/Min.)</span>

			<!--
			<?php
          			if ($exceptions) {
					echo 'Ausnahme';
				} else {
					echo 'Keine Ausnahme';
				}
			?>
				-->

		</div>

	</section>

</div>

</div>

<div id="footer">

<section class="hotline">
	<span class="text-light">MO - FR</span> <span class="text-bold">10 bis 18 Uhr&nbsp;&nbsp;|&nbsp;&nbsp;</span> <span class="text-light">SA</span> <span class="text-bold">10 bis 13 Uhr</span>
</section>

</div>

</div>

