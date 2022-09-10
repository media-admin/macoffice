<?php

function open_closed_message() {

    // Include the store hours class
    require_once __DIR__ . '/StoreHours.class.php';

    // Include the Store Hours SETTINGS
    require_once __DIR__ . '/store-hours-settings.php';

    date_default_timezone_set('Europe/Vienna');
    $date = new DateTime;
    // echo date("D m/d/y  h:i:s ",time());

    $template = array(
        'open'           => "Offen",
        'closed'         => "Grade zu.",
        'closed_all_day' => "Geschlossen",
        'separator'      => " bis ",
        'join'           => " and ",
        'format'         => "H", // options listed here: https://php.net/manual/en/function.date.php
        'hours'          => "{%open%}"
    );

    $store_hours = new StoreHours($hours, $exceptions, $template);

    function compileHours($times, $timestamp) {
        $times = $times[strtolower(date('D',$timestamp))];
        if(!strpos($times, '-')) return array();
        $hours = explode(",", $times);
        $hours = array_map('explode', array_pad(array(),count($hours),'-'), $hours);
        $hours = array_map('array_map', array_pad(array(),count($hours),'strtotime'), $hours, array_pad(array(),count($hours),array_pad(array(),2,$timestamp)));
        end($hours);
        if ($hours[key($hours)][0] > $hours[key($hours)][1]) $hours[key($hours)][1] = strtotime('+1 day', $hours[key($hours)][1]);
        return $hours;
    }

    function isOpen($now, $times) {
        $open = 0; // time until closing in seconds or 0 if closed
        // merge opening hours of today and the day before
        $hours = array_merge(compileHours($times, strtotime('yesterday',$now)),compileHours($times, $now));

        foreach ($hours as $h) {
            if ($now >= $h[0] and $now < $h[1]) {
                $open = $h[1] - $now;
                return $open;
            }
        }
        return $open;
    }

    $now = time();
    $open = isOpen($now, $times);

    echo $open;

    if ($open <= 600) {
            /* $tomorrow = strtotime('tomorrow', $now);
            if (date('N', $tomorrow) == 7) {
                $tomorrow = strtotime('next monday', $now);
            }
            $day = strtolower(date('D', $tomorrow));
            $tomorrow = date('l', $tomorrow);
            $opentime = preg_replace('/^(\d+:\d+ [AP]M).*//*', '$1', $times[$day]); */
            echo '<div class="opening-hours__state-closing">&nbsp;</div>';
        }

else {
            $store_hours->render();
        }

    // $store_hours->render();
}

open_closed_message();



