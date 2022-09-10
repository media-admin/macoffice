<?php

// REQUIRED
// Define daily open hours
// Must be in 24-hour format, separated by dash
// If closed for the day, leave blank (ex. sunday)
// If open multiple times in one day, enter time ranges separated by a comma
$hours = array(
	'mon' => array('10:00-18:00'),
	'tue' => array('10:00-18:00'),
	'wed' => array('10:00-18:00'),
	'thu' => array('10:00-18:00'),
	'fri' => array('10:00-18:00'),
	'sat' => array('10:00-13:00'),
	'sun' => array('')
);

$times = array(
	'mon' => '10:00 - 18:00',
	'tue' => '10:00 - 18:00',
	'wed' => '10:00 - 18:00',
	'thu' => '10:00 - 18:00',
	'fri' => '10:00 - 18:00',
	'sat' => '10:00 - 13:00',
	'sun' => 'closed'
);

// OPTIONAL
// Add exceptions (great for holidays etc.)
// MUST be in a format month/day[/year] or [year-]month-day
// Do not include the year if the exception repeats annually
$exceptions = array(
	'1/1'  => array(),
	'1/30/2021'  => array(),
	'1/2'  => array(),
	'1/3'  => array(),
	'1/4'  => array(),
	'1/5'  => array(),
	'1/6' => array(),
	'4/5/2021' => array(),
	'5/1' => array(),
	'5/13/2021' => array(),
	'5/24/2021' => array(),
	'6/3/2021' => array(),
	'8/15' => array(),
	'9/8' => array(),
	'10/26' => array(),
	'11/1' => array(),
	'12/24' => array(),
	'12/25' => array(),
	'12/26' => array(),
	'12/31' => array()
);

/* $exceptions = array(
	'Christmas' => '12/25',
	'New Years Day' => '1/1',
	'Urlaub' => '9/8'
); */


?>