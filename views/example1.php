<?php

/**
 * Possible icon sets include:
 *  Google
 *  Dotvoid
 *  KWeather
 *  NYTimes
 *  Yahoo
 */
$iconSet = 'Dotvoid';

/*
 * Eg: http://mgates.me/weather/assets/img/Dotvoid/*.gif
 */
$imagePath = (!empty($_SERVER['https']) ? 'https://' : 'http://')
			. $_SERVER['HTTP_HOST']
			. dirname(str_replace('views', '', $_SERVER['SCRIPT_NAME']))
			. '/assets/img/'
			. $iconSet;

$degree = ($degree == 'f')
	? '&deg;F | <a href="?degree=c">&deg;C</a>'
	: '&deg;C | <a href="?degree=f">&deg;F</a>';

?>

<style>
#details { width: 450px; margin: 0 auto; }
.tempDetails{margin-top:5px;color: #999;line-height: 11px;}
.forecast{text-align:center;padding-right: 20px;}
.weatherHeader{text-align:center;font-size:20px;padding-bottom:10px;}
#mainTempImg{width:60px; height:auto; margin-right:6px;vertical-align:top;}
.forecastImages{width:40px; height:auto;}
.low { color:#999 }
</style>

<div class="weatherHeader">
  <b>Weather</b> for <b><?php echo $weatherData['info']['city']; ?></b>
</div>

<table>

  <tbody>

  <tr>
    <td><img id="mainTempImg" src="<?php echo $imagePath . $weatherData['current']['icon']; ?>"></td>
    <td>
      <h3 class="currentTemp"><?php echo $weatherData['current']['temp_f']; ?> <?php echo $degree; ?></h3>
      <div class="tempDetails">
        <p><?php echo $weatherData['current']['condition']; ?></p>
        <p><?php echo $weatherData['current']['wind_condition']; ?></p>
        <p><?php echo $weatherData['current']['humidity']; ?></p>
      </div>
    </td>

    <td style="width:5px;border-left:solid 1px #d8d8d8"></td>

    <?php foreach ( $weatherData['forecast'] as $day => $forecast ) : ?>

      <td class="forecast">
        <p><?php echo $day; ?></p>
        <p><img class="forecastImages" src="<?php echo $imagePath . $forecast['icon']; ?>"></p>
        <div class="forecastTemp">
          <span class="high"><?php echo $forecast['high']; ?>&deg;</span>
          <span class="low"><?php echo $forecast['low']; ?>&deg;</span>
        </div>
      </td>

    <?php endforeach; ?>

  </tr>

  </tbody>

</table>