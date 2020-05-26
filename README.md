# AirQualityCalculator

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/ca26d473f53d432a8cb1bd4e82dfe236)](https://app.codacy.com/manual/russelomua/AirQualityCalculator?utm_source=github.com&utm_medium=referral&utm_content=russelomua/AirQualityCalculator&utm_campaign=Badge_Grade_Dashboard)

Air Quality Index Calculator - is a tool for calculating Air Quality Index for PM2.5, PM19, CO, SO2, NO2, O3 (ozone)

## Usage
```php
use AirQuality\AirQualityCalculator;
use AirQuality\Pollutants\PollutantsFactory;

$pollutantsFactory = new PollutantsFactory();
$calculator = new AirQualityCalculator();

// Must contains min 2 values of measurements
// good to use 12 or 24 values for PM25 and PM10
$concentrations = [13, 16, 10, 21, 74, 64, 53, 82, 90, 75, 80, 50];

// Supperted pollutants:
//
// - PollutantsFactory::PM25
// - PollutantsFactory::PM10
// - PollutantsFactory::CO
// - PollutantsFactory::SO2
// - PollutantsFactory::NO2
// - PollutantsFactory::OZONE_8H
// - PollutantsFactory::OZONE_1H
$pollutant = $pollutantsFactory->create(PollutantsFactory::PM25);
$airQialityDTO = $calculator->getAirQuality($concentrations, $pollutant);

// AQI
echo $airQialityDTO->index;

// NowCast
echo $airQialityDTO->nowCast;

// AQI category
// See references how to convert category to colors and messages
//
// 0 = AirQualityIndex::GOOD
// 1 = AirQualityIndex::MODERATE
// 2 = AirQualityIndex::UNHEALTHY_SENSITIVE
// 3 = AirQualityIndex::UNHEALTHY
// 4 = AirQualityIndex::VERY_UNHEALTHY
// 5 = AirQualityIndex::HAZARDOUS
// 6 = AirQualityIndex::VERY_HAZARDOUS
echo $airQialityDTO->category;
```

## References
  * [Reference calculator](https://www3.epa.gov/airnow/aqicalctest/nowcast.htm)
  * [Technical Assistance Document for the Reporting of Daily Air Quality](https://www.airnow.gov/sites/default/files/2020-05/aqi-technical-assistance-document-sept2018.pdf)
  * [Communicating Daily and Real-time Air Quality with the Air Quality Index and the NowCast](http://airnowtech.org/Resources/NACAANowCastPresentation.pdf)
  * [NowCast](https://cran.r-project.org/web/packages/PWFSLSmoke/vignettes/NowCast.html)
  * [Transitioning to a new NowCast Method](https://www3.epa.gov/airnow/ani/pm25_aqi_reporting_nowcast_overview.pdf)
