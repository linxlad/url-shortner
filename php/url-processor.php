<?php
/**
 * Process all the form data (act like our service layer)
 */

// Include class
include_once 'Housesimply.php';

// Trim input
$url = trim($_POST['bigUrl']);

// Instantiate link shrinker class
$hSimply = new Housesimply($url);

// Start link shrinking process
$hSimply->minifyUrl();

// Check if an error occured and return it
if (isset($hSimply->exceptionMsg) && !empty($hSimply->exceptionMsg)) {
    echo json_encode(array('success' => false, 'message' => $hSimply->exceptionMsg));
    exit;
}

// Return minified URL
echo json_encode(array('success' => true, 'littleUrl' => 'http://ly.tracksy.co.uk/?'.$hSimply->littleUrl));
exit;
