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
    'open'           => '<div class="opening-hours__state-open">&nbsp;</div>',
    'closed'         => '<div class="opening-hours__state-closed">&nbsp;</div>',
    'closed_all_day' => '<div class="opening-hours__state-closed">&nbsp;</div>',
    'separator'      => " - ",
    'join'           => " and ",
    'format'         => "H", // options listed here: https://php.net/manual/en/function.date.php
    'hours'          => "{%open%}{%separator%}{%closed%}"
);

// Instantiate class
$store_hours = new StoreHours($hours, $exceptions, $template);

function compileHours($times, $timestamp) {
    $times = $times[strtolower(date('D',$timestamp))];
    if(!strpos($times, '-')) return array();
    $hour = explode(",", $times);
    $hour = array_map('explode', array_pad(array(),count($hour),'-'), $hour);
    $hour = array_map('array_map', array_pad(array(),count($hour),'strtotime'), $hour, array_pad(array(),count($hour),array_pad(array(),2,$timestamp)));
    end($hour);
    if ($hour[key($hour)][0] > $hour[key($hour)][1]) $hour[key($hour)][1] = strtotime('+1 day', $hour[key($hours)][1]);
    return $hour;
}

function isOpen($now, $times) {
    $open = 0; // time until closing in seconds or 0 if closed
    // merge opening hours of today and the day before
    $hour = array_merge(compileHours($times, strtotime('yesterday',$now)), compileHours($times, $now));

    foreach ($hour as $h) {
        if ($now >= $h[0] and $now < $h[1]) {
            $open = $h[1] - $now;
            return $open;
        }
    }
    return $open;
}

$now = time();
$open = isOpen($now, $times);



// OUTPUT
if ($open == 0) {
    $store_hours->render();
}
elseif ($open <= 600) {
    $tomorrow = strtotime('tomorrow', $now);
    if (date('N', $tomorrow) == 7) {
        $tomorrow = strtotime('next monday', $now);
    }
    $day = strtolower(date('D', $tomorrow));
    $tomorrow = date('l', $tomorrow);
    $opentime = preg_replace('/^(\d+:\d+ [AP]M).*/', '$1', $times[$day]);
    echo '<div class="opening-hours__state-closing">&nbsp;</div>';
}
else {
    $store_hours->render();
}

?>

