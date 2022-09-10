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
$now = time();
$startzeit = strtotime("{%open%}");
$endzeit = strtotime("{%closed%}");

// Subtrahiere die Endzeit von der Startzeit und Teile durch 60 um den Wert in Minuten zu bekommen
// Ergebnis zeigt die Zeit bis zum Aufsperren --> Variable > 0 = geschlossen, Variable < 0 = geöffnet.
$macoffice_opening = ($endzeit - $startzeit)/60;
$macoffice_closing = ($endzeit - $startzeit)/60;
$time_check = $macoffice_closing*(-1);
$sa_status = $store_hours->is_open();

// OUTPUT
/* if ($status_today != 'Sun' and $store_hours->is_open() and $macoffice_closing < 11) {
    echo '<div class="opening-hours__state-closing">&nbsp;</div>';
} elseif ($status_today != 'Sun' and $macoffice_closing > 0) {
    echo '<div class="opening-hours__state-closed">&nbsp;</div>';
} elseif ($status_today != 'Sun' and $store_hours->is_open() and $macoffice_closing < 0) {
    echo '<div class="opening-hours__state-open">&nbsp;</div>';
} else {
    echo '<div class="opening-hours__state-unclear">&nbsp;</div>';
} */

echo 'now =' . $now . '<br/>';
echo 'startzeit  =' . $startzeit . '<br/>';
echo 'endzeit =' . $endzeit . '<br/>';
echo 'macoffice_opening =' . $macoffice_opening . '<br/>';
echo 'macoffice_closing =' . $macoffice_closing . '<br/>';
echo 'time_check =' . $time_check . '<br/>';
echo 'sa_status =' . $sa_status;

if($store_hours->is_open()) {
    echo "Yes, we're open! Today's hours are " . $store_hours->hours_today() . ".";
} else {
    echo "Sorry, we're closed. Today's hours are " . $store_hours->hours_today() . ".";
}


if($store_hours->is_open()) {
    echo "Yes, we're open! Today's hours are " . $store_hours->hours_today() . ".";
} else {
    echo "Sorry, we're closed. Today's hours are " . $store_hours->hours_today() . ".";
}


// Display full list of open hours (for a week without exceptions)
echo '<table>';
foreach ($store_hours->hours_this_week() as $days => $hours) {
    echo '<tr>';
    echo '<td>' . $days . '</td>';
    echo '<td>' . $hours . '</td>';
    echo '</tr>';
}
echo '</table>';




?>

