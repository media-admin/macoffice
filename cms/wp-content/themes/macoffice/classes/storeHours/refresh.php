<div class="opening-hours-notification">

    <a id="banner" href='/kontakt'>

        <div class="adresse">Fischauergasse 150, 2700 Wiener Neustadt</div>

        <?php
            // REQUIRED
            // Set your default time zone (listed here: https://php.net/manual/en/timezones.php)
            date_default_timezone_set('Europe/Vienna');

            // Include the store hours class
            require_once __DIR__ . '/StoreHours.class.php';

            // Include the Store Hours SETTINGS
            require_once __DIR__ . '/store-hours-settings.php';



            // OPTIONAL
            // Place HTML for output below. This is what will show in the browser.
            // Use {%hours%} shortcode to add dynamic times to your open or closed message.
            $template = array(
                'open'			 => "jetzt geöffnet",
                'closed'         => "jetzt geschlossen",
                'closed_all_day' => "heute geschlossen",
                'separator'      => " bis ",
                'join'           => " and ",
                'format'         => "H", // options listed here: https://php.net/manual/en/function.date.php
                'hours'          => "{%open%}{%separator%}{%closed%}"
            );


            $template = array(
                'open'           => "<div class=jetzt-geoeffnet>Heute ab {%hours%} geöffnet.</div>",
                'closed'         => "<div class=jetzt-geschlossen>Momentan geschlossen.</div><div class=morgen-geoeffnet>Morgen ab {%hours%} wieder geöffnet.</div>",
                'closed_all_day' => "Heute geschlossen.<br/><div class=morgen-geoeffnet>Morgen ab {%hours%} wieder geöffnet.</div>",
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

            if ($status_today == 'Sat' and $time_check > 180) {
                echo '<div class="opening-hours__state-closed status-geschlossen">';
                echo '<h2 class="jetzt-geschlossen">Am Montag ab 10 Uhr wieder geöffnet.</h2>';
            } elseif ($status_today != 'Sun' and $macoffice_opening > 0) {
                echo '<div class="opening-hours__state-closing status-geschlossen">';
                echo '<h2 class="jetzt-geschlossen">Heute ab 10 Uhr geöffnet.</h2>';
            } elseif ($status_today != 'Sun' and $store_hours->is_open() and $macoffice_opening < 0) {
                echo '<div class="status-geoeffnet">';
                echo '<h2 class="opening-hours__state-open jetzt-geoeffnet">Jetzt geöffnet.</h2>';  // <-- BEI NORMALBETRIEB DIESE ZEILE WIEDER AKTIVIEREN !!!
                // ------
                // BEI NORMALBETRIEB FOLGENDE ZEILE WIEDER AUSKOMMENTIEREN UND DIE SCREEN.CSS EBENFALLS BZGL. SCHRIFTGRÖSSE ANPASSEN!!!
                // echo '<h2 class="jetzt_geoeffnet mach-mich-kleiner">Jetzt geöffnet. Maskenpflicht, maximal 3 Kunden gleichzeitig!</h2>';
            } else {
                echo '<div class="opening-hours__state-closing status-geschlossen">';
                // Standardmeldung
                echo '<h2 class="morgen-geoeffnet">Morgen ab 10 Uhr geöffnet.</h2>';

                // Spezialmeldung
                // echo '<h2 class="morgen_geoeffnet"><span class="exception-text">Geschlossen: Urlaub bis 06.01.2020</span></h2>';
            }

       ?>
       </div>

       <div class="oeffnungszeiten-link">Zu den &Ouml;ffnungszeiten</div>

    </a>

 </div>