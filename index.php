<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Google Weather</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Google Weather API">
    <meta name="author" content="Matthew Gates">

    <!-- Le styles -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>
    <style>body{font-family: 'PT Sans', sans-serif;padding-top: 120px;padding-bottom: 40px;}</style>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!--
      Le fav icon
      <link rel="shortcut icon" href="assets/ico/favicon.ico">
     -->
    </head>

    <body>

    <div class="container">

      <div class="row">
        <div class="span12">
          <legend>Google Weather <small><a href="https://github.com/Geczy/google-weather-api">More info</a>.</small></legend>
          <div id="details">
            <p><?php

              include_once('classes/google_weather.class.php');

              /* Instantiate class. */
              $googleWeather = new Google_Weather();

              /* Set degree. */
              $degree = !empty($_GET['degree']) ? $_GET['degree'] : 'f';
              $googleWeather->setDegree($degree);

              /* Get data. */
              $weatherData = $googleWeather->getWeather();

              /* Include the view. */
              $viewsDir = dirname(__FILE__) . '/views/';
              include($viewsDir . 'example1.php');

            ?></p>
          </div>
        </div>
      </div>

    <hr>

    <footer>
    <p><a tabindex="99" target="_TOP" href="http://mgates.me">&copy; Matthew Gates 2012</a></p>
    </footer>

    </div> <!-- /container -->

  </body>
</html>