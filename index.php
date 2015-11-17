<?php

// Include link shrinking class
include_once 'php/Housesimply.php';

// Check the URL has a "?"
if (strpos($_SERVER['REQUEST_URI'], '?')) {

    // Get everything from the URL afte "/"
    $urlIn = explode('/', $_SERVER['REQUEST_URI']);

    // Trim "?" from URL string
    $url = trim(trim($urlIn[1]), '?');

    // Instantiate link shrinking class with reverse parameter
    $hSimply = new Housesimply($url, true);

    // If the URL exists then redirect to it
    if ($hSimply->checkUrlExists()) {
        header('Location: '.$hSimply->bigUrl);
        exit;
    }
}
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Housesimply | Link Shrinker</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">
        <script src="js/vendor/modernizr-2.6.2.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <div class="row">
            <div class="twelve columns">
                <!-- Add your site or application content here -->
                <form class="form-horizontal">
                    <fieldset>
                        <li id="message"></li>

                        <!-- Form Name -->
                        <legend>Housesimply </legend>

                        <!-- Text input-->
                        <div class="field">
                            <input id="bigUrl" class="input" type="text" placeholder="URL to shrink example: http://google.com/" />
                        </div>

                        <li id="urlOut"></li>

                        <!-- Button -->
                        <div id="submitUrl" class="medium primary btn"><a href="#">Shrink</a></div>

                    </fieldset>
                </form>
            </div>
        </div>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.2.min.js"><\/script>')</script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>
