Google Weather API
=================

Google Weather API is a PHP based library for retrieving and displaying weather at any location, utilizing Google's weather API.

Quick start
------------

Clone the repo, `git clone git://github.com/Geczy/google-weather-api.git`, or [download the latest release](https://github.com/Geczy/google-weather-api/zipball/master).

```php
<?php

include_once('classes/google_weather.class.php');

$googleWeather = new Google_Weather();
$weatherData = $googleWeather->getWeather();

/* Your view file that formats the array response. */
include('views/example1.php');
```

Example response
------------

Here's an example of what getWeather() will return:

```php
<?php

$weatherData =
array (size=3)
  'info' =>
	array (size=3)
	  'city' => string 'Los Angeles, CA' (length=15)
	  'zip' => string 'Los+Angeles' (length=11)
	  'unit' => string 'US' (length=2)
  'current' =>
	array (size=5)
	  'condition' => string 'Cloudy' (length=6)
	  'temp_f' => string '66' (length=2)
	  'humidity' => string 'Humidity: 73%' (length=13)
	  'icon' => string 'http://mgates.me/weather/assets/img/Dotvoid/cloudy.gif' (length=94)
	  'wind_condition' => string 'Wind: N at 0 mph' (length=16)
  'forecast' =>
	array (size=4)
	  'Sun' =>
		array (size=4)
		  'low' => string '63' (length=2)
		  'high' => string '79' (length=2)
		  'icon' => string 'http://mgates.me/weather/assets/img/Dotvoid/mostly_sunny.gif' (length=100)
		  'condition' => string 'Mostly Sunny' (length=12)
	  'Mon' =>
		array (size=4)
		  'low' => string '64' (length=2)
		  'high' => string '81' (length=2)
		  'icon' => string 'http://mgates.me/weather/assets/img/Dotvoid/sunny.gif' (length=93)
		  'condition' => string 'Clear' (length=5)
	  'Tue' =>
		array (size=4)
		  'low' => string '64' (length=2)
		  'high' => string '84' (length=2)
		  'icon' => string 'http://mgates.me/weather/assets/img/Dotvoid/mostly_sunny.gif' (length=100)
		  'condition' => string 'Mostly Sunny' (length=12)
	  'Wed' =>
		array (size=4)
		  'low' => string '64' (length=2)
		  'high' => string '82' (length=2)
		  'icon' => string 'http://mgates.me/weather/assets/img/Dotvoid/sunny.gif' (length=93)
		  'condition' => string 'Clear' (length=5)
```

Show case
------------

* http://mgates.me/weather/
* You?

Bug tracker
-----------

Have a bug? Please create an issue here on GitHub!

https://github.com/Geczy/google-weather-api/issues

Copyright and License
---------------------

Copyright 2012 Matthew Gates

Licensed under the Apache License, Version 2.0 (the "License"); you may not use this work except in
compliance with the License. You may obtain a copy of the License in the LICENSE file, or at:

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software distributed under the License is
distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and limitations under the License.